<?php
namespace App\Model;

use System\Lib\DB;

class Goods extends Model
{
    protected $table='goods';
    public function __construct()
    {
        parent::__construct();
    }

    //首页商品
    public function getListByHome($num=10,$cate_id,$site_id)
    {
        return $this->where("status=1 and stock_count>0 and category_path like '2,{$cate_id}%'")->orderBy('id desc')->limit("0,{$num}")->get();
    }

    public function addSpec($spec_id=0)
    {
        $this->pullSupplyGoods();
        if($this->is_have_spec && $spec_id!=0){
            $spec=(new GoodsSpec())->find($spec_id);
            if($spec->goods_id ==$this->id){
                $this->spec_1=$spec->spec_1;
                $this->spec_2=$spec->spec_2;
                $this->stock_count=$spec->stock_count;
                $this->price=$spec->price;
                $this->retail_float_money=$spec->retail_float_money;
                $this->spec_is_exist=true;
                if($this->supply_goods_id!=0){
                    $supplySpec=(new SupplyGoodsSpec())->find($spec->supply_spec_id);
                    if($supplySpec->is_exist){
                        $this->stock_count=$supplySpec->stock_count;
                    }
                }
            }else{
                $this->spec_1='';
                $this->spec_2='';
                $this->stock_count=0;
                $this->price=0;
                $this->retail_float_money=0;
                $this->spec_is_exist=false;
            }
        }else{
            $this->spec_is_exist=false;
        }
        return $this;
    }

    /***
     * @param $quantity 1 or -1
     * @param int $spec_id
     */
    public function setStockCount($quantity,$spec_id=0)
    {
        if(!$this->is_exist){
            return;
        }
        //高并发需要优化
        if($this->supply_goods_id!=0){
            $supplyGoods=(new SupplyGoods())->find($this->supply_goods_id);
            $supplyGoods->stock_count=$supplyGoods->stock_count+$quantity;
            $supplyGoods->sale_count=$supplyGoods->sale_count-$quantity;
            $supplyGoods->save();
            //商品表里库存、规格表里库存都更新
            if($supplyGoods->is_exist){
                if($supplyGoods->is_have_spec){
                    $spec=(new GoodsSpec())->find($spec_id);
                    $supplySpec=(new SupplyGoodsSpec())->find($spec->supply_spec_id);
                    $supplySpec->stock_count=$supplySpec->stock_count+$quantity;
                    $supplySpec->save();
                }
            }
        }else{
            $goods = (new Goods())->find($this->id);//为防止修改其它字段 创建一个新对象
            $goods->stock_count = $goods->stock_count + $quantity;
            $goods->sale_count = $goods->sale_count - $quantity;
            $goods->save();
            if($this->is_have_spec){
                $spec=(new GoodsSpec())->find($spec_id);
                if($spec->goods_id==$this->id){
                    $spec->stock_count=$spec->stock_count+$quantity;
                    $spec->save();
                }
            }
        }
    }

    public function pullSupplyGoods()
    {
        if($this->supply_goods_id!=0){
            $supplyGoods=(new SupplyGoods())->find($this->supply_goods_id);
            $this->stock_count=$supplyGoods->stock_count;
            $this->is_have_spec=$supplyGoods->is_have_spec;
            $this->sale_count=$supplyGoods->sale_count;
            $this->spec_name1=$supplyGoods->spec_name1;
            $this->spec_name2=$supplyGoods->spec_name2;
        }
        return $this;
    }

/*    public function __get($key)
    {
        if($this->is_exist && $this->supply_goods_id!=0){
            if(in_array($key,array('stock_count','is_have_spec','sale_count','spec_name1','spec_name2'))){
                $goods=(new SupplyGoods())->find($this->supply_goods_id);
                return $goods->$key;
            }
        }
        return parent::__get($key);
    }*/

    public function GoodsData()
    {
        if($this->is_exist && $this->supply_goods_id!=0){
            return (new SupplyGoodsData())->where('goods_id=?')->bindValues($this->supply_goods_id)->first();
        }
        return $this->hasOne('\App\Model\GoodsData','goods_id','id');
    }

    public function GoodsImage()
    {
        if($this->is_exist && $this->supply_goods_id!=0){
            return (new SupplyGoodsImage())->where('goods_id=? and status=1')->bindValues($this->supply_goods_id)->get();
        }
        return $this->hasMany('\App\Model\GoodsImage','goods_id','id',"status=1");
    }

    public function GoodsSpec()
    {
        $array_spec=array(0);
        if($this->is_have_spec){
            $goodsSpecList=$this->hasMany('\App\Model\GoodsSpec','goods_id','id');
            if($this->supply_goods_id!=0){
                $specArr=array();
                foreach ($goodsSpecList as $spec){
                    $specArr[$spec->supply_spec_id]=$spec;
                }

                $supplySpecList=(new SupplyGoodsSpec())->where('goods_id=?')->bindValues($this->supply_goods_id)->get();
                foreach ($supplySpecList as $i=>$supplySpec){
                    if(!isset($specArr[$supplySpec->id])){
                        $newSpec=new GoodsSpec();
                        $newSpec->goods_id=$this->id;
                        $newSpec->spec_1=$supplySpec->spec_1;
                        $newSpec->spec_2=$supplySpec->spec_2;
                        $newSpec->price=$supplySpec->retail_price;
                        $newSpec->supply_goods_id=$supplySpec->goods_id;
                        $newSpec->supply_spec_id=$supplySpec->id;
                        $newSpec->retail_float_money=abs(math($newSpec->price,$supplySpec->price,'-',2));
                        $newSpec->stock_count=0;
                        $newId=$newSpec->save(true);
                        $specArr[$supplySpec->id]=(new GoodsSpec())->find($newId);
                    }
                    $spec=$specArr[$supplySpec->id];
                    $supplySpecList[$i]->id=$spec->id;
                    $supplySpecList[$i]->price=math($supplySpec->price,$spec->retail_float_money,'+',2);
                    array_push($array_spec,$spec->id);
                }
                DB::table('goods_spec')->where("goods_id={$this->id} and id not in(".implode(',',$array_spec).")")->delete();
                return $supplySpecList;
            }
            return $goodsSpecList;
        }
        return array();
    }

    /**
     * @return Shop
     */
    public function Shop()
    {
        return $this->hasOne('\App\Model\Shop','user_id','user_id');
    }

    /**
     * @return Shop
     */
    public function Supply()
    {
        return $this->hasOne('\App\Model\Shop','user_id','supply_user_id');
    }
}