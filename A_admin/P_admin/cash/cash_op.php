<?php
 include_once("../../include/config.php");
 include_once("../common/login_check.php");

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




/****************************************************************/
//into_style 1表示公司入款 2 表示线上入款
//make_sure 0表示未处理 1表示确定 2表示取消
 //公司入款
 $b = M('k_user_bank_in_record',$db_config);
if ($_GET['act'] == 'bank') {
    $data = '';
	/*if($_GET['op']==1){
		$id = $_GET['id'];
		$dat['make_sure']=$_GET['status'];
		if($b->where("id='".$id."'")->update($dat)){
			echo 1;
			exit();
		}else{
			echo 2;
			exit();
		}

	}*/
   //公司入款
    $map = "k_user_bank_in_record.site_id = '".SITEID."' and k_user_bank_in_record.make_sure = '0' and k_user_bank_in_record.into_style = '1'";
    $bank_data = $b->join('left join k_user_level on k_user_level.id = k_user_bank_in_record.level_id left join k_bank on k_bank.id = k_user_bank_in_record.bid')->field("k_user_bank_in_record.*,k_user_level.level_des,k_bank.card_ID,k_bank.card_userName,k_bank.card_address")->order('id desc')->where($map)->select();

    foreach ($bank_data as $key => $val) {

    	$bank_data[$key]['bank_name'] = bank_type($val['bank_style']);//银行
    	$bank_data[$key]['bank_fs'] = in_type($val['in_type']);
    }

    //判断是否有新入款
    $new_down_state = '';
    $new_down_state = M('k_user_bank_in_record',$db_config)->field("id")
                       ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '1'")
                       ->find();
    if (!empty($new_down_state)) {
        $data['status'] = 1;
    }else{
        $data['status'] = 0;
    }
    $data['info'] = $bank_data;
     echo JSON($data);
     exit;
}











/***************************************************************/
//线上入款
 $b = M('k_user_bank_in_record',$db_config);
if ($_GET['act'] == 'online') {
    $data = '';
	if($_GET['op']==1){
		$id = $_GET['id'];
		$dat['make_sure']=$_GET['status'];
        $dat['admin_user']=$_SESSION['login_name'];
		if($b->where("id='".$id."'")->update($dat)){
			echo 1;
			exit();
		}else{
			echo 2;
			exit();
		}

	}
   //线上入款
    $map = "k_user_bank_in_record.site_id = '".SITEID."' and k_user_bank_in_record.make_sure = '0' and k_user_bank_in_record.into_style = '2'";
    $bank_data = $b->join('left join k_user_level on k_user_level.id = k_user_bank_in_record.level_id')->field("k_user_bank_in_record.*,k_user_level.level_des")->where($map)->select();

    foreach ($bank_data as $key => $val) {

    	$bank_data[$key]['bank_name'] = bank_type($val['bid']);//银行
    	$bank_data[$key]['bank_fs'] = in_type($v['in_type']);
    }
    //p($bank_data);
    //判断是否有新入款
    $new_on_state = '';
    $new_on_state = M('k_user_bank_in_record',$db_config)->field("id")
                       ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '2'")
                       ->find();
    if (!empty($new_on_state)) {
        $data['status'] = 1;
    }else{
        $data['status'] = 0;
    }
     $data['info'] = $bank_data;
     echo JSON($data);
     exit;


}


/******************************默认列表显示出款***************************/
//out_status 0表示未处理 1已出款 2已拒绝 3已取消 4 正在出款

if ($_GET['act'] == 'out1') {
    $data = '';
   //出款
    $map = "k_user_bank_out_record.site_id = '".SITEID."' and (k_user_bank_out_record.out_status = '0' or k_user_bank_out_record.out_status = '4')";
    $bank_data = M('k_user_bank_out_record',$db_config)->join('left join k_user_level on k_user_level.id = k_user_bank_out_record.level_id')->field("k_user_bank_out_record.*,k_user_level.level_des")->where($map)->order("id desc")->select();
    //判断是否有新出款
    $new_out_state = '';
    $new_out_state = M('k_user_bank_out_record',$db_config)->field("id")
                       ->where("(k_user_bank_out_record.out_status = '0' or k_user_bank_out_record.out_status = '4') and site_id = '".SITEID."'")
                       ->find();
    if (!empty($new_out_state)) {
        $data['status'] = 1;
    }else{
        $data['status'] = 0;
    }
    $data['info'] = $bank_data;
     echo JSON($data);
     exit();
}
