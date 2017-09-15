<?php require 'header.php';?>
<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <? foreach($banners as $ad) : ?>
                <div class="swiper-slide" style="text-align: center">
                    <a href="<?=$ad['href']?>"><img src="<?=$ad['picture']?>" style="max-width: 100%;"></a>
                </div>
            <? endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<div class="layui-main">
        <? foreach ($floorList as $j=>$floor) : ?>
    <dl class="allbox clearFix">
        <dt>
            <span><?=$j+1?>F</span><h3><?=$floor['cate']['name']?></h3>        <a class="morebox" href="/goods/lists/<?=$floor['cate']['id']?>">更多</a>
        </dt>
        <dd>
            <div class="mod-conleft">
                <a href="<?=$floor['ad']['href']?>" class="md-img">
                    <img src="<?=$floor['ad']['picture']?>">
                    <p class="title-bg"></p>
                    <p class="con-til" style="display: none">说明<i>»</i></p>
                </a>
                <div class="md-othertry">
                    <ul>
                        <? foreach ($floor['goodsList'] as $i=>$goods) :
                        if($i>=8) : ?>
                            <li><a target="_blank" href="/goods/detail/<?=$goods->id?>">
                                    <img lay-src="<?=$goods->image_url?>">
                                    <div class="ot-con"><p><?=$goods->name?></p>
                                        <p><span class="ot-tag">立即购买</span>￥<strong><?=$goods->price?></strong></p></div>
                                </a></li>
                        <?
                            endif;
                        endforeach;?>
                    </ul>
                </div>
            </div>

            <div class="middle-goods-list ">
                <ul>
                    <? foreach ($floor['goodsList'] as $i=>$goods) :
                        if($i>=8){
                            break;
                        }
                        ?>
                        <li>
                            <a target="_blank" href="/goods/detail/<?=$goods->id?>">
                                <div class="goods-thumb">
                                    <img  lay-src="<?=$goods->image_url?>">
                                </div>
                                <div class="goods-name"><?=$goods->name?></div>
                                <div class="goods-price">
                                    <em>￥<?=$goods->price?></em>
                                    <strong>立即购买</strong>
                                </div>
                            </a>
                        </li>
                    <? endforeach;?>
                </ul>
            </div>
        </dd>
    </dl>
    <? endforeach;?>
</div>
<script>
    index_js();
</script>
<?php require 'footer.php';?>
