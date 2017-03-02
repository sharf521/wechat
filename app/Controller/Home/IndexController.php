<?php
namespace App\Controller\Home;

use App\Model\Goods;
use App\UserCenter;

class IndexController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Goods $goods)
    {
        if($this->is_wap){
            redirect('car');
            $data['goods_result']=$goods->where("status=1 and stock_count>0")->orderBy('id desc')->limit("0,10")->get();
        }else{
            $data['images']=array('\themes\default\images\ad1.jpg','\themes\default\images\ad2.jpg','\themes\default\images\ad3.jpg');
        }
        //$this->view('index',$data);
    }

    public function up()
    {
       

    }
}