<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 11:56
 */

namespace App\Controller\Api;

use App\Model\CashierLog;
use App\Model\Order;
use App\Model\PreSaleOrder;
use System\Lib\DB;

class CashierController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    //获取最新订单状态
    public function detail()
    {
        $data=$this->data;
        $cashierLog=(new CashierLog())->find($data['order_sn']);
        if ($cashierLog->is_exist) {
            if ($cashierLog->typeid == 'order_pay') {
                $order=(new Order())->find($cashierLog->order_id);
                $row['order_money']=$order->order_money;
                if($order->status==1){
                    $row['status']=1;
                }
                return $this->returnSuccess($row);
            }elseif($cashierLog->typeid=='preSaleOrder_pre'){
                $preSaleOrder=(new PreSaleOrder())->find($cashierLog->order_id);
                $row['order_money']=$preSaleOrder->pre_money;
                if($preSaleOrder->status==1){
                    $row['status']=1;
                }
                return $this->returnSuccess($row);
            }elseif($cashierLog->typeid=='preSaleOrder_end'){
                $preSaleOrder=(new PreSaleOrder())->find($cashierLog->order_id);
                $row['order_money']=math($preSaleOrder->order_money,$preSaleOrder->pre_money,'-',2);
                if($preSaleOrder->status==3){
                    $row['status']=1;
                }
                return $this->returnSuccess($row);
            }
        }
        return $this->error('error');
    }

    //用户中心支付完成后调用
    public function getPayed()
    {
        $data=$this->data;
        $cashierLog=(new CashierLog())->find($data['order_sn']);
        if($cashierLog->is_exist){
            $cashierLog->payed_funds=$data['payed_funds'];
            $cashierLog->payed_integral=$data['payed_integral'];
            $cashierLog->payed_at=time();
            $cashierLog->save();
            if ($cashierLog->typeid == 'order_pay') {
                $order=(new Order())->find($cashierLog->order_id);
                if($order->status==1){
                    $order->setStatusPayed($data);
                }
                return $this->returnSuccess();
            }elseif($cashierLog->typeid == 'preSaleOrder_pre'){
                $preSaleOrder=(new PreSaleOrder())->find($cashierLog->order_id);
                if($preSaleOrder->status==1){
                    $preSaleOrder->status=2;
                    $preSaleOrder->save();
                }
                return $this->returnSuccess();
            }elseif($cashierLog->typeid == 'preSaleOrder_end'){
                $preSaleOrder=(new PreSaleOrder())->find($cashierLog->order_id);
                if($preSaleOrder->status==3){
                    $preSaleOrder->status=4;
                    $preSaleOrder->save();
                }
                return $this->returnSuccess();
            }
        }
        return $this->error('error');
    }
}