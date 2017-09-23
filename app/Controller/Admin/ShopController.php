<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;

use App\Model\Shop;
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
        $q=$request->get('q');
        if ($user_id!=0) {
            $where .= " and user_id={$user_id}";
        }
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        if(!empty($q)){
            $where.=" and name like '%{$q}%'";
        }
        if($_GET['recommend']!=''){
            $recommend=(int)$_GET['recommend'];
            $where.=" and recommend=$recommend";
        }

        $data['result']=$shop->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('shop',$data);
    }

    //推荐状态切换
    public function recommend(Shop $shop, Request $request)
    {
        $id = (int)$request->get('id');
        $page = (int)$request->get('page');
        $shop = $shop->where("id=?")->bindValues($id)->first();
        if ($shop->recommend == '1') {
            $shop->recommend = 0;
        } else {
            $shop->recommend = 1;
        }
        $shop->save();
        redirect('shop/?page=' . $page)->with('msg', '操作成功！');
    }
    
    public function edit(Request $request,Shop $shop)
    {
        $shop=$shop->findOrFail($request->get('user_id'));
        $page=$request->get('page');
        if($shop->status!=1){
            redirect()->back()->with('error','状态异常');
        }
        if($_POST){
            $shop->name=$request->post('name');
            $shop->contacts=$request->post('contacts');
            $shop->tel=$request->post('tel');
            $shop->qq=$request->post('qq');
            $shop->is_presale=$request->post('is_presale');
            $shop->save();
            redirect("shop/?page={$page}")->with('msg','操作成功！');
        }else{
            $this->title='编辑';
            $data['shop']=$shop;
            $this->view('shopEdit',$data);
        }
    }

    public function checked(Request $request,Shop $shop)
    {
        $shop=$shop->findOrFail($request->get('user_id'));
        $page=$request->get('page');
        if($shop->status==1){
            redirect()->back()->with('error','状态异常,勿要重复操作！');
        }
        if($_POST){
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
            redirect("shop/?page={$page}")->with('msg','操作成功！');
        }else{
            $this->title='审核';
            $data['shop']=$shop;
            $this->view('shop',$data);
        }
    }     
}