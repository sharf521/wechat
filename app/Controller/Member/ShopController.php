<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:51
 */

namespace App\Controller\Member;


use App\Model\Shop;

class ShopController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Shop $shop)
    {
        $shop=$shop->find($this->user_id);
        $data['shop']=$shop;
        $this->view('shop',$data);
    }
}