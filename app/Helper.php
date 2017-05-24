<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6
 * Time: 11:42
 */

namespace App;


use PHPQRCode\QRcode;

class Helper
{
    public static function getSystemParam($code)
    {
        $value = app('\App\Model\System')->getCode($code);
        if ($code == 'convert_rate') {
            if (empty($value)) {
                $value = 2.52;
            }
        }
        return $value;
    }
    public static function getQqLink($qq=123456)
    {
        return "<a href='http://wpa.qq.com/msgrd?v=3&uin={$qq}&site=qq&menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:{$qq}:4' alt='QQ'></a>";
    }
    
    public static function getStoreUrl($user_id,$is_wap=0)
    {
        if($is_wap){
            $store_url="http://shop-{$user_id}.wap.".self::getTopDomain(1);
        }else{
            $store_url="http://shop-{$user_id}.".self::getTopDomain(1);
        }
        return $store_url;
    }

    public static function QRcode($txt,$type='goods',$type_id=0,$level='L')
    {
        $type_id=(int)$type_id;
        $img_url="/data/QRcode/{$type}/".ceil($type_id/2000)."/";
        $file_dir = ROOT . "/public".$img_url;
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $file_name=$type_id.'.png';
        $file_path=$file_dir.$file_name;
        $img_url.=$file_name;
        if(!file_exists($file_path)){
            QRcode::png($txt,$file_path,$level,4,2);
        }
        return $img_url;
    }

    /**
     * //获取顶级域名
     * @return array|string
     */
    public static function getTopDomain($port=0)
    {
        $domain=strtolower($_SERVER['HTTP_HOST']);
        if($port==0 && strpos($domain,':')!==false){
            //去除端口
            $domain=explode(':',$domain);
            $domain=$domain[0];
        }
        $domain_arr=explode('.',$domain);
        if($domain_arr[count($domain_arr)-2]=='com'){
            $domain=$domain_arr[count($domain_arr)-3].'.'.$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
        }else{
            $domain=$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
        }
        return $domain;
    }

    public static function smallPic($image_url)
    {
        return $image_url.'_100X100.png';
    }
}