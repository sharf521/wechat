<?php require 'header.php';
$arr_status=array('0'=>'待审核','1'=>'己通过','2'=>'未通过');
?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>店铺管理</span>列表
    </blockquote>
    <div class="main_content">
        <table class="layui-table" lay-skin="line">
            <thead>
            <th>ID</th>
            <th>申请人</th>
            <th>店铺名称</th>
            <th>联系人</th>
            <th>QQ</th>
            <th>详细地址</th>
            <th>时间</th>
            <th>状态</th>
            <th>推荐</th>
            <th>操作</th>
            </thead>
            <tbody>
            <? foreach ($result['list'] as $row) {
                ?>
                <tr>
                    <td><?=$row->id?></td>
                    <td><?= $row->User()->username ?>(<?=$row->user_id?>)</td>
                    <td><?= $row->name ?></td>
                    <td><?= $row->contacts ?><br><?= $row->tel ?></td>
                    <td><?= $row->qq ?></td>
                    <td> <?=$row->region_name?><br><?= $row->address ?></td>
                    <td><?= $row->created_at ?></td>
                    <td><?=$arr_status[$row->status]?></td>
                    <td> <a class="layui-btn layui-btn-mini <? echo $row->recommend == '1'?'layui-btn-normal':'';?>" href="<?= url("shop/recommend/?id={$row->id}&page={$_GET['page']}") ?>"><?= ($row->recommend == '1') ? '取消推荐' : '设为推荐' ?></a></td>
                    <td>
                        <? if($row->status==0) : ?>
                        <a href="<?= url("shop/checked/?user_id={$row->user_id}&page={$_GET['page']}") ?>" class="layui-btn layui-btn-mini">审核</a>
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
<? elseif ($this->func == 'checked') : ?>
    <blockquote class="layui-elem-quote"><span>审核</span>
        <a href="<?= url('shop') ?>" class="layui-btn layui-btn-small">返回列表</a></blockquote>
    <div class="main_content">
        <form method="post" class="layui-form">
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">申请店铺</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux"><?=$shop->name?></div>

                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">审核</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="checked" value="1" title="通过">
                        <input type="radio" name="checked" value="2" title="不通过">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <textarea name="verify_remark" placeholder="请输入备注" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
                        <button class="layui-btn" onclick="history.go(-1)">返回</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(function () {
                layui.form('radio').render();
                layui.form().on('submit(*)', function(data){
                    var form=data.form;
                    var fields=data.field;
                    var verify_remark=$(form).find('textarea[name=verify_remark]');
                    var radio1=$(form).find('input[name=checked]').eq(0);
                    var radio2=$(form).find('input[name=checked]').eq(1);
                    if(radio1.attr('checked')!='checked' && radio2.attr('checked')!='checked'){
                        layer.tips('请选择！', $(radio1).parent('.layui-input-inline'));
                        return false;
                    }
                    if(verify_remark.val()==''){
                        layer.tips('不能为空！', verify_remark);
                        verify_remark.focus();
                        return false;
                    }
                });
            });
        </script>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>