<?php require 'header.php';?>

    <div class="swiper-container car_mes">
        <div class="swiper-wrapper">
            <? foreach ($ads as $ad) : ?>
            <div class="swiper-slide"><a href="<?=$ad->url?>"><img src="<?=$ad->picture?>"></a></div>
            <? endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!--ad end-->
    <div class="m_regtilinde">推荐品牌<span><a href="<?=url('brand')?>">查看更多</a></span></div>
    <div class="br_box clearFix">
        <ul class="clearFix">
            <? foreach ($brands as $brand) : ?>
                <li><a href="<?=url("product/?name={$brand->name}")?>"><div><img src="<?=$brand->picture?>" /></div><span><?=$brand->name?></span></a></li>
            <? endforeach;?>
        </ul>
    </div>

    <div class="m_regtilinde" style="margin-top: -1px">推荐汽车<span><a href="<?=url('product')?>">查看更多</a></span></div>
    <div class="clearFix">
        <ul class="commoditylist_content">
            <? foreach ($products as $product) : ?>
                <li>
                    <a href="<?=url("product/detail/?id={$product->id}")?>">
                        <span class="imgspan">
                            <img src="/themes/images/blank.gif" data-echo="<?=$product->picture?>">
                        </span>
                        <div class="info">
                            <p class="cd_title"><?=$product->name?></p>
                            <p class="cd_money">
                                <span>￥</span>
                                <var><?=$product->price?></var>
                            </p>
                            <p class="cd_sales"><?=$product->brand_name?></p>
                        </div>
                        <i class="iconfont"></i>
                    </a>
                </li>
            <? endforeach;?>
        </ul>
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