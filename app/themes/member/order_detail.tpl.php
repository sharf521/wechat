<?php require 'header.php';?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box order_detail">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend><?=$this->title?></legend>
                </fieldset>
                <div class="order-progress">
                    <ul class="progress-list">
                        <li class="step step-first <?=($order->status==1 || $order->status==2)?'step-active':'step-done';?>">
                            <div class="progress"><span class="text">下单</span></div>
                            <div class="info"><?=$order->created_at?></div>
                        </li>
                        <li class="step <? if($order->status==3){echo 'step-active';}?> <? if($order->status>3){echo 'step-done';}?>">
                            <div class="progress"><span class="text">付款</span></div>
                            <div class="info"><?=$order->payed_at?></div>
                        </li>
                        <li class="step <? if($order->status==4){echo 'step-active';}?> <? if($order->status>4){echo 'step-done';}?>">
                            <div class="progress"><span class="text">发货</span></div>
                            <div class="info"><?=$shipping->shipping_at?></div>
                        </li>
                        <li class="step step-last <? if($order->status==5){echo 'step-active';}?>">
                            <div class="progress"><span class="text">交易成功</span></div>
                            <div class="info"><?=$order->finished_at?></div>
                        </li>
                    </ul>
                </div>

                <table class="layui-table">
                    <tr><td width="80">订单编号</td><td><?= $order->order_sn ?></td></tr>
                    <tr><td>下单时间</td><td><?= $order->created_at ?></td></tr>
                    <tr><td>订单状态</td><td class="status"><?=$order->getLinkPageName('order_status',$order->status)?></td></tr>
                    <tr><td>备注</td><td><?=nl2br($order->buyer_remark)?></td></tr>
                    <?php if($buyer->user_id==$this->user_id || $supplyer->user_id==$this->user_id) : ?>
                        <tr><td>卖家</td><td><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></td></tr>
                    <? endif;?>

                    <?php if($shop->user_id==$this->user_id) : ?>
                        <tr><td>买家</td><td><?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?> </td></tr>
                    <? endif;?>

                    <?php if($shop->user_id==$this->user_id) : ?>
                        <tr><td>供应商</td><td><?=$supplyer->name?> <?=\App\Helper::getQqLink($buyer->qq)?> </td></tr>
                    <? endif;?>

                    <tr><td>物流费用</td><td>¥<?=$order->shipping_fee?></td></tr>
                    <tr><td>订单金额</td><td class="money">¥<?=$order->order_money?></td></tr>
                </table>

                <div class="order_detail_tit">收货地址</div>
                <div style="padding: 10px;">
                    <?=$shipping->name?>，<?=$shipping->phone?>，<?=$shipping->region_name?> <?=$shipping->address?>，<?=$shipping->address?>,<?=$shipping->zipcode?>
                </div>
                
                <? if($shipping->shipping_at!=0) : ?>
                    <div class="order_detail_tit">运送方式</div>
                    <table class="layui-table">
                        <tr><td width="80">物流公司</td><td><?= $shipping->shipping_name ?></td></tr>
                        <tr><td>运单号码</td><td><?= $shipping->shipping_no ?></td></tr>
                        <tr><td>发货时间</td><td><?=$shipping->shipping_at?></td></tr>
                        <tr><td>追踪详情</td><td><a href="http://www.kuaidi100.com/chaxun?com=<?=$shipping->shipping_name?>&nu=<?=$shipping->shipping_no?>" target="_blank" class="layui-btn layui-btn-mini">查看</a></td></tr>
                    </table>
                <? endif;?>


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
            </div>
        </div>
    </div>
<?php require 'footer.php';?>