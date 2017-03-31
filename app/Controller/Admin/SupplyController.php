<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;

use App\Model\Shop;
use App\Model\Supply;
use System\Lib\Request;

class SupplyController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Supply $supply,Request $request)
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
        $data['result']=$supply->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('supply',$data);
    }

    public function checked(Request $request,Supply $supply)
    {
        $supply=$supply->findOrFail($request->get('user_id'));
        if($_POST){
            if($supply->status==1){
                redirect()->back()->with('error','状态异常,勿要重复操作！');
            }
            $checked=$request->post('checked');
            if(! in_array($checked,array(1,2))){
                redirect()->back()->with('error','数据异常！');
            }
            $supply->status=$checked;
            $supply->verify_userid=$this->user_id;
            $supply->verify_at=time();
            $supply->verify_remark=$request->post('verify_remark');
            $supply->save();
            if($checked==1){
                $user=$supply->User();
                $user->is_supply=1;
                $user->save();
            }
            redirect('supply')->with('msg','操作成功！');
        }else{
            $this->title='审核';
/*            $center=new Center();
            $user=(new User())->find($shop->user_id);
            $account=$center->getUserFunc($user->openid);
            $data['user']=$user;
            $data['account']=$account;*/
            $data['supply']=$supply;
            $data['shop']=$supply->Shop();
            $this->view('supply',$data);
        }
    }     
}