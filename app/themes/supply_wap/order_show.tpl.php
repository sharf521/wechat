<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('order')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>订单详情</h1>
    </div>
    <div class="weui-form-preview margin_header">
        <div class="weui-form-preview__bd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">下单时间</label>
                <span class="weui-form-preview__value"><?=$order->created_at?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">商品价格</label>
                <span class="weui-form-preview__value">¥<?=$order->goods_money?></span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">物流费用</label>
                <span class="weui-form-preview__value">¥<?=$order->shipping_fee?></span>
            </div>
        </div>
        <div class="weui-form-preview__hd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单总金额</label>
                <em class="weui-form-preview__value">¥<?=$order->order_money?></em>
            </div>
        </div>
    </div>
<br>
<?php if ($order->payed_money > 0) : ?>
    <div class="div_box">
        <table class="table_box">
            <tr><td >支付金额：</td><td><?=$order->payed_money?></td></tr>
            <tr><td >订单号：</td><td><?=$order->out_trade_no?></td></tr>
            <tr><td >支付时间：</td><td><?=$order->payed_at?></td></tr>
            <tr><td >收货人：</td><td><?=$shipping->name?></td></tr>
            <tr><td >联系电话：</td><td><?=$shipping->phone?></td></tr>
            <tr><td >收货地址：</td><td><?=$shipping->region_name?> <?=$shipping->address?></td></tr>
        </table>
    </div>
    <?php
endif;
if ($order->status >=4) : ?>
    <div class="div_box">
        <table class="table_box">
            <tr><td >快递公司：</td><td><?=$shipping->shipping_name?></td></tr>
            <tr><td >快递单号：</td><td><?=$shipping->shipping_no?></td></tr>
            <tr><td >发货时间：</td><td><?=$shipping->shipping_at?></td></tr>
        </table>
    </div>
<? endif ?>
<?php require 'footer.php';?>