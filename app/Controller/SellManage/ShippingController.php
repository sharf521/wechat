<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/17
 * Time: 17:16
 */

namespace App\Controller\SellManage;


use App\Model\Region;
use App\Model\Shipping;
use System\Lib\Request;

class ShippingController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Shipping $shipping)
    {
        $ships=$shipping->where("user_id=?")->bindValues($this->user_id)->get();
        foreach($ships as $key=>$ship)
        {
            $ships[$key]->areas=unserialize($ship->code_areas);
        }
        $data['ships']=$ships;
        $this->view('shipping',$data);
    }

    //配送方式数据处理
    private function setData($data)
    {
        $ships=array();
        $data['v_val_tr0']='default';
        $data['v_txt_tr0']='全国';
        foreach ($data['one'] as $i => $v) {
            $t = trim($data['v_val_tr' . $i]);
            $tt = $data['v_txt_tr' . $i];
            if ($t != '') {
                if ($data['one'][$i] <= 0) {
                    $data['one'][$i] = 1;
                }
                if ($data['next'][$i] <= 0) {
                    $data['next'][$i] = 1;
                }
                $ship = array(
                    'areaid' => $t,
                    'areaname' => $tt,
                    'one' => abs((int)$data['one'][$i]),
                    'price' => abs((float)$data['price'][$i]),
                    'next' => abs((int)$data['next'][$i]),
                    'nprice' => abs((float)$data['nprice'][$i])
                );
                array_push($ships, $ship);
            }
        }
        $data['code_areas']=serialize($ships);//转换成字符串
        return $data;
    }

    public function add(Request $request,Shipping $shipping)
    {
        if($_POST){
            $name=$request->post('name');
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            $data=$this->setData($_POST);
            $shipping->code_areas=$data['code_areas'];
            $shipping->name=$name;
            $shipping->user_id=$this->user_id;
            $shipping->typeid=1;
            $shipping->status=1;
            $shipping->updated_at=time();
            $shipping->save();
            redirect('shipping')->with('msg','添加成功！');
        }else{
            $data['regions']=(new Region())->getShippingRegion();
            $this->view('shipping_form',$data);
        }
    }

    public function edit(Shipping $shipping,Request $request)
    {
        $id=(int)$request->get('id');
        $shipping=$shipping->findOrFail($id);
        if($shipping->user_id!=$this->user_id){
            redirect()->back()->with('error','异常！');
        }
        if($_POST){
            $name=$request->post('name');
            if(empty($name)){
                redirect()->back()->with('error','名称不能为空！');
            }
            $data=$this->setData($_POST);
            $shipping->code_areas=$data['code_areas'];
            $shipping->name=$name;
            $shipping->user_id=$this->user_id;
            $shipping->typeid=1;
            $shipping->status=1;
            $shipping->updated_at=time();
            $shipping->save();
            redirect('shipping')->with('msg','修改成功！');
        }else{
            $data['regions']=(new Region())->getShippingRegion();
            $shipping->areas=unserialize($shipping->code_areas);
            $data['shipping']=$shipping;
            $this->view('shipping_form',$data);
        }
    }

    public function del(Shipping $shipping,Request $request)
    {
        $shipping=$shipping->findOrFail($request->get('id'));
        if($shipping->user_id==$this->user_id){
            $shipping->delete();
            redirect('shipping')->with('msg','册除成功！');
        }else{
            redirect('shipping')->with('error','操作失败！');
        }
    }
}