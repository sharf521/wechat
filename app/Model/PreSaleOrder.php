<?php

namespace App\Model;


class PreSaleOrder extends Model
{
    protected $table='presale_order';
    public function __construct()
    {
        parent::__construct();
    }
    public function showStatusName()
    {
        $arr=array(1=>'待支付定金',2=>'等待商家备货',3=>'待支付尾款',4=>'待发货',5=>'己发货',6=>'己收货');
        return $arr[$this->status];
    }

    /**
     * @return OrderShipping
     */
    public function OrderShipping()
    {
        return $this->hasOne('\App\Model\OrderShipping','order_sn','order_sn');
    }

    /**
     * @return Shop
     */
    public function Shop()
    {
        return $this->hasOne('\App\Model\Shop','user_id','seller_id');
    }

    /**
     * @return User
     */
    public function Buyer()
    {
        return $this->hasOne('\App\Model\User','id','buyer_id');
    }
}