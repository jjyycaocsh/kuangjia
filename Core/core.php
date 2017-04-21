<?php

/**
*创建工作目录
*导入运行文件
*
*/
//创建工作目录
is_dir(APP_PATH) ? '' : mkdir(APP_PATH);
//创建公共文档
is_dir(PUBLIC_PATH) ? '' : mkdir(PUBLIC_PATH);
defined('CORE_PATH') or define('CORE_PATH', __DIR__ . '/');

// 类文件后缀
const EXT = '.php';
// 页面后缀
$conf = require_once BASE_PATH . '/Core/conf/convention'.EXT;
if (is_dir(APP_PATH)) {
    defined('RUNTIME_PATH') or define('RUNTIME_PATH', APP_PATH . $conf['RUNTIME_PATH'] . '/');   // 系统运行时目录
    is_dir(RUNTIME_PATH) ? '' : mkdir(RUNTIME_PATH);
//    defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'Logs/'); // 应用日志目录
//    is_dir(LOG_PATH) ? '' : mkdir(LOG_PATH);
//    defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'Temp/'); // 应用缓存目录
//    is_dir(TEMP_PATH) ? '' : mkdir(TEMP_PATH);
//    defined('DATA_PATH') or define('DATA_PATH', RUNTIME_PATH . 'Data/'); // 应用数据目录
//    is_dir(DATA_PATH) ? '' : mkdir(DATA_PATH);
//    defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'Cache/'); // 应用模板缓存目录
//    is_dir(CACHE_PATH) ? '' : mkdir(CACHE_PATH);
    defined('DEFAULT_MODULE') or define('DEFAULT_MODULE', APP_PATH . $conf['DEFAULT_MODULE'] . '/'); // 默认组
    is_dir(DEFAULT_MODULE) ? '' : mkdir(DEFAULT_MODULE);
    defined('DEFAULT_C_LAYER') or define('DEFAULT_C_LAYER', DEFAULT_MODULE . $conf['DEFAULT_C_LAYER'] . '/'); //控制器
    is_dir(DEFAULT_C_LAYER) ? '' : mkdir(DEFAULT_C_LAYER);
	defined('SHOW_VIEW') or define('SHOW_VIEW', DEFAULT_MODULE . $conf['SHOW_VIEW'] . '/'); //HTML 目录
    is_dir(SHOW_VIEW) ? '' : mkdir(SHOW_VIEW);
	defined('Index_VIEW') or define('Index_VIEW', SHOW_VIEW . $conf['DEFAULT_CONTROLLER'] . '/'); //Index目录
    is_dir(Index_VIEW) ? '' : mkdir(Index_VIEW);
    //写入默认控制器
    if (!is_file(DEFAULT_C_LAYER . $conf['DEFAULT_CONTROLLER'] . EXT)) {
        $myfile = fopen(DEFAULT_C_LAYER . $conf['DEFAULT_CONTROLLER'] . EXT, "w") or die("Unable to open file!");
        $txt = "<?php\n\r  namespace Home\\Controller;\n\r  class ".$conf['DEFAULT_CONTROLLER']."{\n\r    public function ".$conf['DEFAULT_ACTION']."(){\n        echo '欢迎使用';\n}\n\r }\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
	//写入默认控制器
    if (!is_file(Index_VIEW . $conf['DEFAULT_CONTROLLER'] . '.html')) {
        $myfile = fopen(Index_VIEW . $conf['DEFAULT_CONTROLLER'] . '.html', "w") or die("Unable to open file!");
        $txt = "<!DOCTYPE html>\n<html>\n\t<head lang='en'>\n\t\t<meta charset='UTF-8'>\n\t\t<title>".$conf['DEFAULT_CONTROLLER']."</title>\n\t</head>\n\t<body>\n\t\t<div>欢迎使用</div>\n\t</body>\n</html>";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
	
	
    defined('DEFAULT_COMM') or define('DEFAULT_COMM', APP_PATH . 'Common/'); // 默认公共目录
    is_dir(DEFAULT_COMM) ? '' : mkdir(DEFAULT_COMM);
	defined('COMM_COMM') or define('COMM_COMM', DEFAULT_COMM . 'Common/'); // 默认公共函数目录
    is_dir(COMM_COMM) ? '' : mkdir(COMM_COMM);
    defined('COMM_CONF') or define('COMM_CONF', DEFAULT_COMM . 'Conf/'); // 默认公共配置目录
    is_dir(COMM_CONF) ? '' : mkdir(COMM_CONF);
}
//公共文件下Common
if (is_dir(PUBLIC_PATH)) {
    is_dir(PUBLIC_PATH . $conf['PUBLIC_IMG']) ? '' : mkdir(PUBLIC_PATH . $conf['PUBLIC_IMG']);
    is_dir(PUBLIC_PATH . $conf['PUBLIC_CSS']) ? '' : mkdir(PUBLIC_PATH . $conf['PUBLIC_CSS']);
    is_dir(PUBLIC_PATH . $conf['PUBLIC_JS']) ? '' : mkdir(PUBLIC_PATH . $conf['PUBLIC_JS']);
    is_dir(PUBLIC_PATH . $conf['PUBLIC_UPLOAD']) ? '' : mkdir(PUBLIC_PATH . $conf['PUBLIC_UPLOAD']);
}

//$cls = new ReflectionClass('/my');
		//;
require_once CORE_PATH.'conf/run'.EXT;
//加载公共函数
include APP_PATH.'./../Core/MyCore/common.php';


run::start($conf);
