<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/14
 * Time: 14:55
 */

namespace App\Controller\Car;

use App\Model\Advert;
use App\Model\CarBrand;
use App\Model\CarProduct;

class IndexController extends CarController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(CarProduct $product,Advert $advert)
    {
        $data['ads']=$advert->getRealList('wap_car_banner');
        $data['brands']=(new CarBrand())->orderBy('showorder,id')->limit('0,6')->get();
        $data['products']=$product->where("status=1 and is_recommend=1")->limit('0,10')->get();
        $this->view('index',$data);
    }
}