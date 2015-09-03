<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
/**
*
*    into_style 1表示公司入款 
*
**/

//修改状态确认
if($_GET['btn'] == 's1' && !empty($_GET['uid'])){
    $iMoney = M('k_user_bank_in_record',$db_config)
	        ->field('deposit_money,deposit_num,order_num,favourable_num,other_num,do_time,make_sure')
	        ->where("id = '".$_GET['id']."'")
	        ->find();//会员存入总金额
	if ($iMoney['make_sure'] == '1') {
		message("该笔入款已经确定成功！",'./bank_record.php');
	}else{
		$id=$_GET['id'];
		$userMoney = M('k_user',$db_config)
		           ->field("username,level_id,agent_id,money")
		           ->where("uid = '".$_GET['uid']."'")
		           ->find();//会员余额

		$level_des = M('k_user_level',$db_config)
		           ->field("RMB_pay_set")
		           ->where("id = '".$userMoney['level_id']."'")
		           ->find();
	    //稽核对应参数
		$aud = M('k_cash_config_view',$db_config)
		      ->where("id = '".$level_des['RMB_pay_set']."'")
		      ->find();

		 //余额判断
		if ($userMoney['money'] < $aud['line_ct_fk_audit']) {
		       //稽核状态查询
	         $map_audit = array();
	         $map_audit['site_id'] = SITEID;
	         $map_audit['type'] = 1;
	         $map_audit['uid'] = $_GET['uid'];
	         $is_audit = M('k_user_audit',$db_config)->where($map_audit)->order("id desc")->find();  
	               //判断稽核是否锁定
             $map_lock = array();
             $map_lock['site_id'] = SITEID;
             $map_lock['uid'] = $_GET['uid'];
             $map_lock['out_status'] = array('in','(0,4)');
             $is_audit_lock = M('k_user_bank_out_record',$db_config)->where($map_lock)->find();
		 }


	    //统一时间
	     $now_date = date('Y-m-d H:i:s');
	     $kUser = M("k_user", $db_config);
	     $kUser->begin();
	     try{
	    /**
	      * 事务开始
	     */ //0表示未处理 1表示已确认 2表示已取消
	        $data_u = array();
	        $data_u['money'] = array('+',$iMoney['deposit_money']);
	        $log_1 = $kUser->where("uid = '".$_GET['uid']."'")
	                 ->update($data_u);//更新会员余额

	        $data_bin=array();
	        $data_bin['make_sure'] = 1;
	        $data_bin['do_time'] = $now_date;
	        $data_bin['admin_user'] = $_SESSION['login_name'];
	        $log_2 = $kUser->setTable("k_user_bank_in_record")
	                 ->where("id = '".$id."'")
	                 ->update($data_bin);//更新入款状态
	     
		    //现金系统写入记录
		    $unow_m = $kUser->setTable("k_user")
		             ->where("uid = '".$_GET['uid']."'")
		             ->getField('money');//获取会员当前余额
	       if(empty($unow_m)){
	       	 //未获取到或者之前余额为负数回滚
	       	  $kUser->rollback();
	       	  message("会员当前余额为负数,请先执行负数归零",'./bank_record.php');
	       	  exit();
	       }

	       if ($is_audit && !$is_audit_lock) {
	               //稽核清除
               $data_au = array();
               $data_au['type'] = 2;
               $log_c1 = $kUser->setTable('k_user_audit')->update($data_au);  
               //更新稽核最后一笔结束时间
               $data_al = array();
               $data_al['end_date'] = $now_date;
               $log_c2 = $kUser->setTable('k_user_audit')
                      ->where("id = '".$is_audit['id']."'")
                      ->update($data_al);
               //写入稽核日志
               $data_auto = array();
               $data_auto['update_date'] = $now_date;
               $data_auto['uid'] = $_GET['uid'];
               $data_auto['site_id'] = SITEID;
               $data_auto['username'] = $userMoney['username'];
               $data_auto['content'] = '會員：'.$val['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
               $log_c3 = $kUser->setTable('k_user_audit_log')->add($data_auto);
	       }else{
	           $log_c1 = 1;
	           $log_c2 = 1;
	           $log_c3 = 1;
	       }


	       
	       $data_c=array();
	       $data_c['source_id'] = $id;
	       $data_c['source_type'] = 1;//表示公司线上入款
	       $data_c['site_id'] = SITEID;
	       $data_c['uid'] = $_GET['uid'];
	       $data_c['agent_id'] = $userMoney['agent_id'];
	       $data_c['username'] = $userMoney['username'];
	       $data_c['cash_date'] = $now_date;
	       $data_c['cash_type'] = 11;
	       $data_c['cash_do_type'] = 1;
	       $data_c['cash_balance'] = $unow_m;//当前余额
	       $data_c['cash_num'] = $iMoney['deposit_num'];
	       $data_c['discount_num'] = $iMoney['favourable_num']+$iMoney['other_num'];//优惠总额
	       $data_c['remark'] = $iMoney['order_num'].',操作者：'.$_SESSION['login_name'];//备注
	       $log_4 = $kUser->setTable("k_user_cash_record")
		          ->add($data_c);

		      //稽核
	       //更新上一个稽核的终止时间
	       $l_audit = M('k_user_audit',$db_config)
	              ->field("max(id) as maxid")
	              ->where("uid = '".$_GET['uid']."' and type = '1' and site_id = '".SITEID."'")
	              ->find();
	       if ($l_audit['maxid']) {
	       	  //存在更新终止时间
	       	  $data_l = array();
	       	  $data_l['end_date'] = $now_date;
	       	  $log_5 = $kUser->setTable("k_user_audit")
	       	         ->where("id = '".$l_audit['maxid']."'")
	       	         ->update($data_l);
	       }
	       $data_a = array();
	       $data_a['source_type'] = 2;
	       $data_a['source_id'] = $id;
	       $data_a['uid'] = $_GET['uid'];
	       $data_a['type'] = 1;
	       $data_a['is_ct'] = 1;//公司入款有常态稽核无综合稽核
	       $data_a['site_id'] = SITEID;
	       $data_a['deposit_money'] = $iMoney['deposit_num'];//存款金额没优惠
	       $data_a['username'] = $userMoney['username'];
	       $data_a['begin_date'] = $now_date;
	       $data_a['relax_limit'] = $aud['line_ct_fk_audit'];//放宽额度
	       $data_a['normalcy_code'] = $aud['line_ct_audit']*$iMoney['deposit_num']*0.01;//常态稽核
	       $data_a['atm_give'] = $iMoney['other_num'];//汇款优惠
	       $data_a['catm_give'] = $iMoney['favourable_num'];//存款优惠
	       $data_a['expenese_num'] = $aud['line_ct_xz_audit'];//行政费率
	       $log_6 = $kUser->setTable("k_user_audit")
	       	      ->add($data_a);
          // 发送用户信息
           $dataM = array();
		   $dataM['type'] = 3;//表示出入款类型
		   $dataM['site_id'] = SITEID;
		   $dataM['uid'] = $_GET['uid'];
		   $dataM['level'] = 2;
		   $dataM['msg_title'] =  $userMoney['username'].','."公司入款";
		   $dataM['msg_info'] = $userMoney['username'].','."公司入款" . $iMoney['deposit_num'] .'元,其他優惠：'.$iMoney['other_num']. "元成功, 祝您游戏愉快！";
		   $log_7 = $kUser->setTable("k_user_msg")->add($dataM);

		   if($log_1 && $log_2 && $log_4 && $log_6 && $log_7){
				  $kUser->commit(); //事务提交
				  $do_log = '确定公司入款:'.$iMoney['order_num'];
	              admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	

	             
				  if ($_GET['op'] == 'cop') {
					    	//ajax监控
					    sleep(1);//延迟
					    echo json_encode(1);
					    exit();
				   }else{
				   	    sleep(1);//延迟
					    message("确定成功！",'./bank_record.php');
				   }
		       
			}else{
				 $kUser->rollback(); //数据回滚
				 if ($_GET['op'] == 'cop') {
					    	//ajax监控
				    echo json_encode(2);
				    exit();
				}else{
					message("确定失败,联系管理员！",'./bank_record.php');
				}
				
			}

		}catch(Exception $e){
			 $kUser->rollback(); //数据回滚
		     if ($_GET['op'] == 'cop') {
					    	//ajax监控
				    echo json_encode(2);
				    exit();
				}else{
					message("确定失败,联系管理员！",'./bank_record.php');
			 }
		} 
    }
}elseif ($_GET['btn'] == 's0' && !empty($_GET['uid'])) {
	//更改状态为取消
	$iMoney = M('k_user_bank_in_record',$db_config)
	        ->field('make_sure')
	        ->where("id = '".$_GET['id']."'")
	        ->find();//会员存入总金额
  if ($iMoney['make_sure'] == '0') {
  	  $data_q = array();
	  $data_q['make_sure'] = 2;
	  $data_q['do_time'] = date('Y-m-d H:i:s');
	  $data_q['admin_user'] = $_SESSION['login_name'];
	  $qState = M('k_user_bank_in_record',$db_config)->where("id = '".$_GET['id']."'")->update($data_q);
	if ($qState) {
		 $do_log = '取消公司入款:'.$_GET['order_num'];
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	

          // 发送用户信息
        $dataM = array();
		$dataM['type'] = 3;
		$dataM['site_id'] = SITEID;
		$dataM['uid'] = $_GET['uid'];
		$dataM['level'] = 2;
		$dataM['msg_title'] = '公司入款被取消';
		$dataM['msg_info'] = '公司入款:' . $_GET['order_num'] . "被取消,详情联系24小时在线客服,祝您游戏愉快！";
	    M("k_user_msg",$db_config)->add($dataM);
	    if ($_GET['op'] == 'cop') {
			//ajax监控
			sleep(1);
			echo json_encode(1);
			exit();
		}else{
			sleep(1);
			message("取消成功！",'./bank_record.php');
		}
	}
  }elseif($iMoney['make_sure'] == '2'){
      message("该笔入款已经取消成功！",'./bank_record.php');
  }
}

?>
