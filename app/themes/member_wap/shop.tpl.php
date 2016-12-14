<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>申请开店</h1>
    </div>
<br><br>
    <form method="post">
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">店铺名称</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="name"  placeholder="请输入店铺名称"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">联系人</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="contacts" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="联系人姓名"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">电话</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="tel" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入手机号"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">QQ</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="qq" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入QQ号"/>
                </div>
            </div>
        </div>
        <div class="weui-cells__title">店铺介绍</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" name="content" id="content" placeholder="请输入详细介绍" rows="3"><?=$GoodsData->content?></textarea>
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
<?php require 'footer.php';?>