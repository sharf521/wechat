<?php require 'header.php';?>
    <div class="layui-main topnav">
        <span class="layui-breadcrumb"><?=$topnav_str?></span>
    </div>

    <div class="layui-main container">
        <div class="sidebar">

        </div>
        <div class="main">
            <ul class="goods_list clearFix">
<? foreach ($result['list'] as $goods) : ?>
                <li>
                    <div class="goods-content" nctype_goods=" 128076" nctype_store="209">
                        <div class="goods-pic"><a href="<?=url("/goods/detail/?id={$goods->id}")?>" target="_blank"><img src="/themes/images/blank.gif" data-echo="<?=$goods->image_url?>"></a></div>
                        <div class="goods-info" style="top: 230px;">
                            <div class="goods-name"><a href="<?=url("/goods/detail/?id={$goods->id}")?>" target="_blank"><?=$goods->name?></a></div>
                            <div class="goods-price"> <em class="sale-price">¥<?=$goods->price?></em>
                                <!--<em class="market-price">¥0.00</em>-->  </div>
                            <div class="sell-stat">
                                <ul>
                                    <li><span class="status"><?=$goods->sale_count?></span>
                                        <p>商品销量</p>
                                    </li>
                                    <li><span><?=$goods->stock_count?></span>
                                        <p>商品库存</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="store"><a></a></div>
                        </div>
                    </div>
                </li>
<? endforeach;?>
            </ul>
            <? if($result['total']==0) : ?>
                <div class="no-result"><i></i>没有找到符合条件的商品</div>
            <? else: ?>
                <?=$result['page']?>
            <? endif;?>
        </div>
    </div>



<?php require 'footer.php';?>