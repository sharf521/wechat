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
    
    public function getPrice($cityName,$quantity=1)
    {
        $default='';
        $checked='';
        if($this->is_exist){
            $areas=unserialize($this->code_areas);
            if(is_array($areas)){
                foreach ($areas as $area){
                    if($area['areaid']=='default'){
                        $default=$area;
                    }
                    $citys=explode(',',$area['areaname']);
                    if(in_array($cityName,$citys)){
                        $checked=$area;
                        break;
                    }
                }
            }
            if($checked==''){
                $checked=$default;
            }
            if(is_array($checked)){
                if($checked['one']>=$quantity){
                    $fee=$checked['price'];
                }else{
                    //$fee=$checked['price']+ceil(($quantity-$checked['one'])/$checked['next'])*$checked['nprice'];
                    $t=math($quantity,$checked['one'],'-',2);
                    $t=math($t,$checked['next'],'/',2);
                    $t=math($t,$checked['nprice'],'*',2);
                    $fee=math($checked['price'],$t,'+',2);
                }
            }
            return (float)$fee;
        }else{
            echo 'shipp error';
            exit;
        }
    }
}