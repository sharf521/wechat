$(function () {
    $.get("/cart/getGoodsCount/",{},function(data) {
        $('#cart_num').html(data);
        if(data>0){
            $('#cart_num').show();
        }
    });
});
/* 确认订单*/
function getSelectedMoney() {
    $.get("/index.php/cart/getSelectedMoney?cart_ids="+cart_ids+'&cityName='+cityName, function (data) {
        if (data != "") {
            var data = eval('(' + data + ")");
            $("#totalPrice span").html(data.countTotal);
            $("#totalNum span").html(data.countNum);
            $('.shop_total').each(function(){
                var shop_id=$(this).attr('shop_id');
                if(data['shop'+shop_id+'_total']){
                    $('#shop'+shop_id+'_money').html(data['shop'+shop_id+'_total']);
                }else{
                    $('#shop'+shop_id+'_total').html(0);
                }
                if(data['shop'+shop_id+'_shippingFee']){
                    $('#shop'+shop_id+'_shipping_fee').html(data['shop'+shop_id+'_shippingFee']);
                }else{
                    $('#shop'+shop_id+'_shipping_fee').html(0);
                }
            });
        } else {
            $("#totalPrice span").html(0);
            $("#totalNum span").html(0);
        }
    });
}
function order_js() {
    getSelectedMoney();
    $('.order_bottom .btn').on('click',function () {
        if($('#address_id').val()==''){
            layer.open({
                content: '收货地址不能为空！',
                skin: 'msg',
                time:5
            });
        }else{
            $('#form_order').submit();
        }
    });
}
/* 购物车 start*/
var cart_id = "";
function getCartedMoney() {
    cart_id = "";
    var allChecked = true;
    $("input:checkbox[name='cart_id[]']").each(function (i) {
        if($(this).attr('disabled')!='disabled'){
            if ($(this).attr('checked')) {
                if (cart_id == "") {
                    cart_id = $(this).val();
                } else {
                    cart_id += ("," + $(this).val());
                }
            } else {
                if (allChecked == true) {
                    allChecked = false;
                }
            }
        }
    });
    $(".checkall").attr("checked", allChecked);
    if (cart_id == '') {
        $("#totalPrice span").html(0);
        $("#totalNum span").html(0);
    } else {
        $.get("/index.php/cart/getSelectedMoney?cart_ids=" + cart_id, function (data) {
            cart_ajaxSetMoney(data);
        });
    }
}
function cart_ajaxSetMoney(data) {
    if (data != "") {
        var data = eval('(' + data + ")");
        $("#totalPrice span").html(data.countTotal);
        $("#totalNum span").html(data.countNum);
        $('.cart_foot .shop_total').each(function () {
            var shop_id = $(this).attr('shop_id');
            if (data['shop' + shop_id + '_goodsPrice']) {
                $(this).html(data['shop' + shop_id + '_goodsPrice']);
            } else {
                $(this).html(0);
            }
        });
    } else {
        $("#totalPrice span").html(0);
        $("#totalNum span").html(0);
    }
}
function cart_js() {
    getCartedMoney();
    $("input[name='cart_id[]']").on('click',function () {
        getCartedMoney();
    });
    $('.checkall').on('click',function () {
        $("input[name='cart_id[]']").not("input[disabled='disabled']").attr('checked',this.checked);
        getCartedMoney();
    });
    $('.wrap-input .btn-reduce').on('click',function(){
        var input=$(this).parent().find('input');
        var num=Number(input.val());
        if(num>1){
            num--;
            var chkbox=$(this).parents('.goods_item').find('input:checkbox');
            $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val()+"&cart_ids="+cart_id, function (json) {
                var data=eval("("+json+")");
                if(data.code=='0'){
                    input.val(num);
                }else{
                    input.val(data.stock_count);
                    layer.open({
                        content: data.msg,
                        skin: 'msg',
                        time:1
                    });
                }
                cart_ajaxSetMoney(json);
            });
        }
    });
    $('.wrap-input .btn-add').on('click', function () {
        var input=$(this).parent().find('input');
        var num=Number(input.val())+1;
        var chkbox=$(this).parents('.goods_item').find('input:checkbox');
        $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val()+"&cart_ids="+cart_id, function (json) {
            var data=eval("("+json+")");
            if(data.code=='0'){
                input.val(num);
            }else{
                input.val(data.stock_count);
                layer.open({
                    content: data.msg,
                    skin: 'msg',
                    time:1
                });
            }
            cart_ajaxSetMoney(json);
        });
    });
    $(".goods_item .del").on('click',function () {
        var id=Number($(this).attr('data-id'));
        layer.open({
            content: '您确定要删除吗？'
            ,btn: ['删除', '取消']
            ,yes: function(index){
                location.href='/index.php/cart/del/?id='+id;
                layer.close(index);
            }
        });
    });
    $('.cart_bottom .btn_pay').on('click',function () {
        var cart_id='';
        $("input:checkbox[name='cart_id[]']").each(function (i) {
            if ($(this).attr('checked')) {
                if (cart_id == "") {
                    cart_id ="?cart_id[]="+$(this).val();
                } else {
                    cart_id += "&cart_id[]="+$(this).val();
                }
            }
        });
        if(cart_id==''){
            layer.open({
                content: '请选择商品',
                skin: 'msg',
                time:1
            });
        }else{
            window.location='/order/confirm/'+cart_id;
        }
    });
}
/* 购物车 end*/

function showBuyBox() {
    $('.weui-mask').show();
    $('#bottom_buy_box').slideDown(150);
}

function hideBuyBox(){
    $('.weui-mask').hide();
    $('#bottom_buy_box').slideUp(150);
}
function goods_detail_js()
{
    $(function(){
        var mySwiper = new Swiper('.swiper-container',{
            loop : true,
            autoplay:4800,
            autoplayDisableOnInteraction : false,
            pagination : '.swiper-pagination',
            paginationClickable :true,
        });

        $('.bottom_opts .opt_add').on('click',function () {
            showBuyBox();
        });
        $('.bottom_opts .opt_buy').on('click',function () {
            showBuyBox();
        });

        $('.weui-mask').on('click',function(){
            hideBuyBox();
        });

        $('#bottom_buy_box dt').find('i').on('click',function(){
            hideBuyBox();
        });

        $('.wrap-input .btn-reduce').on('click',function(){
            var input=$(this).parent().find('input');
            var num=Number(input.val());
            if(num>1){
                input.val(num-1);
            }
        });
        $('.wrap-input .btn-add').on('click', function () {
            var input=$(this).parent().find('input');
            var num=Number(input.val());
            var max=Number($('#goods_stock_count').html());
            if(num < max){
                input.val(num+1);
            }
        });
        $("#buy_quantity").bind('input propertychange',function(){
            var num=Number($(this).val());
            if(Number.isInteger(num)){
                var max=Number($('#goods_stock_count').html());
                if(num > max){
                    $(this).val(max);
                }
            }else{
                $(this).val(1);
            }
        });
        $('#specBox_1 span:first').click();
    });
    //加入购物车
    $('#bottom_buy_box .opt1').on('click',function () {
        var quantity=Number(document.forms['form_order'].quantity.value);
        var spec_id=$('#spec_id').val();
        $.post("/index.php/cart/add/",{goods_id:goods_id,spec_id:spec_id,quantity:quantity},function(data){
            var json=eval("("+data+")");
            if(json.code=='0'){
                $("#cart_num").html(Number($("#cart_num").html())+quantity);
                $("#cart_num").show();
                var msg='添加成功！';
            }else{
                var msg=json.msg;
            }
            layer.open({
                content: msg,
                skin: 'msg',
                time:1
            });
            hideBuyBox();
        });
    });
    //立刻购买
    $('#bottom_buy_box .opt2').on('click',function(){
        var form=document.forms['form_order'];
        var quantity=form.quantity;
        var tag=true;
        if(Number(quantity.value)==0){
            $(quantity).focus();
            layer.open({
                content: '请正确选择数量',
                skin: 'msg',
                time:1
            });
            tag=false;
        }
        if(Number($('#goods_stock_count').html()) < Number(quantity.value)){
            layer.open({
                content: '库存不足',
                skin: 'msg',
                time:1
            });
            tag=false;
        }
        if(tag){
            form.submit();
        }
    })
}
function spec(id, spec1, spec2, price, stock) {
    this.id = id;
    this.spec1 = spec1;
    this.spec2 = spec2;
    this.price = price;
    this.stock = stock;
}
function GoodsSpec(specs)
{
    this.spec1_name='';
    this.spec2_name='';
    this.specQty=1;
    this.spec1=new Array();
    for (var x in specs) {
        var spec = specs[x];
        if($.inArray(spec.spec1,this.spec1)==-1){
            this.spec1.push(spec.spec1);
        }
        if(this.specQty==1 && spec.spec2!=''){
            this.specQty=2;
        }
    }
    this.initSpec2=function(){
        if(this.specQty==2){
            $("#specBox_2").html('');
            this.spec2_name='';
            for (var x in specs){
                if(specs[x].spec1==this.spec1_name){
                    $("#specBox_2").append("<span onclick='selectSpec(2,this)'>" + specs[x].spec2 + "</span>");
                }
            }
        }
    };
    for (var x in this.spec1){
        $("#specBox_1").append("<span onclick='selectSpec(1,this)'>" + this.spec1[x] + "</span>");
    };
    this.getSpec=function(){
        for (var x in specs){
            var spec=specs[x];
            if(this.specQty==1){
                if(spec.spec1==this.spec1_name){
                    return spec;
                    break;
                }
            }else{
                if(spec.spec1==this.spec1_name && spec.spec2==this.spec2_name){
                    return spec;
                    break;
                }
            }
        }
        return null;
    };
    this.setFormValue=function(){
        var spec=this.getSpec();
        if(spec!=null){
            $('#spec_id').val(spec.id);
            $('#goods_price').html(spec.price);
            $('#goods_stock_count').html(spec.stock);
        }
    };
}
function selectSpec(type,obj){
    var obj=$(obj);
    obj.addClass('active').siblings().removeClass('active');
    if(type==1){
        goodsSpec.spec1_name=obj.html();
        goodsSpec.initSpec2();

        $('#specBox_2 span:first').click();
    }else{
        goodsSpec.spec2_name=obj.html();
    }
    var goods_prompt='已选择 ';
    goods_prompt+='<strong>'+goodsSpec.spec1_name+'</strong>';
    if(goodsSpec.specQty==2){
        goods_prompt+=' , <strong>'+goodsSpec.spec2_name+'</strong>';
    }
    //$('.goods_prompt').html(goods_prompt);
    goodsSpec.setFormValue();
    $('#buy_quantity').val(1);
}