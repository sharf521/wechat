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
     * @return Goods
     */
    public function Goods()
    {
        return $this->hasOne('\App\Model\Goods','id','goods_id');
    }

    /**
     * @return User
     */
    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }
}