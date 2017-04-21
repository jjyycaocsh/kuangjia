 <?php

// 应用入口文件
header("Content-type: text/html; charset=utf-8");
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_PATH','./App/');
define('PUBLIC_PATH','./Public/');
define('BASE_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/')) . "/");
// 引入入口文件
require './Core/core.php';
