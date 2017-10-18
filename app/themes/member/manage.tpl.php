<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="jiben">
            <div class="jbtx">
                <div class="touxiang">
                    <img src="<?= $this->user->headimgurl; ?>">
                </div>
                <div class="toutext">
                    <h2><?= $this->username ?></h2>
                    <p><?= $this->user->name ?></p>
                </div>
            </div>
        </div>
        <div class="marginLine"></div>
        <!--买家-->
        <div class="buyer clearfix">
            <div class="buyer_box">
                <img class="buyer_icon" src="themes/member/images/icon1.png" alt="">
                <div class="buyer_text">
                    <p>待支付订单：<span class="num">0</span></p>
                    <a>查看待收货订单&nbsp;></a>
                </div>
            </div>
            <div class="buyer_box">
                <img class="buyer_icon"  src="themes/member/images/icon2.png" alt="">
                <div class="buyer_text">
                    <p>待收货订单：<span class="num">0</span></p>
                    <a>查看待收货订单&nbsp;></a>
                </div>
            </div>
        </div>
        <!--卖家-->
        <div class="seller">
            <header class="seller_til"></header>
            <div class="seller_box clearfix">
                <img class="seller_icon" src="themes/member/images/icon3.png" alt="">
                <div class="seller_text">
                    <!--<p>待发货订单：<span class="num">0</span></p>-->
                    <button class="btn">查看店铺</button>
                    <button class="btn">添加分类</button>
                    <button class="btn">配送地址</button>
                    <div class="tips">
                        <span>提示：</span>
                        <p>您有0个订单待处理，请尽快到“已提交订单”中处理</p>
                        <p>您有0个订单待处理，请尽快到“已提交订单”中处理</p>
                        <p>您有0个订单待处理，请尽快到“已提交订单”中处理</p>
                        <p>您有0个订单待处理，请尽快到“已提交订单”中处理</p>
                        <p>您有0个订单待处理，请尽快到“已提交订单”中处理</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="supplier">
            <div class="supplier_til"></div>
            <div class="supplier_box clearfix">
                <img class="supplier_icon"  src="themes/member/images/icon4.png" alt="">
                <div class="supplier_text">
                    <p>待发货订单：<span class="num">0</span></p>
                </div>
                <div class="tips">
                    <span>提示：订单发货</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php';?>
