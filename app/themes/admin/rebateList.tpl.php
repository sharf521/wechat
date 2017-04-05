<?php require 'header.php';

$arr_status=array('-1'=>'己删除','0'=>'','1'=>'正常','2'=>'己下架');
?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>队列</span>列表
    </blockquote>
    <form method="get">
        <div class="search">
            Label：<input type="text" name="label" value="<?=$_GET['label']?>" size="15" placeholder="label"/>
            用户ID：<input type="text" name="user_id" value="<?=$_GET['user_id']?>" size="15" placeholder="用户id"/>
            下单时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <div class="main_content">
        <table class="layui-table">
            <thead>
            <tr>
                <th>用户</th>
                <th>Label</th>
                <th>奖励金额</th>
                <th>添加时间</th>
                <th>开始时间</th>
                <th>开始操作人</th>
                <th>状态</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?
            $arr_status=array('未开始','己开始');
            foreach ($result['list'] as $rebate) {
                $user=$rebate->User();
                ?>
                <tr>
                    <td><?= $user->username ?>(<?=$user->id?>)<?=\App\Helper::getQqLink($user->qq)?></td>
                    <td><?= $rebate->label ?></td>

                    <td>￥<?=$rebate->money?></td>
                    <td><?= $rebate->created_at ?></td>
                    <td><?=$rebate->start_at?></td>
                    <td><?=$rebate->start_uid?></td>
                    <td><?=$arr_status[$rebate->status]?></td>
                    <td>
                        <? if($rebate->status==0) : ?>
                            <a href="javascript:goStart(<?=$rebate->id?>)" class="layui-btn layui-btn-mini">开始奖励</a>
                        <? endif;?>
                        </td>
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
        function goStart(id)
        {
            layer.open({
                content: '您确定要开始奖励吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    location.href='<?=url('rebateList/start/?id=')?>'+id;
                    layer.close(index);
                }
            });
        }

    </script>
<?php require 'footer.php'; ?>