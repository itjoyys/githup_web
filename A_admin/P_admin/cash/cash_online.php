<?php 

include_once("../../include/config.php");
include_once("../common/login_check.php");

if ($_GET['act'] == 'cash_delete') {
	$result = '';
	$result = M('k_user_bank_in_record',$db_config)->field('in_date,id')->where("site_id = '".SITEID."' and make_sure = 0 and into_style = 2")->select();
	
	if($result){
		foreach ($result as $key => $value) {
			$data = time() - strtotime($value['in_date']);
			if($data > 1800){
				$info = array();
				$info['make_sure'] = 2;
				$info['do_time'] = date('Y-m-d H:i:s');
				$table = M('k_user_bank_in_record',$db_config);
				$table->where("site_id = '".SITEID."' and make_sure = 0 and into_style = 2 and id = '".$value['id']."'")->update($info);
			}
		}
	}
	
}

?>