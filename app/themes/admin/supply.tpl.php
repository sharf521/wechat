<?php require 'header.php';

$arr_status=array('0'=>'待审核','1'=>'己通过','2'=>'未通过');
?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>供货商管理</span>列表
    </blockquote>
    <div class="main_content">
        <table class="layui-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>申请人</th>
                <th>名称</th>
                <th>法人</th>
                <th>资质1</th>
                <th>资质2</th>
                <th>资质3</th>
                <th>联系人</th>
                <th>QQ</th>
                <th>详细地址</th>
                <th>时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($result['list'] as $row) {
                $shop=$row->Shop();
                $user=$row->User();
                ?>
                <tr>
                    <td><?=$row->id?></td>
                    <td><?= $user->username ?>(<?=$row->user_id?>)</td>
                    <td><?= $shop->name ?><br><?=$row->company_name?></td>
                    <td><?=$row->company_owner?></td>
                    <td>
                        <? if ($row->picture1 != '') { ?>
                            <a href="<?= $row->picture1 ?>" target="_blank"><img
                                    src="<?= $row->picture1 ?>" align="absmiddle" width="100"/></a><br>
                        <? } ?>
                    </td>
                    <td>

                        <? if ($row->picture2 != '') { ?>
                            <a href="<?= $row->picture2 ?>" target="_blank"><img
                                    src="<?= $row->picture2 ?>" align="absmiddle" width="100"/></a><br>
                        <? } ?>
                    </td>
                    <td>
                        <? if ($row->picture3 != '') { ?>
                            <a href="<?= $row->picture3 ?>" target="_blank"><img
                                    src="<?= $row->picture3 ?>" align="absmiddle" width="100"/></a><br>
                        <? } ?>
                    </td>
                    <td><?= $shop->contacts ?><br><?= $shop->tel ?></td>
                    <td><?= $shop->qq ?></td>
                    <td> <?=$shop->region_name?><br><?= $shop->address ?></td>
                    <td><?= $row->created_at ?></td>
                    <td><?=$arr_status[$row->status]?></td>
                    <td>
                        <? if($row->status==0) : ?>
                        <a href="<?= url("supply/checked/?user_id={$row->user_id}&page={$_GET['page']}") ?>" class="layui-btn layui-btn-mini">审核</a>
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
        <a href="<?= url('supply') ?>" class="layui-btn layui-btn-small">返回列表</a></blockquote>
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
                    <label class="layui-form-label">企业名称</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux"><?=$supply->company_name?></div>

                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">企业法人</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux"><?=$supply->company_owner?></div>

                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">企业资质</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux">
                            <? if ($supply->picture1 != '') { ?>
                                <a href="<?= $supply->picture1 ?>" target="_blank"><img
                                        src="<?= $supply->picture1 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                            <? if ($supply->picture2 != '') { ?>
                                <a href="<?= $supply->picture2 ?>" target="_blank"><img
                                        src="<?= $supply->picture2 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                            <? if ($supply->picture3 != '') { ?>
                                <a href="<?= $supply->picture3 ?>" target="_blank"><img
                                        src="<?= $supply->picture3 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">供货说明</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux"><?=nl2br($supply->remark)?></div>
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