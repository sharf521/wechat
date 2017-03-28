<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;

use App\Model\Goods;
use System\Lib\Request;

class GoodsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Goods $goods,Request $request)
    {
        $where = " status>-1";
        $user_id=(int)$request->get('user_id');
        $supply_user_id=(int)$request->get('supply_user_id');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        $q=$request->get('q');
        if ($user_id!=0) {
            $where .= " and user_id={$user_id}";
        }
        if($supply_user_id!=0){
            $where .= " and supply_user_id={$supply_user_id}";
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
        $data['result']=$goods->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('goods',$data);
    }
}