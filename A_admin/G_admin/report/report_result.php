<?php
// ini_set("errors_display",1);
// error_reporting(E_ALL);
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../lib/video/Games.class.php");

$userRe = array();
$tmpARR = array();
$types = array();

//时间
$date_start = $_POST['date_start'].' 00:00:00';
$date_end = $_POST['date_end'].' 23:59:59';

$reTitles = array('all'=>'总报表','cp'=>'彩票','sp'=>'体育',
                  'ag'=>'AG视讯','og'=>'OG视讯','mg'=>'MG视讯',
                  'mgdz'=>'MG电子','ct'=>'CT视讯','lebo'=>'LEBO视讯',
                  'bbin'=>'BBIN视讯','bbdz'=>'BB电子');

//占成比例
$scale = M('k_user_agent',$db_config)->field("video_scale,sports_scale,lottery_scale")
      ->where("id = '".$_SESSION['agent_id']."'")
      ->find();
//彩票
if (!empty($_POST['cp']) && $_POST['cp'] == '2') 
{	
	$mapFc['agent_id'] = $_SESSION['agent_id'];
	$mapFc['addtime'] = array(
	                      array('>=',$_POST["date_start"].' 00:00:00'),
	                      array('<=',$_POST["date_end"].' 23:59:59')
	                     );
	$fcBet = M('c_bet',$db_config)
	       ->field("distinct uid,username,count(id) as num ,sum(case when status in (1,2) then money end) as bet,sum(money) as betAll,sum(case when js = 1 then win end) as win")
	       ->where($mapFc)->group('uid')->select("username");

	$types['cp']['type'] = 'cp';
	if (!empty($fcBet)) {
		//数组重组
    	foreach ($fcBet as $fk => $fv) {
		   $fcBet[$fk]['income'] = ($fv['bet']-$fv['win'])*$scale['lottery_scale'];
		   $fcBet[$fk]['delivery'] = ($fv['bet']-$fv['win'])*(1-$scale['lottery_scale']);
	    }
		$tmpARR = $fcBet;
		$types['cp']['keys'] =  array_keys($fcBet);
		$userRe['cp'] = $fcBet;
	}
	
    
}


//体育
if (!empty($_POST['sp']) && $_POST['sp'] == '1') 
{
	$mapSp['agent_id'] = $_SESSION['agent_id'];
	$mapSp['bet_time'] = array(
	                      array('>=',$_POST["date_start"].' 00:00:00'),
	                      array('<=',$_POST["date_end"].' 23:59:59')
	                     );
	$spBet = M('k_bet', $db_config)
	       ->field("distinct uid,username,sum(case when status in (1,2,4,5) then bet_money end) as bet,sum(bet_money) as betAll,sum(case when is_jiesuan = 1 then win end) as win,count(bid) as num")
	       ->where($mapSp)->group('uid')->select("username");
	//串关
	$spc_bet = M('k_bet_cg_group', $db_config)
	         ->field("distinct uid,username,sum(case when status in (1,2) then bet_money end) as bet,sum(bet_money) as betAll,sum(case when is_jiesuan = 1 then win end) as win,count(gid) as num")
	         ->where($mapSp)->group('uid')->select("username");

	if (!empty($spBet)) {
		if (!empty($spc_bet)) {
		    $spcKEY = array_keys($spc_bet);
		    foreach ($spBet as $key => $val) {
			  if(array_key_exists($key,$spcKEY)){
			  	 $spBet[$key]['bet'] += $spc_bet[$key]['bet'];
			     $spBet[$key]['win'] += $spc_bet[$key]['win'];
			     $spBet[$key]['num'] += $spc_bet[$key]['num'];
			     unset($spc_bet[$key]);
			  }
			}
			
			
		}
		$types['sp']['keys'] =  array_keys($spBet);
	    $userRe['sp'] = $spBet;
		
	}else{
		if (!empty($spc_bet)) {
			$types['sp']['keys'] =  array_keys($spc_bet);
			$userRe['sp'] = $spc_bet;
		}
		
	}
	if (!empty($userRe['sp'])) {
		//数组重组
    	foreach ($userRe['sp'] as $sk => $sv) {
		   $userRe['sp'][$sk]['income'] = ($sv['bet']-$sv['win'])*$scale['sports_scale'];
		   $userRe['sp'][$sk]['delivery'] = ($sv['bet']-$sv['win'])*(1-$scale['sports_scale']);
	    }
		$tmpARR  += $userRe['sp'];
	}
	$types['sp']['type'] = 'sp';
}

//视讯
if (!empty($_POST['g_type'])) {
	$games = new Games();
	$videoBet = array();
	$date = array();
	$date[0] = $_POST["date_start"].' 00:00:00';
	$date[1] = $_POST["date_end"].' 23:59:59';

	foreach ($_POST['g_type'] as $key => $val) {
		 $types[$val]['type'] = $val;
		 if ($val == 'mgdz') {
              $vBet = $games->GetUserAvailableAmountByAgentid('mg',$_SESSION['agent_id'], $date[0], $date[1],1);
          }elseif($val == 'bbdz'){
              $vBet = $games->GetUserAvailableAmountByAgentid('bbin',$_SESSION['agent_id'], $date[0], $date[1],5);
          }else{
              $vBet = $games->GetUserAvailableAmountByAgentid($val,$_SESSION['agent_id'], $date[0], $date[1]);
          }
		$tmpvBet = json_decode($vBet);
		if (!empty($tmpvBet->data->data)) {
		  foreach ($tmpvBet->data->data as $key => $v) {

			 $userRe[$val][$v->username]['username'] = $v->username;
			 $userRe[$val][$v->username]['bet'] = $v->BetYC;
			 $userRe[$val][$v->username]['win'] =$v->BetPC+$v->BetYC;;
			 $userRe[$val][$v->username]['income'] = (-$v->BetPC)*$scale['video_scale'];
			 $userRe[$val][$v->username]['delivery'] = (-$v->BetPC)*(1-$scale['video_scale']);
			 $userRe[$val][$v->username]['num'] = $v->BetBS;
			 $userRe[$val][$v->username]['betAll'] = $v->BetAll;

			}
		}

		if (!empty($userRe[$val])) {
			$types[$val]['keys'] =  array_keys($userRe[$val]);
				//数组重组
	    	$tmpARR  += $userRe[$val];
	    }
	}	
}

//总报表
$ALL = array();
foreach ($tmpARR as $k_tmp => $v_tmp) {
	$userRe['all'][$k_tmp]['income'] = 0;
	$userRe['all'][$k_tmp]['delivery'] = 0;
	$userRe['all'][$k_tmp]['bet'] = 0;
	$userRe['all'][$k_tmp]['win'] = 0;
	$userRe['all'][$k_tmp]['betAll'] = 0;
foreach ($types as $m => $n) {
	if (isset($n['keys']) && array_key_exists($k_tmp, array_flip($n['keys']))) {

		$userRe['all'][$k_tmp]['username'] = $k_tmp;
		$userRe['all'][$k_tmp]['bet'] += $userRe[$m][$k_tmp]['bet'];
		$userRe['all'][$k_tmp]['num'] += $userRe[$m][$k_tmp]['num'];
		$userRe['all'][$k_tmp]['win'] += $userRe[$m][$k_tmp]['win'];
		$userRe['all'][$k_tmp]['delivery'] += $userRe[$m][$k_tmp]['delivery'];
		$userRe['all'][$k_tmp]['income'] += $userRe[$m][$k_tmp]['income'];
		$userRe['all'][$k_tmp]['betAll'] += $userRe[$m][$k_tmp]['betAll'];
	}
}	
}

$userRe = array_reverse($userRe);

$titleR = array('会员名稱','總筆數','總下注金額','總有效金額','總派彩','代理占成','代理退水','代理損益');

?>

<?php $title="报表明细"; require("../common_html/header.php");?>
<body> 
<style type="text/css">
table.m_tab th span{
	margin-right:8px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	  $("td").each(function () {
          if (parseFloat($(this).text())<0) {
        	  $(this).css("color","red");
          }
      })
	});
	  
$(document).ready(function(){  
	$("#m_tab1").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列是可以保证正负数值正确排序，改动时请注意列的对应
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});
	$("#m_tab2").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});	
	$("#m_tab3").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}				
		}
	});	
	$("#m_tab4").tablesorter({
		sortList:false,
		headers:{
			2:{
				sorter:'links'
			},
			4:{
				sorter:'links'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});	
	//$("th[id^=target]").css("background-color","#527A98");
} );
$.tablesorter.addParser({
    // set a unique id 
    id: 'links',
    is: function(s)
    {
        // return false so this parser is not auto detected 
        return false;
    },
    format: function(s)
    {
        // format your data for normalization 
        return s.replace(new RegExp(/<.*?>/),"");
    },
    // set type, either numeric or text
    type: 'numeric'
}); 
</script>
<div id="con_wrap">
  <div class="input_002">報表查詢</div>
  <div class="con_menu">
  	<a href="javascript:history.go(-1);">返回上一頁</a>
  </div>
</div>
<div class="content" id="content_report">

<?php 
  if (!empty($userRe)) {
     foreach ($userRe as $key => $val){ 

     	?>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab1" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="14" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left"><?=$reTitles[$key]?> 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
        <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	 <?php foreach ($titleR as $ktit => $vtit): ?>
		 <th rowspan="2" nowrap="nowrap" style="font-size:14px;width:100px;" class="header"><span><?=$vtit?></span></th>
	  <?php endforeach ?>
	  <td colspan="3" style="text-align:center;font-size:12px;background-color: #67030F;color:#122858:width:650px;"><span>總各層交收</span></td>
    </tr>
	<tr class="m_title">
        <th class="header"><span>會員</span></th>
        <th class="header"><span>代理商</span></th>
        <th class="header"><span>代理商所得</span></th>
	</tr>
	</thead>
	<tbody>
       <?php 
           $Tnum = $TbetAll = $Tbet= $Tdelivery= $Twin= $Tbw = 0;
       	   foreach ($val as $ka => $va){ 
              $Tnum+= $va['num'];
              $TbetAll += $va['betAll'];
              $Tbet += $va['bet'];
              $Twin += $va['win'];
              $Tbw += $va['bet']-$va['win'];
              $Tfs = 0;
              $Tincome += $income;
              $Tdelivery += $va['delivery'];
       	   	?>
			<tr class="m_rig" align="left">
				<td align="center"><?=$va['username']?></td>
				<td><?=$va['num']?></td>
				<td><?=$va['betAll']?></td>
				<td><?=$va['bet']?></td>
				<td><?=$va['win']?></td>
				<td><?=($va['bet']-$va['win'])?></td>
				<td>0.00</td>
				<td><?=($va['bet']-$va['win'])?></td>
		        <td><?=($va['bet']-$va['win'])?></td>
		        <td><?=$va['delivery']?></td>
		        <td><?=$va['income']?></td>
				
			</tr>
        <?php } ?>
	</tbody>
	<tfoot>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$Tnum?></td>
		<td><?=$TbetAll?></td>
		<td><?=$Tbet?></td>
		<td><?=$Twin?></td>
		<td><?=$Tbw?></td>
		<td>0.00</td>
		<td><?=$Tbw?></td>
        <td><?=$Tbw?></td>
        <td><?=$Tdelivery?></td>
        <td><?=$Tincome?></td>
  
     
	</tr>
	</tfoot>
</table>
<br>
<?php }}else{ ?>
		<p align="center">暂无数据</p>
<?php }?>
<?php 
//返回标题
function returnRtite($type){
	switch ($type) {
		case 'value':
			# code...
			break;
		
		default:
			# code...
			break;
	}
}

?>
<?php include("../common_html/footer.php"); ?>