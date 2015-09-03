<?php


Class web_siteModel extends Model{

    function get_catelist($map){
    	if (empty($map)) {
    		exit('system error 0000');
    	}
    	return M('web_cate')->where($map)->find();
    }

    function get_cate($map){
    	if (empty($map)) {
    		exit('system error 0000');
    	}
    	return M('web_cate_view')->where($map)->find();
    }
}
?>