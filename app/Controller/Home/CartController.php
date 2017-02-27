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
            $this->view('cart',$data);
        }
    }

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
            echo json_encode(array('code'=>'0','total'=>math($num,$goods->price,'*',2)));
        }else{
            $num=$stock_count;
            echo json_encode(array('code'=>'fail','stock_count'=>$stock_count,'msg'=>"库存不足，剩余：{$stock_count}件"));
        }
        $cart->quantity=$num;
        $cart->save();
    }

    public function getSelectedMoney(Request $request,Cart $cart)
    {
        $ids=trim($request->get('cart_ids'));
        $cart_id=explode(',',$ids);
        if(count($cart_id)>0 && $ids!=''){
            $arr=array(
                //'cityName'=>'郑州',
                'buyer_id'=>$this->user_id,
                'cart_id'=>$cart_id
            );
            $carts_result=$cart->getList($arr);
            $result=array();
            $result['total']=0;
            $result['nums']=0;
            foreach ($carts_result as $seller_id=>$carts){
                $result[$seller_id]=0;
                foreach ($carts as $cart){
                    $_t=math($cart->price,$cart->quantity,'*',2);
                    $result[$seller_id]=math($result[$seller_id],$_t,'+',2);
                    $result["shipping_fee_{$seller_id}"]=$cart->shipping_fee;
                    $result['nums']++;
                }
                $result['total']=math($result['total'],$result[$seller_id],'+',2);
            }
            echo json_encode($result);
        }
    }
}