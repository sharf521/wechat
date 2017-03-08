<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:56
 */

namespace App\Model;


class Notice extends Model
{
    protected $table='notice';
    public function __construct()
    {
        parent::__construct();
    }
}