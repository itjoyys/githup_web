<?php

class Router {
	public static $loader;
	public static function init() {
		if (self::$loader == NULL) {
			self::$loader = new self();
		}

		return self::$loader;
	}

	public function __construct() {
		//TODO 从config文件 初始化Controller数组
		$ControllerArr = array("common");

		$router = array(
				"Controller" => @$_GET["c"],
				"Action" => @$_GET["a"],
		);
		
		if (empty($router["Controller"]) && !in_array('index',$router)) {
		//if (empty($router["Controller"]) && !in_array($router["Controller"], haystack)) {
			$router["Controller"] = "common";
		}
		// ||!in_array($router["Action"],get_class_methods($router["Controller"]))
		if (empty($router["Action"])) {
			$router["Action"] = "index";
		}
		$router["Controller"] = ucfirst(strtolower($router["Controller"]));
		$router["Action"] = ucfirst(strtolower($router["Action"]));

		$controller = new $router["Controller"];

		$controller->$router["Action"]();
	}
}
?>