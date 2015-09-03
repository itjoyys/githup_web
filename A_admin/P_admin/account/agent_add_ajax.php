<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");

$scatype = array('sports_scale','video_scale','lottery_scale');

//添加股东获取上级占成
if(!empty($_POST['id']) && $_POST['atype']== 'ua'){
	//查询分成信息
	$id = $_POST['id'];
	$vhtml = array('sports_scale'=>'','video_scale'=>'','lottery_scale'=>'');
	$agent = M('k_user_agent',$db_config);
	$data = $agent->field("video_scale,sports_scale,lottery_scale")
	      ->where("id = '".$_POST['id']."'")->find();
	if(!empty($data)){
		foreach ($scatype as $key => $val) {
           for ($i=0; $i <= $data[$val] ; $i += 0.05) { 
        	    $vhtml[$val] .= '<option  value="'.$i.'" >'.$i.'成</option>';
            }		
        }	
	}else{
		$vhtml['video_scale'] = '<option  value="0" >0成</option>';
		$vhtml['sports_scale'] = '<option  value="0" >0成</option>';
		$vhtml['lottery_scale'] = '<option  value="0" >0成</option>';
	}
    echo json_encode($vhtml);
}












