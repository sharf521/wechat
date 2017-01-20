<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>配送方式管理</legend>
            </fieldset>
            <a href="<?=url('shipping/add')?>" class="layui-btn layui-btn-small">新增</a><br>
            <?
            if(count($ships)==0) {
                ?>
                <br><blockquote class="layui-elem-quote">暂无添加，<a href="<?=url('shipping/add')?>" class="layui-btn layui-btn-mini">添加</a> </blockquote>
                <?
            }else{?>
                <? foreach($ships as $ship) : ?>
                    <table class="layui-table">
                        <tr style="background-color: #f5f5f5;">
                            <td style="width: 60%;">
                                <?=$ship->name?>
                                ( <?if($ship->typeid==1){?>按件计算<?}?>
                                <?if($ship->typeid==2){?>按重量计算<?}?>
                                <?if($ship->typeid==3){?>按体积计算<?}?>)</td>
                            <td colspan="3">最后编辑时间：<?=$ship->updated_at?></td>
                            <td>
                                <a href="<?=url("shipping/edit/?id={$ship->id}")?>" class="layui-btn layui-btn-mini">编辑</a><a href="javascript:shippingDel(<?=$ship->id?>)" class="layui-btn layui-btn-mini">删除</a></td>
                        </tr>
                        <tr>
                            <td >运送到</td>
                            <td>
                                <?if($ship->typeid==1){?>首件(件)<?}?>
                                <?if($ship->typeid==2){?>首重(kg)<?}?>
                                <?if($ship->typeid==3){?>首体积(m3)<?}?></td>
                            <td>运费(元)</td>
                            <td>
                                <?if($ship->typeid==1){?>续件(件)<?}?>
                                <?if($ship->typeid==2){?>续重(kg)<?}?>
                                <?if($ship->typeid==3){?>续体积(m3)<?}?></td>
                            <td>运费(元)</td>
                        </tr>
                        <? if(is_array($ship->areas)){
                            foreach($ship->areas as $key_r=>$value_r){?>
                                <tr>
                                    <td><?=$value_r['areaname']?></td>
                                    <td><?=$value_r['one']?></td>
                                    <td><?=$value_r['price']?></td>
                                    <td><?=$value_r['next']?></td>
                                    <td><?=$value_r['nprice']?></td>
                                </tr>
                            <? }}?>
                    </table>
                <? endforeach;?>
            <?php }?>
        </div>
    </div>
</div>
    <script>
        function shippingDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url("shipping/del/?id=")?>'+id;
                    layer.close(index);
                }
            });
        }
    </script>
<?php require 'footer.php';?>