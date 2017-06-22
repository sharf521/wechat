<?php

namespace App\Controller\Home;

use App\Controller\Controller;
use App\Model\Shop;
use App\Model\User;
use System\Lib\Request;

class WxAppController extends Controller
{
    private $appid='wx204f04f341161ef4';
    private $secret='d9be5427135fd5bbc51693b2347a213d';
    public function __construct()
    {
        parent::__construct();
        $userName=$_REQUEST['user_name'];
        $this->user=(new User())->where('username=?')->bindValues($userName)->first();
        if($this->user->is_exist){
            $this->shop=(new Shop())->where('user_id=?')->bindValues($this->user->id)->first();
            if(!$this->shop->is_exist){
                $this->returnError('No permission');
                exit;
            }
        }else{
            $this->returnError('Not find user');
            exit;
        }        
    }

    public function index()
    {

    }

    public function login(Request $request)
    {
        //获得session_key;
        $code=$request->post('code');
        $url="https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
        $data['url']=$url;
        $json=json_decode(curl_url($url));
        $session_key=$json->session_key;
        $openid=$json->openid;

        //解密数据
        require ROOT.'/app/wxapp_aes/wxBizDataCrypt.php';
        $iv = $request->post('iv');
        $encryptedData = $request->post('encryptedData');
        $pc = new \WXBizDataCrypt($this->appid, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );
        if ($errCode == 0) {
            print_r($data);
            $this->returnSuccess($data);
        } else {
            $this->returnError(json_encode($errCode));
        }
    }

    public function dianye()
    {
        $shop=$this->shop;
        $array=array();
        $array['companyName']=$shop->name;
        $array['address']=$shop->region_name.'-'.$shop->address;
        $array['tel']=$shop->tel;
        $array['imgList']=array(
            'http://mallimg.yuantuwang.com/data/upload/1/2/201702/14871483885622.jpg',
            'http://mallimg.yuantuwang.com/data/upload/1/2/201702/14871484021778.jpg'
        );
        $_arr=explode(',',$shop->gps_wx);
        $array['location']=array(
            'latitude'=>(float)$_arr[0],
            'longitude'=>(float)$_arr[1]
        );
        $array['service']=explode(',',$shop->service);
        $array['content']=$shop->remark;
        //分享
        $array['shareInfo']=array(
            'title'=>$shop->name,
            'desc'=>$shop->service
        );
        $this->returnSuccess($array);
    }

    private function returnSuccess($data = array())
    {
        $data['return_code'] = 'success';
        echo json_encode($data);
    }

    private function returnError($msg)
    {
        $data = array(
            'return_code' => 'fail',
            'return_msg' => $msg
        );
        echo json_encode($data);
    }
}