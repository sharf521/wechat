<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <?php if($this->func=='index') : ?>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>配送方式管理</legend>
                </fieldset>
                <a href="<?=url('shipping/add')?>" class="layui-btn layui-btn-small">新增</a><br><br>
                <?
                if(count($cates)==0) {
                    echo '<blockquote class="layui-elem-quote">暂无添加</blockquote>';
                }else{?>
                    <table class="layui-table"  lay-skin="line">
                        <thead>
                        <tr>
                            <th>分类名称</th><th>添加时间</th><th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach($cates as $cate) : ?>
                            <tr>
                                <td><?=$cate['name']?></td>
                                <td><?=date('Y-m-d H:i:s',$cate['created_at'])?></td>
                                <td><a href="<?=url("category/add/?pid={$cate['id']}")?>"></a>
                                    <a href="<?=url("category/edit/?id={$cate['id']}")?>">编辑</a>
                                    <a href="javascript:cateDel(<?=$cate['id']?>)">删除</a></td>
                            </tr>
                        <? endforeach;?>
                        </tbody>
                    </table>
                <?php }?>
            <? endif;?>
    </div>
</div>
    <script>
        category_js();
        function cateDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url("category/del/?id=")?>'+id;
                    layer.close(index);
                }
            });
        }
    </script>
<?php require 'footer.php';?>