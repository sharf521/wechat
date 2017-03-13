<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:57
 */

namespace App\Controller\Car;


use App\Model\CarBrand;
use System\Lib\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(CarBrand $brand,Request $request)
    {
        $data['brands']=$brand->getAll();
        $this->title='品牌列表';
        $this->view('brand',$data);
    }
}