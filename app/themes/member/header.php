<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if($this->title!=''){echo $this->title.'-';}?><?=$this->site->name;?></title>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
    <script src="/themes/member/member.js"></script>
    <link rel="stylesheet" href="/themes/base.css"/>
    <link href="/themes/member/member.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="usernav">
    <div class="userlogo">
        <div class="logoleft">
            <a href="/"><img src="<?=$this->site->logo?>" height="60"></a>
        </div>
        <div class="usermenu">
            <ul>
                <li>
                    <a href="<?=url('')?>">个人中心</a>
                </li>
                <li>
                    <a href="<?=$this->site->center_url?>" target="_blank">支付中心</a>
                </li>
                <li>
                    <a href="<?=url('logout')?>">退出</a>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
</div>