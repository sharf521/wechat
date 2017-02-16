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
}