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
        $array=array();
        $array['companyName']='闪电宝MPOS机';
        $array['address']='郑州市中原区建设西路118号美丽源大厦5-4-1701';
        $array['tel']='037156600698';
        $array['imgList']=array(
            'http://mallimg.yuantuwang.com/data/upload/1/2/201702/14871483885622.jpg',
            'http://mallimg.yuantuwang.com/data/upload/1/2/201702/14871484021778.jpg'
        );
        $array['location']=array(
            'latitude'=>34.754689,
            'longitude'=>113.614009
        );
        $array['service']=array('闪电宝', 'POS机', '手刷', '招代理','个人POS机');
        $array['content']='坤通金融：特邀大家上门喝茶免费开机，我们已经准备好了上好的茶等候阁下光临，您只需带着你的信用卡，银行卡，身份证，就可到我公司来喝茶，并免费开启闪电宝一台。';

        //分享
        $array['shareInfo']=array(
            'title'=>'share_title',
            'desc'=>'share_desc'
        );
        $this->returnSuccess($array);
    }
}