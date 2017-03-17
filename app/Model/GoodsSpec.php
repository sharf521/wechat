<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/19
 * Time: 15:10
 */

namespace App\Model;


class GoodsSpec extends Model
{
    protected $table='goods_spec';
    public function __construct()
    {
        parent::__construct();
    }

    public function __get($key)
    {
        if($this->is_exist && $this->supply_spec_id!=0){
            if(in_array($key,array('price','stock_count'))){
                $spec=(new SupplyGoodsSpec())->find($this->supply_spec_id);
                if($key=='price'){
                    return math($spec->price,$this->retail_float_money,'+',2);
                }
                return $spec->$key;
            }
        }
        return parent::__get($key);
    }
}