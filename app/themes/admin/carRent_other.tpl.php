<?php
require 'header.php';
if ($this->func=='checked') :
    ?>
    <div class="main_title">
        <span>租车</span> <a href="<?= url('carRent') ?>" class="but1">列表</a>
    </div>
    <blockquote class="layui-elem-quote">
        车款：<?=$carRent->car_name?><br>
        申请人：<?=$carRent->contacts?><br>
        联系电话：<?=$carRent->tel?><br>
        地址：<?=$carRent->area?>-<?=$carRent->address?><br>
    </blockquote>
    <form method="post" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <label class="layui-form-label">材料</label>
                <div class="layui-input-block">
                    <?
                    foreach ($carRentImgs as $img) :
                        ?>
                        <a href="<?=$img->image_url?>" target="_blank"><img src="<?=$img->image_url?>" height="100"></a>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">信审</label>
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
    <?
elseif($this->func=='deductMoney') : 
?>
    <div class="main_title">
        <span>租车</span> <a href="<?= url('carRent') ?>" class="but1">列表</a>
    </div>
    <blockquote class="layui-elem-quote">
        车款：<?=$carRent->car_name?><br>
        申请人：<?=$carRent->contacts?><br>
        联系电话：<?=$carRent->tel?><br>
        地址：<?=$carRent->area?>-<?=$carRent->address?><br>
    </blockquote>

    <blockquote class="layui-elem-quote">
        添加人ID：<?=$user->id?><br>
        添加人：<?=$user->username?><br>
        可用余额：￥<?=$account->funds_available?><br>
        可用积分：<?=$account->integral_available?><br>
    </blockquote>
    <br><br>
    <form method="post" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <label class="layui-form-label">扣除积分</label>
                <div class="layui-input-inline">
                    <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="layui-input" autocomplete="off"/>
                </div>
                <div class="layui-form-mid layui-word-aux">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">扣除余额</label>
                <div class="layui-input-inline">
                    <input type="text" id="funds" name="funds" value="0" placeholder="￥" onkeyup="value=value.replace(/[^0-9.]/g,'')" class="layui-input" autocomplete="off"/>
                </div>
                <div class="layui-form-mid layui-word-aux">可用金额：￥<span id="span_funds"><?=$account->funds_available?></span></div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">扣除价值：￥<span id="money_yes">0</span><br>
                <button class="layui-btn" lay-submit="" lay-filter="*">立即扣除</button>
                <button class="layui-btn" onclick="history.go(-1)">返回</button>
            </div>
        </div>
    </form>
    <script src="/plugin/js/math.js"></script>
    <script>
        var lv='<?=$convert_rate?>';
        $(function () {
            function change_input() {
                var integral=Number($("#integral").val());
                if(integral>Number($('#span_integral').html())){
                    integral=Number($('#span_integral').html());
                    $("#integral").val(integral);
                }
                var _m=Math.div(integral,lv);
                var funds=Number($("#funds").val());
                if(funds>Number($('#span_funds').html())){
                    funds=Number($('#span_funds').html());
                    $("#funds").val(funds);
                }
                var money=Math.add(funds,Math.moneyRound(_m,2));
                $('#money_yes').html(money);
            }
            $("#integral").bind('input propertychange',function(){
                change_input();
            });
            $("#funds").bind('input propertychange',function(){
                change_input();
            });
        });
    </script>
<?
endif;
require 'footer.php';?>