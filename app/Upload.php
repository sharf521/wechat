<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/14
 * Time: 13:05
 */

namespace App;


class Upload
{
    public function curl_file($file_path)
    {
        $curl_url=Config::$upload_url;
        $post = array();
        $post['sign'] = Config::$upload_sign;
        $post['path'] = $file_path;
        $filePath = __DIR__ . '/../public' . $file_path;//不用ROOT  
        if (class_exists('\CURLFile')) {
            $post['field'] =  new \CURLFile($filePath);
        } else {
            $post['field'] = '@' . $filePath;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl);
        curl_close($curl);
        //if((int)$data['is_del'])	unlink($file); 删除文件
        $result = json_decode($result, true);
        if ($result['status'] == 1) {
            $arr['status'] = 1;
            $arr['file'] =  $result['file'];
            return $arr;
        } else {
            $arr['status'] = 0;
            $arr['error'] = $result['error'];
            return $arr;
        }
    }
}