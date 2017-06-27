<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend><?=$this->title?></legend>
            </fieldset>
            <form method="post" class="layui-form">
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">PC端通栏</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="pc_banner" id="pc_banner" value="<?= $row->pc_banner ?>"/>
                            <span id="upload_span_pc_banner" class="<? if ($row->pc_banner == '') {echo 'hide';} ?>">
                                    <a href="<?= $row->pc_banner ?>" target="_blank"><img
                                            src="<?= $row->pc_banner ?>" align="absmiddle" width="400"/></a>
                            </span>
                            <input type="file" name="file" class="layui-upload-file" upload_id="pc_banner" upload_type="advert">
                        </div>
                        <div class="layui-form-mid layui-word-aux">1000*150</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">链接</label>
                        <div class="layui-input-block">
                            <input type="text" name="pc_banner_link"  placeholder="http://" class="layui-input" value="<?=$row->pc_banner_link?>" autocomplete="off"/>
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">WAP端轮播1</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="wap_banner1" id="wap_banner1" value="<?= $row->wap_banner1 ?>"/>
                            <span id="upload_span_wap_banner1" class="<? if ($row->wap_banner1 == '') {echo 'hide';} ?>">
                                    <a href="<?= $row->wap_banner1 ?>" target="_blank"><img src="<?= $row->wap_banner1 ?>" align="absmiddle" width="200"/></a>
                            </span>
                            <input type="file" name="file" class="layui-upload-file" upload_id="wap_banner1" upload_type="advert">
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">链接</label>
                        <div class="layui-input-block">
                            <input type="text" name="wap_banner_link1"  placeholder="http://" class="layui-input" value="<?=$row->wap_banner_link1?>" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">WAP端轮播2</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="wap_banner2" id="wap_banner2" value="<?= $row->wap_banner2 ?>"/>
                            <span id="upload_span_wap_banner2" class="<? if ($row->wap_banner2 == '') {echo 'hide';} ?>">
                                    <a href="<?= $row->wap_banner2 ?>" target="_blank"><img src="<?= $row->wap_banner2 ?>" align="absmiddle" width="200"/></a>
                            </span>
                            <input type="file" name="file" class="layui-upload-file" upload_id="wap_banner2" upload_type="advert">
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">链接</label>
                        <div class="layui-input-block">
                            <input type="text" name="wap_banner_link2"  placeholder="http://" class="layui-input" value="<?=$row->wap_banner_link2?>" autocomplete="off"/>
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">WAP端轮播3</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="wap_banner3" id="wap_banner3" value="<?= $row->wap_banner3 ?>"/>
                            <span id="upload_span_wap_banner3" class="<? if ($row->wap_banner3 == '') {echo 'hide';} ?>">
                                    <a href="<?= $row->wap_banner3 ?>" target="_blank"><img src="<?= $row->wap_banner3 ?>" align="absmiddle" width="200"/></a>
                            </span>
                            <input type="file" name="file" class="layui-upload-file" upload_id="wap_banner3" upload_type="advert">
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">链接</label>
                        <div class="layui-input-block">
                            <input type="text" name="wap_banner_link3"  placeholder="http://" class="layui-input" value="<?=$row->wap_banner_link3?>" autocomplete="off"/>
                        </div>
                    </div>





                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer.php';?>