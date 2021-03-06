<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;

use App\Center;
use App\Model\Order;
use App\Model\RebateList;
use System\Lib\DB;
use System\Lib\Request;

class RebateListController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(RebateList $rebateList,Request $request)
    {
        if($_POST){
            $ids=$request->post('id');
            sort($ids);//id从底到高
            $page=$request->get('page');
            foreach ($ids as $id){
                $rebate=$rebateList->findOrFail($id);
                if($rebate->status==0){
                    try {
                        DB::beginTransaction();
                        $rebate->status=1;
                        $rebate->start_uid=$this->user_id;
                        $rebate->start_at=time();
                        $rebate->save();
                        $user=$rebate->User();
                        $integral=math($rebate->money,2.52,'*',5);
                        $return=(new Center())->rebateAdd($user->openid,$rebate->typeid,$integral,$rebate->remark,$rebate->site_id);
                        if($return===true){
                            DB::commit();
                        }else{
                            throw new \Exception($return);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error= "Failed: " . $e->getMessage();
                        redirect()->back()->with('error',$error);
                    }
                }else{
                    redirect()->back()->with('error','状态异常');
                }
            }
            redirect("rebateList/?page={$page}")->with('msg','操作完成');
        }else{
            $where = " status>-1";
            $label=$request->get('label');
            $user_id=(int)$request->get('user_id');
            $starttime=$request->get('starttime');
            $endtime=$request->get('endtime');
            $remark=$request->get('remark');
            if($label!=''){
                $where.=" and label='{$label}'";
            }
            if ($user_id!=0) {
                $where .= " and user_id={$user_id}";
            }
            if(!empty($starttime)){
                $where.=" and created_at>=".strtotime($starttime);
            }
            if(!empty($endtime)){
                $where.=" and created_at<".strtotime($endtime);
            }
            if(!empty($remark)){
                $where.=" and remark like '%{$remark}%'";
            }
            $data['result']=$rebateList->where($where)->orderBy('id desc')->pager($_GET['page'],20);
            $this->view('rebateList',$data);
        }
    }
}