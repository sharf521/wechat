<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=$this->home_url?>"><i class="iconfont">&#xe64f;</i></a>
        <a class="m_header_r"></a>
        <h1>用户中心</h1>
    </div>
    <div class="margin_header"></div>

    <div class="weui-cells__title">会员中心</div>
    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=url("order/?st_uid={$this->st_uid}")?>">
            <div class="weui-cell__bd">
                <p>我的订单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url("preSaleOrder/?st_uid={$this->st_uid}")?>">
            <div class="weui-cell__bd">
                <p>我的预订</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="<?=url("address/?st_uid={$this->st_uid}")?>">
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
    </div>
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

    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=$this->site->center_url_wap."/member/?st_uid={$this->st_uid}"?>">
            <div class="weui-cell__bd">
                <p>帐户中心</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>

    <div class="weui-cells">
        <a class="weui-cell weui-cell_access" href="<?=url('logout')?>">
            <div class="weui-cell__bd">
                <p>安全退出</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
    <br><br>
    <div class="weui-tabbar" style="position: fixed">
        <a href="<?=$this->home_url?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
            <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="<?=url("/cart/?st_uid={$this->st_uid}")?>" class="weui-tabbar__item">
            <i class="iconfont weui-tabbar__icon" style="position: relative">&#xe698;<span class="weui-badge" id="cart_num"></span></i>
            <p class="weui-tabbar__label">购物车</p>
        </a>
        <a href="<?=url("?st_uid={$this->st_uid}")?>" class="weui-tabbar__item weui-bar__item_on">
            <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
            <p class="weui-tabbar__label">我</p>
        </a>
    </div>
<script>
    getCartNum();
</script>
<?php require 'footer.php';?>