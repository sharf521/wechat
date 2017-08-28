<?php require 'header.php';?>
    <div class="layui-main wrapper">
        <div class="topnav">
            <span class="layui-breadcrumb">
                <a href="/">首页</a>
                <a href='/member'>个人中心</a>
                <a><cite><?=$this->title?></cite></a></span>
        </div>
        <div class="order_detail clearFix">
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
                <?php if($this->user->type_id!=1 || $order->seller_id==$this->user_id) : ?>
                    <tr><td>买家</td><td><?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?> </td></tr>
                <? endif;?>
                <tr><td>备注</td><td><?=nl2br($order->buyer_remark)?></td></tr>
                <?php if($this->user->type_id!=1 || $order->buyer_id==$this->user_id || $order->supply_user_id==$this->user_id) : ?>
                    <tr><td>卖家</td><td><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></td></tr>
                <? endif;?>
                <?php if($this->user->type_id!=1 || $order->seller_id==$this->user_id) : ?>
                    <tr><td>供应商</td><td><?=$supplyer->name?> <?=\App\Helper::getQqLink($supplyer->qq)?> </td></tr>
                <? endif;?>
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

            <table class="layui-table">
                <thead>
                <tr>
                    <th>商品信息</th>
                    <th width="100">商品价格</th>
                    <th width="100">购买数量</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($goods as $g) : ?>
                    <tr>
                        <td class="goods_info">
                            <img src="<?=\App\Helper::smallPic($g->goods_image)?>">
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

            <div class="order_detail_bottom">
                <label>商品总价：</label>¥<?=$order->goods_money?><br>
                <label>运费：</label>¥<?=$order->shipping_fee?><br>
                <?php  if ($order->fulldown_money > 0) : ?>
                    <label>满减优惠：</label>-<?= $order->fulldown_money ?><br>
                <?php endif; ?>
                <label>订单总价：</label><span class="money">¥<?=$order->order_money?></span><br>
                <?php if($order->status>=3) : ?>
                    <label>实付积分：</label><?=(float)$order->payed_integral?> 积分<br>
                    <label>实付款：</label><span class="money">¥<?=(float)$order->payed_funds?></span><br>
                <? endif;?>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>