<?php require 'header.php';?>
    <div class="layui-main">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>商品管理</legend>
                </fieldset>
                <a href="<?=url('goods/selCategory')?>" class="layui-btn layui-btn-small">新增</a><br>

                <div class="layui-tab layui-tab-brief" lay-filter="tab">
                    <ul class="layui-tab-title">
                        <li <? if($this->func=='index'){echo 'class="layui-this"';}?>>出售中</li>
                        <li <? if($this->func=='list_stock0'){echo 'class="layui-this"';}?>>售罄的</li>
                        <li <? if($this->func=='list_status2'){echo 'class="layui-this"';}?>>仓库中</li>
                    </ul>
                </div>
                <table class="layui-table"  lay-even lay-skin="row">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>价格</th>
                        <th>库存</th>
                        <th>添加时间</th>
                        <th width="140">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($result['list'] as $goods) : ?>
                    <tr>
                        <td><img width="100" src="<?=\App\Helper::smallPic($goods->image_url)?>" style="float: left">
                            <a href="/purchase/detail/<?=$goods->id?>" target="_blank"><?=$goods->name?></a></td>
                        <td>￥<?=$goods->price?></td>
                        <td><?=$goods->stock_count?></td>
                        <td><?=$goods->created_at?></td>
                        <td>
                            <? if($this->func=='index') : ?>
                                <a href="<?=url("goods/change/?id={$goods->id}")?>" class="layui-btn layui-btn-mini">下架</a>
                            <? endif;?>
                            <? if($this->func=='list_status2') :?>
                                <a href="<?=url("goods/change/?id={$goods->id}")?>" class="layui-btn layui-btn-mini">上架</a>
                            <? endif;?>
                            <a href="<?=url("goods/edit/?id={$goods->id}")?>" class="layui-btn layui-btn-mini">编辑</a><a href="javascript:goodsDel(<?=$goods->id?>)" class="layui-btn layui-btn-mini">删除</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <? if($result['total']==0) : ?>
                    <blockquote class="layui-elem-quote">暂无商品</blockquote>
                <? else : ?>
                    <?= $result['page']?>
                <? endif;?>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            layui.element.on('tab(tab)', function(data){
                if(data.index==0){
                    location.href='<?=url("goods")?>';
                }else if(data.index==1){
                    location.href='<?=url("goods/list_stock0")?>';
                }else if(data.index==2){
                    location.href='<?=url("goods/list_status2")?>';
                }
            });
        });
        function goodsDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url("goods/del/?id=")?>'+id;
                    layer.close(index);
                }
            });
        }
    </script>
<?php require 'footer.php';?>