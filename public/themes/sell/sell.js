$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,laydate = layui.laydate;
    util.fixbar();
    layui.element().init();
    //上传文件
    if ($('.layui-upload-file').length>0){
        var index;
        $('.layui-upload-file').each(function(index,obj){
            var id=obj.getAttribute('upload_id');
            var type=obj.getAttribute('upload_type');
            if(id==null){
                return;
            }
            layui.upload({
                url: '/index.php/upload/save?type='+type
                ,elem:obj
                ,before: function(input){
                    index=layer.msg('上传中', {icon: 16,time:1000000});
                }
                ,success: function(res){
                    layer.close(index);
                    if(res.code=='0'){
                        var path=res.url+'?'+Math.random();
                        $('#'+id).val(path);
                        var _str="<a href='"+path+"' target='_blank'><img src='"+path+"' height='50'/></a>";
                        $('#upload_span_'+id).html(_str);
                    }else{
                        alert(res.msg);
                    }
                }
            });
        });
    }
});

//goods start
function goodsAdd_js() {
    var index;
    layui.upload({
        url: '/index.php/upload/save?type=goods'
        ,before: function(input){
            index=layer.msg('上传中', {icon: 16,time:1000000});
        }
        ,success: function(res){
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
        $("#MySpecTB tr:last").after(tem);
    });

    window.delGoodsImg=function(o,id) {
        $.get("/index.php/upload/del?type=goods&id=" + id, {}, function (str) { });
        var ids = $('#imgids').val();
        $('#imgids').val(ids.replace(','+id+',',','));
        $(o).parents('li').remove();
    }
    layui.form('select').render();
    layui.form().on('submit(*)', function(data){
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
            layer.tips('请上传图片！', '.layui-upload-file');
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

function category_js() {
    $(function () {
        layui.form().on('submit(*)', function(data){
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
    $(function () {
        layui.form('radio').render();
        layui.form().on('submit(*)', function(data){
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

//userInfo   start
function changeProvince(value)
{
    document.getElementById('province').value=value;
    var sel=document.getElementById('city');
    if(value!='0')
    {
        changeSel(sel,value);
    }
    else
    {
        sel.options.length=0;
    }
    document.getElementById('county').options.length=0;
}
function changeCity(value)
{
    document.getElementById('city').value=value;
    var sel=document.getElementById('county');
    changeSel(sel,value);
}
function changeSel(sel,id)
{
    $.post("/index.php/ajax/region_substring/"+id,{},function(str){
        var arr =str.split("[#]");
        sel.options.length=0;
        sel.options.add(new Option('请选择','0'));
        for(v in arr)
        {
            var v=arr[v].split("::");
            if(v[0]!='')
                sel.options.add(new Option(v[1],v[0]));
        }
    });
}
//userInfo   end


