<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:55
 */

namespace App\Model;


class CarBrand extends Model
{
    protected $table='car_brand';
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->orderBy('`showorder`,id')->get();
    }
}