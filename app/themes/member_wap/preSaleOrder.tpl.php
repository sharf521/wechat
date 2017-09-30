<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url("?st_uid={$this->st_uid}")?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>我的预订单</h1>
    </div>
<div class="margin_header"></div>
<? foreach ($orders['list'] as $order) :
    $shop=$order->Shop();
    ?>
    <div class="order_box">
        <div class="order_head">
            <p class="status"><em class="co_blue"><?=$order->showStatusName()?></em></p>
            <span class="time"><b><?=substr($order->created_at,0,10)?></b></span>
        </div>
        <div class="order_shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em> <?=\App\Helper::getQqLink($shop->qq)?></div>
        <a href="<?=url("/preSaleOrder/detail/?sn={$order->order_sn}&st_uid={$this->st_uid}")?>">
            <div class="order_item clearFix">
                <img class="image" src="<?=$order->goods_image?>">
                <div class="oi_content">
                    <?=$order->goods_name?>  <?=$order->spec_1?> <?=$order->spec_2?>
                    <p><span class="count gray"><?=$order->quantity?> 件</span></p>
                </div>
            </div>
        </a>
        <div class="remark">备注：<?=nl2br($order->buyer_remark)?></div>
        <div class="order_footer">
            <p>总价：<em>¥<?=$order->order_money?></em></p>
            <p>定金：<em class="co_red">¥<?=$order->pre_money?></em></p>
            <? if($order->status==1) : ?>
                <a href="/member/preSaleOrder/prePay/?sn=<?=$order->order_sn?>" class="weui-btn weui-btn_mini weui-btn_primary">支付定金</a><br>
            <? elseif($order->status==3) : ?>
                <a href="/member/preSaleOrder/endPay/?sn=<?=$order->order_sn?>" class="weui-btn weui-btn_mini weui-btn_primary">支付尾款</a><br>
            <? elseif($order->status==5) : ?>
                <a href="<?=url("preSaleOrder/success/?id={$order->id}")?>" class="weui-btn weui-btn_mini weui-btn_primary">确认收货</a><br>
            <? elseif($order->status==6) : ?>
                <span>己完成</span>
            <? endif;?>
        </div>
    </div>
<? endforeach;?>

<? if($orders['total']==0) : ?>
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">没有任何记录！</h2>
            <a href="<?=url('/goods/lists')?>" class="weui-btn weui-btn_plain-primary weui-btn_mini">去逛逛</a>
        </div>
    </div>
<? else: ?>
    <?=$orders['page']?>
<? endif;?>
<?php require 'footer.php';?>