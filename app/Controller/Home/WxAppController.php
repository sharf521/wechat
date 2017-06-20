<?php

namespace App\Controller\Home;

use App\Controller\Controller;
use System\Lib\Request;

class WxAppController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function dianye()
    {

    }
    public function getShareInfo(Request $request)
    {
        $module=$request->get('module');
        $arr=array(
            'title'=>'title',
            'desc'=>'desc'
        );
        echo json_encode($arr);
    }
}