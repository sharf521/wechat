<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/6
 * Time: 15:56
 */

namespace App\Controller\SellManage;


use App\Model\SupplyGoods;
use System\Lib\Request;

class SupplyController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(SupplyGoods $goods,Request $request)
    {
        $data['result']=$goods->where("status=1 and stock_count>0")->orderBy('id desc')->pager($request->get('page'),10);
        $this->view('supply',$data);
    }
}