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
            <a href="<?=url("/goods/detail/?id={$g->goods_id}")?>">
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
<? if($order->status==1) : ?>
    <div class="pay_footer">
        总计：¥<?= $order->order_money ?>
        <a href="javascript:;" id="pay_btn" class="pay_btn">立即支付</a>
    </div>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?=$config?>);
        wx.ready(function () {
            $("#pay_btn").click(function () {
                wx.chooseWXPay({
                    timestamp: '<?=$pay['timestamp']?>',
                    nonceStr: '<?=$pay['nonceStr']?>',
                    package: '<?=$pay['package']?>',
                    signType: 'MD5',
                    paySign: '<?=$pay['paySign']?>',
                    success: function (res) {
                        alert('支付成功！');
                        //window.location = "/index.php/weixin/orderShow/?task_id=<?=$task->id?>";
                    }
                });
            });
        });
    </script>
<? else: ?>
    <div class="pay_footer">
        总计：¥<?= $order->order_money ?>
        <a href="javascript:;" class="pay_btn">己支付或己取消</a>
    </div>
<? endif;?>

<?php require 'footer.php';?>