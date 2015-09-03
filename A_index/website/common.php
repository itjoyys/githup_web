<?php
define("WEBSITE_ROOT", dirname(__FILE__));

//配置文件
require_once WEBSITE_ROOT . '/config/smarty_config.php';
//Smarty类
require_once WEBSITE_ROOT . '/libs/Smarty/Smarty.class.php';
require_once dirname(dirname(__FILE__)) . '/video/games/Games.class.php';
spl_autoload_register("__autoload"); // 添加这段代码
/*
require_once WEBSITE_ROOT . '/libs/class/View.class.php';
require_once WEBSITE_ROOT . '/libs/class/Router.class.php';
require_once WEBSITE_ROOT . '/libs/class/Controller.class.php';
require_once WEBSITE_ROOT . '/controller/Common.controller.php';
 */

function __autoload($class_name) {
	//echo WEBSITE_ROOT . '/controller/' . ucfirst($class_name) . '.controller.php';
	$class_name = preg_replace('/_controller$/ui', '', $class_name);
	if (file_exists(WEBSITE_ROOT . '/controller/' . ucfirst($class_name) . '.controller.php')) {
		require_once WEBSITE_ROOT . '/controller/' . ucfirst($class_name) . '.controller.php';
	}
	if (file_exists(WEBSITE_ROOT . '/libs/class/' . ucfirst($class_name) . '.class.php')) {
		require_once WEBSITE_ROOT . '/libs/class/' . ucfirst($class_name) . '.class.php';
	}
}

?>