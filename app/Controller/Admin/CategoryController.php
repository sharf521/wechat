<?php
namespace App\Controller\Admin;

use App\Model\Category;
use System\Lib\DB;
use System\Lib\Request;

class CategoryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Category $category)
    {
        $pid = (int)$_GET['pid'];
        if (isset($_POST['showorder'])) {
            $id = $_POST['id'];
            $showorder = $_POST['showorder'];
            foreach ($id as $key => $val) {
                DB::table('category')->where("id={$val}")->limit(1)->update(array('showorder' => intval($showorder[$key])));
            }
            $category->createjs();
            redirect("category/?pid={$_GET['pid']}")->with('msg','操作成功！');
        } else {
            $data['list'] = $category->getList(array('pid' => $pid));
            if ($pid != 0) {
                $pCate=$category->find($pid);
                $data['pCate']=$pCate;
            }
            $data['pid'] = $pid;
            $this->view('category', $data);
        }
    }

    public function add(Category $category,Request $request)
    {
        $pid=(int)$request->get('pid');
        if ($_POST) {
            $category->pid=$pid;
            $category->name=$request->post('name');
            $category->picture=$request->post('picture');
            $category->title=$request->post('title');
            $category->keyword=$request->post('keyword');
            $category->remark=$request->post('remark');
            $category->showorder=(int)$request->post('showorder');
            $category->aside1=$request->post('aside1');
            $category->aside2=$request->post('aside2');
            $category->aside3=$request->post('aside3');
            $id=$category->save(true);
            $category=(new Category())->find($id);
            if($pid==0){
                $category->path=$id.',';
                $category->level=1;
                $category->save();
            }else{
                $pCate=(new Category())->find($pid);
                $category->path=$pCate->path . $id . ",";
                $category->level=$pCate->level+1;
                $category->save();
            }
            redirect("category/?pid={$pid}")->with('msg','添加成功！');
            $category->createjs();
        } else {
            $category->showorder=10;
            $category->pid=$pid;
            $data['row'] =$category;
            $this->view('category',$data);
        }
    }

    public function edit(Category $category,Request $request)
    {
        $id=$request->get('id');
        $category=$category->findOrFail($id);
        $pid=$category->pid;
        if ($_POST) {
            $category->name=$request->post('name');
            $category->picture=$request->post('picture');
            $category->title=$request->post('title');
            $category->keyword=$request->post('keyword');
            $category->remark=$request->post('remark');
            $category->showorder=(int)$request->post('showorder');
            $category->aside1=$request->post('aside1');
            $category->aside2=$request->post('aside2');
            $category->aside3=$request->post('aside3');
            $category->save();

            $category->createjs();
            redirect("category/?pid={$pid}")->with('msg','修改成功！');
        } else {
            $data['row'] =$category;
            $this->view('category', $data);
        }
    }

    public function delete(Category $category)
    {
        $id = (int)$_GET['id'];
        $category=$category->findOrFail($id);
        $list = $category->getList(array('pid' => $id));
        if ($list) {
            show_msg(array('存在子分类，先删除子分类！'));
            exit;
        }
        $category->delete();
        $category->createjs();
        redirect("category/?pid={$category->pid}")->with('msg','删除成功');
    }
}
