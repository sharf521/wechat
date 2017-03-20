<?php

namespace App\Controller\Home;

use App\Controller\Controller;
use App\Model\GoodsImage;
use App\Model\SupplyGoodsImage;
use App\Model\System;
use App\Model\UploadLog;
use App\Upload;
use System\Lib\Request;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UploadController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        if($_FILES['file']['size']<=0){
            return $this->_error('error');
        }
        $type = $_GET['type'];
        $about_id=(int)$_GET['about_id'];
        $name = time() . rand(1000, 9000);
        $user_id = $this->user_id;
        if (empty($user_id)) {
            return $this->_error('超时，请重新登陆');
        }
        $path="/data/upload/".ceil($user_id/2000)."/".$user_id."/".date('Ym').'/';
        if ($type == 'article') {
            $path = "/data/upload/" . ceil($user_id / 2000) . "/" . $user_id . "/article/" . date('Ym') . '/';
        } elseif ($type == 'goods') {
            $path = "/data/upload/" . ceil($user_id / 2000) . "/" . $user_id . "/goods/" . date('Ym') . '/';
        } elseif ($type == 'supply') {
            $path = "/data/upload/" . ceil($user_id / 2000) . "/" . $user_id . "/supply/" . date('Ym') . '/';
        } elseif ($type == 'carBrand') {
            $path = "/data/upload/car/brand/";
        } elseif ($type == 'carPlan') {
            $path = "/data/upload/car/plan/";
        } elseif ($type == 'category') {
            $path = "/data/upload/category/";
        }elseif ($type == 'carProduct') {
            $path = "/data/upload/car/product/".date('Ym').'/';
        } elseif ($type == 'headimgurl') {
            $name = 'face';
        } elseif ($type == 'card1' || $type == 'card2') {
            $name = $type;
        }elseif ($type=='logo'){
            $name = 'logo'.$name;
            $path="/data/upload/web/".date('Ym').'/';
        }
        //创建文件夹
        $_path = ROOT . '/public' . $path;
        if (!file_exists($_path)) {
            if (!mkdir($_path, 0777, true)) {
                return $this->_error('Can not create directory');
            }
        }
        if (empty($_FILES['file']['tmp_name'])) {
            return $this->_error('文件大小超过最大限额');
        }
        if ($_FILES['file']['size'] > 1048576 * 5) {
            return $this->_error('文件超过限额，最大5M');
        }
        $ext = $this->getext($_FILES['file']['name']);
        if ($_FILES['file']['name'] != '') {
            if (function_exists('exif_imagetype')) {
                if (exif_imagetype($_FILES['file']['tmp_name']) < 1) {
                    return $this->_error('not a imagetype');
                }
            } else {
                if (!in_array($ext, array(".gif", ".png", ".jpg", ".jpeg", ".bmp"))) {
                    return $this->_error('type error');
                }
            }
        }
        $filename = $name . $ext;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $_path . $filename)) {
            $this->_error('can not move to tempath');
        } else {
            $path=$path . $filename;
            $path=$this->toPicService($path);
            if($type=='goods'){
                $goodsImg=new GoodsImage();
                $goodsImg->user_id=$user_id;
                $goodsImg->image_url=$path;
                $goodsImg->goods_id=0;
                $goodsImg->status=1;
                $id=$goodsImg->save(true);
            }elseif ($type=='supply'){
                $goodsImg=new SupplyGoodsImage();
                $goodsImg->user_id=$user_id;
                $goodsImg->image_url=$path;
                $goodsImg->goods_id=0;
                $goodsImg->status=1;
                $id=$goodsImg->save(true);
            }else{
                $UploadLog=new UploadLog();
                $UploadLog->user_id=$user_id;
                $UploadLog->path=$path;
                $UploadLog->type=$_FILES['file']['type'];
                $UploadLog->module=$type;
                $UploadLog->module_id=$about_id;
                $UploadLog->status=1;
                $id=$UploadLog->save(true);
            }
            if($type=='chat'){
                $data = array(
                    'code' => '0',
                    'data'=>array(
                        'name' => $filename,
                        'src' => $path
                    )
                );
                echo json_encode($data);
            }else{
                $data = array(
                    'code' => '0',
                    'id'=>$id,
                    'url' => $path
                );
                echo json_encode($data);
            }

            //$this->toQiniu($path);
        }
    }

    public function toPicService($file_path)
    {
        $return=(new Upload())->curl_file($file_path);
        if($return['status']!=1){
            $this->_error($return['error']);
        }else{
            return $return['file'];
        }
    }

    /*private function curl_file($file_path)
    {
        $curl_url='http://picture.test.cn:8080/upload.php';
        $post = array();
        $post['sign'] = 'picture_upload_img';
        $post['path'] = $file_path;
        $filePath = ROOT . '/public' . $file_path;
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
    }*/

    private function toQiniu($path)
    {
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'eumnKSaYPKOvuZyFvUrXxKNm6DpJ4HJ6Vn9QqzLZ';
        $secretKey = 'ICO6OwtICQbq_XcPGJxqJSEJ-yKHF458CuqyT89E';

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = 'wuluan';
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = ROOT . '/public' . $path;
        // 上传到七牛后保存的文件名
        $key = $path;
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            var_dump($err);
        } else {
            //var_dump($ret);
            return true;
        }
    }

    public function del(Request $request)
    {
        $id=(int)$request->get('id');
        $type=$request->get('type');
        if($type=='goods'){
            $Log=new GoodsImage();
        }elseif($type=='supply'){
            $Log=new SupplyGoodsImage();
        }else{
            $Log=new UploadLog();
        }
        $Log=$Log->findOrFail($id);
        if($Log->user_id==$this->user_id){
            $Log->status=-1;
            $Log->save();
        }
    }

    private function getext($filename)
    {
        return strtolower(strrchr($filename, "."));
    }

    private function _error($msg = '')
    {
        $data = array(
            'code' => 'fail',
            'msg' => "Error:{$msg}"
        );
        echo json_encode($data);
        exit;
    }
}