<?php
$title = "出入账目汇总";
require "../common_html/header.php";
include_once "../../include/config.php";
include_once "../common/login_check.php";

/**
 * 实际盈亏 = 公司入款+线上支付+人工存入-会员出款-人工提出;
 * 账目统计 = 前面四项 - 后面四项;
 * 人工存入 = 人工存入 + 取消出款
 */

$out = M('k_user_bank_out_record', $db_config);
$in = M('k_user_bank_in_record', $db_config);
$catm_map = "site_id = '" . SITEID . "' ";
$map = "site_id = '" . SITEID . "' ";

//时间条件
$start_date;
$end_date;
if (!empty($_GET['date_start'])) {
	$s_date = $start_date = $_GET['date_start'];
} else {
	$s_date = $start_date = date("Y-m-d");
}

if (!empty($_GET['date_end'])) {
	$e_date = $end_date = $_GET['date_end'];
} else {
	$e_date = $end_date = date("Y-m-d");
}

$start_date = strtotime($start_date . ' 00:00:00') + $_GET['timearea'] * 3600;
$end_date = strtotime($end_date . ' 23:59:59') + $_GET['timearea'] * 3600;

$start_date = date('Y-m-d H:i:s', $start_date);
$end_date = date('Y-m-d H:i:s', $end_date);
$catm_map .= " and updatetime >= '" . $start_date . "' ";
$map .= " and cash_date >= '" . $start_date . "' ";
$catm_map .= " and updatetime <= '" . $end_date . "' ";

$map .= "and cash_date <= '" . $end_date . "' ";

//公司入款 线上入款
$map_in1['do_time'] = array(array('<=', $end_date), array('>=', $start_date));
$map_in1['site_id'] = SITEID;
$map_in1['into_style'] = 1;
$map_in1['make_sure'] = 1;
$data_in_1 = $in->field("sum(deposit_num) as deposit_num,count(id) as countNum,count(distinct uid) as userNum")->where($map_in1)->find(); //公司入款总额

$map_in1['into_style'] = 2;
$data_in_2 = $in->field("sum(deposit_num) as deposit_numo,count(id) as countNumo,count(distinct uid) as userNum")->where($map_in1)->find(); //线上支付总额

//会员出款 出款被扣金额
$map_out = array();
$map_out = "site_id = '" . SITEID . "' and out_status = '1' and (charge+favourable_num+expenese_num) > '0' and out_time >= '" . $start_date . "' and out_time <= '" . $end_date . "'";
$count_1 = $out->field("count(id) as outNum,sum(charge) as charge,sum(favourable_num) as favourable_num,sum(expenese_num) as expenese_num,count(distinct uid) as userNum")->where($map_out)->find(); //会员出款被扣

//会员出款
$map_oua = array();
$map_oua = "site_id = '" . SITEID . "' and out_status = '1' and out_time >= '" . $start_date . "' and out_time <= '" . $end_date . "'";
$count_2 = $out->field("count(id) as outNum,sum(outward_money) as outward_money,count(distinct uid) as userNum")->where($map_oua)->find();

//人工存入 提出
$map_catm['updatetime'] = array(array('<=', $end_date), array('>=', $start_date));
$map_catm['site_id'] = SITEID;
$map_catm['type'] = 1;
$map_catm['catm_type'] = array('in', '(1,4,6)');
$catm = M("k_user_catm", $db_config)->field("count(id) as catmNum,sum(catm_money) as catm_money,count(distinct uid) as userNum")->where($map_catm)->find();
//人工提出
$map_catm['type'] = 2;
unset($map_catm['catm_type']); //去掉对应条件
$catmM = M("k_user_catm", $db_config)->field("count(id) as catmNum,sum(catm_money) as catm_money,count(distinct uid) as userNum")->where($map_catm)->find();

//给予返水
$map_f['site_id'] = SITEID;
$map_f['addtime'] = array(array('<=', $end_date), array('>=', $start_date));
$data_f = M('k_user_discount_search', $db_config)->field("sum(people_num-no_people_num) as people_num,sum(money) as money")->where($map_f)->find();
//给予返水的人数
$discIds = M('k_user_discount_search', $db_config)->field("id")->where($map_f)->select();
if (!empty($discIds)) {
	$discIds = '(' . implode(',', i_array_column($discIds, 'id')) . ')';
	$map_dp['kds_id'] = array('in', $discIds);
	$map_dp['state'] = 1;
	$data_f_user = M('k_user_discount_count', $db_config)->field("count(distinct uid) as userNum")->where($map_dp)->find();
}

//给予优惠
$map_fav = "site_id = '" . SITEID . "' and discount_num > 0 and cash_date >= '" . $start_date . "' and cash_date <= '" . $end_date . "' and (cash_only = 1 or cash_type in (10,11,12))";
$fav_num = M('k_user_cash_record', $db_config)->field("sum(discount_num) as fav_money,count(id) as fav_num,count(distinct uid) as userNum")->where($map_fav)->find();

//各项链接
$bank_line_inurl = "./bank_record.php?status=1&start_date=$s_date&end_date=$e_date&timearea=" . $_GET['timearea'];
$bank_ol_inurl = "./bank_record_online.php?status=1&start_date=$s_date&end_date=$e_date&timearea=" . $_GET['timearea'];
$out_url = "./out_record.php?out_status=1&start_date=$s_date&end_date=$e_date&timearea=" . $_GET['timearea'];
$catm_url = "./catm_record.php?otype=1-1-4-6&start_date=$s_date&end_date=$e_date&timearea=" . $_GET['timearea'];
$catmM_url = "./catm_record.php?otype=2&start_date=$s_date&end_date=$e_date&timearea=" . $_GET['timearea'];

//账目统计
$all_own_money = $data_in_1['deposit_num']//公司入款
 + $data_in_2['deposit_numo']//线上支付
 + $catm['catm_money']///人工存入
 + $count_1['charge'] + $count_1['expenese_num'] + $count_1['favourable_num']//会员出款被扣
    -$count_2['outward_money'] //会员出款
 //- $count_1['out_money']//会员出款
 - $data_f['money']//返水
 - $catmM['catm_money']//人工提出
 - $fav_num['fav_money']; //给予优惠

//实际盈亏
$yinkui_money = $data_in_1['deposit_num']
 + $data_in_2['deposit_numo']
 + $catm['catm_money']
 - $count_2['outward_money']  //会员出款
 - $catmM['catm_money'];
/* - $count_1['out_money']
 - $catmM['catm_money'];*/

?>
<body>
<script>
function chg_date(range,num1,num2){
if(range=='t'){
	queryform.date_start.value ='<?=date("Y-m-d")?>';
	queryform.date_end.value =queryform.date_start.value;
}

if(range!='t'){
 if(queryform.date_start.value!=queryform.date_end.value){ FrmData.date_start.value ='<?=date("Y-m-d", strtotime("-12 hour"))?>'; queryform.date_end.value =queryform.date_start.value;}
 var aStartDate = queryform.date_start.value.split('-');
 var newStartDate = new Date(parseInt(aStartDate[0], 10),parseInt(aStartDate[1], 10) - 1,parseInt(aStartDate[2], 10) + num1);

    if (newStartDate.getMonth() < 9) {
         tmp_date = newStartDate.getFullYear()+ '-0' + (newStartDate.getMonth() + 1);
    }else{
        tmp_date = newStartDate.getFullYear()+ '-' + (newStartDate.getMonth() + 1);
    }
      if (newStartDate.getDate() < 9) {
        queryform.date_start.value = tmp_date + '-0' + (newStartDate.getDate());
    }else{
        queryform.date_start.value = tmp_date+ '-' + (newStartDate.getDate());
    }

 var aEndDate = queryform.date_end.value.split('-');
 var end_tmp_date;
 var newEndDate = new Date(parseInt(aEndDate[0], 10),parseInt(aEndDate[1], 10) - 1,parseInt(aEndDate[2], 10) + num2);

    if (newEndDate.getMonth() < 9) {
         end_tmp_date = newEndDate.getFullYear()+ '-0' + (newEndDate.getMonth() + 1);
    }else{
         end_tmp_date = newEndDate.getFullYear()+ '-' + (newEndDate.getMonth() + 1);
    }
      if (newEndDate.getDate() < 9) {
        queryform.date_end.value = end_tmp_date + '-0' + (newEndDate.getDate());
    }else{
        queryform.date_end.value = end_tmp_date+ '-' + (newEndDate.getDate());
    }
}
}

 function getthis(){

	  var d_now = Date(today);
	  var year = today.split('-')[0];

	  for(i=1;i<=13;i++){
		  target_str = $("#"+year+'_'+i).html();
		  var data_arr = target_str.split('~');
		  var start = $.trim(data_arr[0].toString());
		  var end = $.trim(data_arr[1].toString());

		  var d1 = new Date(start);
		  var d2 = new Date(end);
		  if((Date.parse(d_now) - Date.parse(d1))>=0 && (Date.parse(d_now) -86400000 - Date.parse(d2))<=0){
			  return(year+'_'+i);
		  }
	  }
  }
 /**
   * 此方法计算本期或者上个期的第一天和最后一天并返回相应的日期格式
 */
function changMonth(timea)
{
	var   month = getthis();

	var target = '';
	var target_str = '';
	if(timea == 'last'){
		var month_arr = month.split('_');
		if(parseInt(month_arr[1])=='1'){
			var y = parseInt(month_arr[0])-1;
			target = y +'_13';
		}else{
			var m = parseInt(month_arr[1])-1;
			target = month_arr[0]+'_'+ m;
		}
	}else{
		target = month;
	}
	target_str = $("#"+target).html();
	var data_arr = target_str.split('~');
	var start = $.trim(data_arr[0].replace(/\//g,'-'));
	var end = $.trim(data_arr[1].replace(/\//g,'-'));
	document.getElementById('startdate').value = start;
	document.getElementById('enddate').value = end;
}


</script>
<div id="con_wrap">
<div class="input_002">出入账目汇总</div>
<div class="con_menu" style="width:90%">
<form name="queryform" id="queryform" action="" method="get">
    時區:
<select name="timearea">
  <option value="0" <?=select_check(0, $_GET['timearea'])?>>美東</option>
  <option value="12" <?=select_check(12, $_GET['timearea'])?>>北京</option>
</select>
	日期：
	 <input class="za_text Wdate" id="date_start" onClick="WdatePicker()" value="<?=$s_date?>" name="date_start"> &nbsp;
	  <input class="za_text Wdate" id="date_end" onClick="WdatePicker()" value="<?=$e_date?>" name="date_end">
	<input type="Button" style="margin-top:5px" class="za_button" onclick="chg_date('t',0,0)" value="今日">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="chg_date('l',-1,-1)" value="昨日">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="queryform.date_start.value='<?=date("Y-m-d", strtotime("last  Monday"))?>';queryform.date_end.value='<?=date("Y-m-d")?>'" value="本周">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="queryform.date_start.value='<?=date("Y-m-d", strtotime("last Monday -1 week"))?>';queryform.date_end.value='<?=date("Y-m-d", strtotime("last Sunday"))?>'" value="上周">
<!--
    <input type="Button" style="margin-top:5px" class="za_button" onclick="$(&#39;#startdate&#39;).val(&#39;2015-01-05&#39;);$(&#39;#enddate&#39;).val(&#39;2015-02-01&#39;);" value="本期">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="$(&#39;#startdate&#39;).val(&#39;2014-12-01&#39;);$(&#39;#enddate&#39;).val(&#39;2015-01-04&#39;);" value="上期">
-->
    &nbsp;&nbsp;&nbsp;
    <input type="submit" name="subbtn" value="查詢" class="button_d">

</form>
</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>收入</td>
			<td>收入金额</td>
			<td>支出</td>
			<td>支出明细</td>
		</tr>

		<tr class="m_cen">
			<td>公司入款</td>
			<td><a href="<?=$bank_line_inurl?>"><font class="in_font"><?=$data_in_1['deposit_num'] + 0?></font></a>(<?=$data_in_1['countNum'] + 0?>笔)(<?=$data_in_1['userNum'] + 0?>人)</td>
			<td>会员出款</td>
			<td><a href="<?=$out_url?>"><?=$count_2['outward_money'] + 0?></a>(<?=$count_2['outNum'] + 0?>笔)(<?=$count_2['userNum'] + 0?>人)</td>
		</tr>
		<tr class="m_cen">
			<td>线上支付</td>
			<td><a href="<?=$bank_ol_inurl?>"><font class="in_font"><?=$data_in_2['deposit_numo'] + 0?></font></a>(<?=$data_in_2['countNumo'] + 0?>笔)(<?=$data_in_2['userNum'] + 0?>人)</td>
			<td>给予优惠</td>
			<td><?=$fav_num['fav_money'] + 0?>(<?=$fav_num['fav_num'] + 0?>笔)(<?=$fav_num['userNum'] + 0?>人)</td>
		</tr>
		<tr class="m_cen">
			<td>人工存入</td>
			<td><a href="<?=$catm_url?>"><font class="in_font"><?=$catm['catm_money'] + 0?></font></a>(<?=$catm['catmNum'] + 0?>笔)(<?=$catm['userNum'] + 0?>人)</td>
			<td>人工提出</td>
			<td><a href="<?=$catmM_url?>"><?=$catmM['catm_money'] + 0?></a>(<?=$catmM['catmNum'] + 0?>笔)(<?=$catmM['userNum'] + 0?>人)</td>
		</tr>
		<tr class="m_cen">
			<td>会员出款被扣金额</td>
			<td><font class="in_font"><?=$count_1['charge'] + $count_1['expenese_num'] + $count_1['favourable_num'] + 0?></font>(<?=$count_1['outNum'] + 0?>笔) (<?=$count_1['userNum'] + 0?>人)</td>
			<td>给予反水</td>
			<td>
			    <?=$data_f['money'] + 0?>(<?=$data_f['people_num'] + 0?>笔)(<?=$data_f_user['userNum'] + 0?>人)
			</td>
		</tr>
		<tr><td colspan="15" class="table_bg1" align="right">实际盈亏：	 <?=$yinkui_money?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账目统计：<?=$all_own_money?></td></tr>
	</tbody></table>
</div>
<?php require "../common_html/footer.php";?>