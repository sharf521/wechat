<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('rent')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1><?=$this->title?></h1>
    </div>

    <div class="clearFix margin_header">
        <div class="my-navbar margin_header">
            <div class="my-navbar__item <? if($this->func=='editContacts'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/editContacts/?id={$_GET['id']}")?>">申请人</a>
            </div>
            <div class="my-navbar__item <? if($this->func=='editUpload'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/editUpload/?id={$_GET['id']}")?>">上传资料</a>
            </div>
            <div class="my-navbar__item <? if($this->func=='pay'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/pay/?id={$_GET['id']}")?>">支付费用</a>
            </div>
        </div>
        <? if($this->func=='editContacts') : ?>
            <form method="post" id="form1">
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">申请人</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" required type="text" name="contacts" placeholder="申请人姓名" value="<?=$rent->contacts?>"/>
                        </div>
                        <div class="weui-cell__ft">
                            <i class="weui-icon-warn"></i>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">电话</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" required type="text" name="tel" placeholder="联系电话" value="<?=$rent->tel?>"/>
                        </div>
                        <div class="weui-cell__ft">
                            <i class="weui-icon-warn"></i>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">地址</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" required type="text" name="address" placeholder="联系地址" value="<?=$rent->address?>"/>
                        </div>
                        <div class="weui-cell__ft">
                            <i class="weui-icon-warn"></i>
                        </div>
                    </div>
                </div>
                <div class="weui-btn-area">
                    <? if($rent->status==0) : ?>
                        <input class="weui-btn weui-btn_primary" type="submit" value="保存">
                    <? else : ?>
                        <a href="javascript:;" class="weui-btn weui-btn_plain-primary weui-btn_plain-disabled">保存</a>
                    <? endif;?>
                </div>
            </form>
        <? elseif($this->func=='editUpload') : ?>
            <script src="/plugin/js/ajaxfileupload.js?111"></script>
            <form method="post" id="form1">
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">身份证</label></div>
                        <div class="weui-cell__bd">
                            <div class="weui-uploader">
                                <ul class="weui-uploader__files">
                                    <?  foreach ($rentImages as $img) :
                                        if($img->typeid=='card') :
                                        ?>
                                        <li class="weui-uploader__file goods_add_uploaderLi" style="background-image:url(<?=$img->image_url?>)"><i class='iconfont' onclick=delRentImg(this)>&#xe642;</i>
                                            <input type="hidden" name="img_id[]" value="<?=$img->id?>">
                                        </li>
                                    <?
                                        endif;
                                    endforeach;?>
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input name="file" id="uploaderInput_card" upload_type="card" class="weui-uploader__input" type="file" accept="image/*" onchange="uploadImgs(this)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">驾驶证</label></div>
                        <div class="weui-cell__bd">
                            <div class="weui-uploader">
                                <ul class="weui-uploader__files">
                                    <?  foreach ($rentImages as $img) :
                                        if($img->typeid=='drive') :
                                            ?>
                                            <li class="weui-uploader__file goods_add_uploaderLi" style="background-image:url(<?=$img->image_url?>)"><i class='iconfont' onclick=delRentImg(this)>&#xe642;</i>
                                                <input type="hidden" name="img_id[]" value="<?=$img->id?>">
                                            </li>
                                            <?
                                        endif;
                                    endforeach;?>
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input name="file" id="uploaderInput_drive" upload_type="drive" class="weui-uploader__input" type="file" accept="image/*" onchange="uploadImgs(this)"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">信用报告</label></div>
                        <div class="weui-cell__bd">
                            <div class="weui-uploader">
                                <ul class="weui-uploader__files">
                                    <?  foreach ($rentImages as $img) :
                                        if($img->typeid=='credit') :
                                            ?>
                                            <li class="weui-uploader__file goods_add_uploaderLi" style="background-image:url(<?=$img->image_url?>)"><i class='iconfont' onclick=delRentImg(this)>&#xe642;</i>
                                                <input type="hidden" name="img_id[]" value="<?=$img->id?>">
                                            </li>
                                            <?
                                        endif;
                                    endforeach;?>
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input name="file" id="uploaderInput_credit" upload_type="credit" class="weui-uploader__input" type="file" accept="image/*" onchange="uploadImgs(this)"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">其它</label></div>
                        <div class="weui-cell__bd">
                            <div class="weui-uploader">
                                <ul class="weui-uploader__files">
                                    <?  foreach ($rentImages as $img) :
                                        if($img->typeid=='other') :
                                            ?>
                                            <li class="weui-uploader__file goods_add_uploaderLi" style="background-image:url(<?=$img->image_url?>)"><i class='iconfont' onclick=delRentImg(this)>&#xe642;</i>
                                                <input type="hidden" name="img_id[]" value="<?=$img->id?>">
                                            </li>
                                            <?
                                        endif;
                                    endforeach;?>
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input name="file" id="uploaderInput_other" upload_type="other" class="weui-uploader__input" type="file" accept="image/*" onchange="uploadImgs(this)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="weui-btn-area">
                    <? if($rent->status==0) : ?>
                        <input class="weui-btn weui-btn_primary" type="submit" value="保存">
                    <? else : ?>
                        <a href="javascript:;" class="weui-btn weui-btn_plain-primary weui-btn_plain-disabled">保存</a>
                    <? endif;?>
                </div>
            </form>
        <? endif;?>
    </div>
<?php require 'footer.php';?>