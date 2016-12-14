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
            $name=$request->post('name');
            $contacts=$request->post('contacts');
            $tel=$request->post('tel');
            $qq=$request->post('qq');
            $remark=$request->post('remark');
            if(!$shop->is_exist){
                $shop->user_id=$this->user_id;
                $shop->status=0;
            }
            $shop->name=$name;
            $shop->contacts=$contacts;
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
            $data['shop']=$shop;
            $this->view('shop',$data);
        }
    }
}