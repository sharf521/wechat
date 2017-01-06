<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>申请开店</h1>
    </div>
    <form method="post" id="form1" class="margin_header">
        <?
        if($shop->is_exist){
            if($shop->status==0){
                echo '<div class="weui-cells__title">待审核</div>';
            }
        }else{
            echo '<div class="weui-cells__title">填写申请</div>';
        }
        ?>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">店铺名称</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="name" value="<?=$shop->name?>"  placeholder="请输入店铺名称"/>
                </div>
                <div class="weui-cell__ft">
                    <i class="weui-icon-warn"></i>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">联系人</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="contacts" value="<?=$shop->contacts?>"  placeholder="联系人姓名"/>
                </div>
                <div class="weui-cell__ft">
                    <i class="weui-icon-warn"></i>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">电话</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="tel" value="<?=$shop->tel?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入手机号"/>
                </div>
                <div class="weui-cell__ft">
                    <i class="weui-icon-warn"></i>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">QQ</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="qq" value="<?=$shop->qq?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入QQ号"/>
                </div>
                <div class="weui-cell__ft">
                    <i class="weui-icon-warn"></i>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" name="remark" placeholder="详细介绍" rows="3"><?=$shop->remark?></textarea>
                </div>
                <div class="weui-cell__ft">
                    <i class="weui-icon-warn"></i>
                </div>
            </div>
        </div>
        <div class="weui-cells__tips">以上必填，请认真填写！</div>
        <div class="weui-btn-area">
            <input class="weui-btn weui-btn_primary" type="submit" value="保存">
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $('#form1').validate({
                onkeyup: false,
                errorPlacement: function (error, element) {
                    //element.nextAll('b').first().after(error);
                },
                submitHandler: function (form) {
                    ajaxpost('form1', '', '', 'onerror');
                },
                rules: {
                    name: {
                        required: true
                    },
                    contacts: {
                        required: true
                    },
                    tel: {
                        required: true
                    },
                    qq: {
                        required: true
                    },
                    remark: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: '请填写'
                    }
                }
            });
        });
    </script>
<?php require 'footer.php';?>