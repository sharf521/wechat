<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:46
 */

namespace App\Model;


class Supply extends Model
{
    protected $table='supply';
    protected $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
}