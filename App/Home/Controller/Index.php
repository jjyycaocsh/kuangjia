<?php  namespace Home\Controller;
  use MyCore\my;  class Index{
		public function index(){			$data = array('name'=>'CSH','say'=>'哈哈哈');		 view('index',$data);
	} }

 