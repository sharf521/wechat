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
                    $buyer=$order->Buyer();
                    ?>
                    <dl class="orderbox">
                        <dt>
                            <span class="time"><?=substr($order->created_at,0,10)?></span> 订单号：<?= $order->order_sn ?>
                            <span class="status"><?=$order->getLinkPageName('order_status',$order->status)?></span>
                             <span class="buyer">
                                买家：<?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?>
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
                                        <a href="<?=url("/preSaleOrder/detail/?sn={$order->order_sn}")?>" target="_blank">订单详情</a>
                                    </td>
                                    <td class="operate">
                                        <? if($order->status==2) : ?>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="layui-btn layui-btn-small setPreTrue">设为预订成功</a><br>
                                        <? elseif($order->status==4) : ?>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="layui-btn layui-btn-small editShipping">发货</a><br>
                                        <? elseif($order->status==6) : ?>
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
                        $('.setPreTrue').on('click',function () {
                            var id=$(this).attr('data-id');
                            layer.open({
                                content: '设为预订成功，确定要用户支付尾款吗？'
                                ,btn: ['是', '否']
                                ,yes: function(index){
                                    location.href='<?=url("preSaleOrder/setPreTrue/?id=")?>'+id;
                                    layer.close(index);
                                }
                            });
                        });

                        $('.editShipping').on('click',function () {
                            var id=$(this).attr('data-id');
                            layer.open({
                                type: 2,
                                title: '发货',
                                shadeClose: true,
                                shade: 0.8,
                                area: ['460px', '290px'],
                                content: '/sellManage/preSaleOrder/editShipping/?id='+id
                            });
                        });

                    });
                </script>
            </div>
        </div>
    </div>
<?php require 'footer.php';?>