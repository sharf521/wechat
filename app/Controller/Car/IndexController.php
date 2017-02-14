<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/14
 * Time: 14:55
 */

namespace App\Controller\Car;

use App\Model\CarProduct;

class IndexController extends CarController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(CarProduct $product)
    {
        
        $this->view('index');
    }
}