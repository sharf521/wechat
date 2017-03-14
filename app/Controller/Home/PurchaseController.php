<?php

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Category;
use App\Model\Goods;
use App\Model\GoodsSpec;
use App\Model\Order;
use App\Model\OrderGoods;
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
                $topnav_str.="<a href='/goods/lists/{$c->id}'>{$c->name}</a>";
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

    public function detail(SupplyGoods $goods,Request $request)
    {
        $id=(int)$request->get(2);
        $goods=$goods->findOrFail($id);
        if($_POST){
            $data=array(
                'is_direct_buy'=>1,
                'buyer_id'=>$this->user_id,
                'goods_id'=>$goods->id,
                'spec_id'=>$request->post('spec_id'),
                'quantity'=>$request->post('quantity')
            );
            $return=(new Cart())->add($data);
            if($return['code']!='0'){
                redirect()->back()->with('error',$return['msg']);
                return;
            }else{
                redirect('order/confirm/?cart_id[]='.$return['cart_id']);
            }
        }else{
            //当前位置
            $topnav_str='<a href="/">首页</a>';
            $path=trim($goods->category_path,',');
            $paths=explode(',',$path);
            array_shift($paths);
            foreach ($paths as $cid){
                $c=(new Category())->find($cid);
                $topnav_str.="<a href='/goods/lists/{$c->id}'>{$c->name}</a>";
            }
            $topnav_str.="<a><cite>{$goods->name}</cite></a>";
            $data['topnav_str']=$topnav_str;
            $data['goods']=$goods;
            $data['images']=$goods->GoodsImage();
            $data['GoodsData']=$goods->GoodsData();
            $this->title='商品详情';
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