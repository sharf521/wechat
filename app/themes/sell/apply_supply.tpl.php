<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend><?=$this->title?></legend>
            </fieldset>
            <?php
            if($supply->is_exist){
                if($supply->status==0){
                    echo '<blockquote class="layui-elem-quote">待审核</blockquote>';
                }elseif($supply->status==2){
                    echo '<blockquote class="layui-elem-quote">未通过<br>原因：'.nl2br($supply->verify_remark).'</blockquote>';
                }
            }
            ?>
            <form method="post" class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">企业名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="company_name" value="<?=$supply->company_name?>" placeholder="请填写企业名称" class="layui-input" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">企业法人</label>
                    <div class="layui-input-block">
                        <input type="text" name="company_owner" value="<?=$supply->company_owner?>" placeholder="企业法人" class="layui-input" value="" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">上传资质</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="picture1" id="picture1" value="<?= $supply->picture1 ?>"/>
						<span id="upload_span_picture1">
							<? if ($supply->picture1 != '') { ?>
                                <a href="<?= $supply->picture1 ?>" target="_blank"><img
                                        src="<?= $supply->picture1 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <button type="button" class="layui-btn upload_btn" upload_id="picture1" upload_type="company_picture">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">上传资质</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="picture2" id="picture2" value="<?= $supply->picture2 ?>"/>
						<span id="upload_span_picture2">
							<? if ($supply->picture2 != '') { ?>
                                <a href="<?= $supply->picture2 ?>" target="_blank"><img
                                        src="<?= $supply->picture2 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <button type="button" class="layui-btn upload_btn" upload_id="picture2" upload_type="company_picture">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">上传资质</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="picture3" id="picture3" value="<?= $supply->picture3 ?>"/>
						<span id="upload_span_picture3">
							<? if ($supply->picture3 != '') { ?>
                                <a href="<?= $supply->picture3 ?>" target="_blank"><img
                                        src="<?= $supply->picture3 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <button type="button" class="layui-btn upload_btn" upload_id="picture3" upload_type="company_picture">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">供货说明</label>
                    <div class="layui-input-block">
                        <? ueditor(array('value' => $supply->remark)); ?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        supply_apply();
    });
</script>
<?php require 'footer.php';?>