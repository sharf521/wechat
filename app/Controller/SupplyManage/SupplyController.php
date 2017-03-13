<?php
namespace App\Controller\SupplyManage;

use App\Controller\Controller;
use App\Model\Shipping;
use App\Model\ShopCategory;

class SupplyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        if($this->is_wap){
            $this->template = 'supply_wap';
        }else{
            $this->template = 'supply';
        }
    }

    public function getCates()
    {
        return (new ShopCategory())->getListTree($this->user_id);
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