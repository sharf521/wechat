<?php

namespace App\Controller\SupplyManage;

class IndexController extends SupplyController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title_herder'] = '供应商中心';
        $this->view('manage', $data);
    }
}