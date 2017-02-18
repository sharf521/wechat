<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1><?=$this->title?></h1>
    </div>

    <div class="clearFix margin_header">
        <ul class="carRentList">
            <? foreach ($result['list'] as $rent) : ?>
                <li>
                        <span class="imgspan">
                            <img src="/themes/images/blank.gif" data-echo="<?=$rent->car_picture?>">
                        </span>
                        <div class="info">
                            <p class="cd_title"><?=$rent->car_name?></p>
                            <p>
                                首付：<span><?=$rent->first_payment_money/10000?>万</span>
                                <?=$rent->time_limit?>期 <span><?=(float)$rent->month_payment_money?>元/期</span>
                                <?
                                if((float)$rent->last_payment_money!=0){
                                    echo "尾付：". $rent->first_payment_money/10000 .'万';
                                }
                                ?>
                            </p>
                            <p class="contacts"><?=$rent->contacts?> <?=$rent->tel?></p>
                            <p>
                                <a href="<?=url("rent/editUpload/?id={$rent->id}")?>" class="weui-btn weui-btn_mini weui-btn_primary">上传资料</a>
                            </p>
                        </div>
                </li>
            <? endforeach;?>
        </ul>

        <? if($result['total']==0) : ?>
            <div class="weui-msg">
                <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg-primary"></i></div>
                <div class="weui-msg__text-area">
                    <h2 class="weui-msg__title">没有匹配到任何记录！</h2>
                    <p class="weui-msg__desc"></p>
                </div>
            </div>
        <? else : ?>
            <?=$result['page'] ;?>
        <? endif;?>
    </div>
<?php require 'footer.php';?>