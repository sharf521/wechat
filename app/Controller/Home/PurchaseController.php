<?php

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Category;
use App\Model\Goods;
use App\Model\GoodsSpec;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\Shipping;
use App\Model\ShopCategory;
use App\Model\SupplyGoods;
use System\Lib\DB;
use System\Lib\Request;

class PurchaseController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        if($this->user->is_shop!=1){
            redirect()->back()->with('error','您还不是商家，没有权限！');
        }
    }

    public function index()
    {
        redirect('purchase/lists');
    }

    public function lists(SupplyGoods $goods,Request $request,Category $category)
    {
        if($this->is_wap){
            redirect()->back()->with('error','请在电脑端操作');
        }
        $cid=(int)$request->get(2);
        $keyword=$request->get('keyword');
        $minPrice=$request->get('minPrice');
        $maxPrice=$request->get('maxPrice');
        $orderBy=$request->get('orderBy');
        $where="status=1 and stock_count>0";
        if($keyword!=''){
            $where.=" and name like '%{$keyword}%'";
        }
        if($minPrice!=''){
            $minPrice=(float)$minPrice;
            $where.=" and price>={$minPrice}";
        }
        if($maxPrice!=''){
            $maxPrice=(float)$maxPrice;
            $where.=" and price<={$maxPrice}";
        }
        if(in_array($orderBy,array('id','sale_count'))){
            $orderBy="{$orderBy} desc";
        }else{
            $orderBy='id desc';
        }

        $topnav_str='<a href="/">首页</a>';
        if($cid!=0){
            $cate=$category->findOrFail($request->get(2));
            $data['cate']=$cate;
            //当前位置分类
            $path=trim($cate->path,',');
            $paths=explode(',',$path);
            array_shift($paths);
            array_pop($paths);
            foreach ($paths as $cid){
                $c=$category->find($cid);
                $topnav_str.="<a href='/purchase/lists/{$c->id}'>{$c->name}</a>";
            }
            $topnav_str.="<a><cite>{$cate->name}</cite></a>";

            $where.=" and category_path like '{$cate->path}%'";
        }else{
            $topnav_str.="<a><cite>列表</cite></a>";
        }
        $data['topnav_str']=$topnav_str;
        $data['result']=$goods->where($where)->orderBy($orderBy)->pager($request->get('page'),10);
        $this->title='采购列表';
        $this->view('purchase_lists',$data);
    }

    public function detail(SupplyGoods $supplyGoods,Goods $goods,Request $request)
    {
        $id=(int)$request->get(2);
        $supplyGoods=$supplyGoods->findOrFail($id);
        $goods=$goods->where("supply_goods_id=? and user_id={$this->user_id} and status!=-1")->bindValues($supplyGoods->id)->first();
        if($goods->is_exist){
            $data['isPurchase']=true;
        }
        if($_POST){
            if($data['isPurchase']==true){
                redirect()->back()->with('error','请不要重复采购');
            }
/*            if($supplyGoods->is_have_spec==0){
                $retail_price=(float)$request->post('retail_price');
                if($retail_price<$supplyGoods->price){
                    redirect()->back()->with('error','零售价不能小于供货价');
                }
            }*/
            try{
                DB::beginTransaction();
                $goods=new Goods();
                $goods->supply_goods_id=$supplyGoods->id;
                $goods->supply_user_id=$supplyGoods->user_id;
                $goods->user_id=$this->user_id;
                $goods->site_id=$this->user->site_id;
                $goods->category_id=$supplyGoods->category_id;
                $goods->category_path=$supplyGoods->category_path;

                $shop_cateid=(int)$request->post('shop_category');
                if($shop_cateid!=0){
                    $shop_catepath=(new ShopCategory())->find($shop_cateid)->path;
                }
                $goods->shop_cateid=$shop_cateid;
                $goods->shop_catepath=$shop_catepath;

                $goods->image_url=$supplyGoods->image_url;
                $goods->name=$supplyGoods->name;
                $goods->stock_count=$supplyGoods->stock_count;
                $goods->is_have_spec=$supplyGoods->is_have_spec;
                $goods->shipping_id=$supplyGoods->shipping_id;
                $goods->sale_count=$supplyGoods->sale_count;
                $goods->status=2;
                $goods_id=$goods->save(true);
                $goods=$goods->find($goods_id);
                if($supplyGoods->is_have_spec){
                    $specs=$supplyGoods->GoodsSpec();
                    foreach($specs as $i=>$v){
                        $spec=new GoodsSpec();
                        $spec->goods_id=$goods->id;
                        $spec->spec_1=$v->spec_1;
                        $spec->spec_2=$v->spec_2;
                        //$spec->price=(float)$request->post("retail_price{$v->id}");
                        $spec->price=math($v->price,1.31,'*',2);
                        $spec->supply_goods_id=$v->goods_id;
                        $spec->supply_spec_id=$v->id;
                        $spec->retail_float_money=abs(math($spec->price,$v->price,'-',2));
                        $spec->stock_count=0;
                        if($i==0){
                            $goods->price=$spec->price;
                            $goods->retail_float_money=$spec->retail_float_money;
                        }
                        $spec->save();
                    }
                }else{
                    //$goods->price=$retail_price;
                    $goods->price=math($supplyGoods->price,1.31,'*',2);
                    $goods->retail_float_money=math($goods->price,$supplyGoods->price,'-',2);
                }
                $goods->save();
                DB::commit();
                redirect('/sellManage/goods/list_status2')->with('msg', '添加成功！');
            }catch(\Exception $e){
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        }else{
            //当前位置
            $topnav_str='<a href="/">首页</a>';
            $path=trim($supplyGoods->category_path,',');
            $paths=explode(',',$path);
            array_shift($paths);
            foreach ($paths as $cid){
                $c=(new Category())->find($cid);
                $topnav_str.="<a href='/purchase/lists/{$c->id}'>{$c->name}</a>";
            }
            $topnav_str.="<a><cite>{$supplyGoods->name}</cite></a>";
            $data['topnav_str']=$topnav_str;
            $data['goods']=$supplyGoods;
            $data['images']=$supplyGoods->GoodsImage();
            $data['GoodsData']=$supplyGoods->GoodsData();
            $this->title='商品详情';
            $areas=(new Shipping())->find($supplyGoods->shipping_id)->code_areas;
            $data['areas']=unserialize($areas);
            $data['cates']=(new ShopCategory())->getListTree($this->user_id);
            $this->view('purchase_detail',$data);
        }
    }

    public function getQuantity(SupplyGoods $goods,Request $request)
    {
        $goods=$goods->findOrFail($request->get('id'));
        if($goods->is_have_spec){
            $Spec=(new GoodsSpec())->find($request->get('spec_id'));
            if($Spec->goods_id=$goods->id){
                echo $Spec->stock_count;
            }else{
                echo 0;
            }
        }else{
            echo $goods->stock_count;
        }
    }


}