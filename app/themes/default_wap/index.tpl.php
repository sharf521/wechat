<?php require 'header.php';?>
    <!-- 搜索框 -->
    <div class="searchBox clear">
        <form action="/goods/lists/">
            <div class="search-input">
                <i class="iconfont">&#xe634;</i>
                <input type="text" name="keyword" value="<?=$_GET['keyword']?>" placeholder="搜索全部商品">
            </div>
            <button>搜索</button>
        </form>
    </div>
    <div class="swiper-container car_mes  my-banner">
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
    <!-- 热门店铺 -->
    <div class="mod-title">
        <i></i><span>推荐店铺</span><i></i><a href="" class="more"></a>
    </div>
    <div class="br_box clearFix my-remendianpu">
        <ul class="clearFix">
            <? foreach ($shopList as $shop) :
                $_img=$shop->User()->headimgurl;
                ?>
                <li><a href="<?=$shop->getLink(1)?>"><div><img src="<?=$_img?>" /></div><span><?=$shop->name?></span></a></li>
            <? endforeach;?>
        </ul>
    </div>
    <div class="mod-title tuijian">
        <i></i><span>推荐商品</span><i></i><a href="/goods/lists/" class="more">更多</a>
    </div>
    <ul class="commoditylist_content my-commoditylist">
        <? foreach ($goods_result as $goods) : ?>
            <li>
                <a href="<?=url("/goods/detail/{$goods->id}")?>">
                    <div class="info ">
                        <span class="imgspan">
                            <img src="/themes/images/blank.gif" data-echo="<?=\App\Helper::smallPic($goods->image_url)?>">
                        </span>
                        <p class="cd_title"><?=$goods->name?></p>
                        <div class="info-bottom  clearFix">
                            <p class="cd_money">
                                <span>￥</span><var><?=(float)$goods->price?></var>
                            </p>
                            <p class="cd_sales">库存 <?=$goods->stock_count?></p>
                            <p class="cd-payment"></p>
                        </div>
                    </div>
                </a>
            </li>
        <? endforeach;?>
    </ul>
<?php require 'footer_bar.php';?>
<?php require 'footer.php';?>