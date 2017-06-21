<?php

namespace App\Controller\Home;

use App\Controller\Controller;
use App\Model\Shop;
use App\Model\User;
use System\Lib\Request;

class WxAppController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $userName=(new Request())->get('user_name');
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

    public function index()
    {

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
}