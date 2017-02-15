<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:56
 */

namespace App\Model;


class Advert extends Model
{
    protected $table='advert';
    public function __construct()
    {
        parent::__construct();
    }

    public function getRealList($typeid='')
    {
        $where='status=1';
        if($typeid!=''){
            $where.=" and typeid='{$typeid}'";
        }
        return $this->where($where)->orderBy('`showorder`,id')->get();
    }
}