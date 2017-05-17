<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/26
 * Time: 11:43
 */

namespace App\Controller\SellManage;

use App\Model\UserData;
use System\Lib\Request;

class IndexController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title_herder']='卖家中心';
        $this->view('manage', $data);
    }

    //奖励承诺
    public function commitment(UserData $userData,Request $request)
    {
        $userData=$userData->where("user_id={$this->user_id} and typeid='seller_commitment'")->first();
        if($_POST){
            if(!$userData->is_exist){
                $userData->user_id=$this->user_id;
                $userData->typeid='seller_commitment';
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