<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:56
 */

namespace App\Model;


class CarPlan extends Model
{
    protected $table='car_plan';
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->orderBy('showorder,id')->get();
    }
}