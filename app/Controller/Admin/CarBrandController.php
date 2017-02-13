<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:57
 */

namespace App\Controller\Admin;


use App\Model\CarBrand;
use System\Lib\Request;

class CarBrandController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(CarBrand $brand,Request $request)
    {
        if (isset($_POST['showorder'])) {
            $id = $request->id;
            $showorder = $request->showorder;
            foreach ($id as $key => $val) {
                $brand=$brand->find($val);
                $brand->showorder=intval($showorder[$key]);
                $brand->save();
            }
            redirect('CarBrand')->with('msg','操作成功！');
        }else{
            $data['list']=$brand->orderBy('`showorder`,id')->get();
            $this->view('carBrand',$data);
        }
    }

    public function add(Request $request,CarBrand $brand)
    {
        if($_POST){
            $order = (int)$request->post('showorder');
            if ($order == 0) {
                $order = 10;
            }
            $brand->name=$request->post('name');
            $brand->picture=$request->post('picture');
            $brand->showorder=$order;
            $brand->save();
            redirect('CarBrand')->with('msg','添加成功！');
        }else{
            $this->view('carBrand');
        }
    }
    public function edit(Request $request,CarBrand $brand)
    {
        $brand=$brand->findOrFail($request->id);
        if($_POST){
            $brand->name=$request->post('name');
            $brand->picture=$request->post('picture');
            $brand->showorder=(int)$request->post('showorder');
            $brand->save();
            redirect('CarBrand')->with('msg','保存成功！');
        }else{
            $data['row']=$brand;
            $this->view('carBrand',$data);
        }
    }
    public function delete(Request $request,CarBrand $brand)
    {
        $brand=$brand->findOrFail($request->id);
        $brand->delete();
        redirect('CarBrand')->with('msg','删除成功！');
    }
}