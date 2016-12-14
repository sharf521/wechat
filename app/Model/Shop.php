<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:45
 */

namespace App\Model;


class Shop extends Model
{
    protected $table='shop';
    protected $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
}