<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$config['template_dir'] = VIEWPATH;
$config['compile_dir'] = APPPATH .'/../../../../../cache/'.SITEID.INDEX_ID. 'cache/templates_c';
$config['cache_dir'] = APPPATH .'/../../../../../cache/'.SITEID.INDEX_ID. 'cache';
$config['config_dir'] = APPPATH . 'config';
$config['template_ext'] = '.html';
$config['left_delimiter'] = "<{";
$config['right_delimiter'] = "}>";
$config['caching'] = false;
$config['lefttime'] = 0;
?>