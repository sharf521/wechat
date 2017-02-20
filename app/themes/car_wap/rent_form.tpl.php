<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('rent')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1><?=$this->title?></h1>
    </div>
    <div class="margin_header"></div>
    <div class="car-wrapper clearFix">
        <div class="car-photo"><img src="<?=$product->picture?>">
        </div>
        <div class="car-info">
            <div class="car-name"><?=$product->name?></div>
            <div class="car-price">厂商指导价：<?=$product->price/10000?>万</div>
        </div>
    </div>
    <div class="lease">
        <div class="product">
            <label class="active">
                <span><strong><?=$rent->time_limit?>期</strong></span>
                <span>首付: <?=$rent->first_payment_money/10000?>万元</span>
                <span><?=(float)$rent->month_payment_money?>元/期</span>
                <span><?
                    if((float)$rent->last_payment_money!=0){
                        echo "尾付：". $rent->first_payment_money/10000 .'万';
                    }
                    ?></span>
            </label>
        </div>
    </div>
    <div class="clearFix" style="margin-top: 8px;">
        <div class="my-navbar">
            <div class="my-navbar__item <? if($this->func=='editContacts'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/editContacts/?id={$_GET['id']}")?>">申请人</a>
            </div>
            <div class="my-navbar__item <? if($this->func=='editUpload'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/editUpload/?id={$_GET['id']}")?>">上传资料</a>
            </div>
            <div class="my-navbar__item <? if($this->func=='editPay'){echo 'my-navbar__item_on';}?>">
                <a href="<?=url("rent/editPay/?id={$_GET['id']}")?>">支付定金</a>
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
        <? elseif($this->func=='editPay') : ?>

            <?
            if($rent->booked_money!=0) :
                    echo "<div style='margin-top: 50px; font-size: 20px; text-align: center'>己交定金：{$rent->booked_money}元</div>";
            else : ?>

            <form method="post" id="form1">
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">使用积分</label></div>
                        <div class="weui-cell__bd">
                            <input type="text" id="integral" name="integral" value="0" placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="weui-input" autocomplete="off"/>
                        </div>
                        可用:<span id="span_integral"><?=$account->integral_available?></span>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">支付密码</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" required type="password" name="zf_password" placeholder="请填写" />
                        </div>
                        可用:￥<span id="span_funds"><?=$account->funds_available?></span>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">实际支付</label></div>
                        <div class="weui-cell__bd">
                            <span id="money_yes"><?=$booked_money?></span> 元
                        </div>
                    </div>
                </div>
                <div class="weui-btn-area">
                    <input class="weui-btn weui-btn_primary" type="submit" value="立即支付">
                    <a class="recharge weui-btn weui-btn_plain-primary">我要充值</a>
                </div>
            </form>
            <? endif;?>

            <script src="/plugin/js/math.js"></script>
            <script>
                $(function () {
                    var lv='<?=$convert_rate?>';
                    var price_true = '<?=$booked_money?>';
                    $("#integral").bind('input propertychange',function(){
                        if(Number($(this).val())>Number($('#span_integral').html())){
                            $(this).val($('#span_integral').html());
                        }
                        var max_jf=Math.mul(price_true,lv);
                        if(Number($(this).val())>max_jf){
                            $("#integral").val(max_jf);
                        }
                        var _m=Math.div(Number($("#integral").val()),lv);
                        var money=Math.sub(price_true,Math.moneyRound(_m,2));
                        $('#money_yes').html(money);
                    });

                    $('.recharge').on('click',function () {
                        var money=$('#money_yes').html();
                        window.location='/user/recharge/?money='+money+'&url='+window.location.href;
                    });
                });
            </script>
        <? endif;?>
    </div>
<?php require 'footer.php';?>