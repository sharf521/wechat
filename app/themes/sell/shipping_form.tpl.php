<?php require 'header.php';?>

<div class="layui-main">
<?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <script src="/themes/sell/wuliu.js"></script>
            <link href="/themes/sell/wuliu.css" rel="stylesheet"/>
                <span class="layui-breadcrumb">
                  <a href="<?= url('shipping') ?>">配送方式</a>
                  <a><cite><?= $this->func == 'add' ? '新增' : '编辑'; ?></cite></a>
                </span>
            <hr>
                <form method="post" class="layui-form">
                    <?
                    $areas=$shipping->areas;
                    if(!$areas){
                        $areas[0]=array(
                            'one'	=>1,
                            'price'=>10,
                            'next'	=>1,
                            'nprice'=>5
                        );
                    }
                    ?>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">物流公司</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" value="<?=$shipping->name?>"  placeholder="请填写物流公司" class="layui-input" value="" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">计价方式</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="typeid" checked value="1" title="按件数">
<!--                                <input type="radio" name="typeid" --><?// if($shipping->typeid==2){echo 'checked';}?><!-- value="2" title="按重量">-->
<!--                                <input type="radio" name="typeid" --><?// if($shipping->typeid==3){echo 'checked';}?><!-- value="3" title="按体积">-->
                            </div>
                        </div>
                    </div>

                    <p class="pxcss">运送方式：除指定地区外，其余地区的运费采用"默认运费"</p>
                    <div class="tablebox">
                        <div class="entity">
                            <div class="default">
                                默认运费：
                                <input class="inputtext" type="text" maxlength="6" value="<?=$areas[0]['one']?>" name="one[]" onKeyUp="value=value.replace(/[^0-9]/g,'')"><span name='unit'>件</span>内，
                                <input class="inputtext" type="text" maxlength="6" value="<?=$areas[0]['price']?>"  name="price[]" onKeyUp="value=value.replace(/[^0-9.]/g,'')">元，每增加
                                <input class="inputtext" type="text" maxlength="6" value="<?=$areas[0]['next']?>"  name="next[]" onKeyUp="value=value.replace(/[^0-9]/g,'')"><span name='unit'>件</span>，增加运费
                                <input class="inputtext" type="text" maxlength="6" value="<?=$areas[0]['nprice']?>" name="nprice[]" onKeyUp="value=value.replace(/[^0-9.]/g,'')">元
                            </div>
                            <div class="yfbox">
                                <table class="layui-table" id="yltable" style="display:<? if(count($ships)==1){echo 'none';}?>">
                                    <tr id="tr0" style="background:#f5f5f5">
                                        <td width="300">运送到</td><td>首<span name='unitname'>件</span>(<span name='unit'>件</span>)</td><td>首费(元)</td><td>续<span name='unitname'>件</span>(<span name='unit'>件</span>)</td><td>续费(元)</td><td>操作</td>
                                    </tr>
                                    <?
                                    array_shift($areas);
                                    foreach($areas as $i=>$area) :
                                        $j=$i+1; ?>
                                        <tr id="tr<?=$j?>">
                                            <td width="300">
                                                <a href="javascript:showArea(<?=$j?>)" class="layui-btn layui-btn-mini">编辑</a>
                                                <p><?=$area['areaname']?></p>
                                                <input type="hidden" name="v_txt_tr<?=$j?>" id="v_txt_tr<?=$j?>" value="<?=$area['areaname']?>"/>
                                                <input type="hidden" name="v_val_tr<?=$j?>" id="v_val_tr<?=$j?>" value="<?=$area['areaid']?>"/>
                                            </td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$area['one']?>" onKeyUp="value=value.replace(/[^0-9]/g,'')"  name="one[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$area['price']?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')"  name="price[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$area['next']?>" onKeyUp="value=value.replace(/[^0-9]/g,'')" name="next[]"></td>
                                            <td><input class="inputtext" type="text" maxlength="6" value="<?=$area['nprice']?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')" name="nprice[]"></td>
                                            <td><input class="layui-btn layui-btn-mini" type="button" value="删除" onClick="deleteRow(this)"></td>
                                        </tr>
                                     <? endforeach;?>
                                </table>
                                <a style="color:#3366CC" href="javascript:addRow()">为指定地区城市设置运费</a>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                                <input type="button" class="layui-btn" value="取消" onClick="window.history.go(-1)">
                            </div>
                        </div>
                    </div>
                </form>

        </div>
    </div>
    <script>
        shipping_js();
    </script>
</div>
        <div class="aqbox" id="divArea">
            <div class="aqalbox">
                <div class="topdq"><div class="title-wuliu">选择地区</div><a href="javascript:hideArea()">x</a></div>
                <ul  class="plabox">
                    <input type="hidden" value="1" id="tr_num" />
                    <?
                    $j=0;
                    foreach($regions as $i=>$result){
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
                <p><input type="button" value="确定" class="layui-btn layui-btn-mini" onClick="saveArea()"/>
                    <input type="button" value="取消" class="layui-btn layui-btn-mini" onClick="hideArea()"/></p>
            </div>
            <div class="clear"></div>
        </div>
<?php require 'footer.php';?>




