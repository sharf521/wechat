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
    public static function getQqLink($qq=123456)
    {
        return "<a href='http://wpa.qq.com/msgrd?v=3&uin={$qq}&site=qq&menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:{$qq}:4' alt='QQ'></a>";
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
    public static function smallPic($image_url)
    {
        return $image_url.'_100X100.png';
    }
}