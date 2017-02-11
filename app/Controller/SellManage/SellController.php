<?php
namespace App\Controller\SellManage;

use App\Controller\Controller;
use App\Model\Shipping;
use App\Model\ShopCategory;

class SellController extends Controller
{
    protected $user;
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        if($this->is_wap){
            $this->template = 'sell_wap';
        }else{
            $this->template = 'sell';
        }
    }

    public function getCates()
    {
        return (new ShopCategory())->getListTree($this->user_id);
        //return (new ShopCategory())->where("user_id=?")->bindValues($this->user_id)->get();
    }

    public function getShippings()
    {
        return (new Shipping())->where('user_id=?')->bindValues($this->user_id)->get();
    }

    public function error()
    {
        echo 'not find page';
    }
}