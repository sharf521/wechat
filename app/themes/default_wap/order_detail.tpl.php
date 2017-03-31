<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1);"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>订单详情</h1>
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

            <?php if($this->user->type_id!=1 || $order->buyer_id==$this->user_id || $order->supply_user_id==$this->user_id) : ?>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">卖家</label>
                    <span class="weui-form-preview__value"><?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?></span>
                </div>
            <? endif;?>

            <?php if($this->user->type_id!=1 || $order->seller_id==$this->user_id) : ?>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">供应商</label>
                    <span class="weui-form-preview__value"><?=$supplyer->name?> <?=\App\Helper::getQqLink($supplyer->qq)?></span>
                </div>
            <? endif;?>

            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单状态</label>
                <span class="weui-form-preview__value"><?=$order->getLinkPageName('order_status',$order->status)?></span>
            </div>
        </div>
        <div class="weui-form-preview__hd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">订单总金额</label>
                <em class="weui-form-preview__value">¥<?=$order->order_money?></em>
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
        <?php foreach ($goods as $g) : ?>
            <a href="<?=url("/goods/detail/{$g->goods_id}")?>">
                <div class="order_item clearFix">
                    <img class="image" src="<?=$g->goods_image?>">
                    <div class="oi_content">
                        <?=$g->goods_name?> <?=$g->spec_1?> <?=$g->spec_2?>
                        <p><span class="count price">¥<?=$g->price?> x<?=$g->quantity?></span></p>
                    </div>
                </div>
            </a>
        <? endforeach;?>
    </div>

<?php require 'footer.php';?>