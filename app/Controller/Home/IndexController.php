<?php
namespace App\Controller\Home;

use App\Model\Advert;
use App\Model\Goods;
use App\Model\OrderGoods;
use App\UserCenter;

class IndexController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Goods $goods,Advert $advert)
    {
        if($this->is_wap){
            redirect('car');
            $data['goods_result']=$goods->where("status=1 and stock_count>0")->orderBy('id desc')->limit("0,10")->get();
        }else{
            $ad_banners=$advert->where("typeid='pc_index_banner' and site_id={$this->site->id}")->get();
            if (empty($ad_banners)) {
                $banners = array(
                    array(
                        'href' => '#',
                        'picture' => '\themes\default\images\ad1.jpg'
                    ),
                    array(
                        'href' => '#',
                        'picture' => '\themes\default\images\ad2.jpg'
                    )
                );
            } else {
                $banners = array();
                foreach ($ad_banners as $ad) {
                    $arr = array(
                        'href' => $ad->href,
                        'picture' => $ad->picture
                    );
                    array_push($banners, $arr);
                }
            }
            $data['banners']=$banners;

            $ad_sides=$advert->where("typeid='pc_index_side' and site_id={$this->site->id}")->get();
            $floorList=array();
            foreach ($this->site->cates as $i=>$cate){
                if($i<5){
                    $goodsList=$goods->getListByHome(9,$cate['id'],$this->site->id);
                    $floorList[$i]['cate']=$cate;
                    $floorList[$i]['goodsList']=$goodsList;
                    //广告位
                    $ad=$ad_sides[$i];
                    if(isset($ad)){
                        $picture=$ad->picture;
                        $url=$ad->url;
                    }else{
                        $picture='/themes/default/images/1F.jpg';
                        $url='#';
                    }
                    $floorList[$i]['ad']['picture']=$picture;
                    $floorList[$i]['ad']['url']=$url;
                }
            }
            $data['floorList']=$floorList;
        }
        $this->view('index',$data);
    }
}