$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,laydate = layui.laydate;
    util.fixbar();
    var element = layui.element();
    element.init();
    //上传文件
    if ($('.layui-upload-file').length>0){
        layui.use(['upload'], function(){
            var index;
            $('.layui-upload-file').each(function(index,obj){
                var id=obj.getAttribute('upload_id');
                var type=obj.getAttribute('upload_type');
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
        });
    }
});

function address_js() {
    $(function () {
        pca.init('select[name=province]', 'select[name=city]', 'select[name=county]', '', '', '');
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


