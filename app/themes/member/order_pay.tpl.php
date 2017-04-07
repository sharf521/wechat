<?php require 'header.php';?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box order_detail">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend><?=$this->title?></legend>
                </fieldset>

                <table class="layui-table">
                    <tr><td width="80">订单编号</td><td><?= $order->order_sn ?></td></tr>
                    <tr><td>下单时间</td><td><?= $order->created_at ?></td></tr>
                    <tr><td>备注</td><td><?=nl2br($order->buyer_remark)?></td></tr>
                    <tr><td>卖家</td><td><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></td></tr>
                    <tr><td>订单金额</td><td class="money">¥<?=$order->order_money?></td></tr>
                </table>

                <div class="order_detail_tit">收货地址</div>
                <div style="padding: 10px;">
                    <?=$shipping->name?>，<?=$shipping->phone?>，<?=$shipping->region_name?> <?=$shipping->address?>，<?=$shipping->address?>,<?=$shipping->zipcode?>
                </div>

                <table class="layui-table goods_list">
                    <thead>
                    <tr>
                        <th width="100">商品编号</th>
                        <th>商品信息</th>
                        <th width="100">商品价格</th>
                        <th width="100">购买数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($goods as $g) : ?>
                        <tr>
                            <td class="goods_id"><?=$g->id?></td>
                            <td class="goods_info">
                                <img src="<?=$g->goods_image?>">
                                <div style="float: left">
                                    <a href="<?=url("/goods/detail/{$g->goods_id}")?>" target="_blank"><?=$g->goods_name?></a><br>
                                    <?=$g->spec_1?> <?=$g->spec_2?>
                                </div>
                            </td>
                            <td class="goods_price">￥<?=$g->price?></td>
                            <td class="goods_num"><?= $g->quantity ?></td>
                        </tr>
                    <? endforeach;?>
                    </tbody>
                </table>

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
                        <div class="layui-input-block">实际支付：￥<span id="money_yes"><?=$order->order_money?></span> &nbsp;
                            <a class="layui-btn layui-btn-mini layui-btn-normal recharge" target="_blank"
                               href="<?= $this->site->center_url?>/member/account/recharge/?money=<?=$order->order_money?>">我要充值</a>
                            <br><br>
                            <button class="layui-btn" lay-submit="" lay-filter="*">立即支付</button>
                            <button class="layui-btn" onclick="history.go(-1)">返回</button>
                        </div>
                    </div>
                </form>
                <script src="/plugin/js/math.js"></script>
                <script>
                    var lv='<?=$convert_rate?>';
                    var price_true='<?=$order->order_money?>';
                    orderPayJs();
                </script>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>