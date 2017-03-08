<?php

namespace App\Controller\Member;

use App\Model\Notice;
use System\Lib\Request;

class NoticeController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Notice $notice)
    {
        $result=$notice->where("status>0 and user_id=?")->bindValues($this->user_id)->pager($_GET['page'],10);
        $data['result']=$result;
        $this->view('notice',$data);
    }


    public function del(Notice $notice,Request $request)
    {
        $notice=$notice->findOrFail($request->get('id'));
        if($notice->user_id==$this->user_id){
            $notice->status=-1;
            $notice->save();
        }
        redirect('notice')->with('msg','己删除！');
    }
}