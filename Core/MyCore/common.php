<?php

/**
*获取配置文件
*@param mixed $k 获取KEY
*/
function getConfig($k=''){
	$path1 = APP_PATH.$GLOBALS['Group'].'/Conf/config.php';
	$path2 = APP_PATH.'Common/Conf/config.php';
	$path3 = APP_PATH.'./../Core/conf/convention.php';
	$arr1 = array();
	$arr2 = array();
	$arr3 = array();
	if(file_exists($path1)){
		$arr1 = include $path1;
	}
	if(file_exists($path2)){
		$arr2 = include $path2;
	}
	if(file_exists($path3)){
		$arr3 = include $path3;
	}
	$config = $arr1+$arr2+$arr3;
	if(empty($k)){
		return $config;
	}
	
	if(is_array($k)){
		$result = array();
		foreach($k as $v){
			$result[$v] = $config[$v];
		}
		return $result;
	}
	return $config[$k];
}

function view($fileName='',$data=array()){
	$tp = new \MyCore\template();//模板
	$tp->display($fileName='',$data);
}

/**
*$_SESSION 简写
*/
function session($k,$arr=array()){
	if(empty($arr)){
		return $_SESSION[$k];
	}else{
		$_SESSION[$k]=$arr;
	}
	
}
