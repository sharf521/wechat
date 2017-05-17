<?php require 'header.php';?>
    <div class="layui-main topnav">
        <span class="layui-breadcrumb"><?=$topnav_str?></span>
    </div>

    <div class="layui-main container">
        <div class="sidebar">

        </div>
        <div class="main">
            <div class="goodsSearch">
                <form method="get" id="searchForm">
                    <input type="hidden" name="orderBy" id="orderBy" value="<?=$_GET['orderBy']?>">
                    <span class="orderBy <? if($_GET['orderBy']=='sale_count'){echo 'active';}?>" data_val="sale_count">销量</span>
                    <span class="orderBy <? if($_GET['orderBy']=='id'){echo 'active';}?>" data_val="id">新品</span>
                    价格：<input type="text" name="minPrice" placeholder="￥" size="5" value="<?=$_GET['minPrice']?>"> -
                    <input type="text" name="maxPrice" placeholder="￥" size="5" value="<?=$_GET['maxPrice']?>">&nbsp;&nbsp;
                    <input type="text" name="keyword" value="<?=$_GET['keyword']?>" placeholder="请输入关键字">
                    <input type="submit" value="搜索">
                </form>
            </div>
            <script>
                $(function () {
                    $('.orderBy').on('click',function () {
                        $('#orderBy').val($(this).attr('data_val'));
                        $('#searchForm').submit();
                    });
                });
            </script>
            <ul class="goods_list clearFix">
<? foreach ($result['list'] as $goods) :
    $shop=$goods->Shop();
    ?>
                <li>
                    <div class="goods-content" nctype_goods=" 128076" nctype_store="209">
                        <div class="goods-pic"><a href="<?=url("/purchase/detail/{$goods->id}")?>" target="_blank"><img src="/themes/images/blank.gif" data-echo="<?=$goods->image_url?>"></a></div>
                        <div class="goods-info" style="top: 230px;">
                            <div class="goods-name"><a href="<?=url("/purchase/detail/{$goods->id}")?>" target="_blank"><?=$goods->name?></a></div>
                            <div class="goods-price"> <em class="sale-price">¥<?=$goods->retail_price?></em>
                                <!--<em class="market-price">¥0.00</em>-->  </div>
                            <div class="sell-stat">
                                <ul>
                                    <li><span class="status"><?=$goods->sale_count?></span>
                                        <p>商品销量</p>
                                    </li>
                                    <li><span><?=$goods->stock_count?></span>
                                        <p>商品库存</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="store"><a><?=$shop->name?></a></div>
                        </div>
                    </div>
                </li>
<? endforeach;?>
            </ul>
            <? if($result['total']==0) : ?>
                <div class="no-result"><i></i>没有找到符合条件的商品</div>
            <? else: ?>
                <?=$result['page']?>
            <? endif;?>
        </div>
    </div>



<?php require 'footer.php';?>