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
                        <li <? if($this->func=='status4'){echo 'class="layui-this"';}?>><a href="<?=url('order/status4')?>">待确认收货</a></li>
                    </ul>
                </div>
                <? foreach($orders['list'] as $order) :
                    $shop=$order->Shop();
                    ?>
                    <dl class="orderbox">
                        <dt>
                            <span class="time"><?=substr($order->created_at,0,10)?></span> 订单号：<?= $order->order_sn ?>
                            <span class="status"><?=$order->getLinkPageName('order_status',$order->status)?></span>
                            <span class="shop">
                                <i class="iconfont">&#xe854;</i> <?=$shop->name?> <?=\App\Helper::getQqLink($shop->qq)?>
                            </span>
                        </dt>
                        <dd>
                            <table class="layui-table" style="margin: 0px;">
                                <tr>
                                    <td>
                                        <?
                                        $goods=$order->OrderGoods();
                                        foreach ($goods as $g) : ?>
                                            <div class="clearFix" style="border-bottom: 1px solid #efefef;">
                                                <a href="<?=url("/goods/detail/{$g->goods_id}")?>" target="_blank"><img class="goodsImg" src="<?=\App\Helper::smallPic($g->goods_image)?>" width="100"></a>
                                                <div class="goodsDetail">
                                                    <div class="name">
                                                        <a href="<?=url("/goods/detail/{$g->goods_id}")?>" target="_blank"><?=$g->goods_name?></a><br>
                                                        <?=$g->spec_1?> <?=$g->spec_2?>
                                                    </div>

                                                    <div class="quantity">￥<?=$g->price?> <span>X</span> <?= $g->quantity ?></div>
                                                </div>
                                            </div>
                                        <? endforeach;?>
                                    </td>
                                    <td align="center"><span class="money">¥<?=$order->order_money?></span><br>(含运费：¥<?=$order->shipping_fee?>)<br>
                                        <a href="<?=url("/order/detail/?sn={$order->order_sn}")?>" target="_blank">订单详情</a>
                                    </td>
                                    <td class="operate">
                                        <? if($order->status==1) : ?>
                                            <!--<a href="<?/*=url("order/pay/?id={$order->id}")*/?>" class="layui-btn layui-btn-small ">立即支付</a><br>-->
                                            <a href="<?=$order->getPayUrl()?>" class="layui-btn layui-btn-small ">立即支付</a><br>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="layui-btn layui-btn-small layui-btn-primary cancel">取消订单</a><br>
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