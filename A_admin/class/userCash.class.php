<?php
   /**
    * 会员现金相关数据处理
   */
   class userCash 
   {

   	  //获取会员人工存入次数还有金额
   	  static function userCash_RG($uid,$date=array()){
   	  	global $db_config;
		$uCashRG = array();
		$map = array();
		$map['uid'] = $uid;
		$map['type'] = 1;
		$map['site_id'] = SITEID;
		$map['catm_type'] = array('in','(1,6,4)');
		if (!empty($date)) {
		   $map['updatetime'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$uCashRG = M('k_user_catm',$db_config)
		           ->field("count(id) as num,sum(catm_money) as money,max(catm_money) as max_money")
		           ->where($map)
		           ->find();
		return $uCashRG;
   	  }

   	   //获取会员公司入款 线上入款存入次数还有金额
   	  static function userCash_GS($uid,$date=array(),$type=0){
   	  	global $db_config;
		$uCashGS = array();
		$map = array();
		$map['uid'] = $uid;
		$map['make_sure'] = 1;
		$map['site_id'] = SITEID;
		if(!empty($type)){
           $map['into_style'] = $type;//1表示公司入款 2 表示线上入款
		}
		if (!empty($date)) {
		   $map['do_time'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$uCashGS =M('k_user_bank_in_record',$db_config)
		         ->field("sum(deposit_num) as money,count(id) as num,max(deposit_num) as max_money")
		         ->where($map)
		         ->find();
		return $uCashGS;
   	  }

   	  	   //获取会员人工取款次数还有金额
   	  static function userOUT_RG($uid,$date=array()){
   	  	global $db_config;
		$uOutRG = array();
		$map = array();
		$map['uid'] = $uid;
		$map['type'] = 2;
		$map['site_id'] = SITEID;
		$map['catm_type'] = array('in','(1,2,4,8)');
		if (!empty($date)) {
		   $map['updatetime'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$uOutRG =M('k_user_catm',$db_config)
		        ->field("count(id) as num,sum(catm_money) as money,max(catm_money) as max_money")
		        ->where($map)
		        ->find();
		return $uOutRG;
   	  }

   	   	   //获取会员人工取款次数还有金额
   	  static function userOUT_XS($uid,$date=array()){
   	  	global $db_config;
		$uOutXS = array();
		$map = array();
		$map['uid'] = $uid;
		$map['out_status'] = 1;
		$map['site_id'] = SITEID;
		if (!empty($date)) {
		   $map['out_time'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$uOutXS = M('k_user_bank_out_record',$db_config)
		        ->field("sum(outward_num) as money,count(id) as num,max(outward_num) as max_money")
		        ->where($map)
		        ->find();
		return $uOutXS;
   	  }
      //会员所有存款信息
   	  static function userCashAll($uid,$date=array()){
   	  	 $cash_XS = userCash::userCash_GS($uid,$date);//线上 公司
   	  	 $cash_RG = userCash::userCash_RG($uid,$date);//人工
   	     $dataC['num'] = $cash_XS['num'] + $cash_RG['num']+0;//存款总次数
		 $dataC['money'] = $cash_XS['money'] + $cash_RG['money']+0;//存款总额
		if ($cash_XS['max_money'] > $cash_RG['max_money']) {
			$dataC['max_money']=$cash_XS['max_money']+0;//最大存款额数
		}else{
			$dataC['max_money']=$cash_RG['max_money']+0;//最大存款额数
		}
		return $dataC;
   	  }

   	    //会员所有出款信息
   	  static function userOutAll($uid,$date=array()){
   	  	 $dataC = array();
   	  	 $cash_RG = userCash::userOUT_RG($uid,$date);//人工
   	  	 $cash_XS = userCash::userOUT_XS($uid,$date);//线上
   	     $dataC['num'] = $cash_XS['num'] + $cash_RG['num']+0;//存款总次数
		 $dataC['money'] = $cash_XS['money'] + $cash_RG['money']+0;//存款总额
		if ($cash_XS['max_money'] > $cash_RG['max_money']) {
			$dataC['max_money']=$cash_XS['maxMg']+0;//最大存款额数
		}else{
			$dataC['max_money']=$cash_RG['maxMc']+0;//最大存款额数
		}
		return $dataC;
   	  }
   	 
   }
?>