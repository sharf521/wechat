<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>分类管理</span>列表
        <?= $this->anchor("category/add/?pid={$pid}", '新增', 'class="layui-btn layui-btn-small"'); ?>
        <? if($pid!=0) : ?>
            <a href="<?=url("category/?pid={$pCate->pid}")?>" class="layui-btn layui-btn-small">返回上一级</a>
        <? endif;?>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table">
                <tr class="bt">
                    <th>ID</th>
                    <th>PID</th>
                    <th>名称</th>
                    <th>路径</th>
                    <th>排序</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                <? foreach ($list as $row) { ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->pid ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= $row->path ?></td>
                        <td>
                            <input type="text" value="<?= $row->showorder ?>" name="showorder[]" size="5">
                            <input type="hidden" name="id[]" value="<?= $row->id ?>">
                        </td>
                        <td><?= $row->created_at ?></td>
                        <td>
                            <a href="<?= url("category/?pid={$row->id}") ?>">管理子项</a>
                            <?
                            echo $this->anchor('category/edit/?id=' . $row->id, '编辑') . " | ";
                            $arr = array(
                                'onclick' => "return confirm('确定要删除吗？')"
                            );
                            echo $this->anchor('category/delete/?pid=' . $row->pid . '&id=' . $row->id, '删除', $arr);
                            ?>
                        </td>
                    </tr>
                <? } ?>
            </table>
            <div align="center"><input type="submit" value="更新排序" class="but3"/></div>
        </form>
    </div>
<? elseif ($this->func == 'add' || $this->func == 'edit') : ?>
    <div class="main_title">
        <span>分类管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?=url("category/?pid={$row->pid}")?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table_from">
                <tr>
                    <td >PID：</td>
                    <td >
                        <?=$row->pid?>
                    </td>
                </tr>
                <tr>
                    <td >名称：</td>
                    <td ><input type="text" name="name" value="<?= $row->name ?>"/></td>
                </tr>
                <tr>
                    <td >图标：</td>
                    <td ><input type="hidden" name="picture" id="article" value="<?= $row->picture ?>"/>
						<span id="upload_span_article">
							<? if ($row->picture != '') { ?>
                                <a href="<?= $row->picture ?>" target="_blank"><img
                                        src="<?= $row->picture ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <button type="button" class="layui-btn upload_btn" upload_id="article" upload_type="category">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </td>
                </tr>
                <tr>
                    <td >aside1：</td>
                    <td ><input type="text" name="aside1" value="<?= $row->aside1 ?>"/></td>
                </tr>
                <tr>
                    <td>aside2：</td>
                    <td ><input type="text" name="aside2" value="<?= $row->aside2 ?>"/></td>
                </tr>
                <tr>
                    <td >aside3：</td>
                    <td ><input type="text" name="aside3" value="<?= $row->aside3 ?>"/></td>
                </tr>
                <tr>
                    <td >排序：</td>
                    <td ><input type="text" name="showorder" value="<?= $row->showorder ?>"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" class="but3" value="保存"/>
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td>
                </tr>
            </table>
        </form>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>