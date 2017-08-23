<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>商品列表</h1>
    </div>
    <div class="margin_header"></div>
    <div class="searchBox clear">
        <form action="/goods/lists/">
            <div class="search-input">
                <i class="iconfont">&#xe634;</i>
                <input type="text" name="keyword" value="<?=$_GET['keyword']?>" placeholder="搜索全部商品">
            </div>
            <button>搜索</button>
        </form>
    </div>
    <ul class="commoditylist_content my-commoditylist">
        <? foreach ($result['list'] as $goods) : ?>
            <li>
                <a href="<?=url("/goods/detail/{$goods->id}")?>">
                    <div class="info ">
                        <span class="imgspan">
                            <img src="themes/images/blank.gif" data-echo="<?=\App\Helper::smallPic($goods->image_url)?>">
                        </span>
                        <p class="cd_title"><?=$goods->name?></p>
                        <div class="info-bottom  clearFix">
                            <p class="cd_money">
                                <span>￥</span><var><?=(float)$goods->price?></var>
                            </p>
                            <p class="cd_sales">库存 <?=$goods->stock_count?></p>
                            <p class="cd-payment">付款10人</p>
                        </div>
                    </div>
                </a>
            </li>
        <? endforeach;?>
    </ul>
<? if($result['total']==0) : ?>
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">没有任何记录！</h2>
            <p class="weui-msg__desc"></p>
        </div>
    </div>
<? else: ?>
    <?=$result['page']?>
<? endif;?>
    <script type="text/javascript">
        $(function(){
            var $searchBar = $('#searchBar'),
                $searchResult = $('#searchResult'),
                $searchText = $('#searchText'),
                $searchInput = $('#searchInput'),
                $searchClear = $('#searchClear'),
                $searchCancel = $('#searchCancel');

            function hideSearchResult(){
                $searchResult.hide();
                $searchInput.val('');
            }
            function cancelSearch(){
                hideSearchResult();
                $searchBar.removeClass('weui-search-bar_focusing');
                $searchText.show();
            }

            $searchText.on('click', function(){
                $searchBar.addClass('weui-search-bar_focusing');
                $searchInput.focus();
            });
            $searchInput
                .on('blur', function () {
                    if(!this.value.length) cancelSearch();
                })
                .on('input', function(){
                    if(this.value.length) {
                        $searchResult.show();
                    } else {
                        $searchResult.hide();
                    }
                })
            ;
            $searchClear.on('click', function(){
                hideSearchResult();
                $searchInput.focus();
            });
            $searchCancel.on('click', function(){
                cancelSearch();
                $searchInput.blur();
            });
        });
    </script>

<?php require 'footer_bar.php';?>
<?php require 'footer.php';?>