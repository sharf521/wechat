<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>我的消息</legend>
            </fieldset>
            <?
            if($result['total']==0) {
                echo '<blockquote class="layui-elem-quote">您暂时没有最新消息</blockquote>';
            }else{?>
                <table class="layui-table"  lay-skin="line">
                    <thead>
                    <tr>
                        <th>发件人</th><th>内容</th><th>时间</th><th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach($result['list'] as $notice) : ?>
                        <tr>
                            <td><?=$notice->send_uid?></td>
                            <td><?=$notice->content?></td>
                            <td><?=$notice->created_at?></td>
                            <td>
                                <a class="layui-btn layui-btn-mini del" data_id="<?=$notice->id?>">删除</a></td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <?=$result['page']?>
            <?php }?>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.del').on('click',function () {
            var id=$(this).attr('data_id');
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url("notice/del/?id=")?>'+id;
                    layer.close(index);
                }
            });
        })
    });
</script>
<?php require 'footer.php';?>
