<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");

/**************传中文解码***************/
	  function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}
  function JSON($array) {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }



if(!empty($_POST['action']) && $_POST['action'] == 'bank' && $_POST['id']==""){
	$bank_all = M('k_bank',$db_config)->where("site_id = '".SITEID."' and is_delete = '0' and id in (".$_POST['bank_set'].")")->select();
	$value=array();
	foreach ($bank_all as $k => $v) {
		$value[$k]['id']=$v['id'];
		$value[$k]['bank_type']=bank_type($v['bank_type']);
		$value[$k]['card_ID']=$v['card_ID'];
		$value[$k]['val']='checked';
	}

	echo JSON($value);

}else if(!empty($_POST['action']) && $_POST['action'] == 'bank' && !empty($_POST['id'])){

	$bank_all = M('k_bank',$db_config)->where("site_id = '".SITEID."' and is_delete = '0' and cate = '".$_POST['id']."'")->select();
	$value=array();
	$arr = explode(",", $_POST['bank_set']);
	foreach ($bank_all as $k => $v) {

		if(!in_array($v['id'],$arr)){
			$value[$k]['id']=$v['id'];
			$value[$k]['bank_type']=bank_type($v['bank_type']);
			$value[$k]['card_ID']=$v['card_ID'];
		}else{
			$value[$k]['id']=$v['id'];
			$value[$k]['bank_type']=bank_type($v['bank_type']);
			$value[$k]['card_ID']=$v['card_ID'];
			$value[$k]['val']='checked';
		}
	
		
	}

	echo JSON($value);
}
	


