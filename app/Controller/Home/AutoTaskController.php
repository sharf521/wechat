<?php
namespace App\Controller\Home;

use App\Helper;
use App\Model\Order;
use App\Model\User;
use System\Lib\DB;

class AuToTaskController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public  function  index()
    {

    }

    //取消超时的订单
    public function cancelOrder(Order $order)
    {
        $noPayNum=0;
        $daysAgo=time()-3600*24*7;
        //未付款买家取消
        $orders=$order->where('status=1 and created_at<?')->bindValues($daysAgo)->orderBy('id')->get();
        foreach ($orders as $order){
            try{
                DB::beginTransaction();
                $order->cancel((new User())->find($order->buyer_id));
                $noPayNum++;
                DB::commit();
            }catch (\Exception $e){
                $error=$e->getMessage();
                Helper::log('AutoTask',$error);
                DB::rollBack();
            }
        }
        //已付款卖家取消
        $payNum=0;
        $orders=$order->where('status=3 and payed_at<?')->bindValues($daysAgo)->orderBy('id')->get();
        foreach ($orders as $order){
            try{
                DB::beginTransaction();
                $order->cancel((new User())->find($order->seller_id));
                $payNum++;
                DB::commit();
            }catch (\Exception $e){
                $error=$e->getMessage();
                Helper::log('AutoTask',$error);
                DB::rollBack();
            }
        }
        echo "取消未付款订单：{$noPayNum}个，取消未发货订单：{$payNum}个";
    }
}