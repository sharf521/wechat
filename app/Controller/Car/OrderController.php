<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 10:37
 */

namespace App\Controller\Car;


use App\Model\CarProduct;
use App\Model\CarProductSpec;
use System\Lib\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function confirm(Request $request,CarProduct $product,CarProductSpec $spec)
    {
        //$this->check_login();
        $id=$request->get('id');
        $spec_id=$request->get('spec_id');
        $product=$product->findOrFail($id);
        $spec=$spec->findOrFail($spec_id);
        if($spec->product_id!=$id){
            redirect()->back()->with('error','参数异常');
        }
        $data['product']=$product;
        $data['spec']=$spec;
        $this->title='我要订车';
        $this->view('order_confirm',$data);
    }
}