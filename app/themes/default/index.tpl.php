<?php require 'header.php';?>
<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <? foreach($images as $img) : ?>
                <div class="swiper-slide" style="text-align: center">
                    <img src="<?=$img?>" style="max-width: 100%;">
                </div>
            <? endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<div class="layui-main">
    <? for ($j=1;$j<3;$j++) : ?>
    <dl class="allbox clearFix">
        <dt>
            <span><?=$j?>F</span>        <h3>新品上市</h3>        <a class="morebox" href=""></a>
        </dt>
        <dd>
            <div class="middle-goods-list ">
                <ul>
                    <? for ($i=1;$i<11;$i++) : ?>
                    <li>
                        <dl>
                            <dt class="goods-name"><a target="_blank" href="http://www.mogo100.com/shop/index.php?act=goods&amp;op=index&amp;goods_id=111611" title="乐肤棉印花系列可爱猫咪 可爱猫咪 1.5床单三件套">
                                    乐肤棉印花系列可爱猫咪 可爱猫咪 1.5床单三件套</a></dt>
                            <dd class="goods-thumb">
                                <a target="_blank" href="http://www.mogo100.com/shop/index.php?act=goods&amp;op=index&amp;goods_id=111611">

                                    <img src="/themes/images/blank.gif" data-echo="http://www.mogo100.com/data/upload/shop/store/goods/122/2015/12/10/122_05030544578388186_240.jpg">
                                </a></dd>
                            <dd class="goods-price"><em>￥280.50</em>
                                <span class="original">￥0.00</span></dd>
                        </dl>
                    </li>
                    <? endfor;?>
                </ul>
            </div>
        </dd>
    </dl>
    <? endfor;?>
</div>
<script>
    index_js();
</script>
<?php require 'footer.php';?>
