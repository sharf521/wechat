<?php
namespace App\Model;

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
        if($this->is_have_spec){
            $spec=(new GoodsSpec())->find($spec_id);
            if($spec->goods_id==$this->id){
                $this->spec_1=$spec->spec_1;
                $this->spec_2=$spec->spec_2;
                $this->stock_count=$spec->stock_count;
                $this->price=$spec->price;
            }else{
                $this->stock_count=0;
                $this->price=0;
            }
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
            $this->stock_count=$this->stock_count+$quantity;
            $this->sale_count=$this->sale_count-$quantity;
            $this->save();
            if($this->is_have_spec){
                $spec=(new GoodsSpec())->find($spec_id);
                if($spec->goods_id==$this->id){
                    $spec->stock_count=$spec->stock_count+$quantity;
                    $spec->save();
                }
            }
        }
    }


    public function __get($key)
    {
        if($this->is_exist && $this->supply_goods_id!=0){
            if(in_array($key,array('stock_count','is_have_spec','sale_count','spec_name1','spec_name2'))){
                $goods=(new SupplyGoods())->find($this->supply_goods_id);
                return $goods->$key;
            }
        }
        return parent::__get($key);
    }

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
            return (new SupplyGoodsImage())->where('goods_id=?')->bindValues($this->supply_goods_id)->get();
        }
        return $this->hasMany('\App\Model\GoodsImage','goods_id','id',"status=1");
    }

    public function GoodsSpec()
    {
        return$this->hasMany('\App\Model\GoodsSpec','goods_id','id');
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