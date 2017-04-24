<?php
namespace MyCore;

class template{
	//模板文件存放位置
   private $template_dir = '2';
 
   //编译文件存放位置
   private $compile_dir = '1';
 
   //左定界符
   private $left_delimiter = '{';
 
   //右定界符 
   private $right_delimiter = '}'; 
 
   //内部临时变量，存储用户赋值
   private $tpl_vars = array();
   private $tpl_arr = array();
 
   public function __construct(){
	    $config = getConfig(array('TAGLIB_BEGIN','TAGLIB_END','SHOW_VIEW','RUNTIME_PATH'));
	    $config['template_dir'] =  APP_PATH.$GLOBALS['Group'].'/'.$config['SHOW_VIEW'].'/';
		$config['compile_dir'] =  APP_PATH.$config['RUNTIME_PATH'].'/Cache/'.$GLOBALS['Group'];
		$config['left_delimiter'] = $config['TAGLIB_BEGIN'];
		$config['right_delimiter'] = $config['TAGLIB_END'];
		$arr = $this->setConfigs($config);
		

   }
    /**
    * 修改类属性的值
    * @param array $configs 需要修改的相关属性及值
    * @return bool
    */
   private function setConfigs(array $configs){
     if(count($configs) > 0){
       foreach ($configs as $k => $v) {
         if(isset($this->$k))
           $this->$k = $v;
       }
       return true;
     }
     return false;
   }
/**
*渲染页面模板 未完无参数
*@param string $fileName 模板名(目录/文件名)
*@param array $data 数据
*/
 public function display($fileName='',$data=array()){
	 $arr = explode('/',$fileName);
	 $count = count($arr);
	
	 if(empty($count) or empty($fileName)){
		 $views = $this->template_dir.$GLOBALS['Controller'].'/'.$GLOBALS['Method'].'.html';
	 }elseif($count == 1){
		  $views = $this->template_dir.$GLOBALS['Controller'].'/'.$arr[0].'.html';
	 }elseif($count > 1){
		  $views = $this->template_dir.$fileName.'.html';
	 }
	 //判断模板是否存在
	 if(!file_exists($views)){
		 throw new \Exception('模板文件不存在！');
	 }

	 $this->assign($data);
     //编译后的文件
     $comFile = $this->compile_dir.'/'.md5($fileName).'.php';
     $this->fetch($views,$comFile);
	 //判断编译文件是否存在
	 if(file_exists($comFile)){
		 include $comFile;
	 }
}
/**
  * 生成编译文件
  * @param string $tplFile 模板路径
  * @param string $comFile 编译路径
  * @return string
 */
private function fetch($tplFile,$comFile){

  //判断编译文件是否需要重新生成(编译文件是否存在或者模板文件修改时间大于编译文件的修改时间)
  if(!file_exists($comFile) || filemtime($tplFile) > filemtime($comFile)){
	  if(!file_exists($this->compile_dir)){
		mkdir($this->compile_dir, 0777, true);
        chmod($this->compile_dir,0777);
	}

    //编译,使用ob_start()进行静态化
	ob_start();
    echo $content = $this->tplReplace(file_get_contents($tplFile));
	$jintai = ob_get_contents();
	ob_end_clean();
    file_put_contents($comFile, $jintai);
  }
 
} 

	/**
     * 模板赋值操作
     * @param array $tpl_var 数组，就循环赋值
     */
    private function assign($tpl_var){
		if(is_array($tpl_var) && count($tpl_var) > 0){
			foreach ($tpl_var as $k => $v) {
			  $this->tpl_vars[$k] = $v;
			}
		}
    }

/**
    * 编译文件
    * @param string $content 待编译的内容
    * @return string
    */
   private function tplReplace($content){
     //转义左右定界符 正则表达式字符
     $this->left = preg_quote($this->left_delimiter,'/');
     $this->right = preg_quote($this->right_delimiter,'/');
 
     //简单模拟编译 变量
     $variable = array(
         '/'.$this->left.'\$([^{}]*)'.$this->right.'/i'
       );
	   //循环编译
	    $array = array(
         '/'.$this->left.'foreach[^>]* name=(\"|\')(.*)(\"|\')[^>]* id=(\"|\')(.*)(\"|\')'.$this->right.'(.*)'.$this->left.'\/foreach'.$this->right.'/isU',
       );
     //正则处理
	 
	$step1 =  preg_replace_callback($array, function($matches){
		$variable = array(
		 '/'.$this->left.'\$([^{}]*)'.$this->right.'/i'
       );
	   $this->tpl_arr['name'] = $matches[2];
		$step2 =  preg_replace_callback($variable, function($matches){
			return "<?php echo \$".$matches[1]."; ?>";
		 }, trim($matches[7]));

		 $str = "<?php foreach(\$this->tpl_vars['".$matches[2]."'] as \$k=>\$".$matches[5]."): ?>\n\r";
		  $str .= $step2;
		 $str .= "<?php endforeach; ?>";
		 return $str;
	}, $content);
	 
     $step2 =  preg_replace_callback($variable, function($matches){
			$variable = array(
				'/([^\[\]]*)\[{0,1}([^\[\]]*)\]{0,1}/i'
		   );
		   $step2 =  preg_replace_callback($variable, function($matches){
				$matche = array_filter($matches);
				if(!empty($matche)){
					unset($matche[0]);
					return $this->oneArray($matche);
				}
		 }, trim($matches[1]));
		 if(!empty($step2)){
			 return "<?php echo \$this->tpl_vars".$step2."; ?>";
		 }
	 }, $step1);

	 $html = $step2;
	 return $html;
   }

private function oneArray($arr){
	$str = '';
	$mao = "/\"|\'/";
	foreach($arr as $v){
		$s = preg_replace($mao,'',$v);
		$str .= "['".$s."']";
	}
	return $str;
}
   
   
   
}


