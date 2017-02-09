<?php require 'header.php';?>
    <div class="layui-main topnav">
        <span class="layui-breadcrumb">
                <a href="/">首页</a>
                    <? foreach ($nav_cates as $c) : ?>
                        <a href="/goods/lists/<?=$c->id?>"><?=$c->name?></a>
                    <? endforeach;?>
                      <a><cite><?=$cate->name?></cite></a>
                </span>
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
                <div class="weui-msg">
                    <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
                    <div class="weui-msg__text-area">
                        <h2 class="weui-msg__title">没有任何记录。。</h2>
                        <p class="weui-msg__desc"></p>
                    </div>
                </div>
            <? else: ?>
                <?=$result['page']?>
            <? endif;?>
        </div>
    </div>



<?php require 'footer.php';?>