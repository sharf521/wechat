<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="/car"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1>车辆列表</h1>
    </div>

    <div class="clearFix margin_header">
        <ul class="commoditylist_content">
            <? foreach ($result['list'] as $product) : ?>
                <li>
                    <a href="<?=url("product/detail/?id={$product->id}")?>">
                        <span class="imgspan">
                            <img src="/themes/images/blank.gif" data-echo="<?=$product->picture?>_100X100.png">
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

        <? if($result['total']==0) : ?>
            <div class="weui-msg">
                <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
                <div class="weui-msg__text-area">
                    <h2 class="weui-msg__title">没有任何商品。。</h2>
                    <p class="weui-msg__desc"></p>
                </div>
            </div>
        <? else : ?>
            <?=$result['page'] ;?>
        <? endif;?>
    </div>
<?php require 'footer.php';?>