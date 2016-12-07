<?php require 'header.php';?>
<div class="page-content">
    <div class="rich_media_title"><?=$group->name?></div>
    <div class="rich_media_meta_list">
        <span class="date"><?=date('Y-m-d')?></span>
        <span class="nickname"><a href="weixin://contacts/profile/gh_eaa8b99402a9"><?= app('\App\Model\System')->getCode('webname'); ?></a></span>
    </div>
    <div class="content_txt">
        <?=nl2br($group->remark)?>
    </div>
    <div class="qrcode_div">
        <img src="<?=$qrcodeSrc?>" width="50%">
        <div>↑由 <?=$user->nickname?> 分享，长按二维码关注！</div>
    </div>
    <div class="shop_list">
        <? if(empty($shopList)) : ?>
            <div class='alert-warning'>周边商家正在加入中……</div>
        <? endif;?>
        <ul>
            <?
            //$shop=new \App\Model\PrintShop();
            foreach ($shopList as $shop) :
                //$shop=$shop->find($item['shop_id']);
                //if(! $shop->is_exist){continue;}
                if($shop->name==''){continue;}
                ?>
                <li class="clearFix">
                    <img class="img" src="<?=$shop->picture?>">
                    <div class="shop_info clearFix">
                        <div class="shop_title">
                            <?= $shop->name ?>
                        </div>
                        <div class="shop_remark">
                            <?= nl2br($shop->remark) ?><br>
                            电话：<?=$shop->tel?><br>
                            地址：<?=$shop->address?><br>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
</div>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?=$config?>);
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: '<?=$group->name?>',
                link: window.location.href,
                imgUrl: '<?=$group->picture?>',
                success: function () {
                },
                cancel: function () {
                }
            });
            wx.onMenuShareAppMessage({
                title: '<?=$group->name?>',
                desc: '<?=$group->remark?>',
                link: window.location.href,
                imgUrl: '<?=$group->picture?>',
                success: function () {
                },
                cancel: function () {
                }
            });
            wx.onMenuShareQQ({
                title: '<?=$group->name?>',
                desc: '<?=$group->remark?>',
                link: window.location.href,
                imgUrl: '<?=$group->picture?>',
                success: function () {
                },
                cancel: function () {
                }
            });
            wx.onMenuShareWeibo({
                title: '<?=$group->name?>',
                desc: '<?=$group->remark?>',
                link: window.location.href,
                imgUrl: '<?=$group->picture?>',
                success: function () {
                },
                cancel: function () {
                }
            });
            wx.onMenuShareQZone({
                title: '<?=$group->name?>',
                desc: '<?=$group->remark?>',
                link: window.location.href,
                imgUrl: '<?=$group->picture?>',
                success: function () {
                },
                cancel: function () {
                }
            });
        });
    </script>
<?php require 'footer.php';?>