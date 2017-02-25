<?php require 'header.php';?>
    <script type="text/javascript" src="/data/js/category.js?<?= rand(1, 100) ?>"></script>
    <div class="layui-main">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                    <span class="layui-breadcrumb">
                      <a href="<?= url('goods') ?>">商品管理</a>
                      <a><cite>选择分类</cite></a>
                    </span>
                <hr>
                <br>
                <? if(empty($cates) || empty($shippings)) : ?>
                    <? if(empty($cates)) : ?>
                        <blockquote class="layui-elem-quote">暂无添加店铺分类，<a href="<?=url('category/add')?>" class="layui-btn layui-btn-mini">添加</a></blockquote>
                    <? endif;?>
                    <? if(empty($shippings)) : ?>
                        <blockquote class="layui-elem-quote">暂无添加配送方式，<a href="<?=url('shipping/add')?>" class="layui-btn layui-btn-mini">添加</a></blockquote>
                    <? endif;?>
                <? else : ?>
                <form method="post">
                    <div id="div_category">
                        <select name="categoryid[]" id="category1" class="multiple" multiple="multiple"
                                onchange="getsel(1,this.value)">
                            <? foreach ($categorys as $var) { ?>
                                <option value='<?= $var->id ?>' <? if ($var->id == $row->category_id) {
                                    echo 'selected';
                                } ?>><?= $var->name ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <br><br>
                    <input type="submit" value="下一步，填写商品信息" class="layui-btn">
                </form>
                <script language="javascript">
                    $.ajaxSetup({async: false});
                    <?=$row->sel?>
                </script>
                <? endif;?>
            </div>
        </div>
    </div>
    <script>
        function getsel(idnum, id) {
            str = cate_arr[id];
            if (str != '' && str != undefined) {
                var sel = appendSelect(idnum);
                var arr = str.split("[SER]");
                sel.options.length = 0;
                for (v in arr) {
                    var v = arr[v].split("#");
                    sel.options.add(new Option(v[1], v[0]));
                }
            }
            else {
                removeSelect(idnum);
            }
            document.getElementById('category' + idnum).value = id;
        }
        //获取下一个select  id命名规格category+编号
        function appendSelect(idnum) {
            var thisnum = idnum + 1;
            if (document.getElementById('category' + thisnum)) {
                sel = document.getElementById('category' + thisnum);
                removeSelect(thisnum);//移除后面的分类
            }
            else {
                sel = document.createElement("select");
                sel.id = 'category' + thisnum;
                sel.name = 'categoryid[]';
                sel.multiple = 'multiple';
                sel.className = 'multiple';
                sel.onchange = function () {
                    getsel(thisnum, this.value);
                }
                document.getElementById('div_category').appendChild(sel);
            }
            return sel;
        }
        //移除其它的select
        function removeSelect(idnum) {
            var thisnum = idnum + 1;
            while (document.getElementById('category' + thisnum)) {
                document.getElementById('div_category').removeChild(document.getElementById('category' + thisnum));
                thisnum = thisnum + 1;
            }
        }
    </script>
<?php require 'footer.php';?>