$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,form=layui.form
        ,element=layui.element;
    util.fixbar();
    form.render();
    layui.laydate.render({
        elem: '#starttime'
    });
    layui.laydate.render({
        elem: '#endtime'
    });
    if ($('.upload_btn').length > 0) {
        var index;
        $('.upload_btn').each(function (index, obj) {
            var id = obj.getAttribute('upload_id');
            var type = obj.getAttribute('upload_type');
            layui.upload.render({
                url: '/index.php/upload/save?type=' + type
                , elem: obj
                , before: function (input) {
                    index = layer.load();
                }
                , done: function (res) {
                    layer.close(index);
                    if (res.code == '0') {
                        var path = res.url;
                        $('#' + id).val(path);
                        var _str = "<a href='" + path + "' target='_blank'><img src='" + path + '?' + Math.random() + "' height='50'/></a>";
                        $('#upload_span_' + id).html(_str);
                    } else {
                        alert(res.msg);
                    }
                }, error: function (index, upload) {
                    layer.close(index);
                }
            });
        });
    }
});

function address_js() {
    $(function () {
        pca.init('select[name=province]', 'select[name=city]', 'select[name=county]', '', '', '');
        layui.form.render();
        layui.form.on('submit(*)', function(data){
            var form=data.form;
            var fields=data.field;
            var name=$(form).find('input[name=name]');
            if(name.val()==''){
                layer.tips('不能为空！', name);
                name.focus();
                return false;
            }
            var phone=$(form).find('input[name=phone]');
            if(phone.val()==''){
                layer.tips('不能为空！', phone);
                phone.focus();
                return false;
            }
            var province=$(form).find('select[name=province]');
            if(province.val()=='请选择'){
                layer.tips('不能为空！',$(province).parents('.layui-input-inline'));
                province.focus();
                return false;
            }
            var city=$(form).find('[name=city]');
            if(city.val()=='请选择'){
                layer.tips('不能为空！',$(city).parents('.layui-input-inline'));
                city.focus();
                return false;
            }
            var county=$(form).find('[name=county]');
            if(county.val()=='请选择'){
                layer.tips('不能为空！',$(county).parents('.layui-input-inline'));
                county.focus();
                return false;
            }
        });
    });
}

//上传图片
function upload_image(id,type)
{
    $('#upload_span_'+id).html('上传中...');
    $.ajaxFileUpload({
        //url:'/index.php/plugin/ajaxFileUpload?type='+type,
        url:'/index.php/upload/save?type='+type,
        fileElementId :'upload_'+id,
        dataType:'json',
        success: function (result,status){
            if(result.code == '0'){
                var path=result.url+'?'+Math.random();
                $('#'+id).val(path);
                var _str="<a href='"+path+"' target='_blank'><img src='"+path+"' height='50'/></a>";
                $('#upload_span_'+id).html(_str);
            }else{
                alert(result.msg);
            }
        },
        error: function (result, status, e){
            alert(e);
        }
    });
    return false;
}

function orderPayJs() {
    $(function () {
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
        /*$('.recharge').on('click',function () {
            var center_url=$(this).attr('center_url');
            var money=$('#money_yes').html();
            goCenterRecharge(center_url,money);
        });   */
    });
}

/*function goCenterRecharge(center_url,money,return_url) {
    if(return_url==undefined || return_url==''){
        return_url=window.location.href;
    }
    return_url=encodeURIComponent(return_url);
    window.location=center_url+'/member/account/recharge/?money='+money+'&url='+return_url;
}*/


