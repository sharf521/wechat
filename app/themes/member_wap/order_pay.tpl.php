<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>我的订单</h1>
    </div>
    <div class="order_address margin_header">
        <h4>收货地址</h4>
        <p><?=$shipping->region_name?> <?=$shipping->address?></p>
        <p><strong><?=$shipping->name?></strong><?=$shipping->phone?></p>
    </div>

    <form method="post">
        <div class="order_box">
            <a class="order_shopBar"><i class="iconfont">&#xe854;</i><em>我的小店<?=$order->seller_id?></em></a>
            <? foreach($orderGoods as $goods): ?>
                <div class="order_item clearFix">
                    <img class="image" src="<?=$goods->goods_image?>">
                    <div class="oi_content">
                        <a href="<?=url("/goods/detail/?id={$goods->goods_id}")?>"><?=$goods->goods_name?></a>
                        <p><?
                            if($goods->spec_1!=''){
                                echo "<span class='spec'>{$goods->spec_1}</span>";
                            }
                            if($goods->spec_2!=''){
                                echo "<span class='spec'>{$goods->spec_2}</span>";
                            }
                            ?>
                            <span class="count">数量：<?=$goods->quantity?></span></p>
                    </div>
                    
                </div>
            <? endforeach;?>
        </div>
    </form>
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