<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;


use App\Center;

use App\Model\Shop;
use App\Model\System;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class ShopController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Shop $shop,Request $request)
    {
        $where = " 1=1";
        $user_id=(int)$request->get('user_id');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if ($user_id!=0) {
            $where .= " and user_id={$user_id}";
        }
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$shop->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('shop',$data);
    }

    public function checked(Request $request,Shop $shop)
    {
        $shop=$shop->findOrFail($request->get('user_id'));
        if($_POST){
            if($shop->status==1){
                redirect()->back()->with('error','状态异常,勿要重复操作！');
            }
            $checked=$request->post('checked');
            if(! in_array($checked,array(1,2))){
                redirect()->back()->with('error','数据异常！');
            }
            $shop->status=$checked;
            $shop->verify_userid=$this->user_id;
            $shop->verify_at=time();
            $shop->verify_remark=$request->post('verify_remark');
            $shop->save();
            if($checked==1){
                $user=$shop->User();
                $user->is_shop=1;
                $user->save();
            }
            redirect('shop')->with('msg','操作成功！');
        }else{
            $this->title='审核';
/*            $center=new Center();
            $user=(new User())->find($shop->user_id);
            $account=$center->getUserFunc($user->openid);
            $data['user']=$user;
            $data['account']=$account;*/
            $data['shop']=$shop;
            $this->view('shop',$data);
        }
    }     
}