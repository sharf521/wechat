<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="user clearfix ">
            <div class="userImg"><img src="<?= $this->user->headimgurl; ?>"></div>
            <div class="userName">
                <p><?= $this->username ?></p>
                <p class="name"><?= $this->user->name ?></p>
                <span>欢迎您回来！</span>
            </div>
            <div class="userEmail">绑定邮箱：<span><?= $this->user->email ?></span></div>
        </div>
        <div class="marginLine"></div>
        <!--买家-->
        <div class="buyer clearfix">
            <div class="buyer_box">
                <img class="buyer_icon" src="themes/member/images/icon1.png" alt="">
                <div class="buyer_text">
                    <p>待支付订单：<span class="num"><?=$buyer_status1_count?></span></p>
                    <a href="<?=url('/member/order/status1')?>">查看待支付订单&nbsp;></a>
                </div>
            </div>
            <div class="buyer_box">
                <img class="buyer_icon"  src="themes/member/images/icon2.png" alt="">
                <div class="buyer_text">
                    <p>待收货订单：<span class="num"><?=$buyer_status4_count?></span></p>
                    <a href="<?=url('/member/order/status4')?>">查看待收货订单&nbsp;></a>
                </div>
            </div>
        </div>
        <? if($this->user->is_shop):?>
        <!--卖家-->
        <div class="seller">
            <header class="seller_til"></header>
            <div class="seller_box clearfix">
                <img class="seller_icon" src="themes/member/images/icon3.png" alt="">
                <div class="seller_text">
                    <a class="btn" href="<?=\App\Helper::getStoreUrl($this->user->id)?>" target="_blank">查看店铺</a>
                    <a class="btn" href="/sellManage/category">分类管理</a>
                    <a class="btn" href="/sellManage/shipping">配送地址</a>
                    <div class="tips">
                        <span>提示：</span>
                        <p>您有<i><?=$seller_status3_count?></i>个订单待发货，请尽快到“<a href="/sellManage/order/status3">订单管理</a>”中处理</p>
                    </div>
                </div>
            </div>
        </div>
        <? endif;?>
        <? if($this->user->is_supply):?>
        <div class="supplier">
            <div class="supplier_til"></div>
            <div class="supplier_box clearfix">
                <img class="supplier_icon"  src="themes/member/images/icon4.png" alt="">
                <div class="tips">
                    <span>提示：</span>
                    <p>您有<i><?=$supplyer_status3_count?></i>个订单待发货，请尽快到“<a href="/supplyManage/order/status3">订单管理</a>”中处理</p>
                </div>
            </div>
        </div>
        <? endif;?>
    </div>
</div>

<?php require 'footer.php';?>
