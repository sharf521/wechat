<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:51
 */

namespace App\Controller\SellManage;

use App\Model\Supply;
use System\Lib\Request;

class ApplySupplyController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Supply $supply,Request $request)
    {
        if($this->is_wap){
            redirect()->back()->with('error','请在电脑端操作');
        }
        $supply=$supply->find($this->user_id);
        if($supply->status==1){
            redirect()->back()->with('error','己开店成功！');
        }
        if($_POST){
            $supply->site_id=$this->site->id;
            $supply->user_id=$this->user_id;
            $supply->company_name=$request->post('company_name');
            $supply->company_owner=$request->post('company_owner');
            $supply->picture1=$request->post('picture1');
            $supply->picture2=$request->post('picture2');
            $supply->picture3=$request->post('picture3');
            $remark=$request->post('content',false);
            $supply->remark=$remark;
            $supply->status=0;
            $supply->save();
            redirect('applySupply')->with('msg','修改成功！');
        }else{
            $data['supply']=$supply;
            $this->title='申请供货商';
            $this->view('apply_supply',$data);
        }
    }
}