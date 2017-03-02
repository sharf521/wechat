<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php if($this->title!=''){echo $this->title.'-';}?><?=$this->site->name;?></title>
    <script src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.0/weui.css"/>
    <link rel="stylesheet" href="/plugin/Swiper/css/swiper.min.css"/>
    <script src="/plugin/Swiper/js/swiper.min.js"></script>
    <link rel="stylesheet" href="/themes/base_wap.css?<?=rand(1000,9999)?>"/>
    <link rel="stylesheet" href="/themes/default_wap/default.css?<?=rand(1000,9999)?>"/>
    <script src="/themes/default_wap/default.js"></script>
</head>
<body ontouchstart>