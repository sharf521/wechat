<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1><?=$this->title?></h1>
    </div>

    <div class="weui-form-preview margin_header">
        <div class="weui-form-preview__bd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单编号</label>
                <span class="weui-form-preview__value"><?=$order->order_sn?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">下单时间</label>
                <span class="weui-form-preview__value"><?=$order->created_at?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">备注</label>
                <span class="weui-form-preview__value"><?=nl2br($order->buyer_remark)?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">卖家</label>
                <span class="weui-form-preview__value"><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单状态</label>
                <span class="weui-form-preview__value"><?=$order->getLinkPageName('order_status',$order->status)?></span>
            </div>
        </div>
        <div class="weui-form-preview__hd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单总金额</label>
                <em class="weui-form-preview__value">¥<?=$order->order_money?></em>
            </div>
        </div>
    </div>


    <form method="post">

        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">扣除积分</label></div>
                <div class="weui-cell__bd">
                    <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="weui-input" autocomplete="off"/>
                </div>

            </div>
        </div>
        <div class="weui-cells__tips">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">支付密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" required type="password" name="zf_password" placeholder="支付密码" />
                </div>
            </div>
        </div>
        <div class="weui-cells__tips">可用金额：￥<span id="span_funds"><?=$account->funds_available?></span></div>
        <div style="text-align: right; padding: 10px 20px 0px 0px; font-size: 16px; font-weight: 600; color: #c00;">支付金额：¥<span id="money_yes"><?=$order->order_money?></span></div>
        <div class="weui-btn-area">
            <input class="weui-btn weui-btn_primary" type="submit" value="立即支付">
            <a class="recharge weui-btn weui-btn_plain-primary">我要充值</a>
        </div>
    </form>
    <br>
    <div class="div_box">
        <table class="table_box">
            <tr><td >收货人：</td><td><?=$shipping->name?></td></tr>
            <tr><td >联系电话：</td><td><?=$shipping->phone?></td></tr>
            <tr><td >收货地址：</td><td><?=$shipping->region_name?> <?=$shipping->address?></td></tr>
            <tr><td >邮编：</td><td><?=$shipping->zipcode?></td></tr>
        </table>
    </div>

    <div class="order_box">
        <div class="order_shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em></div>
        <?php foreach ($goods as $g) : ?>
            <a href="<?=url("/goods/detail/{$g->goods_id}")?>">
                <div class="order_item">
                    <img class="image" src="<?=$g->goods_image?>">
                    <div class="oi_content">
                        <?=$g->goods_name?> <?=$g->spec_1?> <?=$g->spec_2?>
                        <p><span class="count price">¥<?=$g->price?> x<?=$g->quantity?></span></p>
                    </div>
                </div>
            </a>
        <? endforeach;?>
    </div>


    <script src="/plugin/js/math.js"></script>
    <script>
        var lv='<?=$convert_rate?>';
        var price_true='<?=$order->order_money?>';
        orderPayJs();
    </script>

<?php require 'footer.php';?>