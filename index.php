<?php
$t1 = microtime(true);
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(7);
header('Content-language: zh');
header('Content-type: text/html; charset=utf-8');
header('X-Powered-By: JAVA');
header('Pragma: no-cache');
header('Cache-Control: private',false); // required for certain browsers
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

//获取域名
$domain=strtolower($_SERVER['HTTP_HOST']);
if(strpos($domain,':')!==false){
    //去除端口
    $domain=explode(':',$domain);
    $domain=$domain[0];
}
$domain_arr=explode('.',$domain);
/*if($domain_arr[count($domain_arr)-2]=='com'){
    $domain=$domain_arr[count($domain_arr)-3].'.'.$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
}else{
    $domain=$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
}*/
//session_start();之前设置  php.ini 里 session.auto_start=0
//ini_set('session.cookie_domain', $domain);//域名不需要端口
//ini_set("session.save_handler", "redis");
//ini_set("session.save_path", "tcp://127.0.0.1:6379");
session_cache_limiter('private,must-revalidate');
session_start();
date_default_timezone_set('Asia/Shanghai');
set_time_limit($set_time = 3600);

define('ROOT', __DIR__);

require 'vendor/autoload.php';
require 'function.php';

\System\Lib\DB::instance(\App\Config::$db1);

$pager = app('\System\Lib\Page');

//weixin 验证
//$options = [
//    'debug' => true,
//    'app_id' => 'wxf2d48e37c9ee3c05',
//    'secret' => '678477b3fbb4081b048df279cc38be5e',
//    'token' => 'print',
//    // 'aes_key' => null, // 可选
//    'log' => [
//        'level' => 'debug',
//        'file' => ROOT . '/public/easywechat.log', // XXX: 绝对路径！！！！
//    ]
//];
//$app = new \EasyWeChat\Foundation\Application($options);
//$app->server->serve()->send();
//exit;


$routes=array(
    'chat'=>'Chat',
    'car'=>'Car',
    'api'=>'Api',
    'member'=>'Member',
    'sellManage'=>'SellManage',
    'supplyManage'=>'SupplyManage'
);
\System\Lib\Application::start($routes);
$t2 = microtime(true);
//echo '<hr>耗时'.round($t2-$t1,3).'秒';