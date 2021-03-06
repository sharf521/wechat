$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,form=layui.form
        ,element=layui.element;
    util.fixbar();
    form.render();
    layui.flow.lazyimg();//lay-src的img元素开启了懒加载
    //右侧
    $.get("/cart/getGoodsCount/",{},function(data) {
        $('#cart_num').html(data);
        if(data>0){
            $('#cart_num').show();
        }
    });
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
    var mySwiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: 4800,
        autoplayDisableOnInteraction: false,
        pagination: '.swiper-pagination',
        paginationClickable: true,
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
                if(data['shop'+shop_id+'_fullDownMoney']>0){
                    $('#shop'+shop_id+'_fulldown_money').find('span').html('-'+data['shop'+shop_id+'_fullDownMoney']);
                    $('#shop'+shop_id+'_fulldown_money').show();
                }else{
                    $('#shop'+shop_id+'_fulldown_money').hide();
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
        if($('#address_id').val()==''){
            layer.open({
                content: '收货地址不能为空！',
                skin: 'msg'
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
        $('#cart_num').html(data.countNum);
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
            var tr=$(this).parents('.goods_item');
            $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val()+"&cart_ids="+cart_id, function (json) {
                var data=eval("("+json+")");
                if(data.code=='0'){
                    input.val(num);
                    tr.find('.price').html(data.total);
                }else{
                    input.val(data.stock_count);
                    layer.msg(data.msg);
                }
                cart_ajaxSetMoney(json);
            });
        }
    });
    $('.wrap-input .btn-add').on('click', function () {
        var input=$(this).parent().find('input');
        var num=Number(input.val())+1;
        var chkbox=$(this).parents('.goods_item').find('input:checkbox');
        var tr=$(this).parents('.goods_item');
        $.get("/index.php/cart/changeQuantity?num="+num+"&id="+chkbox.val()+"&cart_ids="+cart_id, function (json) {
            var data=eval("("+json+")");
            if(data.code=='0'){
                input.val(num);
                tr.find('.price').html(data.total);
            }else{
                input.val(data.stock_count);
                layer.msg(data.msg);
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
            layer.msg('请选择商品');
        }else{
            window.location='/order/confirm/'+cart_id;
        }
    });
}
/* 购物车 end*/

//采购
function purchase_detail_js() {
    // 图片替换效果
    $('.goods_pics li img').mouseover(function(){
        $('.goods_pics li img').removeClass();
        $(this).addClass('ware_pic_hover');
        var src=$(this).attr('src').split('_');
        $('.pic_big img').attr('src', src[0]);
    });
    //立刻采购
    $('.purchase-btn').on('click',function(){
        var form=document.forms['form_order'];
        if($('#is_have_spec').val()=='0'){
            var retail_price=$(form).find('input[name=retail_price]');
            if(retail_price.val()=='' || Number(retail_price.val())==0){
                layer.tips('不能为空！', retail_price);
                retail_price.focus();
                return false;
            }
            var price=retail_price.attr('data_price');
            if(Number(retail_price.val()) < Number(price)){
                layer.tips('零售价不能小于供货价！', retail_price);
                retail_price.focus();
                return false;
            }
        }else{
            var Tag=false;
            $(form).find('input[type=text]').each(function () {
                if($(this).val()=='' || Number($(this).val())==0){
                    layer.tips('不能为空！', this,{tipsMore: true});
                    Tag=true;
                }
                var price=$(this).attr('data_price');
                if(Number($(this).val()) < Number(price)){
                    layer.tips('零售价不能小于供货价！', this,{tipsMore: true});
                    Tag=true;
                }
            });
            if(Tag){
                return false;
            }
        }
        layer.open({
            content: '您确定要采购到店铺吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                form.submit();
                layer.close(index);
            }
        });
    })
}
function goods_detail_js()
{
    // 图片替换效果
    $('.goods_pics li img').mouseover(function(){
        $('.goods_pics li img').removeClass();
        $(this).addClass('ware_pic_hover');
        var src=$(this).attr('src').split('_');
        $('.pic_big img').attr('src', src[0]);
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
    //遍历图片
    /*        $('.goods_detail_txt img').each(function (i) {
     console.log($(this).width());
     if($(this).width()>=700){
     $(this).attr('width','100%');
     }
     });*/

    $('.selectLimit li').on('click',function () {
        $(this).addClass('active').siblings().removeClass('active');
    });
    //购买记录
    layui.element.on('tab(demo)', function(data){
        if(data.index==1){
            $.get('/goods/getOrderRecord/?id='+goods_id,function (data) {
                var json = eval('(' + data + ')');
                if(json.code==1){
                    var list=json.list;
                    html='<table class="layui-table" lay-skin="line"><thead><tr><td>买家</td><td></td><td width="150">购买数量</td><td>购买时间</td></thead>';
                    for(index in list){
                        var obj=list[index];
                        html+="<tr><td>"+obj.username+"</td><td>"+obj.spec_1+obj.spec_2+"</td><td>"+obj.quantity+"</td><td width='200'>"+obj.created_at+"</td></tr>";
                    }
                    html+='</table>';
                }else{
                    html='暂无记录';
                }
                $('#orderRecord').html(html);
            })
        }
    });
    //加入购物车
    $('.buy_box_opts .opt1').on('click',function (event) {
        var quantity=document.forms['form_order'].quantity.value;
        var spec_id=$('#spec_id').val();
        $.post("/index.php/cart/add/",{goods_id:goods_id,spec_id:spec_id,quantity:quantity},function(data){
            var json=eval("("+data+")");
            if(json.code=='0'){
                var scrollTop=$(document).scrollTop();
                var offset = $("#icon_cart").offset();
                var src=$('.pic_big').find('img').attr('src');
                var flyer = $('<img class="u-flyer" src="'+src+'" width="30" height="30">');
                flyer.fly({
                    start: {
                        left: event.pageX, //开始位置（必填）#fly元素会被设置成position: fixed
                        top: event.pageY-scrollTop //开始位置（必填）
                    },
                    end: {
                        left: offset.left, //结束位置（必填）
                        top: offset.top - scrollTop, //结束位置（必填）
                        width: 0, //结束时宽度
                        height: 0 //结束时高度
                    },
                    onEnd: function(){
                        $("#cart_num").html(Number($("#cart_num").html())+Number($('#buy_quantity').val()));
                        $("#cart_num").show();
                        layer.msg('添加成功');
                    }
                });
            }else{
                layer.msg(json.msg);
            }
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
    });
    //预定
    $('.buy_box_opts .btn-presale').on('click',function(){
        var quantity=document.forms['form_order'].quantity.value;
        var spec_id=$('#spec_id').val();
        $.post("/index.php/goods/preSale/",{goods_id:goods_id,spec_id:spec_id,quantity:quantity},function(data){
            var json=eval("("+data+")");
            if(json.code=='noLogin'){
                layer.open({
                    content: '您还没有登陆，登陆后预订？'
                    ,btn: ['确定', '取消']
                    ,yes: function(){
                        window.location='/user/login/?r='+invite_user;
                    }
                });
            }else{
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
                    var url='/preSaleOrder/confirm/?goods_id='+goods_id+'&quantity='+quantity.value+'&spec_id='+spec_id+'&r='+invite_user;
                    window.location=url;
                }
            }
        });
    });
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
    }
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

/**
 * JavaScript脚本实现回到页面顶部示例
 * @param acceleration 速度
 * @param stime 时间间隔 (毫秒)
 **/
function gotoTop(acceleration, stime) {
    acceleration = acceleration || 0.1;
    stime = stime || 10;
    var x1 = 0;
    var y1 = 0;
    var x2 = 0;
    var y2 = 0;
    var x3 = 0;
    var y3 = 0;
    if (document.documentElement) {
        x1 = document.documentElement.scrollLeft || 0;
        y1 = document.documentElement.scrollTop || 0;
    }
    if (document.body) {
        x2 = document.body.scrollLeft || 0;
        y2 = document.body.scrollTop || 0;
    }
    var x3 = window.scrollX || 0;
    var y3 = window.scrollY || 0;

    // 滚动条到页面顶部的水平距离
    var x = Math.max(x1, Math.max(x2, x3));
    // 滚动条到页面顶部的垂直距离
    var y = Math.max(y1, Math.max(y2, y3));

    // 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
    var speeding = 1 + acceleration;
    window.scrollTo(Math.floor(x / speeding), Math.floor(y / speeding));

    // 如果距离不为零, 继续调用函数
    if (x > 0 || y > 0) {
        var run = "gotoTop(" + acceleration + ", " + stime + ")";
        window.setTimeout(run, stime);
    }
}