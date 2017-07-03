<?php require 'header.php';?>
    <div class="swiper-container car_mes">
        <div class="swiper-wrapper">
            <? foreach ($ads as $ad) : ?>
                <div class="swiper-slide"><a href="<?=$ad->url?>"><img src="<?=$ad->picture?>" style="max-width: 100%;"></a></div>
            <? endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
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
    <div class="mod-tit">推荐店铺<span><a href=""></a></span></div>
    <div class="br_box clearFix">
        <ul class="clearFix">
            <? foreach ($shopList as $shop) :
                $_img=$shop->User()->headimgurl;
                ?>
                <li><a href="<?=$shop->getLink(1)?>"><div><img src="<?=$_img?>" /></div><span><?=$shop->name?></span></a></li>
            <? endforeach;?>
        </ul>
    </div>
    <div class="mod-tit">推荐商品<span><a href="/goods/lists/">查看更多</a></span></div>
    <ul class="commoditylist_content">
        <? foreach ($goods_result as $goods) : ?>
            <li>
                <a href="<?=url("/goods/detail/{$goods->id}")?>">
                <span class="imgspan">
                    <img src="/themes/images/blank.gif" data-echo="<?=\App\Helper::smallPic($goods->image_url)?>">
                </span>
                    <div class="info">
                        <p class="cd_title"><?=$goods->name?></p>
                        <p class="cd_money">
                            <span>￥</span>
                            <var><?=$goods->price?></var>
                        </p>
                        <p class="cd_sales">库存：<?=$goods->stock_count?></p>
                    </div>
                    <i class="iconfont">&#xe6a7;</i>
                </a>
            </li>
        <? endforeach;?>
    </ul>
<?php require 'footer_bar.php';?>
<?php require 'footer.php';?>