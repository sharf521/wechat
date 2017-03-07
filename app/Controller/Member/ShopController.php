<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:51
 */

namespace App\Controller\Member;


use App\Model\Shop;
use System\Lib\Request;

class ShopController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Shop $shop,Request $request)
    {
        $shop=$shop->find($this->user_id);
        if($_POST){
            if(!$shop->is_exist){
                $shop->user_id=$this->user_id;
                $shop->status=0;
            }
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
            $shop->remark=$remark;
            $shop->save();
            if($shop->is_exist){
                redirect('shop')->with('msg','修改成功！');
            }else{
                redirect('shop')->with('msg','己提交，等待审核！');
            }
        }else{
            $region_name=explode('-',$shop->region_name);
            $shop->province=$region_name[0];
            $shop->city=$region_name[1];
            $shop->county=$region_name[2];
            $data['shop']=$shop;
            $this->title='申请开店';
            $this->view('shop',$data);
        }
    }
}