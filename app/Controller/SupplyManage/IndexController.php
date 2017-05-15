<?php

namespace App\Controller\SupplyManage;

use App\Model\UserData;
use System\Lib\Request;

class IndexController extends SupplyController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->title = '供应商中心';
        $this->view('manage');
    }

    //奖励承诺
    public function commitment(UserData $userData,Request $request)
    {
        $userData=$userData->where("user_id={$this->user_id} and typeid='supply_commitment'")->first();
        if($_POST){
            if(!$userData->is_exist){
                $userData->user_id=$this->user_id;
                $userData->typeid='supply_commitment';
            }
            $userData->content=$request->post('content',false);
            $userData->save();
            redirect()->back()->with('msg','己保存');
        }else{
            $this->title= '奖励承诺';
            $data['content']=$userData->content;
            $this->view('commitment', $data);
        }        
    }
}