<?php

namespace App\Model;


class ShopAdvert extends Model
{
    protected $table='shop_advert';
    protected $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
}