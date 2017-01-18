<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/18
 * Time: 15:31
 */

namespace App\Controller\SellManage;


use App\Model\ShopCategory;
use System\Lib\Request;

class CategoryController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(ShopCategory $category)
    {
        $data['cates']=$category->getListTree($this->user_id);
        $this->view('category',$data);
    }

    public function add(ShopCategory $category,Request $request)
    {
        if($_POST){
            $pid=(int)$request->post('pid');
            $name=$request->post('name');
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            $path='';
            if($pid!=0){
                $path=(new ShopCategory())->find($pid)->path;
            }
            $category->user_id=$this->user_id;
            $category->pid=$pid;
            $category->path=$path;
            $category->name=$name;
            $category->showorder=10;
            $id=$category->save(true);
            $cate=$category->find($id);
            $cate->path=$cate->path.$id.',';
            $cate->save();
            redirect('category')->with('msg','添加成功！');
        }else{
            $data=array();
            $this->view('category',$data);
        }
    }

    public function edit(ShopCategory $category,Request $request)
    {
        $id=(int)$request->get('id');
        $cate=$category->findOrFail($id);
        if($cate->user_id!=$this->user_id){
            redirect()->back()->with('error','异常！');
        }
        if($_POST){
            $name=$request->post('name');
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            $cate->name=$name;
            $cate->save();
            redirect('category')->with('msg','修改成功！');
        }else{
            $data['cate']=$cate;
            $this->view('category',$data);
        }
    }

    public function del(ShopCategory $category,Request $request)
    {
        $category=$category->findOrFail($request->get('id'));
        if($category->user_id==$this->user_id){
            $sonCount=(new ShopCategory())->where("pid={$category->id}")->value("count(*)",'int');
            if($sonCount==0){
                $category->delete();
                redirect('category')->with('msg','册除成功！');
            }else{
                redirect()->back()->with('error','请先删除子分类！');
            }
        }else{
            redirect('category')->with('error','操作失败！');
        }
    }
}