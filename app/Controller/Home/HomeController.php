<?php
//前台控制器父类

namespace App\Controller\Home;


use App\Controller\Controller;
use App\Model\Category;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->is_wap){
            $this->template = 'default_wap';
        }else{
            $this->template = 'default';
        }
        if(!$_POST){
            $cates=(new Category())->getListTree(array('path'=>'2,'));
            array_shift($cates);
            $this->site->cates=$cates;
        }
    }
}