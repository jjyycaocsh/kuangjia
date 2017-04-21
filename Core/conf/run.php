<?php
session_start();

class run {
	
    // 实例化对象
    static public function start($conf) {
		//获取加载USE 命名空间
		spl_autoload_register(function ($class) {
		if ($class) {
				$file = str_replace('\\', '/', $class);
				$file .= EXT;
				include_once CORE_PATH.$file;
			}
		});
		
        $path = array();
		
        if (!empty($_SERVER['PATH_INFO'])) {
            $PATH_INFO = $_SERVER['PATH_INFO'];
            $path = explode('/', $PATH_INFO);
        }

        if (count($path) > 3) {
            $g = $path[1];
            $c = $path[2];
            $m = $path[3];
        } else {
            $g = $conf['DEFAULT_MODULE'];
            $c = !empty($path[1]) ? $path[1] : $conf['DEFAULT_CONTROLLER'];
            $m = !empty($path[2]) ? $path[2] : $conf['DEFAULT_ACTION'];
			
        }
		$GLOBALS['Group'] = $g;
		$GLOBALS['Controller'] = $c;
		$GLOBALS['Method'] = $m;
        $file_path = APP_PATH . $g . '/' . $conf['DEFAULT_C_LAYER'] . '/' . $c . EXT;
		 $obj =   '\\'.$g.'\\'.$conf['DEFAULT_C_LAYER'].'\\'.$c;//导入命名空间
        self::getInfo($file_path, $c, $m,$obj);
    }

    static public function getInfo($file_path, $c, $m,$obj) {
		self::__autoload($file_path);
		$con = new  $obj();
		if(!method_exists($con,$m)){
			echo '未定义'.$m.'方法！';
			die();
		}
		
		$con->$m();
    }


    static public function __autoload($classname) {
        if (file_exists($classname)) {
            require_once($classname);
			
			
			
        } else {
            echo 'class file' . $classname . ' not found!';
			die();
        }
    }
 


}
