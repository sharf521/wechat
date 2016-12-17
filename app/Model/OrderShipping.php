<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/11
 * Time: 15:43
 */

namespace App\Model;


class OrderShipping extends Model
{
    protected $table="order_shipping";
    protected $primaryKey="order_sn";
    protected $dates=array('created_at','shipping_at');
    public function __construct()
    {
        parent::__construct();
    }
}