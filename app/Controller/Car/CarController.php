<?php

namespace App\Controller\Car;

use App\Controller\Controller;

class CarController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->is_wap){
            $this->template = 'car_wap';
        }else{
            $this->template = 'car';
        }
    }
}