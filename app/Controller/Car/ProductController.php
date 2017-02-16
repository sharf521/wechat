<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/15
 * Time: 15:16
 */

namespace App\Controller\Car;


use App\Model\CarProduct;
use System\Lib\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function lists(CarProduct $product,Request $request)
    {
        $where='status=1';
        $brand_name=$request->get('brand_name');
        if($brand_name!=''){
            $where.=" and brand_name='{$brand_name}'";
        }
        $data['result']=$product->where($where)->pager($request->get('page'));
        $this->title='车辆列表';
        $this->view('product_lists',$data);
    }

    public function detail(Request $request,CarProduct $product)
    {
        $id=$request->get('id');
        $product=$product->findOrFail($id);
        $product->price=$product->price/10000;
        $product->content=$product->CarProductData()->content;
        $product->specs=$product->CarProductSpec();
        $data['product']=$product;
        $this->title='产品详情';
        $this->view('product_detail',$data);
    }
}