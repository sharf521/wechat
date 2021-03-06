<?php
namespace App\Controller\Admin;

use App\Model\CarPlan;
use App\Model\CarProduct;
use App\Model\CarBrand;
use App\Model\CarProductData;
use App\Model\CarProductSpec;
use System\Lib\DB;
use System\Lib\Request;

class CarProductController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->brandList=(new CarBrand())->getAll();
        $this->planList=(new CarPlan())->getAll();
    }

    public function index(CarProduct $product,Request $request)
    {
        $where = ' status>-1 ';
        $brand_name=$request->get('brand_name');
        $plan_id=$request->get('plan_id');
        $keyword=$request->get('keyword');
        if (!empty($keyword)) {
            $where .= " and name like '%{$keyword}%'";
        }
        if (!empty($brand_name)) {
            $where .= " and brand_name='{$brand_name}'";
        }
        if (!empty($plan_id)) {
            $where .= " and plan_id='{$plan_id}'";
        }
        $result = $product->orderBy('id desc')->where($where)->pager($_GET['page']);
        $data['result'] = $result;
        $data['plans'] = $this->planList;
        $data['brands']=$this->brandList;
        $this->view('carProduct', $data);
    }

    function add(CarProduct $product,CarProductData $productData,Request $request)
    {
        if ($_POST) {
            $name=$request->post('name');
            $price=(float)$request->post('price');
            $content=$_POST['content'];
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            try{
                DB::beginTransaction();
                $product->name=$name;
                $product->brand_name=$request->post('brand_name');
                $product->plan_id=$request->post('plan_id');
                $product->price=$price;
                $product->picture=$request->post('picture');
                $product->status=0;
                $product->is_recommend=(int)$request->post('is_recommend');
                $insert_id=$product->save(true);
                $productData->id=$insert_id;
                $productData->content=$content;
                $productData->save();

                //规格
                $time_limit=$request->post('time_limit');
                $first_payment=$request->post('first_payment');
                $month_payment=$request->post('month_payment');
                $last_payment=$request->post('last_payment');
                foreach($time_limit as $i=>$v){
                    $spec=new CarProductSpec();
                    $spec->product_id=$insert_id;
                    $spec->time_limit=(int)$time_limit[$i];
                    $spec->first_payment=$first_payment[$i];
                    $spec->month_payment=$month_payment[$i];
                    $spec->last_payment=$last_payment[$i];
                    $spec->save();
                }

                DB::commit();
                redirect('CarProduct')->with('msg', '添加成功！');
            }catch(\Exception $e){
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $data['plans'] = $this->planList;
            $data['brands']=$this->brandList;
            $data['specs']=array('','');
            $this->view('carProduct', $data);
        }
    }

    //修改
    function edit(CarProduct $product,CarProductData $productData,Request $request)
    {
        $id = (int)$request->id;
        $product=$product->findOrFail($id);
        if ($_POST) {
            $name=$request->post('name');
            $price=(float)$request->post('price');
            $content=$_POST['content'];
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            try{
                DB::beginTransaction();
                $product->name=$name;
                $product->brand_name=$request->post('brand_name');
                $product->plan_id=$request->post('plan_id');
                $product->price=$price;
                $product->picture=$request->post('picture');
                $product->is_recommend=(int)$request->post('is_recommend');
                $product->save(true);
                $productData=$productData->find($id);
                $productData->content=$content;
                $productData->save();

                //规格
                $time_limit=$request->post('time_limit');
                $first_payment=$request->post('first_payment');
                $month_payment=$request->post('month_payment');
                $last_payment=$request->post('last_payment');
                $array_spec=array(0);
                $spec_id=$request->post('spec_id');
                foreach($time_limit as $i=>$v){
                    $spec=(new CarProductSpec())->find($spec_id[$i]);
                    $spec->product_id=$id;
                    $spec->time_limit=(int)$time_limit[$i];
                    $spec->first_payment=$first_payment[$i];
                    $spec->month_payment=$month_payment[$i];
                    $spec->last_payment=$last_payment[$i];
                    if($spec->is_exist){
                        $spec->save();
                        array_push($array_spec,$spec_id[$i]);
                    }else{
                        $_id=$spec->save(true);
                        array_push($array_spec,$_id);
                    }
                }
                DB::table('car_product_spec')->where("product_id={$id} and id not in(".implode(',',$array_spec).")")->delete();

                DB::commit();
                redirect('carProduct')->with('msg', '添加成功！');
            }catch(\Exception $e){
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $data['row'] = $product;
            $data['row']->content =  $product->CarProductData()->content;
            $data['specs']=$product->CarProductSpec();
            $data['plans'] = $this->planList;
            $data['brands']=$this->brandList;
            $this->view('carProduct', $data);
        }
    }

    //状态切换
    public function change(CarProduct $product, Request $request)
    {
        $id = (int)$request->get('id');
        $page = (int)$request->get('page');
        $art = $product->findOrFail($id);
        if ($art->status == '1') {
            $art->status = 0;
        } else {
            $art->status = 1;
        }
        //var_dump($art);
        $art->save();
        redirect('CarProduct/?page=' . $page)->with('msg', '操作成功！');
    }

    //删除
    public function delete(CarProduct $product, Request $request)
    {
        $id = $request->get('id', 'int');
        $page = $request->get('page', 'int');
        $art = $product->findOrFail($id);
        $art->status = -1;
        
        if ($art->save()) {
            redirect('CarProduct/?page=' . $page)->with('msg', '删除成功！');
        } else {
            redirect('CarProduct/?page=' . $page)->with('error', '删除失败！');
        }
    }
}