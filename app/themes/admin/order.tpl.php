<?php require 'header.php';

$arr_status=array('-1'=>'己删除','0'=>'','1'=>'正常','2'=>'己下架');
?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>订单</span>列表
    </blockquote>
    <form method="get">
        <div class="search">
            订单号：<input type="text" name="order_sn" value="<?=$_GET['order_sn']?>" size="15" placeholder="订单号"/>
            买家ID：<input type="text" name="buyer_id" value="<?=$_GET['buyer_id']?>" size="15" placeholder="买家用户id"/>
            商家ID：<input type="text" name="seller_id" value="<?=$_GET['seller_id']?>" size="15" placeholder="商家用户id"/>
            供应商ID：<input type="text" name="supply_user_id" value="<?=$_GET['supply_user_id']?>" size="15" placeholder="供应商用户ID"/>
            下单时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <div class="main_content">
        <table class="layui-table">
            <thead>
            <tr>
                <th>订单号</th>
                <th>买家</th>
                <th>店铺名称</th>
                <th>卖家</th>
                <th>供应商</th>
                <th>下单时间</th>
                <th>订单总价</th>
                <th>状态</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($result['list'] as $order) {
                $shop=$order->Shop();
                $seller=$shop->User();
                $buyer=$order->Buyer();
                ?>
                <tr>
                    <td><?= $order->order_sn ?></td>
                    <td><?= $buyer->username ?>(<?=$buyer->id?>)<?=\App\Helper::getQqLink($buyer->qq)?></td>
                    <td><?= $shop->name ?></td>
                    <td><?=$seller->username?>(<?=$seller->id?>)<?=\App\Helper::getQqLink($seller->qq)?></td>
                    <td>
                        <? if($order->supply_user_id!=0):
                            $supply=$order->Supply();
                            ?>
                            <?=$supply->name?>(<?=$order->supply_user_id?>) <?=\App\Helper::getQqLink($supply->qq)?>
                        <? endif?>
                    </td>
                    <td><?= $order->created_at ?></td>
                    <td>￥<?=$order->order_money?></td>
                    <td><?=$order->getLinkPageName('order_status',$order->status)?></td>
                    <td><a target="_blank" href="<?=url("/order/detail/?sn={$order->order_sn}")?>" class="layui-btn layui-btn-mini">订单详情</a></td>
                </tr>
            <? } ?>
            </tbody>

        </table>
        <? if (empty($result['total'])) {
            echo "无记录！";
        } else {
            echo $result['page'];
        } ?>
    </div>
<? endif; ?>
    <script>
        function goDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url('carBrand/delete/?id=')?>'+id;
                    layer.close(index);
                }
            });
        }

    </script>
<?php require 'footer.php'; ?>