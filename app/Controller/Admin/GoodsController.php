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
        if($_GET['recommend']!=''){
            $recommend=(int)$_GET['recommend'];
            $where.=" and recommend=$recommend";
        }
        $data['result']=$goods->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('goods',$data);
    }

    //推荐状态切换
    public function recommend(Goods $goods, Request $request)
    {
        $id = (int)$request->get('id');
        $page = (int)$request->get('page');
        $goods = $goods->findOrFail($id);
        if ($goods->recommend == '1') {
            $goods->recommend = 0;
        } else {
            $goods->recommend = 1;
        }
        $goods->save();
        redirect('goods/?page=' . $page)->with('msg', '操作成功！');
    }
}