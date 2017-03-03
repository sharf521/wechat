$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,laydate = layui.laydate;
    util.fixbar();
    /*util.fixbar({
        bar1: '<i class="iconfont" style="font-size: 24px;">&#xe698;</i>'
        ,bar2: false
        ,css: {right: 100, bottom: 100}
        ,bgcolor: '#c00'
        ,click: function(type){
            if(type === 'bar1'){
                window.location='/cart/';
            } else if(type === 'bar2') {
                layer.msg('两个bar都可以设定是否开启')
            }
        }
    });*/
    var element = layui.element();
    element.init();
});
/* header */
function header_js() {
    $(".quick-menu dl").hover(function() {
            $(this).addClass("hover");
        },
        function() {
            $(this).removeClass("hover");
        }
    );
    function nav(childUl){
        var box = $(childUl);
        var oLi = $("li",childUl);
        oLi.mouseenter(function(){
            var index = oLi.index(this);
            $(oLi[index]).addClass("hover2").siblings().removeClass("hover2");
        });
        box.mouseleave(function(){
            oLi.removeClass("hover2");
        })
    }
    nav(".nav-list");
    //下拉选择分类
    function headerShowCategorys()
    {
        $('.category-title').hover(function(){
            $('.category-list').show();
        },function(){
            $('.category-list').hide();
        });
        $('.category-list > .item').hover(function(){
            var eq = $('.category-list > .item').index(this),				//获取当前滑过是第几个元素
                h = $('.category-list').offset().top,						//获取当前下拉菜单距离窗口多少像素
                s = $(window).scrollTop(),									//获取游览器滚动了多少高度
                i = $(this).offset().top,									//当前元素滑过距离窗口多少像素
                item = $(this).children('.item-list').height(),				//下拉菜单子类内容容器的高度
                sort = $('.category-list').height();						//父类分类列表容器的高度

            if (item < sort){												//如果子类的高度小于父类的高度
                if ( eq == 0 ){
                    $(this).children('.item-list').css('top', (i-h));
                } else {
                    $(this).children('.item-list').css('top', (i-h)+1);
                }
            } else {
                if ( s > h ) {												//判断子类的显示位置，如果滚动的高度大于所有分类列表容器的高度
                    if ( i-s > 0 ){											//则 继续判断当前滑过容器的位置 是否有一半超出窗口一半在窗口内显示的Bug,
                        $(this).children('.item-list').css('top', (s-h)+2 );
                    } else {
                        $(this).children('.item-list').css('top', (s-h)-(-(i-s))+2 );
                    }
                } else {
                    $(this).children('.item-list').css('top', 3 );
                }
            }

            $(this).addClass('hover');
            $(this).children('.item-list').css('display','block');
        },function(){
            $(this).removeClass('hover');
            $(this).children('.item-list').css('display','none');
        });
    }
    headerShowCategorys();
}

function index_js() {
    $(function() {
        var mySwiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: 4800,
            autoplayDisableOnInteraction: false,
            pagination: '.swiper-pagination',
            paginationClickable: true,
        });
    });
}
/* 确认订单*/
function getSelectedMoney() {
    var cityName=$('.addselect').attr('data_city');
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
    $('.addr-cur').on('click',function () {
        var aId=$(this).attr('data_id');
        $('#address_id').val(aId);
        $(this).addClass('addselect').siblings().removeClass('addselect');
        getSelectedMoney();
    });
    $('.order_bottom .btn').on('click',function () {
        $('#form_order').submit();
    });
}
/* 购物车 start*/
function getCartedMoney() {
    var cart_id = "";
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
    $.get("/index.php/cart/getSelectedMoney?cart_ids=" + cart_id, function (data) {
        if (data != "") {
            var data = eval('(' + data + ")");
            $("#totalPrice span").html(data.countTotal);
            $("#totalNum span").html(data.countNum);
            $('.cart_foot .shop_total').each(function(){
                var shop_id=$(this).attr('shop_id');
                if(data['shop'+shop_id+'_goodsPrice']){
                    $(this).html(data['shop'+shop_id+'_goodsPrice']);
                }else{
                    $(this).html(0);
                }
            });
        } else {
            $("#totalPrice span").html(0);
            $("#totalNum span").html(0);
        }
    });
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
            var tr=$(this).parents('.goods_item');
            $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val(), function (data) {
                var data=eval("("+data+")");
                if(data.code=='0'){
                    input.val(num);
                    tr.find('.price').html(data.total);
                }else{
                    input.val(data.stock_count);
                    layer.msg(data.msg);
                }
            });
        }
        getCartedMoney();
    });
    $('.wrap-input .btn-add').on('click', function () {
        var input=$(this).parent().find('input');
        var num=Number(input.val())+1;
        var chkbox=$(this).parents('.goods_item').find('input:checkbox');
        var tr=$(this).parents('.goods_item');
        $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val(), function (data) {
            var data=eval("("+data+")");
            if(data.code=='0'){
                input.val(num);
                tr.find('.price').html(data.total);
            }else{
                input.val(data.stock_count);
                layer.msg(data.msg);
            }
        });
        getCartedMoney();
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
            layer.msg('请选择商品');
        }else{
            window.location='/order/confirm/'+cart_id;
        }
    });
}
/* 购物车 end*/

function goods_detail_js()
{
    $(function(){
        // 图片替换效果
        $('.goods_pics li img').mouseover(function(){
            $('.goods_pics li img').removeClass();
            $(this).addClass('ware_pic_hover');
            $('.pic_big img').attr('src', $(this).attr('src'));
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
        //alert(goodsSpec.spec1_name);
        //alert(goodsSpec.spec2_name);
    });
    //加入购物车
    $('.buy_box_opts .opt1').on('click',function () {
        var quantity=document.forms['form_order'].quantity.value;
        var spec_id=$('#spec_id').val();
        $.post("/index.php/cart/add/",{goods_id:goods_id,spec_id:spec_id,quantity:quantity},function(data){
            var json=eval("("+data+")");
            if(json.code=='0'){
                var msg='添加成功！';
            }else{
                var msg=json.msg;
            }
            layer.msg(msg);
        });
    });
    //立刻购买
    $('.buy_box_opts .opt2').on('click',function(){
        var form=document.forms['form_order'];
        var quantity=form.quantity;
        var tag=true;
        if(Number(quantity.value)==0){
            $(quantity).focus();
            layer.msg('请正确选择数量');
            tag=false;
        }
        if(Number($('#goods_stock_count').html()) < Number(quantity.value)){
            layer.msg('库存不足');
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
    $('.goods_prompt').html(goods_prompt);
    goodsSpec.setFormValue();
    $('#buy_quantity').val(1);
}