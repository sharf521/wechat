<?php require 'header.php';?>
    <div class="layui-main wrapper">
        <div class="topnav">
            <span class="layui-breadcrumb">
                <a href="/">首页</a>
                <a href='/member'>个人中心</a>
                <a><cite><?=$this->title?></cite></a></span>
        </div>
        <div class="order_detail clearFix">
            <table class="layui-table">
                <tr><td width="80">订单编号</td><td><?= $order->order_sn ?></td></tr>
                <tr><td>下单时间</td><td><?= $order->created_at ?></td></tr>
                <tr><td>订单状态</td><td class="status"><?=$order->showStatusName()?></td></tr>
                <?php if($this->user->type_id!=1 || $order->seller_id==$this->user_id) : ?>
                    <tr><td>买家</td><td><?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?> </td></tr>
                <? endif;?>
                <tr><td>备注</td><td><?=nl2br($order->buyer_remark)?></td></tr>
                <?php if($this->user->type_id!=1 || $order->buyer_id==$this->user_id ) : ?>
                    <tr><td>卖家</td><td><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></td></tr>
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

            <div class="order_detail_bottom">
                <label>订单总价：</label><span class="money">¥<?=$order->order_money?></span><br>
                <label>定金：</label><span class="money">¥<?=$order->pre_money?></span><br>
                <?php if($order->status>=2 && $order->pre_money>0) :
                    $pre=$order->preCashierLog();
                    ?>
                    <label>实付：</label><?=(float)$pre->payed_integral?> 积分<br>
                    <label>实付款：</label><span class="money">¥<?=(float)$pre->payed_funds?></span><br>
                <? endif;?>
                <?
                if($order->status>=4){
                    $end=$order->endCashierLog();
                    ?>
                    <hr><label>尾款：</label><span class="money">¥<?=math($order->order_money,$order->pre_money,'-',2)?></span><br>
                    <label>实付积分：</label><?=(float)$end->payed_integral?> 积分<br>
                    <label>实付款：</label><span class="money">¥<?=(float)$end->payed_funds?></span><br>
                    <?
                }
                ?>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>