<?php require 'header.php';

$arr_status=array('-1'=>'己删除','0'=>'','1'=>'正常','2'=>'己下架');
?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>商品</span>列表
    </blockquote>
    <form method="get">
        <div class="search">
            商家ID：<input type="text" name="user_id" value="<?=$_GET['user_id']?>" size="15" placeholder="商家用户id"/>
            供应商ID：<input type="text" name="supply_user_id" value="<?=$_GET['supply_user_id']?>" size="15" placeholder="供应商用户ID"/>
            <input type="text" name="q" value="<?=$_GET['q']?>" placeholder="名称关键字">
            添加时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <div class="main_content">
        <table class="layui-table">
            <thead>
            <tr>
                <th>商品名</th>
                <th>店铺名称(ID)</th>
                <th>价格</th>
                <th>库存</th>
                <th>销量</th>
                <th>添加时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($result['list'] as $goods) {
                $shop=$goods->Shop();
                ?>
                <tr>
                    <td>
                        <img src="<?=\App\Helper::smallPic($goods->image_url)?>" width="50">
                        <a href="/goods/detail/<?=$goods->id?>" target="_blank"><?=$goods->name?></a>
                        <? if($goods->supply_goods_id!=0):
                            $goods=$goods->pullSupplyGoods();
                            $supply=$goods->Supply();
                            ?>
                            <div style="margin: 10px; color: #999;">供应商：<?=$supply->name?>(<?=$goods->supply_user_id?>) <?=\App\Helper::getQqLink($supply->qq)?></div>
                        <? endif?>
                    </td>
                    <td><?= $shop->name ?>(<?=$goods->user_id?>)<?=\App\Helper::getQqLink($shop->qq)?></td>
                    <td>￥<?=$goods->price?></td>
                    <td><?=$goods->stock_count?></td>
                    <td><?=$goods->sale_count?></td>
                    <td><?= $goods->created_at ?></td>
                    <td><?=$arr_status[$goods->status]?></td>
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