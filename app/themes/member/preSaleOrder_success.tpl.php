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
                    <tr><td>卖家</td><td><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></td></tr>
                    <tr><td>备注</td><td><?=nl2br($order->buyer_remark)?></td></tr>
                    <tr><td>订单金额</td><td class="money">¥<?=$order->order_money?></td></tr>
                </table>

                <div class="order_detail_tit">收货地址</div>
                <div style="padding: 10px;">
                    <?=$shipping->name?>，<?=$shipping->phone?>，<?=$shipping->region_name?> <?=$shipping->address?>，<?=$shipping->address?>,<?=$shipping->zipcode?>
                </div>

                <table class="layui-table goods_list">
                    <thead>
                    <tr>
                        <th>商品信息</th>
                        <th width="100">商品价格</th>
                        <th width="100">购买数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="goods_info">
                            <a href="<?=url("/goods/detail/{$order->goods_id}")?>" target="_blank"><img src="<?=\App\Helper::smallPic($order->goods_image)?>"></a>
                            <div style="float: left">
                                <a href="<?=url("/goods/detail/{$order->goods_id}")?>" target="_blank"><?=$order->goods_name?></a><br>
                                <?=$order->spec_1?> <?=$order->spec_2?>
                            </div>
                        </td>
                        <td class="goods_price">￥<?=$order->price?></td>
                        <td class="goods_num"><?= $order->quantity ?></td>
                    </tr>
                    </tbody>
                </table>


                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">支付密码</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" required type="password" name="zf_password" placeholder="支付密码" />
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="*">确定</button>
                            <button class="layui-btn" onclick="history.go(-1)">返回</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>