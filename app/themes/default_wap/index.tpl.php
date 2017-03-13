<?php require 'header.php';?>


    <ul class="commoditylist_content">
        <? foreach ($goods_result as $goods) : ?>
            <li>
                <a href="<?=url("/goods/detail/{$goods->id}")?>">
                <span class="imgspan">
                    <img src="/themes/images/blank.gif" data-echo="<?=$goods->image_url?>">
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