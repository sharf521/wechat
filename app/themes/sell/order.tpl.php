<?php require 'header.php';?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend><?=$this->title?></legend>
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
                    $buyer=$order->Buyer();
                    ?>
                    <dl class="orderbox">
                        <dt>
                            <span class="time"><?=substr($order->created_at,0,10)?></span> 订单号：<?= $order->order_sn ?>
                            <span class="status"><?=$order->getLinkPageName('order_status',$order->status)?></span>
                             <span class="buyer">
                                买家：<?=$buyer->username?> <?=\App\Helper::getQqLink($buyer->qq)?>
                            </span>
                            <span class="buyer">
                            <? if($order->supply_user_id!=0):
                                $supply=$order->Supply();
                                ?>
                                供应商：<?=$supply->name?> <?=\App\Helper::getQqLink($supply->qq)?>
                            <? endif?>
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
                                                        <a href="<?=url("/goods/detail/{$g->goods_id}")?>" target="_blank"><?=$g->goods_name?></a>                                                                                <br><?=$g->spec_1?> <?=$g->spec_2?>
                                                    </div>
                                                    <div class="quantity">
                                                        ¥<?=$g->price?> <span>X</span> <?= $g->quantity ?>
                                                        <br>
                                                        <span>运费：¥<?=$g->shipping_fee?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <? endforeach;?>
                                    </td>
                                    <td align="center" width="120"><span class="money">¥<?=$order->order_money?></span><br>(含运费：¥<?=$order->shipping_fee?>)
                                        <br>
                                        <a href="<?=url("/order/detail/?sn={$order->order_sn}")?>" target="_blank">订单详情</a>
                                    </td>
                                    <td class="operate">
                                        <? if($order->status==1) : ?>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="layui-btn layui-btn-small editMoney">修改价格</a><br>
                                        <? elseif ($order->status==3) : ?>
                                            <? if($order->supply_user_id==0) :?>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="layui-btn layui-btn-small editShipping">发货</a><br>
                                                <? else:?>
                                                联系供应商发货
                                            <? endif;?>
                                            <a href="javascript:;" data-id="<?=$order->id?>" class="cancel layui-btn layui-btn-small layui-btn-primary">取消订单</a><br>
                                        <? endif;?>
                                    </td>
                                </tr>
                                <tr><td colspan="3">备注：<?=nl2br($order->buyer_remark)?></td></tr>
                            </table>
                        </dd>
                    </dl>
                <? endforeach;?>
                <? if($orders['total']==0) : ?>
                    <blockquote class="layui-elem-quote">没有匹配到任何记录！</blockquote>
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
                       $('.editMoney').on('click',function () {
                           var id=$(this).attr('data-id');
                           layer.open({
                               type: 2,
                               title: '修改订单价格',
                               shadeClose: true,
                               shade: 0.8,
                               area: ['460px', '320px'],
                               content: '<?=url("order/editMoney/?id=")?>'+id
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
                                content: '<?=url("order/editShipping/?id=")?>'+id
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>

<?php require 'footer.php';?>