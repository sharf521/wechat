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
    <div class="pay_footer">
        总计：¥<?= $order->order_money ?>
        <a href="javascript:;" id="pay_btn" class="pay_btn">立即支付</a>
    </div>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
        //获取共享地址
        function editAddress()
        {
            WeixinJSBridge.invoke(
                'editAddress',
                <?php echo $editAddress; ?>,
                function(res){
                    var value1 = res.proviceFirstStageName;
                    var value2 = res.addressCitySecondStageName;
                    var value3 = res.addressCountiesThirdStageName;
                    var value4 = res.addressDetailInfo;
                    var tel = res.telNumber;

                    alert(value1 + value2 + value3 + value4 + ":" + tel);
                }
            );
        }

        window.onload = function(){
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', editAddress);
                    document.attachEvent('onWeixinJSBridgeReady', editAddress);
                }
            }else{
                editAddress();
            }
        };

    </script>
<?php require 'footer.php';?>