<?php

namespace App\Model;


class RebateList extends Model
{
    protected $table='rebate_list';
    protected $dates=array('created_at','start_at');
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return User
     */
    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }
}