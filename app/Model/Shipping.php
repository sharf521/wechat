<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/17
 * Time: 17:14
 */

namespace App\Model;


class Shipping extends Model
{
    protected $table='shipping';
    protected $dates=array('created_at','updated_at');
    public function __construct()
    {
        parent::__construct();
    }
}