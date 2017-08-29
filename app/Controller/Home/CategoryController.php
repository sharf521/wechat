<?php

namespace App\Controller\Home;

use App\Model\Category;

class CategoryController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $category=new Category();
        $goodsCategoryArray=$category->getListTree(array('path'=>'2,'));
        array_shift($goodsCategoryArray);
        foreach ($goodsCategoryArray as $i=>$cate){
            if(is_array($this->site->goodsCates) && !in_array($cate['id'],$this->site->goodsCates)){
                unset($goodsCategoryArray[$i]);
            }
        }
        $data['cates']=$goodsCategoryArray;//商品分类
        $this->title='分类';
        $this->view('category',$data);
    }
}