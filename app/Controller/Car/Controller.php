<?php

namespace App\Controller\Car;

class Controller extends \App\Controller\Controller
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