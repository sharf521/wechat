<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 10:51
 */

namespace App\Controller\SellManage;

use App\Model\Shop;
use App\Model\ShopAdvert;
use System\Lib\Request;

class AdvertController extends SellController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(ShopAdvert $advert,Request $request)
    {
        if($this->is_wap){
            redirect()->back()->with('error','请在电脑端操作');
        }
        $advert=$advert->find($this->user_id);
        if(!$advert->is_exist){
            $advert->user_id=$this->user_id;
            $id=$advert->save(true);
            $advert=$advert->find($id);
        }
        if($_POST){
            $advert->pc_banner=$request->post('pc_banner');
            $advert->pc_banner_link=$request->post('pc_banner_link');


            $advert->wap_banner1=$request->post('wap_banner1');
            $advert->wap_banner_link1=$request->post('wap_banner_link1');

            $advert->wap_banner2=$request->post('wap_banner2');
            $advert->wap_banner_link2=$request->post('wap_banner_link2');

            $advert->wap_banner3=$request->post('wap_banner3');
            $advert->wap_banner_link3=$request->post('wap_banner_link3');

            $advert->save();
            redirect('advert')->with('msg','保存成功！');
        }else{
            $data['row']=$advert;
            $this->title='店铺广告位设置';
            $this->view('advert',$data);
        }
    }
}