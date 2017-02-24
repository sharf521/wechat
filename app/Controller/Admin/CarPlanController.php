<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:57
 */

namespace App\Controller\Admin;


use App\Model\CarPlan;
use System\Lib\Request;

class CarPlanController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(CarPlan $plan,Request $request)
    {
        if (isset($_POST['showorder'])) {
            $id = $request->id;
            $showorder = $request->showorder;
            foreach ($id as $key => $val) {
                $plan=$plan->find($val);
                $plan->showorder=intval($showorder[$key]);
                $plan->save();
            }
            redirect('carPlan')->with('msg','操作成功！');
        }else{
            $data['list']=$plan->getAll();
            $this->view('carPlan',$data);
        }
    }

    public function add(Request $request,CarPlan $plan)
    {
        if($_POST){
            $order = (int)$request->post('showorder');
            if ($order == 0) {
                $order = 10;
            }
            $plan->name=$request->post('name');
            $plan->picture=$request->post('picture');
            $plan->content=$request->post('content');
            $plan->showorder=$order;
            $plan->save();
            redirect('carPlan')->with('msg','添加成功！');
        }else{
            $this->view('carPlan');
        }
    }
    public function edit(Request $request,CarPlan $plan)
    {
        $plan=$plan->findOrFail($request->id);
        if($_POST){
            $plan->name=$request->post('name');
            $plan->picture=$request->post('picture');
            $plan->content=$request->post('content');
            $plan->showorder=(int)$request->post('showorder');
            $plan->save();
            redirect('carPlan')->with('msg','保存成功！');
        }else{
            $data['row']=$plan;
            $this->view('carPlan',$data);
        }
    }
    public function delete(Request $request,CarPlan $plan)
    {
        $plan=$plan->findOrFail($request->id);
        $plan->delete();
        redirect('carPlan')->with('msg','删除成功！');
    }
}