<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('order')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1><?=$this->title?></h1>
    </div>
    <div class="weui-form-preview margin_header">
        <div class="weui-form-preview__bd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单号</label>
                <span class="weui-form-preview__value"><?=$order->order_sn?></span>
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
<? if($this->func=='editMoney') : ?>
    <form method="post" class="margin_header">
        <div class="weui-cells__title">物流费用</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="shipping_fee" onkeyup="value=value.replace(/[^0-9.]/g,'')" value="<?=$order->shipping_fee?>"  placeholder="¥"/>
                </div>
            </div>
        </div>
        <div class="weui-btn-area">
            <input class="weui-btn weui-btn_primary" type="submit" value="保存">
        </div>
    </form>
<? elseif ($this->func=='editShipping') : ?>
    <form method="post" class="margin_header">
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">快递名称</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="shipping_name"  placeholder="请输入快递名称"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">快递单号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="shipping_no"   placeholder="请输入快递单号"/>
                </div>
            </div>
<!--            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">费用</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="shipping_fee"  onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入费用"/>
                </div>
            </div>-->
        </div>
        <div class="weui-btn-area">
            <input class="weui-btn weui-btn_primary" type="submit" value="保存">
        </div>
    </form>
<? endif;?>
<?php require 'footer.php';?>