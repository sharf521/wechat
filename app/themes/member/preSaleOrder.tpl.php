<?php require 'header.php';?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>我的预订单</legend>
                </fieldset>
                <? foreach($orders['list'] as $order) :
                    $shop=$order->Shop();
                    ?>
                    <dl class="orderbox">
                        <dt>
                            <span class="time"><?=substr($order->created_at,0,10)?></span> 订单号：<?= $order->order_sn ?>
                            <span class="status"><?=$order->showStatusName()?></span>
                            <span class="shop">
                                <i class="iconfont">&#xe854;</i> <?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?>
                            </span>
                        </dt>
                        <dd>
                            <table class="layui-table" style="margin: 0px;">
                                <tr>
                                    <td>
                                        <div class="clearFix" style="border-bottom: 1px solid #efefef;">
                                            <a href="<?=url("/goods/detail/{$order->goods_id}")?>" target="_blank"><img class="goodsImg" src="<?=\App\Helper::smallPic($order->goods_image)?>" width="100"></a>
                                            <div class="goodsDetail">
                                                <div class="name">
                                                    <a href="<?=url("/goods/detail/{$order->goods_id}")?>" target="_blank"><?=$order->goods_name?></a><br>
                                                    <?=$order->spec_1?> <?=$order->spec_2?>
                                                </div>

                                                <div class="quantity">￥<?=$order->price?> <span>X</span> <?= $order->quantity ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="center"><span class="money">¥<?=$order->order_money?></span><br>
                                        定金：<?=$order->pre_money?>
                                        <br>
                                        <a href="<?=url("/order/detail/?sn={$order->order_sn}")?>" target="_blank">订单详情</a>
                                    </td>
                                    <td class="operate">
                                        <? if($order->status==1) : ?>
                                            <a href="/member/preSaleOrder/prePay/?sn=<?=$order->order_sn?>" class="layui-btn layui-btn-small ">支付定金</a><br>
                                        <? elseif($order->status==3) : ?>
                                            <a href="/member/preSaleOrder/endPay/?sn=<?=$order->order_sn?>" class="layui-btn layui-btn-small ">支付尾款</a><br>
                                        <? elseif($order->status==4) : ?>
                                            <a href="<?=url("order/success/?id={$order->id}")?>" class="layui-btn layui-btn-small">确认收货</a><br>
                                        <? elseif($order->status==5) : ?>
                                            <span>己完成</span>
                                        <? endif;?>
                                    </td>
                                </tr>
                                <tr><td colspan="3">备注：<?=nl2br($order->buyer_remark)?></td></tr>
                            </table>
                        </dd>
                    </dl>
                <? endforeach;?>


                <? if($orders['total']==0) : ?>
                    <blockquote class="layui-elem-quote">没有匹配到任何记录！ &nbsp;<a href="<?=url('/goods/lists')?>" class="layui-btn layui-btn-small">去逛逛</a></blockquote>
                <? else: ?>
                    <?=$orders['page']?>
                <? endif;?>
                <script type="text/javascript">
                    $(function () {
                        $('.cancel').on('click',function () {
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