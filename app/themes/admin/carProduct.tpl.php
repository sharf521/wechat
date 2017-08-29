<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <blockquote class="layui-elem-quote">
        <span>车辆管理</span>列表
        <a href="<?= url('carProduct/add/') ?>" class="layui-btn layui-btn-small">添 加</a>
    </blockquote>
    <form method="get" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <label class="layui-form-label">品牌</label>
                <div class="layui-input-inline">
                    <select name="brand_name" class="layui-select" lay-search>
                        <option value="0">请选择</option>
                        <? foreach ($brands as $brand) :?>
                            <option value="<?=$brand->name?>" <? if($brand->name==$_GET['brand_name']){echo 'selected';}?>><?=$brand->name?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <label class="layui-form-label">金融方案</label>
                <div class="layui-input-inline">
                    <select name="plan_id" class="layui-select">
                        <option value="0">请选择</option>
                        <? foreach ($plans as $plan) :?>
                            <option value="<?=$plan->id?>" <? if($plan->id==$_GET['plan_id']){echo 'selected';}?>><?=$plan->name?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <label class="layui-form-label">关键字</label>
                <div class="layui-input-inline"><input type="text" class="layui-input" name="keyword" value="<?= $_GET['keyword'] ?>"/></div>
                <input type="submit" class="layui-btn" value="搜索"/>
            </div>
        </div>
    </form>
    <div class="main_content">
        <form method="post">
            <table class="layui-table" lay-skin="line">
                <thead>
                <th>ID</th>
                <th>名称</th>
                <th>参考价</th>
                <th>图片</th>
                <th>品牌</th>
                <th>添加时间</th>
                <th>状态</th>
                <th>操作</th>
                </thead>
                <tbody>
                <? foreach ($result['list'] as $row) { ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td>￥<?=$row->price?></td>
                        <td><img src="<?=$row->picture?>" height="50"></td>
                        <td> <?= $row->brand_name ?></td>
                        <td><?= $row->created_at ?></td>
                        <td><? if ($row->status == '1') {
                                echo '显示';
                            } else {
                                echo '隐藏';
                            } ?></td>
                        <td>
                            <a class="layui-btn layui-btn-mini" href="<?= url("carProduct/change/?id={$row->id}&page={$_GET['page']}") ?>"><?= ($row->status == '1') ? '隐藏' : '显示' ?></a>
                            <a class="layui-btn layui-btn-mini" href="<?= url("carProduct/edit/?id={$row->id}&page={$_GET['page']}") ?>">修改</a>
                            <a class="layui-btn layui-btn-mini" href="javascript:goDel('<?=$row->id?>')">删除</a>
                        </td>
                    </tr>
                <? } ?>
                </tbody>

            </table>
            <? if (empty($result['total'])) {
                echo "无记录！";
            } else {
                ?>
                <?
                echo $result['page'];
            } ?>
        </form>
    </div>
<? elseif ($this->func == 'add' || $this->func == 'edit') : ?>
    <blockquote class="layui-elem-quote"><span>品牌管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?= url('carProduct') ?>" class="layui-btn layui-btn-small">返回列表</a></blockquote>
    <div class="main_content">
        <form method="post" class="layui-form">
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required placeholder="请填写名称" class="layui-input" value="<?=$row->name?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">品牌</label>
                    <div class="layui-input-inline">
                        <select name="brand_name" class="layui-select">
                            <option value="" selected>请选择</option>
                            <? foreach ($brands as $brand) :?>
                                <option value="<?=$brand->name?>" <? if($brand->name==$row->brand_name){echo 'selected';}?>><?=$brand->name?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">金融方案</label>
                    <div class="layui-input-inline">
                        <select name="plan_id" class="layui-select">
                            <option value="" selected>请选择</option>
                            <? foreach ($plans as $plan) :?>
                                <option value="<?=$plan->id?>" <? if($plan->id==$row->plan_id){echo 'selected';}?>><?=$plan->name?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">厂商指导价</label>
                    <div class="layui-input-inline">
                        <input type="text" required name="price" value="<?=$row->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="￥" class="layui-input" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">规格</label>
                    <div class="layui-input-inline" style="width: 800px;">
                        <table class="layui-table" lay-skin="row" lay-even="" id="MySpecTB">
                            <thead>
                            <tr>
                                <th>租期</th>
                                <th>保证金</th>
                                <th>月租</th>
                                <th>尾付</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($specs as $spec) : ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="spec_id[]" value="<?=$spec->id?>">
                                        <input name="time_limit[]" type="text" size="6" value="<?=$spec->time_limit?>"> 期</td>
                                    <td><input name="first_payment[]" type="text" size="6" class="layui-input" placeholder="保证金" value="<?=$spec->first_payment?>"></td>
                                    <td><input name="month_payment[]" type="text" size="6"  placeholder="月租" class="layui-input" value="<?=$spec->month_payment?>" ></td>
                                    <td><input name="last_payment[]" type="text" size="6"  placeholder="0" value="<?=$spec->last_payment?>" class="layui-input"></td>
                                    <td>
                                        <span class="delete_btn layui-btn layui-btn-mini">删除</span>
                                    </td>
                                </tr>
                            <? endforeach;?>
                            </tbody>
                        </table>
                        <input type="button" class="layui-btn layui-btn-mini add_btn" value=" + 添 加 一 个 规 格 ">
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
                        <button type="button" class="layui-btn upload_btn" upload_id="article" upload_type="carProduct">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">介绍</label>
                    <div class="layui-input-block">
                        <? ueditor(array('value' => $row->content)); ?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否推荐</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="is_recommend" value="1" lay-skin="switch" <? if($row->is_recommend==1){echo 'checked';}?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
                        <input class="layui-btn" type="button" value="返回" onclick="window.history.go(-1)"/>
                    </div>
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
                var brand_name=$(form).find('select[name=brand_name]');
                if(brand_name.val()==''){
                    layer.tips('不能为空！', $(brand_name).next('.layui-form-select'));
                    return false;
                }
                var plan_id=$(form).find('select[name=plan_id]');
                if(plan_id.val()==''){
                    layer.tips('不能为空！', $(plan_id).next('.layui-form-select'));
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
                    location.href='<?=url('carProduct/delete/?id=')?>'+id;
                    layer.close(index);
                }
            });
        }

        $('.delete_btn').click(function(){
            var l=$(this).parents('tbody').find('tr').length;//总行数
            if(l>1){
                $(this).parents('tr').remove();
            }else{
                layui.layer.tips('最少保留一行！', this);
            }
        });
        $('.add_btn').click(function(){
            var tem=$("#MySpecTB tr:last").clone(true);
            $(tem).find('input:first').val(0);
            $("#MySpecTB tr:last").after(tem);
        });

    </script>
<?php require 'footer.php'; ?>