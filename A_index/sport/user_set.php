<?php
/***
*  会员设定信息处理
*
*/

//传入设置项id,会员详细设置，返回会员详细设定(体育)
function get_member_set($member_set_data,$setid){
	$arr = array();
	if (!empty($member_set_data)) {
	  foreach ($member_set_data as $key => $val) {
		if ($val['sport_set_name_id'] == $setid) {
			return $val;
		}	
     }
	}
}
//传入设置项id,会员详细设置，返回会员详细设定(福彩)
function get_member_set_fc($member_set_data,$setid){
	$arr = array();
	if (!empty($member_set_data)) {
	  foreach ($member_set_data as $key => $val) {
		if ($val['fc_set_name_id'] == $setid) {
			return $val;
		}	
     }
	}
}
//传入设置项id,会员详细设置，返回会员详细设定(视讯)
function get_member_set_video($member_set_data,$setid){
	$arr = array();
	if (!empty($member_set_data)) {
	  foreach ($member_set_data as $key => $val) {
		if ($val['video_set_name_id'] == $setid) {
			return $val;
		}	
     }
	}
}

function type_set($type){
	if($type=='足球'){
		return 'zq';
	}elseif($type=='籃球'){
		return 'lq';
	}elseif($type=='排球'){
		return 'pq';
	}elseif($type=='網球'){
		return 'wq';
	}elseif($type=='棒球'){
		return 'bq';
	}elseif($type=='其他'){
		return 'qt';
	}elseif($type=='冠軍'){
		return 'gj';
	}elseif($type=='乒乓球'){
		return 'ppq';
	}

}

//读取会员体育设定，组合拼接数据 
function get_mem_s_set($level_set_data,$member_data){
	if(is_array($level_set_data)){
	foreach ($level_set_data as $key => $val) {
		   $re_data = get_member_set($member_data,$val['sport_set_name_id']);
		   if(!empty($re_data)){
		      	switch ($val['sport_type_name']) {
			      	case '足球':
			      	    $set_field = field_set($val['sport_set_name']);
			      		$sets['zq'][$set_field] = $re_data;
			      		$sets['zq'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '籃球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['lq'][$set_field] = $re_data;
			      		$sets['lq'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '排球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['pq'][$set_field] = $re_data;
			      		$sets['pq'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '網球':
			      	    $set_field = field_set($val['sport_set_name']);
			      		$sets['wq'][$set_field] = $re_data;
			      		$sets['wq'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '棒球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['bq'][$set_field] = $re_data;
			      		$sets['bq'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '其他':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['qt'][$set_field] = $re_data;
			      		$sets['qt'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '冠軍':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['gj'][$set_field] = $re_data;
			      		$sets['gj'][$set_field]['m_id'] = $re_data['id'];
			      		break;	
			      	case '乒乓球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['ppq'][$set_field] = $re_data;
			      		$sets['ppq'][$set_field]['m_id'] = $re_data['id'];
			      		break;		
		      }
		   }else{
		        switch ($val['sport_type_name']) {
			      	case '足球':
			      	    $set_field = field_set($val['sport_set_name']);
			      		$sets['zq'][$set_field] = $val;
			      		break;
			      	case '籃球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['lq'][$set_field] = $val;
			      		break;
			      	case '排球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['pq'][$set_field] = $val;
			      		break;
			      	case '網球':
			      	    $set_field = field_set($val['sport_set_name']);
			      		$sets['wq'][$set_field] = $val;
			      		break;
			      	case '棒球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['bq'][$set_field] = $val;
			      		break;
			      	case '其他':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['qt'][$set_field] = $val;
			      		break;
			      	case '冠軍':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['gj'][$set_field] = $val;
			      		break;	
			      	case '乒乓球':
			      		$set_field = field_set($val['sport_set_name']);
			      		$sets['ppq'][$set_field] = $val;
			      		break;	
		      }
		   }
     }
 }
      return $sets;
}

//组合体育设定，拼接数据 (股东，代理)
function get_agent_s_set($member_data){
	if(is_array($member_data)){
		foreach ($member_data as $key => $val) {
			switch ($val['sport_type_name']) {
				case '足球':
					$set_field = field_set($val['sport_set_name']);
					$sets['zq'][$set_field] = $val;
					$sets['zq'][$set_field]['m_id'] = $val['id'];
					break;
				case '籃球':
					$set_field = field_set($val['sport_set_name']);
					$sets['lq'][$set_field] = $val;
					$sets['lq'][$set_field]['m_id'] = $val['id'];
					break;
				case '排球':
					$set_field = field_set($val['sport_set_name']);
					$sets['pq'][$set_field] = $val;
					$sets['pq'][$set_field]['m_id'] = $val['id'];
					break;
				case '網球':
					$set_field = field_set($val['sport_set_name']);
					$sets['wq'][$set_field] = $val;
					$sets['wq'][$set_field]['m_id'] = $val['id'];
					break;
				case '棒球':
					$set_field = field_set($val['sport_set_name']);
					$sets['bq'][$set_field] = $val;
					$sets['bq'][$set_field]['m_id'] = $val['id'];
					break;
				case '其他':
					$set_field = field_set($val['sport_set_name']);
					$sets['qt'][$set_field] = $val;
					$sets['qt'][$set_field]['m_id'] = $val['id'];
					break;
				case '冠軍':
					$set_field = field_set($val['sport_set_name']);
					$sets['gj'][$set_field] = $val;
					$sets['gj'][$set_field]['m_id'] = $val['id'];
					break;	
				case '乒乓球':
					$set_field = field_set($val['sport_set_name']);
					$sets['ppq'][$set_field] = $val;
					$sets['ppq'][$set_field]['m_id'] = $val['id'];
					break;	
			}
		 }
	 }
      return $sets;
}
//视讯根据类别返回字段
function video_type_set($type){
	if($type=='bjl'){
		return '百家乐';
	}elseif($type=='lunp'){
		return '轮盘';
	}elseif($type=='sb'){
		return '骰宝';
	}elseif($type=='lh'){
		return '龙虎';
	}
}


//根据类别设定数组字段
function field_set($type){
	if($type=='讓球'){
		return 'a';
	}elseif($type=='大小'){
		return 'b';
	}elseif($type=='滾球'){
		return 'c';
	}elseif($type=='滾球大小'){
		return 'd';
	}elseif($type=='單雙'){
		return 'e';
	}elseif($type=='獨贏'){
		return 'f';
	}elseif($type=='讓球過關'){
		return 'g';
	}elseif($type=='滾球獨贏'){
		return 'h';
	}elseif($type=='標準過關'){
		return 'i';
	}elseif($type=='總合過關'){
		return 'j';
	}elseif($type=='波膽'){
		return 'k';
	}elseif($type=='入球'){
		return 'l';
	}elseif($type=='半全場'){
		return 'm';
	}elseif($type=='冠軍'){
		return 'n';
	}elseif($type=='總得分'){
		return 'o';
	}
}

//读取会员福彩设定，组合拼接数据 
/*
1=六合彩，2=3D，3=PL3，4=重慶時時彩，5=江西時時彩，6=上海時時樂，7=天津時時彩，8=新疆時時彩，9=廣東快樂十分，10=天津快樂十分，
*/
function get_mem_s_set_fc($level_set_data,$member_data){
	foreach ($level_set_data as $key => $val) {
		   $re_data = get_member_set_fc($member_data,$val['fc_set_name_id']);
		   if(!empty($re_data)){
		      	switch ($val['fc_type_name']) {
			      	case 'fc_3d':
			      	    $set_field = field_set_fc($val['fc_set_name']);
			      		$sets['fc_3d'][$set_field] = $re_data;
			      		$sets['fc_3d'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'pl_3':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['pl_3'][$set_field] = $re_data;
			      		$sets['pl_3'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'cq_ten':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['cq_ten'][$set_field] = $re_data;
			      		$sets['cq_ten'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'gd_ten':
			      	    $set_field = field_set_fc($val['fc_set_name']);
			      		$sets['gd_ten'][$set_field] = $re_data;
			      		$sets['gd_ten'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'cq_ssc':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['cq_ssc'][$set_field] = $re_data;
			      		$sets['cq_ssc'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'bj_8':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['bj_8'][$set_field] = $re_data;
			      		$sets['bj_8'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'liuhecai':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['liuhecai'][$set_field] = $re_data;
			      		$sets['liuhecai'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'bj_10':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['bj_10'][$set_field] = $re_data;
			      		$sets['bj_10'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '8':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['8'][$set_field] = $re_data;
			      		$sets['8'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '9':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['9'][$set_field] = $re_data;
			      		$sets['9'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case '10':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['10'][$set_field] = $re_data;
			      		$sets['10'][$set_field]['m_id'] = $re_data['id'];
			      		break;
		      }
		   }else{
		        switch ($val['fc_type_name']) {
			      	case 'fc_3d':
			      	    $set_field = field_set_fc($val['fc_set_name']);
			      		$sets['fc_3d'][$set_field] = $val;
			      		break;
			      	case 'pl_3':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['pl_3'][$set_field] = $val;
			      		break;
			      	case 'cq_ten':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['cq_ten'][$set_field] = $val;
			      		break;
			      	case 'gd_ten':
			      	    $set_field = field_set_fc($val['fc_set_name']);
			      		$sets['gd_ten'][$set_field] = $val;
			      		break;
			      	case 'cq_ssc':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['cq_ssc'][$set_field] = $val;
			      		break;
			      	case 'bj_8':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['bj_8'][$set_field] = $val;
			      		break;
			      	case 'liuhecai':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['liuhecai'][$set_field] = $val;
			      		break;
			      	case 'bj_10':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['bj_10'][$set_field] = $val;
			      		break;
			      	case '8':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['8'][$set_field] = $val;
			      		break;
			      	case '9':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['9'][$set_field] = $val;
			      		break;
			      	case '10':
			      		$set_field = field_set_fc($val['fc_set_name']);
			      		$sets['10'][$set_field] = $val;
			      		break;				
		      }
		   }
     }
      return $sets;
}


//根据类别设定数组字段(福彩)
function field_set_fc($type){
	if($type=='第一球'){
		return '1';
	}elseif($type=='第二球'){
		return '2';
	}elseif($type=='第三球'){
		return '3';
	}elseif($type=='跨度'){
		return '4';
	}elseif($type=='3连'){
		return '5';
	}elseif($type=='中三球'){
		return '6';
	}elseif($type=='前三球'){
		return '7';
	}elseif($type=='独胆'){
		return '8';
	}elseif($type=='總和,龍虎'){
		return '9';
	}elseif($type=='第八球'){
		return '10';
	}elseif($type=='第七球'){
		return '11';
	}elseif($type=='第六球'){
		return '12';
	}elseif($type=='第五球'){
		return '13';
	}elseif($type=='第四球'){
		return '14';
	}elseif($type=='后三球'){
		return '15';
	}elseif($type=='斗牛'){
		return '16';
	}elseif($type=='梭哈'){
		return '17';
	}elseif($type=='选一'){
		return '18';
	}elseif($type=='选二'){
		return '19';
	}elseif($type=='选三'){
		return '20';
	}elseif($type=='选四'){
		return '21';
	}elseif($type=='选五'){
		return '22';
	}elseif($type=='和值'){
		return '23';
	}elseif($type=='上中下'){
		return '24';
	}elseif($type=='奇和偶'){
		return '25';
	}elseif($type=='龍虎'){
		return '26';
	}elseif($type=='第十名'){
		return '27';
	}elseif($type=='第九名'){
		return '28';
	}elseif($type=='第八名'){
		return '29';
	}elseif($type=='第七名'){
		return '30';
	}elseif($type=='第六名'){
		return '31';
	}elseif($type=='第五名'){
		return '32';
	}elseif($type=='第四名'){
		return '33';
	}elseif($type=='第三名'){
		return '34';
	}elseif($type=='亚军'){
		return '35';
	}elseif($type=='冠军'){
		return '36';
	}elseif($type=='冠、亚军和'){
		return '37';
	}elseif($type=='全不中'){
		return '38';
	}elseif($type=='尾数连'){
		return '39';
	}elseif($type=='生肖连'){
		return '40';
	}elseif($type=='合肖'){
		return '41';
	}elseif($type=='特码生肖'){
		return '42';
	}elseif($type=='一肖/尾数'){
		return '43';
	}elseif($type=='半波'){
		return '44';
	}elseif($type=='连码'){
		return '45';
	}elseif($type=='过关'){
		return '46';
	}elseif($type=='正码1-6'){
		return '47';
	}elseif($type=='正码特'){
		return '48';
	}elseif($type=='正码'){
		return '49';
	}elseif($type=='特码'){
		return '50';
	}

}

//读取会员视讯设定，组合拼接数据 
function get_mem_v_set($level_set_data,$member_data){
	if(!empty($level_set_data)){
	foreach ($level_set_data as $key => $val) {
		   $re_data = get_member_set_video($member_data,$val['video_set_name_id']);
		   if(!empty($re_data)){
		      	switch ($val['video_type_name']) {
			      	case 'bjl':
			      	    $set_field = field_set_video($val['video_set_name']);
			      		$sets['bjl'][$set_field] = $re_data;
			      		$sets['bjl'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'lunp':
			      		$set_field = field_set_video($val['video_set_name']);
			      		$sets['lunp'][$set_field] = $re_data;
			      		$sets['lunp'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'sb':
			      		$set_field = field_set_video($val['video_set_name']);
			      		$sets['sb'][$set_field] = $re_data;
			      		$sets['sb'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			      	case 'lh':
			      	    $set_field = field_set_video($val['video_set_name']);
			      		$sets['lh'][$set_field] = $re_data;
			      		$sets['lh'][$set_field]['m_id'] = $re_data['id'];
			      		break;
			    
		      }
		   }else{
		        switch ($val['video_type_name']) {
			      	case 'bjl':
			      	    $set_field = field_set_video($val['video_set_name']);
			      		$sets['bjl'][$set_field] = $val;
			      		break;
			      	case 'lunp':
			      		$set_field = field_set_video($val['video_set_name']);
			      		$sets['lunp'][$set_field] = $val;
			      		break;
			      	case 'sb':
			      		$set_field = field_set_video($val['video_set_name']);
			      		$sets['sb'][$set_field] = $val;
			      		break;
			      	case 'lh':
			      	    $set_field = field_set_video($val['video_set_name']);
			      		$sets['lh'][$set_field] = $val;
			      		break;
			      
		      }
		   }
     }
      return $sets;
      }
}
//根据类别设定数组字段(视讯)
function field_set_video($type){
	if($type=='閑莊'){
		return '1';
	}elseif($type=='和注'){
		return '2';
	}elseif($type=='對子'){
		return '3';
	}elseif($type=='大小'){
		return '4';
	}elseif($type=='直接注'){
		return '5';
	}elseif($type=='分注'){
		return '6';
	}elseif($type=='街注'){
		return '7';
	}elseif($type=='三數'){
		return '8';
	}elseif($type=='角注'){
		return '9';
	}elseif($type=='四個號碼'){
		return '10';
	}elseif($type=='線注'){
		return '11';
	}elseif($type=='列注'){
		return '12';
	}elseif($type=='下注-打數位'){
		return '13';
	}elseif($type=='單雙'){
		return '14';
	}elseif($type=='紅黑'){
		return '15';
	}elseif($type=='大小單雙'){
		return '16';
	}elseif($type=='圍骰'){
		return '17';
	}elseif($type=='全圍'){
		return '18';
	}elseif($type=='兩顆骰組合(對子)'){
		return '19';
	}elseif($type=='兩顆骰組合'){
		return '20';
	}elseif($type=='單骰點數'){
		return '21';
	}elseif($type=='龍虎'){
		return '22';
	}elseif($type=='9,10,11&12'){
		return '23';
	}elseif($type=='8&13'){
		return '24';
	}elseif($type=='7&14'){
		return '25';
	}elseif($type=='6&15'){
		return '26';
	}elseif($type=='5&16'){
		return '27';
	}elseif($type=='4&17'){
		return '28';
	}
}


//支付平台名称
function get_zhifu_name($type){
	if($type==1)
		{
			return "快汇宝";
		}
		else if($type==2)
		{
			return "易宝";
		}
		else if($type==3)
		{
			return "环迅";
		}
		else if($type==4)
		{
			return "聚付通";
		}
		else if($type==5)
		{
			return "v付通";
		}
		else if($type==6)
		{
			return "财付通";
		}
}




?>