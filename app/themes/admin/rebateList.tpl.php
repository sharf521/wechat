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
            关键字：<input type="text" name="remark" value="<?=$_GET['remark']?>">
            下单时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <div class="main_content">
    <form class="layui-form" method="post">
        <table class="layui-table">
            <thead>
            <tr>
                <th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose"></th>
                <th>分站id</th>
                <th>用户</th>
                <th>Label</th>
                <th>奖励金额</th>
                <th>添加时间</th>
                <th>备注</th>
                <th>开始时间</th>
                <th>开始操作人</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <?
            $arr_status=array('未开始','己开始');
            foreach ($result['list'] as $rebate) {
                $user=$rebate->User();
                ?>
                <tr>
                    <td>
                        <? if($rebate->status==0) : ?>
                            <input type="checkbox" name="id[]" value="<?=$rebate->id?>" lay-skin="primary">
                        <? endif;?>
                        </td>
                    <td><?=$rebate->site_id?></td>
                    <td><?= $user->username ?>(<?=$user->id?>)<?=\App\Helper::getQqLink($user->qq)?></td>
                    <td><?= $rebate->label ?></td>
                    <td>￥<?=$rebate->money?></td>
                    <td><?= $rebate->created_at ?></td>
                    <td><?=$rebate->remark?></td>
                    <td><?=$rebate->start_at?></td>
                    <td><?=$rebate->start_uid?></td>
                    <td><?=$arr_status[$rebate->status]?></td>
                </tr>
            <? } ?>
            </tbody>
        </table>
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="*">开始奖励</button>
    </form>

        <? if (empty($result['total'])) {
            echo "无记录！";
        } else {
            echo $result['page'];
        } ?>
    </div>
<? endif; ?>
    <script>
        $(function () {
            layui.form.on('checkbox(allChoose)', function(data){
                var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
                child.each(function(index, item){
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
            layui.form.on('submit(*)', function(data){
                var form=data.form;
                var t=$("tbody input[type=checkbox]:checked").length;
                if(t==0){
                    layer.msg('请勾选');
                }else{
                    layer.confirm('您确定要开始奖励吗？', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        $(form).submit();
                    });
                }
                return false;
            });
        });
    </script>
<?php require 'footer.php'; ?>