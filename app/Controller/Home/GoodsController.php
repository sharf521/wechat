<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/22
 * Time: 11:31
 */

namespace App\Controller\Home;


use App\Helper;
use App\Model\Cart;
use App\Model\Category;
use App\Model\Goods;
use App\Model\GoodsSpec;
use App\Model\Order;
use App\Model\OrderGoods;
use App\Model\PreSale;
use App\Model\User;
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
        if($cid!=0){
            $cate=$category->findOrFail($cid);
            $data['cate']=$cate;
            $where.=" and category_path like '{$cate->path}%'";
        }
        if(!$this->is_wap){
            $topnav_str='<a href="/">首页</a>';
            if($cid!=0){
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
            }else{
                $topnav_str.="<a><cite>列表</cite></a>";
            }
            $data['topnav_str']=$topnav_str;
        }
        $data['result']=$goods->where($where)->orderBy($orderBy)->pager($request->get('page'),16);
        $this->title='商品列表';
        $this->view('goods_lists',$data);
    }

    public function detail(Goods $goods,Request $request)
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
                redirect("order/confirm/?cart_id[]={$return['cart_id']}&st_uid={$this->st_uid}");
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
            $data['goods']=$goods->pullSupplyGoods();
            $data['images']=$goods->GoodsImage();

            $content=$goods->GoodsData()->content;
            if(!$this->is_wap){
                //图片按需加载处理
                $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
                preg_match_all($pattern, $content, $m);
                foreach ($m[0] as $key => $v) {
                    $content = str_replace($v, "<img lay-src='" . $m[1][$key] . "' />", $content);
                }
            }
            $data['content']=$content;
            $data['shop']=$goods->Shop();
            $domain=explode('|',$this->site->domain);
            $wap_url='http://'.$domain[1]."/goods/detail/{$id}/?st_uid={$goods->user_id}";
            $data['QRcode_url']=Helper::QRcode($wap_url,'goods',$id);
            $this->title=$goods->name;

            //库存0 显示 看看店铺其它商品
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

    //交易记录
    public function getOrderRecord(Goods $goods,Request $request)
    {
        $goods=$goods->findOrFail($request->get('id'));
        $where=" status>=3 ";//己支付
        if($goods->supply_goods_id==0){
            $where.=" and goods_id={$goods->id}";
        }else{
            $where.=" and supply_goods_id={$goods->supply_goods_id}";
        }
        $result=(new OrderGoods())->where($where)->get();
        $list_arr=array();
        foreach ($result as $goods){
            $user=$goods->User();
            $array=array();
            $array['user_id']=$user->id;
            $array['username']=substr($user->username,0,4).'***';
            $array['created_at']=substr($goods->created_at,0,16);
            $array['quantity']=$goods->quantity;
            $array['spec_1']=$goods->spec_1;
            $array['spec_2']=$goods->spec_2;
            array_push($list_arr,$array);
        }
        if(empty($list_arr)){
            $return_arr=array('code'=>0);
        }else{
            $return_arr=array('code'=>1,'list'=>$list_arr);
        }
        echo json_encode($return_arr);
    }

    //预定
    public function preSale()
    {
        $user_id=$this->user_id;
        if(empty($user_id)){
            $return_arr=array('code'=>'noLogin');
        }else{
            $return_arr=array('code'=>0);
        }
        echo json_encode($return_arr);
    }

}