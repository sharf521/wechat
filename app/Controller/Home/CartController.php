<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/29
 * Time: 10:36
 */

namespace App\Controller\Home;


use App\Model\Cart;
use App\Model\Goods;
use System\Lib\Request;

class CartController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Cart $cart)
    {
        if($_GET){
            
        }else{
            $data['result_carts']=$cart->getList(array('buyer_id'=>$this->user_id));
            $this->title='我的购物车';
            $this->view('cart',$data);
        }
    }

    //ajax
    public function getGoodsCount(Cart $cart)
    {
        echo $cart->getGoodsCount($this->user_id);
    }
    //ajax
    public function add(Cart $cart,Request $request)
    {
        $data=array(
            'buyer_id'=>$this->user_id,
            'goods_id'=>$request->post('goods_id'),
            'spec_id'=>$request->post('spec_id'),
            'quantity'=>$request->post('quantity')
        );
        $return=$cart->add($data);
        echo json_encode($return);
    }

    public function del(Cart $cart,Request $request)
    {
        $user_id=$this->user_id;
        $cart=$cart->findOrFail($request->get('id'));
        $tag=false;
        if($cart->is_exist){
            if(! empty($user_id)){
                if($cart->buyer_id==$user_id){
                    $cart->delete();
                    $tag=true;
                }
            }else{
                if($cart->session_id==session_id() && $cart->buyer_id==0){
                    $cart->delete();
                    $tag=true;
                }
            }
        }
        if($tag){
            redirect()->back()->with('msg','删除成功！');
        }else{
            redirect()->back()->with('error','失败');
        }
    }
    
    public function changeQuantity(Request $request,Cart $cart,Goods $goods)
    {
        $num=(int)$request->get('num');
        $cart=$cart->findOrFail($request->get('id'));
        $goods=$goods->findOrFail($cart->goods_id);
        $goods=$goods->addSpec($cart->spec_id);
        $stock_count=$goods->stock_count;
        if($stock_count>=$num){
            $result=array('code'=>'0','total'=>math($num,$goods->price,'*',2));

        }else{
            $num=$stock_count;
            $result=array('code'=>'fail','stock_count'=>$stock_count,'msg'=>"库存不足，剩余：{$stock_count}件");
        }
        $cart->quantity=$num;
        $cart->save();
        //计算价格
        $ids=trim($request->get('cart_ids'));
        $array=$this->getSelMoney($ids);
        $result=array_merge($result,$array);
        echo json_encode($result);
    }

    public function getSelectedMoney(Request $request)
    {
        $ids=trim($request->get('cart_ids'));
        $cityName=$request->get('cityName');
        $result=$this->getSelMoney($ids,$cityName);
        echo json_encode($result);
    }

    private function getSelMoney($cart_ids,$cityName='')
    {
        $result=array();
        $cart_id=explode(',',$cart_ids);
        if(count($cart_id)>0 && $cart_ids!=''){
            $arr=array(
                'cityName'=>$cityName,
                'buyer_id'=>$this->user_id,
                'cart_id'=>$cart_id
            );
            $cart=new Cart();
            $carts_result=$cart->getList($arr);
            $carts_moneys=$cart->getMoneys($carts_result);
            $total=0;
            $num=0;
            foreach ($carts_moneys as $i=>$seller){
                $result["shop{$i}_shippingFee"]=$seller['shippingFee'];
                $result["shop{$i}_goodsPrice"]=$seller['goodsPrice'];
                $result["shop{$i}_total"]=$seller['total'];
                $total=math($total,$seller['total'],'+',2);
                $num=math($num,$seller['num'],'+',2);
            }
            $result['countNum']=$num;
            $result['countTotal']=$total;
            return $result;
        }
    }
}