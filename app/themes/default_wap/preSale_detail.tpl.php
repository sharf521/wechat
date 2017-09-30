<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1);"><i class="iconfont">&#xe604;</i></a>
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
            <?php if($this->user->type_id!=1 || $order->seller_id==$this->user_id) : ?>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">买家</label>
                    <span class="weui-form-preview__value"><?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?></span>
                </div>
            <? endif;?>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">备注</label>
                <span class="weui-form-preview__value"><?=nl2br($order->buyer_remark)?></span>
            </div>

            <?php if($this->user->type_id!=1 || $order->buyer_id==$this->user_id) : ?>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">卖家</label>
                    <span class="weui-form-preview__value"><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></span>
                </div>
            <? endif;?>

            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单状态</label>
                <span class="weui-form-preview__value"><?=$order->getLinkPageName('order_status',$order->status)?></span>
            </div>
        </div>
        <div class="weui-form-preview">
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">订单总价</label>
                    <span class="weui-form-preview__value">¥<?=$order->order_money?></span>
                </div>

                <?php if($order->status>=2 && $order->pre_money>0) :
                    $pre=$order->preCashierLog();
                    ?>
                    <label>实付：</label><?=(float)$pre->payed_integral?> 积分<br>
                    <label>实付款：</label><span class="money">¥<?=(float)$pre->payed_funds?></span><br>
                <? endif;?>

                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">定金</label>
                    <span class="weui-form-preview__value">¥<?=$order->pre_money?></span>
                </div>

                <?php if($order->status>=4) :
                    $end=$order->endCashierLog();
                    ?>
                    <hr><label>尾款：</label><span class="money">¥<?=math($order->order_money,$order->pre_money,'-',2)?></span><br>
                    <label>实付积分：</label><?=(float)$end->payed_integral?> 积分<br>
                    <label>实付款：</label><span class="money">¥<?=(float)$end->payed_funds?></span><br>
                <? endif; ?>
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

<? if($shipping->shipping_at!=0) : ?>
    <div class="div_box">
        <table class="table_box">
            <tr><td>物流公司</td><td><?= $shipping->shipping_name ?></td></tr>
            <tr><td>运单号码</td><td><?= $shipping->shipping_no ?></td></tr>
            <tr><td>发货时间</td><td><?=$shipping->shipping_at?></td></tr>
            <tr><td>追踪详情</td><td><a href="http://www.kuaidi100.com/chaxun?com=<?=$shipping->shipping_name?>&nu=<?=$shipping->shipping_no?>" target="_blank" class="layui-btn layui-btn-mini">查看</a></td></tr>
        </table>
    </div>
<? endif;?>
    <div class="order_box">
        <div class="order_shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em></div>
            <a href="<?=url("/goods/detail/{$order->goods_id}")?>">
                <div class="order_item clearFix">
                    <img class="image" src="<?=\App\Helper::smallPic($order->goods_image)?>">

                    <div class="oi_content">
                        <?=$order->goods_name?> <?=$order->spec_1?> <?=$order->spec_2?>
                        <p><span class="count price">¥<?=$order->price?> x<?=$order->quantity?></span></p>
                    </div>
                </div>
            </a>
    </div>









<?php require 'footer.php';?>