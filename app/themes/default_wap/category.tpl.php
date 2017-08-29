<?php require 'header.php';?>
    <!-- 搜索框 -->
    <div class="searchBox clear">
        <form action="/goods/lists/">
            <div class="search-input">
                <i class="iconfont">&#xe634;</i>
                <input type="text" name="keyword" value="<?=$_GET['keyword']?>" placeholder="搜索全部商品">
            </div>
            <button>搜索</button>
        </form>
    </div>

    <div class="Menu_box clearFix">
        <!-- 左侧栏商品分类 -->
        <div class="left_Menu">
            <ul>
                <?
                foreach ($cates as $cate) {
                    ?>
                    <li><a href="javascript:iscrollto('<?= $cate['id'] ?>');"><span><?= $cate['name'] ?></span></a></li>
                    <?
                }
                ?>
            </ul>
        </div>
        <!-- 右边商品列表 -->
        <div class="right_Menu">
            <div>
                <? foreach ($cates as $cate) : ?>
                    <p id="c_<?= $cate['id'] ?>"><a href="/goods/lists/<?= $cate['id'] ?>"><?= $cate['name'] ?></a></p>
                    <ul>
                        <? if(isset($cate['son']) && is_array($cate['son'])) : ?>
                            <? foreach ($cate['son'] as $sun) :
                                $pic=$row['picture'];
                                if(empty($pic)){
                                    $pic='/themes/images/nopic.gif';
                                }
                                ?>
                                <li><a href="/goods/lists/<?=$sun['id']?>"><img src="<?=$pic?>"><span><?=$sun['name']?></span></a></li>
                            <? endforeach;?>
                        <? endif;?>
                    </ul>
                <? endforeach;?>
            </div>
        </div>
    </div>
    <script src="/plugin/js/iscroll-lite.js" language="javascript"></script>
    <script>
        var myScroll2;
        window.onload = function () {
            var myScroll = new IScroll('.left_Menu', {
                click: true,
                mouseWheel: true,
                scrollbars: true
            });
            myScroll2 = new IScroll('.right_Menu', {
                click: true,
                mouseWheel: true,
                scrollbars: true
            });
            $('.left_Menu').height($(window).height() - 90);
            $('.right_Menu').height($(window).height() - 90);
        }
        function iscrollto(id) {
            myScroll2.scrollToElement('#c_' + id);
        }
        $(function () {
            $('.left_Menu ul').children().click(function (event) {
                $(this).addClass('cur-nav').siblings().removeClass('cur-nav');
            });

        })
    </script>
<?php require 'footer_bar.php';?>
<?php require 'footer.php';?>