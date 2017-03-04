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
            $goodsCategoryArray=$category->getListTree(array('path'=>'2,'));
            array_shift($goodsCategoryArray);
            foreach ($goodsCategoryArray as $i=>$cate){
                if(!in_array($cate['id'],$this->site->goodsCates)){
                    unset($goodsCategoryArray[$i]);
                }
            }
            $this->site->cates=$goodsCategoryArray;//商品分类

            $articleCategoryAll=$category->getList(array('pid'=>1));
            foreach ($articleCategoryAll as $i=>$cate){
                if(!in_array($cate->id,$this->site->articleCates)){
                    unset($articleCategoryAll[$i]);
                }
            }
            $this->site->articleCates=$articleCategoryAll;
        }
    }
}