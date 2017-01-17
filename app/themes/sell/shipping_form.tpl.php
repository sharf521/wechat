<?php require 'header.php';?>

    <div class="warpcon">
<?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <?php if($this->func=='index') : ?>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>配送方式管理</legend>
                </fieldset>
                <a href="<?=url('shipping/add')?>" class="layui-btn layui-btn-small">新增</a><br><br>
                <?
            if(count($cates)==0) {
                echo '<blockquote class="layui-elem-quote">暂无添加</blockquote>';
            }else{?>
                <table class="layui-table"  lay-skin="line">
                    <thead>
                    <tr>
                        <th>分类名称</th><th>添加时间</th><th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach($cates as $cate) : ?>
                        <tr>
                            <td><?=$cate['name']?></td>
                            <td><?=date('Y-m-d H:i:s',$cate['created_at'])?></td>
                            <td><a href="<?=url("category/add/?pid={$cate['id']}")?>"></a>
                                <a href="<?=url("category/edit/?id={$cate['id']}")?>">编辑</a>
                                <a href="javascript:cateDel(<?=$cate['id']?>)">删除</a></td>
                        </tr>
                    <? endforeach;?>
                    </tbody>
                </table>
            <?php }?>
            <?php elseif ($this->func=='add' || $this->func=='edit') : ?>
                <script src="/themes/sell/wuliu.js"></script>
            <link href="/themes/sell/wuliu.css" rel="stylesheet" />
                <span class="layui-breadcrumb">
                  <a href="<?=url('shipping')?>">配送方式</a>
                  <a><cite><?=$this->func == 'add'?'新增':'编辑'; ?></cite></a>
                </span>
            <hr>

                <form method="post" onSubmit="return chkform()">
                    <?
                    if(!$ships)
                    {
                        $ships[0]=array(
                            'one'	=>1,
                            'price'=>10,
                            'next'	=>1,
                            'nprice'=>5
                        );
                    }
                    ?>
                    <p class="pxcss xzwl">
                        物流公司：<input type="text" name="name" value="<?=$ship['name']?>"><br />
                    </p>
                    <p class="pxcss xzwl">
                        计价方式：
                        <label><input type="radio" name="typeid" value="1" checked="checked" />按件数</label>
                        <!--<label><input type="radio" name="typeid" value="2" <? if($ship['typeid']==2){echo 'checked';}?>/>按重量</label>
                    <label><input type="radio" name="typeid" value="3" <? if($ship['typeid']==3){echo 'checked';}?>/>按体积</label><br />-->
                    </p>
                    <p class="pxcss">
                        运送方式：除指定地区外，其余地区的运费采用"默认运费"<br /></p>
                    <div class="tablebox">
                        <div class="entity">
                            <div class="default">
                                默认运费：
                                <input class="inputtext" type="text" maxlength="6" value="<?=$ships[0]['one']?>" name="one[]" onKeyUp="value=value.replace(/[^0-9]/g,'')"><span name='unit'>件</span>内，
                                <input class="inputtext" type="text" maxlength="6" value="<?=$ships[0]['price']?>"  name="price[]" onKeyUp="value=value.replace(/[^0-9.]/g,'')">元，每增加
                                <input class="inputtext" type="text" maxlength="6" value="<?=$ships[0]['next']?>"  name="next[]" onKeyUp="value=value.replace(/[^0-9]/g,'')"><span name='unit'>件</span>，增加运费
                                <input class="inputtext" type="text" maxlength="6" value="<?=$ships[0]['nprice']?>" name="nprice[]" onKeyUp="value=value.replace(/[^0-9.]/g,'')">元
                            </div>

                            <div class="yfbox">
                                <table width="100%" id="yltable" style="display:<? if(count($ships)==1){echo 'none';}?>">
                                    <tr id="tr0" style="background:#f5f5f5">
                                        <td width="300">运送到</td><td>首<span name='unitname'>件</span>(<span name='unit'>件</span>)</td><td>首费(元)</td><td>续<span name='unitname'>件</span>(<span name='unit'>件</span>)</td><td>续费(元)</td><td>操作</td>
                                    </tr>
                                    <?
                                    array_shift($ships);
                                    foreach($ships as $i=>$ship)
                                    {
                                        $j=$i+1;
                                        ?>
                                        <tr id="tr<?=$j?>">
                                            <td width="300">
                                                <a href="javascript:showArea(<?=$j?>)">编辑</a>
                                                <p><?=$ship['areaname']?></p>
                                                <input type="hidden" name="v_txt_tr<?=$j?>" id="v_txt_tr<?=$j?>" value="<?=$ship['areaname']?>"/>
                                                <input type="hidden" name="v_val_tr<?=$j?>" id="v_val_tr<?=$j?>" value="<?=$ship['areaid']?>"/>
                                            </td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$ship['one']?>" onKeyUp="value=value.replace(/[^0-9]/g,'')"  name="one[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$ship['price']?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')"  name="price[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$ship['next']?>" onKeyUp="value=value.replace(/[^0-9]/g,'')" name="next[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$ship['nprice']?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')" name="nprice[]"></td>
                                            <td><input class="delete" type="button" value="删除" onClick="deleteRow(this)"></td>
                                        </tr>
                                        <?
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="zdbox"><a href="javascript:addRow()">为指定地区城市设置运费</a></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="bbtncss"><input type="submit" value="保存"/>&nbsp; <input type="button" value="取消" onClick="window.history.go(-1)"/></div>
                </form>


            <? endif;?>
        </div>
    </div>
    <script>
        category_js();
        function cateDel(id)
        {
            layer.open({
                content: '您确定要删除吗？'
                ,btn: ['删除', '取消']
                ,yes: function(index){
                    location.href='<?=url("category/del/?id=")?>'+id;
                    layer.close(index);
                }
            });
        }
    </script>
<?php require 'footer.php';?>



        <div class="aqbox" id="divArea">
            <div class="aqalbox">
                <div class="topdq"><div class="title-wuliu">选择地区</div><a href="javascript:hideArea()">x</a></div>

                <ul  class="plabox">
                    <input type="hidden" value="1" id="tr_num" />
                    <?
                    $j=0;
                    foreach($area as $i=>$result)
                    {
                        $j++;
                        ?>
                        <li class="choosbox <? if($j%2==0){echo 'bgcss';}?>">
                            <div class="ffbox"><label><input type="checkbox" title="<?=$i?>" value="<?=$i?>" name='area' onClick="chxclick(this)"/><b><?=$i?></b></label></div>
                            <div class="elsbox">
                                <?
                                foreach($result as $provs)
                                {
                                    ?>
                                    <div class="fwbox ">
                                        <div  class="greas"><label><input type="checkbox" title="<?=$provs['name']?>" name='province' onClick="chxclick(this)" value="<?=$provs['id']?>"/><?=$provs['name']?></label><span></span><img src="/themes/images/jt.jpg" onClick="subarea(this)"/></div>

                                        <div class="citys">
                                            <?
                                            $citys=(new \App\Model\Region())->getList($provs['id']);
                                            foreach($citys as $city)
                                            {
                                                ?>
                                                <label><input type="checkbox" title="<?=$city['name']?>" value="<?=$city['id']?>"  onclick="chxclick(this)"/><?=$city['name']?></label>
                                                <?
                                            }
                                            ?>
                                            <p align="right"><input type="button" value="关闭" onClick="subhide(this)"/></p>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </li>
                        <?
                    }
                    ?>
                </ul>
                <p><input type="button" value="确定" onClick="saveArea()"/> <input type="button" value="取消" onClick="hideArea()"/></p>

            </div>
            <div class="clear"></div>
        </div>
