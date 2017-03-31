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

    /**
     * @return  User
     */
    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }

    /**
     * @return Shop
     */
    public function Shop()
    {
        return $this->hasOne('\App\Model\Shop','user_id','user_id');
    }
}