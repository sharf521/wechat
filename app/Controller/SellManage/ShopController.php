<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:51
 */

namespace App\Controller\SellManage;

use App\Model\Shop;
use System\Lib\Request;

class ShopController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Shop $shop,Request $request)
    {
        if($this->is_wap){
            redirect()->back()->with('error','请在电脑端操作');
        }
        $shop=$shop->findOrFail($this->user_id);
        if($_POST){
            $name=$request->post('name');
            $contacts=$request->post('contacts');
            $tel=$request->post('tel');
            $qq=$request->post('qq');
            $remark=$request->post('content',false);
            $shop->name=$name;
            $shop->contacts=$contacts;
            $shop->region_name=$request->post('province').'-'.$request->post('city').'-'.$request->post('county');
            $shop->address=$request->post('address');
            $shop->tel=$tel;
            $shop->qq=$qq;
            $shop->is_fulldown=(int)$request->post('is_fulldown');
            $shop->remark=$remark;
            $shop->service=str_replace('，',',',$request->post('service'));
            $shop->gps=$request->post('gps');
            if($shop->gps!=''){
                $_arr=explode(',',$shop->gps);
                $url="http://apis.map.qq.com/ws/coord/v1/translate?locations={$_arr[1]},{$_arr[0]}&type=3&key=6EEBZ-YJRA5-MFTIZ-QJN5O-6A36V-CAFDO";
                $html=curl_url($url);
                $result=json_decode($html,true);
                $shop->gps_wx=(float)$result['locations'][0]['lat'].','.(float)$result['locations'][0]['lng'];
            }
            $shop->save();
            redirect('shop')->with('msg','修改成功！');
        }else{
            $region_name=explode('-',$shop->region_name);
            $shop->province=$region_name[0];
            $shop->city=$region_name[1];
            $shop->county=$region_name[2];
            $data['shop']=$shop;
            $this->title='店铺设置';
            $this->view('shop',$data);
        }
    }
}