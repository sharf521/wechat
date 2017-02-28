<?php require 'header.php';?>

    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>我的订单</legend>
                </fieldset>

                <div class="layui-tab layui-tab-brief" lay-filter="tab">
                    <ul class="layui-tab-title">
                        <li <? if($this->func=='index'){echo 'class="layui-this"';}?>><a href="<?=url('order')?>">全部订单</a></li>
                        <li <? if($this->func=='status1'){echo 'class="layui-this"';}?>><a href="<?=url('order/status1')?>">待付款</a></li>
                        <li <? if($this->func=='status3'){echo 'class="layui-this"';}?>><a href="<?=url('order/status3')?>">待发货</a></li>
                        <li <? if($this->func=='status4'){echo 'class="layui-this"';}?>><a href="<?=url('order/status4')?>">待收货</a></li>
                    </ul>
                </div>

                <? foreach ($orders['list'] as $order) :
                    $goods=$order->OrderGoods();
                    ?>
                    <div class="order_box">
                        <div class="order_head">
                            <p class="status"><em class="co_blue"><?=$order->getLinkPageName('order_status',$order->status)?></em></p>
                            <span class="time"><?=$order->created_at?></span>
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
                        <div class="remark">备注：<?=nl2br($order->buyer_remark)?></div>
                        <div class="order_footer">
                            <p>总价：<em class="co_red">¥<?=$order->order_money?></em></p>
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
                            <a href="<?=url('/goods/lists')?>" class="weui-btn weui-btn_plain-primary weui-btn_mini">去逛逛</a>
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


            </div>


        </div>
    </div>

<?php require 'footer.php';?>