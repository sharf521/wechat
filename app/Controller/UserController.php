<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/14
 * Time: 17:22
 */

namespace App\Controller;


use App\Center;
use System\Lib\Request;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $center=new Center();
        $url=$center->loginUrl();
        $url='http://center.test.cn:800/'.$url;
        redirect($url);
    }

    public function register()
    {
        $center=new Center();
        $url=$center->registerUrl();
        $url='http://center.test.cn:800/'.$url;
        redirect($url);
    }

    public function auth(Request $request,Center $center)
    {
        $target_url=session('target_url');
        if(empty($target_url)){
            $target_url='/';
        }
        $openid=$request->get('openid');
        
        print_r($center->getUserInfo($openid));
        echo $target_url;
    }
}