<?php
require 'header.php';
if($this->func=='index') :    ?>

    <div class="main_title">
        <span>租车</span>列表 <a href="<?= url('carRent/add') ?>" class="but1">添加</a>
    </div>
    <form method="get">
        <div class="search">
            用户ID：<input type="text" name="user_id" value="<?=$_GET['user_id']?>"/>
            联系人：<input type="text" name="contacts" value="<?=$_GET['contacts']?>"/>
            申请时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="layui-table" lay-even lay-skin="line">
        <thead>
        <tr>
            <th>添加人</th>
            <th>申请人</th>
            <th>联系地址</th>
            <th>所选车款</th>
            <th>首付</th>
            <th>尾付</th>
            <th>租期</th>
            <th>月付</th>
            <th>线下己付</th>
            <th>线上己扣</th>
            <th>添加时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <?
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->user_id?><br><?=$row->User()->username?></td>
                <td><?=$row->contacts?><br><?=$row->tel?></td>
                <td><?=$row->area?><br><?=$row->address?></td>
                <td><?=$row->car_name?></td>
                <td><?=$row->first_payment_scale*100?>% <br> ￥<?=$row->first_payment_money?></td>
                <td><?=$row->last_payment_scale*100?>% <br>￥<?=$row->last_payment_money?></td>
                <td><?=$row->time_limit?>月</td>
                <td>￥<?=$row->month_payment_money?><br>每月<?=$row->month_payment_day?>号</td>
                <td>￥<?=$row->money_linedown?></td>
                <td>￥<?=$row->money_yes?><br><?=$row->money_yes_at?></td>
                <td><?=$row->created_at?></td>
                <td><?=$row->getLinkPageName('rent_status',$row->status)?></td>
                <td>
                    <div class="layui-btn-group">
                        <? if($row->status==0 || $row->status==2) : ?>
                            <a href="<?= url("carRent/checked/?id={$row->id}") ?>" class="layui-btn layui-btn-small">信审</a>
                        <? endif;?>
                        <? if ($row->status !=5) : ?>
                            <a href="<?= url("carRent/edit/?id={$row->id}") ?>" class="layui-btn layui-btn-small">编辑</a>
                            <a href="<?= url("carRent/deductMoney/?id={$row->id}") ?>" class="layui-btn layui-btn-small">扣除车款</a>
                        <? endif;?>
                        <? if($row->status ==1 && ($row->money_yes !=0 || $row->money_linedown!=0)) : ?>
                            <a href="javascript:goRepayment(<?=$row->id?>)" class="layui-btn layui-btn-small">生成还款列表</a>
                        <? endif;?>
                        <? if($row->status ==5) : ?>
                            <a href="<?= url("carRent/repayment/?id={$row->id}") ?>" class="layui-btn layui-btn-small">还款列表</a>
                        <? endif;?>
                    </div>
                </td>
            </tr>
        <? }?>
    </table>
    <? if (empty($result['total'])) {
    echo "无记录！";
} else {
    echo $result['page'];
} ?>
    <script>
        function goRepayment(id)
        {
            layer.open({
                content: '生成还款列表，将不可编辑？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    location.href='<?= url("carRent/repayment/?id=") ?>'+id;
                    layer.close(index);
                }
            });
        }
    </script>
    <?
elseif($this->func=='repayment') :
    $arr_status=array('','待付款','己还款');
    ?>
    <div class="main_title">
        <span>租车</span> <a href="<?= url('carRent') ?>" class="but1">列表</a>
    </div>
    <blockquote class="layui-elem-quote">
        车款：<?=$carRent->car_name?><br>
        申请人：<?=$carRent->contacts?><br>
        联系电话：<?=$carRent->tel?><br>
        地址：<?=$carRent->area?>-<?=$carRent->address?><br>
    </blockquote>

    <blockquote class="layui-elem-quote">
        添加人ID：<?=$user->id?><br>
        添加人：<?=$user->username?><br>
        可用余额：￥<?=$account->funds_available?><br>
        可用积分：<?=$account->integral_available?><br>
    </blockquote>
    <table class="layui-table" lay-even lay-skin="line">
        <thead>
        <tr>
            <th>用户ID</th>
            <th>说明</th>
            <th>应还</th>
            <th>己还</th>
            <th>应还日期</th>
            <th>还款时间</th>
            <th>逾期天数</th>
            <th>逾期利息</th>
            <th>管理员ID</th>
            <th>备注</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($repayments as $repayment) : ?>
            <tr>
                <td><?=$repayment->user_id?></td>
                <td><?=$repayment->title?></td>
                <td>￥<?=$repayment->money?></td>
                <td>￥<?=$repayment->money_yes?></td>
                <td><?=substr($repayment->	repayment_time,0,10)?></td>
                <td><?=$repayment->	repaymented_at?></td>
                <td><?=$repayment->last_days?></td>
                <td>￥<?=$repayment->last_interest?></td>
                <td><?=$repayment->verify_userid?></td>
                <td><?=nl2br($repayment->verify_remark)?></td>
                <td><?=$arr_status[$repayment->status]?></td>
                <td>
                    <? if($repayment->status==2) : ?>
                        <span class="layui-btn layui-btn-mini layui-btn-disabled">己还</span>
                    <? else : ?>
                        <a class="layui-btn layui-btn-mini" href="<?=url("carRent/repaymentPay/?repay_id={$repayment->id}")?>">还款</a>
                    <? endif;?>
                </td>
            </tr>
        <? endforeach;?>
        </tbody>
    </table>
<?
endif;
require 'footer.php';?>