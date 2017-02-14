<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 17:33
 */

namespace App\Model;


class CarProduct extends Model
{
    protected $table='car_product';
    public function __construct()
    {
        parent::__construct();
    }
    
    public function CarProductData()
    {
        return $this->hasOne('\App\Model\CarProductData','id','id');
    }
    public function CarProductSpec()
    {
        return$this->hasMany('\App\Model\CarProductSpec','product_id','id');
    }
}