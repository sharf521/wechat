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
            $category=new Category();
            $cates=$category->getListTree(array('path'=>'2,'));
            array_shift($cates);
            $this->site->cates=$cates;//商品分类

            $articleCates=$category->getList(array('pid'=>1));
            $this->site->articleCates=$articleCates;
        }
    }
}