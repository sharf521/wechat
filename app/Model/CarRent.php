<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:46
 */

namespace App\Model;


class CarRent extends Model
{
    protected $table='car_rent';
    protected $dates=array('created_at','money_yes_at','verify_at');
    public function __construct()
    {
        parent::__construct();
    }

    //用户是否可以修改
    public function isHasUserEdit()
    {
        if(in_array($this->status,array(0,2))){
            return true;
        }else{
            return false;
        }
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }

    public function Repayments()
    {
        return $this->hasMany('\App\Model\CarRentRepayment','car_rent_id','id','','id');
    }

    public function CarRentImage()
    {
        return $this->hasMany('\App\Model\CarRentImage','rent_id','id','','id');
    }
}