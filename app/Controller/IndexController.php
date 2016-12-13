<?php
namespace App\Controller;

use App\Model\Goods;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Goods $goods)
    {
        $data['goods_result']=$goods->where("status=1 and stock_count>0")->orderBy('id desc')->limit("0,10")->get();
        $this->view('index',$data);
    }
}