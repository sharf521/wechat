<?php require 'header.php';?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>我的订单</legend>
                </fieldset>

                <blockquote class="layui-elem-quote">
                    <h4>收货地址</h4>
                    <p><?=$shipping->region_name?> <?=$shipping->address?></p>
                    <p><strong><?=$shipping->name?></strong><?=$shipping->phone?></p>
                </blockquote>
                <dl class="orderbox">
                    <dt>
                        订单号：<?= $order->order_sn ?>
                        <span class="status"><?=$order->getLinkPageName('order_status',$order->status)?></span>
                        <span class="time"><?=$order->created_at?></span>
                    </dt>
                    <dd>
                        <table class="layui-table" style="margin: 0px;">
                            <tr>
                                <td>
                                    <?
                                    foreach ($goods as $g) : ?>
                                        <div class="clearFix" style="border-bottom: 1px solid #efefef;">
                                            <img class="goodsImg" src="<?=$g->goods_image?>" width="100">
                                            <div class="goodsDetail">
                                                <div class="name">
                                                    <a href="<?=url("/goods/detail/?id={$g->goods_id}")?>" target="_blank"><?=$g->goods_name?></a><br>
                                                    <?=$g->spec_1?> <?=$g->spec_2?>
                                                </div>

                                                <div class="quantity">￥<?=$g->price?> <span>X</span> <?= $g->quantity ?></div>
                                            </div>
                                        </div>
                                    <? endforeach;?>
                                </td>
                                <td align="center">¥<?=$order->order_money?><br>(含运费：¥<?=$order->shipping_fee?>)</td>
                            </tr>
                            <tr><td colspan="3">备注：<?=nl2br($order->buyer_remark)?></td></tr>
                        </table>
                    </dd>
                </dl>


                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">扣除积分</label>
                            <div class="layui-input-inline">
                                <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="layui-input" autocomplete="off"/>
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">支付密码</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" required type="password" name="zf_password" placeholder="支付密码" />
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用金额：￥<span id="span_funds"><?=$account->funds_available?></span></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">实际支付：￥<span id="money_yes"><?=$order->order_money?></span><br><br>
                            <button class="layui-btn" lay-submit="" lay-filter="*">立即支付</button>
                            <button class="layui-btn" onclick="history.go(-1)">返回</button>
                        </div>
                    </div>
                </form>
                <script src="/plugin/js/math.js"></script>
                <script>
                    var lv='<?=$convert_rate?>';
                    $(function () {
                        var price_true='<?=$order->order_money?>';
                        $("#integral").bind('input propertychange',function(){
                            if(Number($(this).val())>Number($('#span_integral').html())){
                                $(this).val($('#span_integral').html());
                            }
                            var max_jf=Math.mul(price_true,lv);
                            if(Number($(this).val())>max_jf){
                                $("#integral").val(max_jf);
                            }
                            var _m=Math.div(Number($("#integral").val()),lv);
                            var money=Math.sub(price_true,Math.moneyRound(_m,2));
                            $('#money_yes').html(money);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>