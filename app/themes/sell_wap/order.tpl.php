<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('/member')?>"><i class="iconfont">&#xe604;</i></a>
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
            <p class="status"><em class="co_blue"><?=$order->getLinkPageName('order_status',$order->status)?></em></p>
            <span class="time"><?=$order->created_at?></span>
        </div>
        <? if($order->supply_user_id!=0):
            $supply=$order->Supply();?>
            <div class="order_shopBar">
                供应商：<?=$supply->name?>
                <?=\App\Helper::getQqLink($supply->qq)?>
            </div>
        <? endif;?>
        <? foreach ($goods as $g) : ?>
            <a href="<?=url("/order/detail/?sn={$order->order_sn}")?>">
                <div class="order_item">
                    <img class="image" src="<?=$g->goods_image?>">
                    <div class="oi_content">
                        <?=$g->goods_name?> <?=$g->spec_1?> <?=$g->spec_2?>
                        <p><span class="count price">¥<?=$g->price?> x<?=$g->quantity?></span></p>
                    </div>
                </div>
            </a>
        <? endforeach;?>
        <div class="remark">备注：<?=nl2br($order->buyer_remark)?></div>
        <div class="order_footer">
            <p>总价：<em class="co_red price">¥<?=$order->order_money?></em></p>
            <? if($order->status==1) : ?>
                <a href="<?=url("order/editMoney/?id={$order->id}")?>" class="weui-btn weui-btn_mini weui-btn_primary">修改价格</a>
            <? elseif ($order->status==3) : ?>
                <a href="javascript:;" data-id="<?=$order->id?>" class="cancel weui-btn weui-btn_mini weui-btn_plain-primary">取消订单</a>


                <? if($order->supply_user_id==0) :?>
                    <a href="<?=url("order/editShipping/?id={$order->id}")?>" class="weui-btn weui-btn_mini weui-btn_primary">发货</a>
                <? else:?>
                    <div>联系供应商发货</div>
                <? endif;?>

            <? endif;?>
        </div>
    </div>
<? endforeach;?>

<? if($orders['total']==0) : ?>
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">没有任何商品！</h2>
            <p class="weui-msg__desc"></p>
        </div>
    </div>
<? endif;?>
    <script type="text/javascript">
        $(function () {
            $('.order_footer').find('.cancel').on('click',function () {
                var id=$(this).attr('data-id');
                layer.open({
                    content: '确定要取消该订单吗？'
                    ,btn: ['是', '否']
                    ,yes: function(index){
                        location.href='<?=url("order/cancel/?id=")?>'+id;
                        layer.close(index);
                    }
                });
            });
        });
    </script>
<?php require 'footer.php';?>