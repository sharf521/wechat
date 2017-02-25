<?php
namespace App\Controller\Admin;

use App\Model\Category;
use System\Lib\DB;

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
                $row = DB::table('category')->where("id={$pid}")->row();
                $pid = $row['pid'];
                $data['level'] = $row['level'];
            }
            $data['pid'] = $pid;
            $this->view('category', $data);
        }
    }

    public function add(Category $category)
    {
        if ($_POST) {
            $category->add($_POST);
            redirect("category/?pid={$_GET['pid']}")->with('msg','添加成功！');
            $category->createjs();
        } else {
            $this->view('category');
        }
    }

    public function edit(Category $category)
    {
        if ($_POST) {
            $pid = $_POST['pid'];
            $category->edit($_POST);
            $category->createjs();
            redirect("category/?pid={$pid}")->with('msg','修改成功！');
        } else {
            $data['row'] = DB::table('category')->where("id=?")->bindValues($_GET['id'])->row();
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
