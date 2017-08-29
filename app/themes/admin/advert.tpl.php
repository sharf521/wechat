<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>广告位管理</span>列表
        <a href="<?= url('advert/add/') ?>" class="layui-btn layui-btn-small">添 加</a>
    </blockquote>
    <form method="get">
        <div class="search">
            分站：<select name="site_id">
                <option value="">请选择</option>
                <? foreach ($this->siteList as $site) : ?>
                    <option value="<?=$site->id?>" <? if($_GET['site_id']==$site->id){echo 'selected';}?>><?=$site->name?></option>
                <? endforeach;?>
            </select>
            类型：<?=$typeid?>
            关键字：<input type="text" name="keyword" value="<?= $_GET['keyword'] ?>"/>
            <input type="submit" class="but2" value="查询"/>
        </div>
    </form>
    <div class="main_content">
        <form method="post">
            <table class="layui-table" lay-skin="line">
                <thead>
                <th>类型</th>
                <th>名称</th>
                <th>图片</th>
                <th>链接</th>
                <th>说明</th>
                <th>排序</th>
                <th>添加时间</th>
                <th>状态</th>
                <th>操作</th>
                </thead>
                <tbody>
                <? foreach ($list as $row) { ?>
                    <tr>
                        <td><?=(new \App\Model\LinkPage())->getLinkPageName('advert_type',$row->typeid); ?></td>
                        <td><?= $row->name ?></td>
                        <td><img src="<?=$row->picture?>" height="50"></td>
                        <td><?=$row->url?> </td>
                        <td><?=nl2br($row->content)?></td>
                        <td>  <input type="text" value="<?= $row->showorder ?>" name="showorder[]" size="5">
                            <input type="hidden" name="id[]" value="<?= $row->id ?>"></td>
                        <td><?= $row->created_at ?></td>
                        <td><? if ($row->status == '1') {
                                echo '显示';
                            } else {
                                echo '隐藏';
                            } ?></td>
                        <td>
                            <a class="layui-btn layui-btn-mini" href="<?= url("advert/change/?id={$row->id}&page={$_GET['page']}") ?>"><?= ($row->status == '1') ? '隐藏' : '显示' ?></a>
                            <a href="<?= url("advert/edit/?id={$row->id}&page={$_GET['page']}") ?>" class="layui-btn layui-btn-mini">修改</a>
                            <a href="javascript:goDel('<?=$row->id?>')" class="layui-btn layui-btn-mini">删除</a>
                        </td>
                    </tr>
                <? } ?>
                </tbody>

            </table>
            <div align="center" style="margin-top: 10px;"><input type="submit" value="更新排序" class="layui-btn"/></div>
        </form>
    </div>
<? elseif ($this->func == 'add' || $this->func == 'edit') : ?>
    <blockquote class="layui-elem-quote">
        <span>广告位管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?= url('advert') ?>" class="layui-btn layui-btn-small">返回列表</a>
    </blockquote>
    <div class="main_content">
        <form method="post" class="layui-form">
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">分站</label>
                    <div class="layui-input-inline">
                        <select name="site_id">
                            <option value="0"></option>
                            <? foreach ($this->siteList as $site) : ?>
                                <option value="<?=$site->id?>" <? if($row->site_id==$site->id){echo 'selected';}?>><?=$site->name?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required placeholder="请填写名称" class="layui-input" value="<?=$row->name?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-block">
                        <?=$typeid?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">图片</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="picture" id="article" value="<?= $row->picture ?>"/>
						<span id="upload_span_article">
							<? if ($row->picture != '') { ?>
                                <a href="<?= $row->picture ?>" target="_blank"><img
                                        src="<?= $row->picture ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <input type="file" name="file" class="layui-upload-file" upload_id="article" upload_type="advert">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="url" required placeholder="http://" class="layui-input" value="<?=$row->url?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">说明</label>
                    <div class="layui-input-block">
                        <textarea name="content" required class="layui-textarea"><?=$row->content?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="text" name="showorder" placeholder="10" class="layui-input" value="<?=$row->showorder?>" autocomplete="off"/>
                    </div>
                    <div class="layui-form-mid layui-word-aux">越小越靠前</div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
                    <input class="layui-btn" type="button" value="返回" onclick="window.history.go(-1)"/>
                </div>
            </div>
        </form>
    </div>
<? endif; ?>
    <script>
        $(function () {
            layui.form.on('submit(*)', function(data){
                var form=data.form;
                var fields=data.field;
                var picture=$(form).find('input[name=picture]');
                if(picture.val()==''){
                    layer.tips('不能为空！', $('.layui-box'));
                    picture.focus();
                    return false;
                }
            });
        });
        function goDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url('advert/delete/?id=')?>'+id;
                    layer.close(index);
                }
            });
        }

    </script>
<?php require 'footer.php'; ?>