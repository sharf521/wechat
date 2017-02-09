<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <td class="box">
            <br>
            <?php if($this->func=='index') : ?>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>店铺分类管理</legend>
                </fieldset>
                <a href="<?=url('category/add')?>" class="layui-btn layui-btn-small">添加一级分类</a><br><br>
                <?
                if(count($cates)==0) {
                    echo '<blockquote class="layui-elem-quote">暂无添加分类</blockquote>';
                }else{?>
                    <table class="layui-table"  lay-skin="row" lay-even>
                        <thead>
                        <tr>
                            <th>分类名称</th><th>添加时间</th><th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach($cates as $cate) : ?>
                            <tr>
                                <td><?=$cate['name_pre']?><?=$cate['name']?></td>
                                <td><?=date('Y-m-d H:i:s',$cate['created_at'])?></td>
                                <td>
                                    <a href="<?=url("category/edit/?id={$cate['id']}")?>" class="layui-btn layui-btn-mini">编辑</a>
                                    <a href="javascript:cateDel(<?=$cate['id']?>)" class="layui-btn layui-btn-mini">删除</a>
                                    <? if($cate['pid']==0) : ?>
                                        <a href="<?=url("category/add/?pid={$cate['id']}")?>" class="layui-btn layui-btn-mini">添加子分类</a>
                                    <? endif;?>
                                </td>
                            </tr>
                        <? endforeach;?>
                        </tbody>
                    </table>
                <?php }?>
            <?php elseif ($this->func=='add' || $this->func=='edit') : ?>
                <span class="layui-breadcrumb">
                  <a href="<?=url('category')?>">分类管理</a>
                  <a><cite><?=$this->func == 'add'?'新增':'编辑'; ?>分类</cite></a>
                </span>
                <hr>
                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">分类名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" value="<?=$cate->name?>"  placeholder="请填写分类名称" class="layui-input" value="" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
                            </div>
                        </div>
                    </div>
                </form>
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