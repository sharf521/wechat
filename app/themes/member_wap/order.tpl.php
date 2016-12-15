<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>我的订单</h1>
    </div>
    <div class="my-navbar margin_header">
        <div class="my-navbar__item <? if($this->func=='index'){echo 'my-navbar__item_on';}?>">
            <a href="<?=url('order')?>">全部订单</a>
        </div>
        <div class="my-navbar__item <? if($this->func=='status1'){echo 'my-navbar__item_on';}?>">
            <a href="<?=url('order/status1')?>">待付款</a>
        </div>
        <div class="my-navbar__item <? if($this->func=='status3'){echo 'my-navbar__item_on';}?>">
            <a href="<?=url('order/status3')?>">待发货</a>
        </div>
        <div class="my-navbar__item <? if($this->func=='status4'){echo 'my-navbar__item_on';}?>">
            <a href="<?=url('order/status4')?>">待收货</a>
        </div>
    </div>

<? foreach ($orders['list'] as $order) :
    $goods=$order->OrderGoods();
    ?>
    <div class="order_box">
        <div class="order_head">
            <div class="oh_content">
                <p class="pState"><span>状<i></i>态：</span><em class="co_blue"><?=$order->getLinkPageName('order_status',$order->status)?></em></p>
                <p><span>总<i></i>价：</span><em class="co_red">¥<?=$order->order_money?></em></p>
                <span class="time"><?=$order->created_at?></span>
            </div>
        </div>
        <a class="order_shopBar"><i class="iconfont">&#xe854;</i><em>我的小店</em></a>
        <? foreach ($goods as $g) : ?>
            <a href="<?=url("/goods/detail/?id={$g->goods_id}")?>">
                <div class="order_item">
                    <img class="image" src="<?=$g->goods_image?>">
                    <div class="oi_content">
                        <?=$g->goods_name?> <?=$g->spec_1?> <?=$g->spec_2?>
                        <p><span class="count"><?=$g->quantity?> 件</span></p>
                    </div>
                </div>
            </a>
        <? endforeach;?>
        <div class="order_footer">
            <? if($order->status==1) : ?>
                <a href="javascript:;" data-id="<?=$order->id?>" class="cancel weui-btn weui-btn_mini weui-btn_plain-primary">取消订单</a>
                <a href="<?=url("order/pay/?id={$order->id}")?>" class="weui-btn weui-btn_mini weui-btn_primary">支付</a>
            <? endif;?>
        </div>
    </div>
<? endforeach;?>

<? if($orders['total']==0) : ?>
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">没有任何记录。。</h2>
            <p class="weui-msg__desc"></p>
        </div>
    </div>
<? else: ?>
    <?=$orders['page']?>
<? endif;?>


    <script type="text/javascript">
        $(function () {
            $('.order_footer').find('.cancel').on('click',function () {
                var id=$(this).attr('data-id');
                layer.open({
                    content: '您确定要删除吗？'
                    ,btn: ['删除', '取消']
                    ,yes: function(index){
                        location.href='<?=url("order/cancel/?id=")?>'+id;
                        layer.close(index);
                    }
                });
            });
        });
    </script>
<?php require 'footer.php';?>