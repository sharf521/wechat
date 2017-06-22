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
        require ROOT.'/app/wxapp_aes/wxBizDataCrypt.php';
        $appid = 'wx204f04f341161ef4';
        $sessionKey = 'vuq52s0w\/rvT\/+pOP6P1Sg==';

        $encryptedData="b1IdVLL+DcLCWMdvA4SPzfi5gQJVQldweC/zXlaszV6LXUn+Y69GRXSLAPPKk2Wke1GRECw2hobNtLrPqAOZPD9AC7dngrQhIb/b5PYJBcbX+fMv11gD7jxEFBoT9hMU3m1cRk+9FmBUa13EXUe7t+VmjtQQfhXJ3puwAnJRmZI9dWHzjo17tgZERKRkGH8SgMyUBOlwj2HD1RaLJCdTDB2bw8ndrt6fgnavmIIoik1tPWnRddm+lAVGJuDDFq0OSBnW37fhioVP9L1faAl7i67MPnWHzBix6SKDkYpFVVU6iWufLCfsPez60MVH3W0HEBaa0GAgvv0nvL4Y29wAsXuwWTxGTYo4m20G+e0S+tFIWGZ9jeeg0OG7uKy+QZMc87YE2oy0yStlPdWWl4uJHte9/XvyBg24pw3+K2uz5mxeuuHREM+AzaI6LtEZcsPTiPei1fSCTLgsCENDsMWBKndHtqQh8DDo6S8FUtdYYtj2URtSLaq/sgGGYlfyPNVlNZUpJ84K6iHHQ2FUSkMTBA==";

        $iv = "FNKWb7zTaezsO73o2Za8Og==";

        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            print($data . "\n");
        } else {
            print($errCode . "\n");
        }
    }

    public function login(Request $request)
    {
        $code=$request->post('code');
        $url="https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
        $data['url']=$url;
        $data['html']=curl_url($url);
        $data['post']=$_POST;
        $this->returnSuccess($data);
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

        $_arr=explode(',',$shop->gps);
        $url="http://apis.map.qq.com/ws/coord/v1/translate?locations={$_arr[1]},{$_arr[0]}&type=3&key=6EEBZ-YJRA5-MFTIZ-QJN5O-6A36V-CAFDO";
        $html=curl_url($url);
        $result=json_decode($html,true);
        $array['location']=array(
            'latitude'=>(float)$result['locations'][0]['lat'],
            'longitude'=>(float)$result['locations'][0]['lng']
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