<?php

class View extends Smarty {

	function __construct() {
		parent::__construct();
		$this->template_dir = TEMPLATE_DIR; //模板路径
		$this->compile_dir = COMPILE_DIR; //编译后文件
		$this->config_dir = CONFIG_DIR; //配置文件
		$this->cache_dir = CACHE_DIR; //缓存文件
		//$this->caching = true;
		//$this->debugging = TRUE;
		//$this->cache_lifetime = 200;
		$this->left_delimiter = "<{";
		$this->right_delimiter = "}>";
	}

	function show($name, $cacheId = '') {
		if ($cacheId == '') {
			$this->display($name . '.html');
		} else {
			$this->display($name . '.html', $cacheId);
		}
	}

	function add($name, $value) {
	    $this->assign($name, $value);
	}

	function parse($tpl_var, $resource_name) {
	    
		$this->assign($tpl_var, $this->fetch($resource_name));
	}

}

?>