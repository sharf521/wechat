<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1><?=$this->title?></h1>
    </div>

    <div class="clearFix margin_header">

        <div class="car-wrapper clearFix">
            <div class="car-photo"><img src="<?=$product->picture?>">
            </div>
            <div class="car-info">
                <div class="car-name"><?=$product->name?></div>
                <div class="car-price">厂商指导价：<?=$product->price/10000?>万</div>
            </div>
        </div>
        <div class="lease">
            <div class="product">
                <label data_id="<?=$spec->id?>" class="active">
                    <span><strong><?=$spec->time_limit?>期</strong></span>
                    <span>首付: <?=$spec->first_payment/10000	?>万元</span>
                    <span>月租: <?=$spec->month_payment?>元</span>
                    <span>尾付: <?=(float)$spec->last_payment?>元</span>
                </label>
            </div>
        </div>
        <div class="m_regtilinde">提交资料</div>
        <form method="post" id="order_confirm_form">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">申请人</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" name="contacts" placeholder="申请人姓名" value="<?=$goods->contacts?>"/>
                    </div>
                    <div class="weui-cell__ft">
                        <i class="weui-icon-warn"></i>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">电话</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="tel" name="tel" placeholder="联系电话" value="<?=$goods->tel?>"/>
                    </div>
                    <div class="weui-cell__ft">
                        <i class="weui-icon-warn"></i>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">地址</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" name="address" placeholder="联系地址" value="<?=$goods->address?>"/>
                    </div>
                    <div class="weui-cell__ft">
                        <i class="weui-icon-warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                当时可用余额：<?=$account->funds_available?> 元
                <?  if($account->funds_available<5000) : ?>
                <span style="color: #f00">帐户余额不足5000元，请充值！</span>
                    <a href="javascript:;" class="weui-btn weui-btn_disabled weui-btn_primary">下一步</a>
                    <a class="recharge weui-btn weui-btn_plain-primary">我要充值</a>
                <? else : ?>
                    <input class="weui-btn weui-btn_primary" type="submit" value="下一步">
                <? endif;?>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        order_confirm();
    </script>
<?php require 'footer.php';?>