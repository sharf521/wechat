<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 11:56
 */

namespace App\Controller\Api;

use App\Model\Order;
use System\Lib\DB;

class CashierController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function detail()
    {
        $data=$this->data;
        if ($data['typeid'] == 'order_pay') {
            $row = DB::table('order')->where('order_sn=?')->bindValues($data['order_sn'])->row();
            if ($row) {
                return $this->returnSuccess($row);
            }
        }
        return $this->error('error');
    }
    
    public function getPayed(Order $order)
    {
        $data=$this->data;
        if ($data['typeid'] == 'order_pay') {
            $order = $order->where('order_sn=?')->bindValues($data['order_sn'])->first();
            if($order->is_exist){
                if($order->status==1){
                    $order->setStatusPayed($data);
                }
                return $this->returnSuccess();   
            }
        }
        return $this->error('error');
    }
}