<?php require 'header.php';?>

    <div class="swiper-container car_mes">
        <div class="swiper-wrapper">
            <? foreach ($ads as $ad) : ?>
            <div class="swiper-slide"><a href="<?=$ad->url?>"><img src="<?=$ad->picture?>" style="max-width: 100%;"></a></div>
            <? endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!--ad end-->
    <div class="m_regtilinde">推荐品牌<span><a href="<?=url('brand')?>"></a></span></div>
    <div class="br_box clearFix">
        <ul class="clearFix">
            <? foreach ($brands as $brand) : ?>
                <li><a href="<?=url("product/lists/?brand_name={$brand->name}")?>"><div><img src="<?=$brand->picture?>" /></div><span><?=$brand->name?></span></a></li>
            <? endforeach;?>
        </ul>
    </div>

    <div class="m_regtilinde" style="margin-top: -1px">推荐汽车<span><a href="<?=url('product/lists')?>">查看更多</a></span></div>
    <div class="clearFix">
        <ul class="commoditylist_content">
            <? foreach ($products as $product) : ?>
                <li>
                    <a href="<?=url("product/detail/?id={$product->id}")?>">
                        <span class="imgspan">
                            <img src="/themes/images/blank.gif" data-echo="<?=$product->picture?>_100X100.png">
                        </span>
                        <div class="info">
                            <p class="cd_title"><?=$product->name?></p>
                            <p class="cd_money">
                                <var><?=$product->price/10000?>万</var>
                            </p>
                            <p class="cd_sales"><?=$product->brand_name?></p>
                        </div>
                        <i class="iconfont"></i>
                    </a>
                </li>
            <? endforeach;?>
        </ul>
    </div><br><br>
    <div class="weui-tabbar" style="position: fixed">
        <a href="/" class="weui-tabbar__item <? if($this->func=='index'){echo 'weui-bar__item_on';}?>">
            <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
            <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="<?=url('product/lists')?>" class="weui-tabbar__item <? if($this->func=='lists'){echo 'weui-bar__item_on';}?>">
            <i class="iconfont weui-tabbar__icon">&#xe600;</i>
            <p class="weui-tabbar__label">车辆列表</p>
        </a>
        <a href="<?=url('rent')?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe89d;</i>
            <p class="weui-tabbar__label">我的订单</p>
        </a>
        <a href="<?=url('/member/invite')?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe643;</i>
            <p class="weui-tabbar__label">邀请链接</p>
        </a>
        <a href="<?=$this->site->center_url_wap?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
            <p class="weui-tabbar__label">帐户中心</p>
        </a>
    </div>
    <script>
        //banner Swiper
        $(function () {
            var mySwiper = new Swiper('.car_mes', {
                loop: true,
                autoplay: 4800,
                autoplayDisableOnInteraction: false,
                pagination: '.swiper-pagination',
                paginationClickable: true,
            });
        });
    </script>
<?php require 'footer.php';?>