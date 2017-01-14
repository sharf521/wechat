<?php
namespace App\Controller;

use App\Model\Goods;
use App\UserCenter;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Goods $goods)
    {
        if($this->is_wap){
            $data['goods_result']=$goods->where("status=1 and stock_count>0")->orderBy('id desc')->limit("0,10")->get();
        }else{
           
        }
        $this->view('index',$data);
    }

    public function up()
    {
       

    }
}