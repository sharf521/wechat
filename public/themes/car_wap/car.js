//detail
function product_detail() {
    $(function () {
        $('.product label').on('click',function () {
            $(this).addClass('active').siblings().removeClass('active');
            $('#spec_id').val($(this).attr('data_id'));
        });

        $('.product label:first').click();
        $('.bottom_opts .opt_buy').on('click',function(){
            var spec_id=$('#spec_id').val();
            var product_id=$('#product_id').val();
            window.location='/car/order/confirm/?id='+product_id+'&spec_id='+spec_id;
        });

    });
}
function order_confirm() {
    $("#order_confirm_form").on('submit',function(){
        if(form_validate(this,'contacts')==false){
            return false;
        }
        if(form_validate(this,'tel')==false){
            return false;
        }
        if(form_validate(this,'address')==false){
            return false;
        }
        return true;
    });
    function form_validate(form,oName){
        var o=$(form).find("input[name="+oName+"]");
        if(oName=='contacts' || oName=='tel' || oName=='address'){
            if(o.val()==''){
                o.parents('.weui-cell').addClass('weui-cell_warn');
                o.focus();
                return false;
            }else{
                o.parents('.weui-cell').removeClass('weui-cell_warn');
            }
        }
    }
    $('.recharge').on('click',function () {
        goWeChatPay(5000);
    });
}

function goWeChatPay(money,url) {
    if(url==undefined || url==''){
        url=window.location.href;
    }
    url=encodeURIComponent(url);
    window.location='http://wx02560f146a566747.wechat.yuantuwang.com/user/goWeChatPay/?money='+money+'&url='+url;
}

/* upload*/
function uploadImgs(o) {
    var uBox=$(o).parents('.weui-cell__bd').find('.weui-uploader__files');
    var upload_type=$(o).attr('upload_type');
    var lay=layer.open({
        type: 2
        ,content: '上传中'
    });
    $.ajaxFileUpload({
        url:'/index.php/upload/save?type=carRent',
        fileElementId :'uploaderInput_'+upload_type,
        dataType:'json',
        success: function (res,status){
            layer.close(lay);
            if(res.code == '0'){
                var imgId=res.id;
                var path=res.url;
                var _str='<li class="weui-uploader__file goods_add_uploaderLi" style="background-image:url('+path+')">' +
                    "<i class='iconfont' onclick=delRentImg(this)>&#xe642;</i>" +
                    '<input type="hidden" name="'+upload_type+'_img[]" value="'+path+'">'+
                    //'<input type="hidden" name="'+upload_type+'_img_id[]" value="'+imgId+'">'+
                    "</li>";
                uBox.append(_str);
            }else{
                alert(res.msg);
            }
        },
        error: function (result, status, e){
            layer.close(lay);
            alert(e);
        }
    });
    $('.weui-uploader__input-box').css('border','1px solid #d9d9d9');
}
function delRentImg(o) {
    $(o).parents('li').remove();
}