<?php require 'header.php';?>


    <ul class="commoditylist_content">
        <? foreach ($goods_result as $goods) : ?>
            <li>
                <a href="<?=url("/goods/detail/?id={$goods->id}")?>">
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
    <div class="weui-tabbar">
        <a href="javascript:;" class="weui-tabbar__item weui-bar__item_on">
            <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
            <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="<?=url('/cart')?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe698;</i>
            <p class="weui-tabbar__label">购物车</p>
        </a>
        <a href="javascript:;" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe89d;</i>
            <p class="weui-tabbar__label">我的订单</p>
        </a>
        <a href="<?=url('member')?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
            <p class="weui-tabbar__label">我</p>
        </a>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.weui-tabbar__item').on('click', function () {
                $(this).addClass('weui-bar__item_on').siblings('.weui-bar__item_on').removeClass('weui-bar__item_on');
            });
        });
    </script>
<?php require 'footer.php';?>