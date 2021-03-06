$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,form=layui.form
        ,element=layui.element;
    util.fixbar();
    form.render();
    lay('.layui-date').each(function(){
        layui.laydate.render({
            elem: this
            ,trigger: 'click'
        });
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

//goods start
function goodsAdd_js() {
    var index;
    layui.upload.render({
        elem: '#uploads'
        ,url: '/index.php/upload/save?type=goods'
        ,before: function(input){
            index=layer.msg('上传中', {icon: 16,time:1000000});
        }
        ,done: function(res){
            layer.close(index);
            if(res.code=='0'){
                var imgId=res.id;
                var path=res.url+'?'+Math.random();
                if($('#imgids').val().split(',').length<7){
                    $('#imgids').val($('#imgids').val()+imgId+',');
                    var _str='<li><img src="'+path+'">' +
                        "<i class='iconfont' onclick=delGoodsImg(this,'"+imgId+"')>&#xe642;</i></li>";
                    $("#uploaderFiles").append(_str);
                }else{
                    layer.alert('最多可以添加5张！');
                }
            }else{
                alert(res.msg);
            }
        }
    });
    $('#addSpecBtn').on("click",function(e){
        if($('#is_have_spec').val()=='0'){
            $('#is_have_spec').val('1');
            $('#specBox_no').hide();
            $('#specBox').show();
            $(this).val('关闭规格');
        }else{
            $('#is_have_spec').val('0');
            $('#specBox').hide();
            $('#specBox_no').show();
            $(this).val('开启规格');
        }

    });
    $('.up_btn').click(function (){
        var i=$(this).parents('tr').index();//当前行的id
        if(i>0){
            var tem=$(this).parents('tr').prev().clone(true);
            $(this).parents('tr').prev().remove();
            $(this).parents('tr').after(tem);
        }else{
            layui.layer.tips('己经在第一行了！', this);
        }
    });
    $('.down_btn').click(function(){
        var l=$(this).parents('tbody').find('tr').length;//总行数
        var i=$(this).parents('tr').index();//当前行的id
        if(i<l-1){
            var tem=$(this).parents('tr').next().clone(true);
            $(this).parents('tr').next().remove();
            $(this).parents('tr').before(tem);
        }else{
            layui.layer.tips('己经在最后一行了！', this);
        }
    });
    $('.delete_btn').click(function(){
        var l=$(this).parents('tbody').find('tr').length;//总行数
        if(l>2){
            $(this).parents('tr').remove();
        }else{
            layui.layer.tips('最少保留两行！', this);
        }
    });
    $('.add_btn').click(function(){
        var tem=$("#MySpecTB tr:last").clone(true);
        $(tem).find('input:first').val(0);
        $("#MySpecTB tr:last").after(tem);
    });

    window.delGoodsImg=function(o,id) {
        $.get("/index.php/upload/del?type=goods&id=" + id, {}, function (str) { });
        var ids = $('#imgids').val();
        $('#imgids').val(ids.replace(','+id+',',','));
        $(o).parents('li').remove();
    }
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
        if($('#is_have_spec').val()=='0'){
            var g_price=$(form).find('input[name=g_price]');
            if(g_price.val()==''){
                layer.tips('不能为空！', g_price);
                g_price.focus();
                return false;
            }
            var g_stock_count=$(form).find('input[name=g_stock_count]');
            if(g_stock_count.val()==''){
                layer.tips('不能为空！', g_stock_count);
                g_stock_count.focus();
                return false;
            }
        }else{
            var Tag=false;
            $('#specBox').find('input[type=text]').each(function () {
                if($(this).attr('name')!='spec_name2' && $(this).attr('name')!='spec_2[]'){
                    if($(this).val()==''){
                        layer.tips('不能为空！', this,{tipsMore: true});
                        Tag=true;
                    }
                }
            });
            if(Tag){
                return false;
            }
        }
        var imgids=$(form).find('input[name=imgids]');
        if(imgids.val().length<2){
            layer.tips('请上传图片！', '#uploads');
            return false;
        }
        if(ue.hasContents()==false){
            layer.tips('介绍不能为空！', '#container');
            return false;
        }
        var shop_category=$(form).find('select[name=shop_category]');
        if(shop_category.val()==''){
            layer.tips('不能为空！', shop_category.parents('.layui-input-inline'));
            shop_category.focus();
            return false;
        }
        var shipping_id=$(form).find('select[name=shipping_id]');
        if(shipping_id.val()==''){
            layer.tips('不能为空！', shipping_id.parents('.layui-input-inline'));
            shipping_id.focus();
            return false;
        }
    });
}

//goods end

function purchaseGoodsEdit_js() {
    layui.form.on('submit(*)', function (data) {
        var form = data.form;
        var fields = data.field;
        var name = $(form).find('input[name=name]');
        if (name.val() == '') {
            layer.tips('不能为空！', name);
            name.focus();
            return false;
        }

        if ($('#is_have_spec').val() == '0') {
            var retail_price = $(form).find('input[name=retail_price]');
            if (retail_price.val() == '' || Number(retail_price.val()) == 0) {
                layer.tips('不能为空！', retail_price);
                retail_price.focus();
                return false;
            }
            var price = retail_price.attr('data_price');
            if (Number(retail_price.val()) < Number(price)) {
                layer.tips('零售价不能小于供货价！', retail_price);
                retail_price.focus();
                return false;
            }
        } else {
            var Tag = false;
            $(form).find('input[type=text]').each(function () {
                if ($(this).val() == '' || Number($(this).val()) == 0) {
                    layer.tips('不能为空！', this, {tipsMore: true});
                    Tag = true;
                }
                var price = $(this).attr('data_price');
                if (Number($(this).val()) < Number(price)) {
                    layer.tips('零售价不能小于供货价！', this, {tipsMore: true});
                    Tag = true;
                }
            });
            if (Tag) {
                return false;
            }
        }
    });
}

function category_js() {
    $(function () {
        layui.form.on('submit(*)', function(data){
            var form=data.form;
            var fields=data.field;
            var name=$(form).find('input[name=name]');
            if(name.val()==''){
                layer.tips('不能为空！', name);
                name.focus();
                return false;
            }
        });
    });
}

function shipping_js() {
    layui.form.on('submit(*)', function(data){
        var form=data.form;
        var fields=data.field;
        var name=$(form).find('input[name=name]');
        if(name.val()==''){
            layer.tips('不能为空！', name);
            name.focus();
            return false;
        }
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

function shop_js() {
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
}

function supply_apply() {
    $(function () {
        layui.form.on('submit(*)', function(data){
            var form=data.form;
            var fields=data.field;
            var company_name=$(form).find('input[name=company_name]');
            if(company_name.val()==''){
                layer.tips('不能为空！', company_name);
                company_name.focus();
                return false;
            }
            var company_owner=$(form).find('input[name=company_owner]');
            if(company_owner.val()==''){
                layer.tips('不能为空！', company_owner);
                company_owner.focus();
                return false;
            }
            if(ue.hasContents()==false){
                layer.tips('说明不能为空！', '#container');
                return false;
            }
        });
    });
}

function map(container,gps) {
    var map = new BMap.Map(container);
    map.addControl(new BMap.NavigationControl());//左上角，添加默认缩放平移控件
    var point = new BMap.Point(116.400244,39.92556);
    if(gps!=''){
        var _gps=gps.split(',');
        point = new BMap.Point(_gps[0],_gps[1]);
    }/*else if($('#address').val()!=''){
     //创建地址解析器实例
     var myGeo = new BMap.Geocoder();
     //将地址解析结果显示在地图上，并调整地图视野。此过程为异步，所以要重设标注
     myGeo.getPoint($('#address').val(), function(poi){
     map.centerAndZoom(poi, 12);
     marker.setPosition(poi); //重调标注位置
     }, $('#city').val());
     }*/
    map.centerAndZoom(point, 16);
    var marker = new BMap.Marker(point);// 创建标注
    map.addOverlay(marker);             // 将标注添加到地图中
    marker.enableDragging();           // 可拖拽

    if(gps==''){
        map.setZoom(12);
        var myCity = new BMap.LocalCity();
        myCity.get(function (result) {
            var cityName = result.name;
            map.setCenter(cityName);
        });
    }
    //显示文本标题
    var label = new BMap.Label('拖拽到您的位置',{offset:new BMap.Size(20,-15)});
    label.setStyle({ backgroundColor:"red", color:"white", fontSize : "12px" });
    marker.setLabel(label);

    //拖拽设置位置
    marker.addEventListener("dragend", function(e){
        try{document.getElementById('gps').value = e.point.lng + "," + e.point.lat;}catch (ex) {}
    });
    //点击设置位置
    map.addEventListener("click", function(e){
        marker.setPosition(e.point); //重调标注位置
        try{document.getElementById('gps').value = e.point.lng + "," + e.point.lat;}catch (ex) {}
    });
}

//奖励承诺
function commitment() {
    layui.form.on('submit(*)', function(data){
        var form=data.form;
        var fields=data.field;
        if(ue.hasContents()==false){
            layer.tips('内容不能为空！', '#container');
            return false;
        }
    });
}