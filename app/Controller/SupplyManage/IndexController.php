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
        $data['title_herder'] = '供货商中心';
        $this->view('manage', $data);
    }
}