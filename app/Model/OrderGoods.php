<?php

namespace App\Model;


class OrderGoods extends Model
{
    protected $table='order_goods';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Shop
     */
    public function Supply()
    {
        return $this->hasOne('\App\Model\Shop','user_id','supply_user_id');
    }
}