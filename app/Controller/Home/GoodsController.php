<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/22
 * Time: 11:31
 */

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Category;
use App\Model\Goods;
use App\Model\GoodsSpec;
use App\Model\Order;
use App\Model\OrderGoods;
use System\Lib\DB;
use System\Lib\Request;

class GoodsController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists(Goods $goods,Request $request,Category $category)
    {
        $where="status=1 and stock_count>0";
        $cid=(int)$request->get(2);
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
        $data['result']=$goods->where($where)->orderBy('id desc')->pager($request->get('page'),10);
        $this->view('goods_lists',$data);
    }

    public function detail(Goods $goods,Request $request)
    {
        $goods=$goods->findOrFail($request->get('id'));
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

/*            try{
                DB::beginTransaction();

                $order=new Order();
                $order->Add($user_id,$goods,$spec_id,$quantity);

                DB::commit();
            }catch(\Exception $e){
                $error=$e->getMessage();
                redirect()->back()->with('error',$error);
                DB::rollBack();
            }
            redirect('/member/order')->with('msg','己ok！');*/
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
            $this->view('goods_detail',$data);
        }

    }

    public function getQuantity(Goods $goods,Request $request)
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