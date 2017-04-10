<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('/')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1>用户中心</h1>
    </div>
    <div class="margin_header"></div>

    <div class="weui-cells__title">会员中心</div>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=url('order')?>">
            <div class="weui-cell__bd">
                <p>我的订单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url('address')?>">
            <div class="weui-cell__bd">
                <p>地址管理</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url('invite')?>">
            <div class="weui-cell__bd">
                <p>邀请链接</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
<? if($this->user->is_shop==0) : ?>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=url('shop')?>">
            <div class="weui-cell__bd">
                <p>申请开店</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
<? else: ?>
    <div class="weui-cells__title">卖家管理</div>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=url('/sellManage/category')?>">
            <div class="weui-cell__bd">
                <p>分类管理</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url('/sellManage/goods')?>">
            <div class="weui-cell__bd">
                <p>商品管理</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url('/sellManage/order')?>">
            <div class="weui-cell__bd">
                <p>我的订单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
<!--        <a class="weui-cell weui-cell_access" href="<?/*=url('/purchase')*/?>">
            <div class="weui-cell__bd">
                <p>我要采购</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>-->
    </div>

    <!--<div class="weui-flex margin_header">
        <div class="weui-flex__item">
            <a href="<?/*=url('/sellManage/category')*/?>" style="text-align: center; width: 100%; height: 100%; display: block">
                <i class="iconfont" style="font-size: 22px; color: #999999">&#xe600;</i>
                <p class="weui-grid__label">分类管理</p>
            </a>
        </div>
        <div class="weui-flex__item">
            <a href="<?/*=url('/sellManage/goods')*/?>" style="text-align: center; width: 100%; height: 100%; display: block">
                <i class="iconfont" style="font-size: 22px; color: #999999">&#xe643;</i>
                <p class="weui-grid__label">商品管理</p>
            </a>
        </div>
        <div class="weui-flex__item">
            <a href="<?/*=url('/sellManage/order')*/?>" style="text-align: center; width: 100%; height: 100%; display: block">
                <i class="iconfont" style="font-size: 22px; color: #999999">&#xe89d;</i>
                <p class="weui-grid__label">我的订单</p>
            </a>
        </div>
        <div class="weui-flex__item">
            <a href="<?/*=url('/purchase')*/?>" style="text-align: center; width: 100%; height: 100%; display: block">
                <i class="iconfont" style="font-size: 22px; color: #999999">&#xe89d;</i>
                <p class="weui-grid__label">我要采购</p>
            </a>
        </div>
    </div>-->
<? endif;?>

<? if($this->user->is_shop==1) : ?>
    <? if($this->user->is_supply==0) : ?>
        <div class="weui-cells">
            <a class="weui-cell weui-cell_access" href="<?=url('/sellManage/applySupply')?>">
                <div class="weui-cell__bd">
                    <p>申请供应商</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>
        </div>
    <? else: ?>
        <div class="weui-cells__title">供应商中心</div>
        <div class="weui-cells">
            <a class="weui-cell weui-cell_access" href="<?=url('/supplyManage/goods')?>">
                <div class="weui-cell__bd">
                    <p>商品管理</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>
            <a class="weui-cell weui-cell_access" href="<?=url('/supplyManage/order')?>">
                <div class="weui-cell__bd">
                    <p>我的订单</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>
        </div>
    <? endif;?>
<? endif;?>

    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="<?=url('logout')?>">
            安全退出
        </a>
    </div>
    <br><br>
    <div class="weui-tabbar" style="position: fixed">
        <a href="/" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
            <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="<?=url('/cart')?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon" style="position: relative">&#xe698;<span class="weui-badge" id="cart_num"></span></i>
            <p class="weui-tabbar__label">购物车</p>
        </a>
        <a href="<?=url('')?>" class="weui-tabbar__item weui-bar__item_on">
            <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
            <p class="weui-tabbar__label">我</p>
        </a>
    </div>
<script>
    getCartNum();
</script>
<?php require 'footer.php';?>