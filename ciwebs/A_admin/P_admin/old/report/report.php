<?php
include_once("../../../include/config.php");
include_once("../common/login_check.php");

//期数
$periods_old = period(date('Y')-1);
$periods = period(date('Y'));
foreach ($periods as $key => $value) {
	if($value['edate']>=date('Y-m-d') && $value['sdate'] <= date('Y-m-d')){
		$periods_now = $value;
	}
}

?>
<?php $title="报表明细"; require("../common_html/header.php");?>
<script language="JavaScript">
var tmp_date;
function chg_date(range,num1,num2){
if(range=='t' || range=='w' || range=='r'){
 FrmData.date_start.value ='<?=date("Y-m-d")?>';
 FrmData.date_end.value =FrmData.date_start.value;}

if(range!='t'){
 if(FrmData.date_start.value!=FrmData.date_end.value){ FrmData.date_start.value ='<?=date("Y-m-d")?>'; FrmData.date_end.value =FrmData.date_start.value;}
 var aStartDate = FrmData.date_start.value.split('-');
 var newStartDate = new Date(parseInt(aStartDate[0], 10),parseInt(aStartDate[1], 10) - 1,parseInt(aStartDate[2], 10) + num1);

		if (newStartDate.getMonth() < 9) {
				 tmp_date = newStartDate.getFullYear()+ '-0' + (newStartDate.getMonth() + 1);
		}else{
				tmp_date = newStartDate.getFullYear()+ '-' + (newStartDate.getMonth() + 1);
		}
			if (newStartDate.getDate() < 9) {
				FrmData.date_start.value = tmp_date + '-0' + (newStartDate.getDate());
		}else{
				FrmData.date_start.value = tmp_date+ '-' + (newStartDate.getDate());
		}

 var aEndDate = FrmData.date_end.value.split('-');
 var end_tmp_date;
 var newEndDate = new Date(parseInt(aEndDate[0], 10),parseInt(aEndDate[1], 10) - 1,parseInt(aEndDate[2], 10) + num2);

		if (newEndDate.getMonth() < 9) {
				 end_tmp_date = newEndDate.getFullYear()+ '-0' + (newEndDate.getMonth() + 1);
		}else{
				 end_tmp_date = newEndDate.getFullYear()+ '-' + (newEndDate.getMonth() + 1);
		}
			if (newEndDate.getDate() < 9) {
				FrmData.date_end.value = end_tmp_date + '-0' + (newEndDate.getDate());
		}else{
				FrmData.date_end.value = end_tmp_date+ '-' + (newEndDate.getDate());
		}
}

}
function tijiao(){
	document.getElementById('ceng').style.display = "none";
	document.getElementById('loading').style.display = "block";
	return true;
}
</script>
<script>
	$(function () {
	    $("#quanxuan").click(function () {//全选

	    	if($(this).attr("checked")== 'checked'){
	        $("input[type='checkbox'][class='game']").attr("checked", true);
	    	}else{
	    		$("input[type='checkbox'][class='game']").attr("checked", false);
	    	}

	    });

	    $("#fanxuan").click(function () {//反选
	        $("input[type='checkbox'][class='game']").each(function () {
	            $(this).attr("checked", !$(this).attr("checked"));
	        });
	    });
	});
</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<div  id="con_wrap">
	<div  class="input_002">報表查詢</div>
	<div  class="con_menu">
	</div>
</div>
<div id="loading" style="display:none">正在整理报表，整个过程大约需要5-10分钟，请耐心等待。</div>
<div class="content" id="ceng">
	<div style="float:left;width:60%;">
<form action="report_result.php" method="get"  name="FrmData" id="FrmData" onSubmit="tijiao();">
	<table width="100%" border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
	<tr class="m_bc">
		<td colspan="4" align="left">
		<table width="650" border="0" cellspacing="1" cellpadding="0" class="m_tab">
			<tr class="m_cen">
				<td width="100" class="m_title_re">日期区间：</td>
				<td colspan="5">
				<table border="0" cellpadding="0" cellspacing="0">

						<td><input style="width:75px;" class="Wdate" name="date_start" type="text" id="date_start" value="<?=date("Y-m-d")?>" onClick="WdatePicker()" size="10" maxlength="10" readonly /> </td>
						<td width="20" align="center">&nbsp;~&nbsp;</td>
						<td><input name="date_end" class="Wdate" style="width:75px;" type="text" id="date_end" value="<?=date("Y-m-d")?>" onClick="WdatePicker()" size="10" maxlength="10" readonly /></td>
						<td>&nbsp;</td>
						<td>
						<input name="Submit" type="Button" class="za_button" onClick="chg_date('t',0,0)" value="今日">&nbsp;&nbsp;
							<input name="Submit" type="Button" class="za_button" onClick="chg_date('l',-1,-1)" value="昨日">&nbsp;&nbsp;
							<input name="Submit" type="Button" class="za_button" onClick="chg_date('n',1,1)" value="明日">&nbsp;&nbsp;
							<input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=date("Y-m-d",strtotime("last Monday"))?>';FrmData.date_end.value='<?=date("Y-m-d")?>'" value="本星期">&nbsp;&nbsp;
							<input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=date("Y-m-d",strtotime("last Monday")-604800)?>';FrmData.date_end.value='<?=date("Y-m-d",strtotime("last Monday")-86400)?>'" value="上星期">&nbsp;&nbsp;
							<input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=$periods_now['sdate']?>';FrmData.date_end.value='<?=date("Y-m-d")?>'" value="本期"></td>
					</tr>
				</table></td>
			</tr>
			<tr class="m_cen">
					<td width="100" class="m_title_re">游戏選擇：</td>
					<td colspan="3" style="text-align:left">


					 <label>
					 <input type="checkbox" name="game[]" class="game" checked="checked" id="zq" value="zq">
						&nbsp;體育</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="cp" value="cp">
						&nbsp;彩票</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="mg" value="mg">
						&nbsp;MG视讯</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="mgdz" value="mgdz">
						&nbsp;MG电子</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="bbsx" value="bbdz">
						&nbsp;BBIN视讯</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="bbdz" value="bbsx">
						&nbsp;BBIN机率</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="bbty" value="bbty">
						&nbsp;BBIN球类</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="bbcp" value="bbcp">
						&nbsp;BBIN彩票</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="ag" value="ag">
						&nbsp;AG</label>&nbsp;
						<label><input type="checkbox" name="game[]" class="game" id="og" value="og">
						&nbsp;OG</label>&nbsp;
						<label> <input type="checkbox" name="game[]" class="game" id="lebo" value="lebo">
						&nbsp;LEBO</label>&nbsp;
						<label> <input type="checkbox" name="game[]" class="game" id="ct" value="ct">
						&nbsp;CT</label>&nbsp;
						</br>
					<div style="border-top:1px dashed #ccc;">
					<label><input type="checkbox" name="quanxuan" class=""  id="quanxuan" value="">
						&nbsp;全选</label>&nbsp;
						<label><input type="checkbox" name="fanxuan" class=""  id="fanxuan" value="">
						&nbsp;反选</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
						</td>
				</tr>
				<tr class="m_cen">
				<td class="m_title_re">游戏種類：</td>
				<td colspan="4" style="text-align:left;"><select name="wtype" class="za_select">
					<option value="all" selected>全部</option>
				</select></td>
			</tr>
				<tr class="m_cen_003">
					<td width="100" class="m_title_re">玩法種類：</td>
					<td colspan="3" style="text-align:left"><select class="gamezl" id="gamezl" name="gamezl" style="display: inline-block;">
							<option value="all">全部</option>
						</select></td>
				</tr>

			<tr class="m_cen_003">
					<td width="100" class="m_title_re">注單狀態：</td>
					<td colspan="3" style="text-align:left"><select name="is_res">
							<option value="0">全部</option>
							<option value="1">有結果</option>
							<!--<option value="2">未有結果</option>-->
						</select></td>
				</tr>
				 <tr bgcolor="#FFFFFF">
					<td width="100" class="m_title_re"></td>
				<td height="30" colspan="5">
						<input type="hidden" name="atype" value="s_h">
						<input type=SUBMIT name="submit" value="查询" class="za_button">
							&nbsp;&nbsp;&nbsp;
						<input type="reset" name="resbtn" id="resbtn" class="za_button" value="重設">

				</td>
			</tr>
			</table>
			</form>
			<table width="100%" border="0" cellspacing="1" cellpadding="0" class="m_tab">
			<tr class="m_title">
				<td class="m_title_re">日期</td>
				<td colspan="2" class="m_title_ce"><?=date("Y-m-d",strtotime("-1 day"))?></td>
				<td colspan="2" class="m_title_ce"><?=date("Y-m-d")?></td>
			</tr>
			<tr class="m_cen">
				<td class="m_title_re">目前状态</td>
				<td style="color:#FF0000">有结果</td>
				<td style="color:#FF0000">无结果</td>
				<td>有结果</td>
				<td>无结果</td>
			</tr>

			<?php
			//彩票昨天有结果
			$data_a = '';
			$data_a['addtime']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_a['js'] = 1;
			$data_a['site_id'] = SITEID;
			$old_cpinfoy = M('c_bet',$db_config)
					->field('count(id) as num')
					->where($data_a)
					->find();

			//彩票昨天无结果
			$data_b = '';
			$data_b['addtime']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_b['js'] = 0;
			$data_b['site_id'] = SITEID;
			$old_cpinfo = M('c_bet',$db_config)
					->field('count(id) as num')
					->where($data_b)
					->find();


			//彩票今天有结果
			$data_c = '';
			$data_c['addtime']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_c['js'] = 1;
			$data_c['site_id'] = SITEID;
			$cpinfoy = M('c_bet',$db_config)
					->field('count(id) as num')
					->where($data_c)
					->find();

			//彩票今天无结果
			$data_d = '';
			$data_d['addtime']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_d['js'] = 0;
			$data_d['site_id'] = SITEID;
			$cpinfo = M('c_bet',$db_config)
					->field('count(id) as num')
					->where($data_d)
					->find();


			//体育昨天有结果
			$data_a = '';
			$data_a['bet_time']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_a['is_jiesuan'] = 1;
			$data_a['site_id'] = SITEID;
			$old_spinfoy = M('k_bet',$db_config)
					->field('count(bid) as num')
					->where($data_a)
					->find();

			//体育昨天无结果
			$data_b = '';
			$data_b['bet_time']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_b['is_jiesuan'] = 0;
			$data_b['site_id'] = SITEID;
			$old_spinfo = M('k_bet',$db_config)
					->field('count(bid) as num')
					->where($data_b)
					->find();


			//体育今天有结果
			$data_c = '';
			$data_c['bet_time']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_c['is_jiesuan'] = 1;
			$data_c['site_id'] = SITEID;
			$spinfoy = M('k_bet',$db_config)
					->field('count(bid) as num')
					->where($data_c)
					->find();

			//体育今天无结果
			$data_d = '';
			$data_d['bet_time']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_d['is_jiesuan'] = 0;
			$data_d['site_id'] = SITEID;
			$spinfo = M('k_bet',$db_config)
					->field('count(bid) as num')
					->where($data_d)
					->find();

			//体育串关昨天有结果
			$data_a = '';
			$data_a['bet_time']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_a['is_jiesuan'] = 1;
			$data_a['site_id'] = SITEID;
			$old_spcinfoy = M('k_bet_cg_group',$db_config)
					->field('count(gid) as num')
					->where($data_a)
					->find();

			//体育串关昨天无结果
			$data_b = '';
			$data_b['bet_time']  = array(array('>=',date('Y-m-d',strtotime("-1 day"))." 00:00:00"),array('<=',date('Y-m-d',strtotime("-1 day"))." 23:59:59"));
			$data_b['is_jiesuan'] = 0;
			$data_b['site_id'] = SITEID;
			$old_spcinfo = M('k_bet_cg_group',$db_config)
					->field('count(gid) as num')
					->where($data_b)
					->find();


			//体育串关今天有结果
			$data_c = '';
			$data_c['bet_time']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_c['is_jiesuan'] = 1;
			$data_c['site_id'] = SITEID;
			$spcinfoy = M('k_bet_cg_group',$db_config)
					->field('count(gid) as num')
					->where($data_c)
					->find();

			//体育串关今天无结果
			$data_d = '';
			$data_d['bet_time']  = array(array('>=',date('Y-m-d')." 00:00:00"),array('<=',date('Y-m-d')." 23:59:59"));
			$data_d['is_jiesuan'] = 0;
			$data_d['site_id'] = SITEID;
			$spcinfo = M('k_bet_cg_group',$db_config)
					->field('count(gid) as num')
					->where($data_d)
					->find();
			?>


			<tr class="m_cen">
				<td class="m_title_re">彩票</td>
				<td style="color:#FF0000"><?=intval($old_cpinfoy['num'])?></td>
				<td style="color:#FF0000"><?=intval($old_cpinfo['num'])?></td>
				<td><?=intval($cpinfoy['num'])?></td>
				<td><?=intval($cpinfo['num'])?></td>
			</tr>

			<tr class="m_cen">
				<td class="m_title_re">体育</td>
				<td style="color:#FF0000"><?=intval($old_spinfoy['num']+$old_spcinfoy['num'])?></td>
				<td style="color:#FF0000"><?=intval($old_spinfo['num']+$old_spcinfo['num'])?></td>
				<td><?=intval($spinfoy['num']+$spcinfoy['num'])?></td>
				<td><?=intval($spinfo['num']+$spcinfo['num'])?></td>
			</tr>

		</table></td>
	</tr>
	</table>
</div>
 <div style="float:left;width:20%;">
		<table border="0" cellpadding="0" cellspacing="0" class="m_tab">
			<tbody><tr class="m_title">
				<td height="9" colspan="2"><?=date('Y')-1?>年月帳期數</td>
			</tr>
		<?php foreach ($periods_old as $key => $val): ?>
			<tr class="m_cen">
				<td width="60" height="10" class="small">第<?=$val['id']?>期</td>
				<td width="174" class="m_cen_top" id="2014_1"><?=$val['sdate']?> ~ <?=$val['edate']?></td>
			</tr>
		<?php endforeach ?>

		</tbody></table>
	</div>
	<div style="float:left;width:20%;">
		<table border="0" cellpadding="0" cellspacing="0" class="m_tab">
			<tbody><tr class="m_title">
				<td height="9" colspan="2"><?=date('Y')?>年月帳期數</td>
			</tr>
		<?php foreach ($periods as $key => $val): ?>
			<tr class="m_cen">
				<td width="60" height="10" class="small">第<?=$val['id']?>期</td>
				<td width="174" class="m_cen_top" id="2014_1"><?=$val['sdate']?> ~ <?=$val['edate']?></td>
			</tr>
		<?php endforeach ?>

		</tbody></table>
	</div>

	</div>
</body>
</html>
<?php
	function period($to_y){
		for ($i=0; $i < 12; $i++) {
			 $to_m = $i+1;//月份
		 //  $days = cal_days_in_month(CAL_GREGORIAN, $to_m,$to_y); //判断当前月份天数
			 if (strlen($to_m) < 2) {
					$tom = '0'.$to_m;
				}else{
					$tom = $to_m;
				}
				if (strlen(week_one($to_m,$to_y)) < 2) {
					 $end_wek = '0'.week_one($to_m,$to_y);
				}else{
					 $end_wek = week_one($to_m,$to_y);
				}
			 $periods[$i]['sdate'] = $to_y.'-'.$tom.'-'.$end_wek;
			 if ($to_m < 12) {
					$next_d = week_one($to_m+1,$to_y)-1;
					$tmp = $to_y.'-'.($to_m+1).'-'.$next_d;
					if ($next_d == 1) {
						$tom = $to_m-1;
					}
					if (strlen($to_m+1) < 2) {
						 $tom = '0'.($to_m+1);
					}else{
						 $tom = $to_m+1;
					}
					if (strlen($next_d) < 2) {
						 $next_d = '0'.$next_d;
					}else{
						 $next_d = $next_d;
					}
					$periods[$i]['edate'] = $to_y.'-'.$tom.'-'.$next_d;
			 }else{
						$to_y = $to_y+1;
						$nto_m = 1;
						$tom = '01';
						$next_d = week_one($nto_m,$to_y)-1;
						if (strlen($next_d) < 2) {
								 $next_d = '0'.$next_d;
							}else{
								 $next_d = $next_d+1;
							}
						$periods[$i]['edate'] = $to_y.'-'.$tom.'-'.$next_d;
			 }

				$periods[$i]['id'] = $to_m;
		}
		return $periods;
	}
	//获取每个月第一个星期一
	function week_one($month,$year){
	$first_monday = 7 - date("w", mktime(0, 0, 0, $month, 0, $year)) + 1;
	return $first_monday;
	}
?>