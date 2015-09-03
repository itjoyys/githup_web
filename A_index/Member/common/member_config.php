<?php
//返回类型
function cash_type_r($cash_type){
   switch ($cash_type) {
     case '1':
       return '額度轉換';
       break;
     case '2':
       return '体育下注';
       break;
     case '3':
       return '彩票下注';
       break;
     case '4':
       return '视讯下注';
       break;
     case '5':
       return '彩票派彩';
       break;
     case '6':
       return '活动优惠';
       break;
    case '7':
       return '系统拒绝出款';
       break;
    case '8':
       return '系统取消出款';
       break;
     case '9':
       return '优惠退水';
       break;
     case '10':
       return '在线存款';
       break;
     case '11':
       return '公司入款';
       break;
     case '12':
       return '存入取出';
       break;
     case '13':
       return '优惠冲销';
       break;
     case '14':
       return '彩票派彩';
       break;
     case '15':
       return '体育派彩';
       break;
     case '19':
       return '线上取款';
       break;
     case '20':
       return '和局返本金';
     case '22':
       return '体育无效注单';
     case '23':
       return '系统取消出款';
     case '24':
       return '系统拒绝出款';
     case '25':
       return '彩票无效注单';
     case '26':
       return '彩票无效注单(扣本金)';
     case '27':
       return '注单取消(彩票)';
     case '28':
       return '注单取消(体育)';
   }
}


//返回交易类别
function cash_do_type_r($do_type){
   switch ($do_type) {
     case '1':
       return '存入';
       break;
     case '2':
       return '取出';
       break;
     case '3':
       return '人工存入';
       break;
      case '4':
       return '人工取出';
       break;
      case '5':
       return '扣除派彩';
       break;
      case '6':
       return '返回本金';
       break;
   }
}

//银行类别区分
function bank_type($type) {
	switch ($type) {
		case '1':
			return '中國銀行';
			break;
		case '2':
			return '中國工商銀行';
			break;
		case '3':
			return '中國建設銀行';
			break;
		case '4':
			return '中國招商銀行';
			break;
		case '5':
			return '中國民生銀行';
			break;
		case '7':
			return '中國交通銀行';
			break;
		case '8':
			return '中國邮政銀行';
			break;
		case '9':
			return '中國农业銀行';
			break;
		case '10':
			return '華夏銀行';
			break;
		case '11':
			return '浦發銀行';
			break;
		case '12':
			return '廣州銀行';
			break;
		case '13':
			return '北京銀行';
			break;
		case '14':
			return '平安銀行';
			break;
		case '15':
			return '杭州銀行';
			break;
		case '16':
			return '溫州銀行';
			break;
		case '17':
			return '中國光大銀行';
			break;
		case '18':
			return '中信銀行';
			break;
		case '19':
			return '浙商銀行';
			break;
		case '20':
			return '漢口銀行';
			break;
		case '21':
			return '上海銀行';
			break;
		case '22':
			return '廣發銀行';
			break;
		case '23':
			return '农村信用社';
			break;
		case '24':
			return '深圳发展银行';
			break;
		case '25':
			return '渤海银行';
			break;
		case '26':
			return '东莞银行';
			break;
		case '27':
			return '宁波银行';
			break;
		case '28':
			return '东亚银行';
			break;
		case '29':
			return '晋商银行';
			break;
		case '30':
			return '南京银行';
			break;
		case '31':
			return '广州农商银行';
			break;
		case '32':
			return '上海农商银行';
			break;
		case '33':
			return '珠海农村信用合作联社';
			break;
		case '34':
			return '顺德农商银行';
			break;
		case '35':
			return '尧都区农村信用联社';
			break;
		case '36':
			return '浙江稠州商业银行';
			break;
		case '37':
			return '北京农商银行';
			break;
		case '38':
			return '重庆银行';
			break;
		case '39':
			return '广西农村信用社';
			break;
		case '40':
			return '江苏银行';
			break;
		case '41':
			return '吉林银行';
			break;
		case '42':
			return '成都银行';
			break;
		case '50':
			return '兴业银行';
			break;
		case '100':
			return '支付宝';
			break;
		case '101':
			return '微信支付';
			break;
		case '102':
			return '财付通';
			break;
	}
}

//线下入款方式
function in_type($type) {
	switch ($type) {
		case '1':
			return '网银转帐';
			break;
		case '2':
			return 'ATM自动柜员机';
			break;
		case '3':
			return 'ATM现金入款';
			break;
		case '4':
			return '银行柜台';
			break;
		case '5':
			return '手机转帐';
			break;
		case '6':
			return '支付宝转账';
			break;
		case '7':
			return '财付通';
			break;
		case '8':
			return '微信支付';
			break;
	}
}

function str_cut($str){
    $arr = array();
    if(strstr($str, ',操作者') || strstr($str, ',返水操作者')){
      if(strstr($str, ',操作者')){
        $arr = explode(',操作者', $str);
        return $arr[0];
      }elseif(strstr($str, ',返水操作者')){
        $arr = explode(',返水操作者', $str);
        return $arr[0];
      }

    }else{
      return $str;
    }
  }

?>