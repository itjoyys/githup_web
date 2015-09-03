<?php
set_time_limit(0);
ini_set("error_reporting",1);
ini_set("display_errors",1);
include_once ("../../include/config.php");
include_once ("../common/login_check.php");
//include("../../class/agentCenter.class.php");

$start_date = $_POST['date_start'] . " 00:00:00";
$end_date = $_POST['date_end'] . " 23:59:59";

//$mg_type_video = array('Diamond LG Baccarat','Diamond LG Casino Holdem');
//$mg_type_games = array('3 Reel Slot Games','5 Reel Slot Games','Soft Game','Bonus Screen Slots','Bingo','Roulette','Table Games');

//获取对应会员
if ($_POST['wtype'] == 1) {
    //返水状态
    if (empty($_POST['sh_name'])) {
        exit('system error 0000');
    }
    //层级
    if (!empty($_POST['level'])) {
        $level_str = implode(',',$_POST['level']);
        $level_title = '(层级类型)';
    }else{
        $level_str = 0;
    }
    
    $agent_ids = explode('-',$_POST['sh_name']);
    $_POST['agent_name'] = $agent_ids[0];
    $index_id = $agent_ids[1];
    
    $mapCg['back_time_start'] = $_POST['date_start'];
    $mapCg['back_time_end'] = $_POST['date_end'];
    $mapCg['agent_id'] = $_POST['agent_name'];
    $mapCg['money'] = array('>','0');//全部冲销,可再次返水
    $stateCg = M('k_user_discount_search',$db_config)
             ->where($mapCg)->select();
    if (!empty($stateCg)) {
        $stateCg = "已返水";
    }

    $db_config['host'] = $_DBC['video']['host'];
    $db_config['user'] = $_DBC['video']['user'];
    $db_config['pass'] = $_DBC['video']['pwd'];
    $db_config['dbname'] = $_DBC['video']['dbname'];
    $disType= array('ag','og','mg','ct','lebo','bbin');

    $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                          );
    $ARR = array();
    //AG视讯
    $str_name = SITEID.'%';
    $map['player_name'] = array('like',$str_name);
    $ARR = M('ag_bet_record',$db_config)->field("player_name,sum(valid_betamount) as ag_bet")->where($map)->group("player_name")->select('player_name');
    unset($map['player_name']);

    //BBIN视讯
    $map['payoff'] = array('<>','0.0000');
    $map['site_id'] = SITEID;
    $bbin_arr= M('bbin_bet_record',$db_config)->field("username,sum(betamount) as bbin_bet")->where($map)->group("username")->select('username');
    unset($map['payoff']);
    if (!empty($ARR) && !empty($bbin_arr)) {
        $ARR = array_merge_recursive($ARR,$bbin_arr);
    }elseif (empty($ARR) && !empty($bbin_arr)) {
        $ARR = $bbin_arr;
    }
    unset($bbin_arr);

    //CT视讯
    $ct_arr = M('ct_bet_record',$db_config)->field("member_id,sum(availablebet) as ct_bet")->where($map)->group("member_id")->select('member_id');
    if (!empty($ARR) && !empty($ct_arr)) {
        foreach($ct_arr as $tk => $v){
          $kname[] = str_replace('@','',$tk); 
        }
        $ct_arr = array_combine($kname,array_slice($ct_arr,0));
        $ARR = array_merge_recursive($ARR,$ct_arr);
    }elseif (empty($ARR) && !empty($ct_arr)) {
        foreach($ct_arr as $tk => $v){
          $kname[] = str_replace('@','',$tk); 
        }
        $ct_arr = array_combine($kname,array_slice($ct_arr,0));
        $ARR = $ct_arr;
    }
    unset($ct_arr);

    //LEBO视讯
    $lebo_arr = M('lebo_bet_record',$db_config)->field("member,sum(valid_betamount) as lebo_bet")->where($map)->group("member")->select('member');
    if (!empty($ARR) && !empty($lebo_arr)) {
        $ARR = array_merge_recursive($ARR,$lebo_arr);
    }elseif (empty($ARR) && !empty($lebo_arr)) {
        $ARR = $lebo_arr;
    }
    unset($lebo_arr);

    //MG
    $mg_arr = M('mg_bet_record',$db_config)->field("account_number,sum(income) as mg_bet")->where($map)->group('account_number')->select('account_number');
    if (!empty($ARR) && !empty($mg_arr)) {
        $ARR = array_merge_recursive($ARR,$mg_arr);
    }elseif (empty($ARR) && !empty($mg_arr)) {
        $ARR = $mg_arr;
    }
    unset($mg_arr);

    //OG视讯
    $og_arr = M('og_bet_record',$db_config)->field("user_name,sum(valid_amount) as og_bet")->where($map)->group("user_name")->select('user_name');
    if (!empty($ARR) && !empty($og_arr)) {
        $ARR = array_merge_recursive($ARR,$og_arr);
    }elseif (empty($ARR) && !empty($og_arr)) {
        $ARR = $og_arr;
    }
    unset($og_arr);

    $db_config['host'] = $_DBC['private']['host'];
    $db_config['user'] = $_DBC['private']['user'];
    $db_config['pass'] = $_DBC['private']['pwd'];
    $db_config['dbname'] = $_DBC['private']['dbname'];
    //彩票
    $agent_obj = M('k_user_agent',$db_config);
    $sh_id = $agent_obj->field("id")->where("pid = '".$_POST['agent_name']."'")->select("id");
    if (!empty($sh_id)) {
        $sh_id = implode(',',array_keys($sh_id));
    }else{
        exit('System error 0000');
    }
    $map_sh = array();
    $map_sh['pid'] = array('in','('.$sh_id.')');
    $map_sh['site_id'] = SITEID;
    $agents = array();
    $agents = $agent_obj->field("group_concat(id) as agents")->where($map_sh)->find();
    $agents_arr = array();
    $map_arr = array();
    $map_arr['pid'] = array('in','('.$sh_id.')');
    $map_arr['site_id'] = SITEID;
    $agents_arr = $agent_obj->field("agent_user,id")
                 ->where($map_arr)->group('id')->select('id');

    $fcBet = array();
    $mapFc = array();
    $mapFc['agent_id'] = array('in','('.$agents['agents'].')');
    $mapFc['status'] = array('in','(1,2)');
    $mapFc['update_time'] = array(
                      array('>=',$start_date),
                      array('<=',$end_date)
    );
    $fcBet = M('c_bet', $db_config)
            ->field("sum(money) as fc_bet,username")
            ->where($mapFc)->group('uid')
            ->select('username');
    if (!empty($fcBet)) {
        $fc_arr = array();
        foreach ($fcBet as $fk => $fv) {
            $fk = SITEID.$fk;
            $fc_arr[$fk] = $fv; 
        }
        if (!empty($ARR)) {
            $ARR = array_merge_recursive($ARR,$fc_arr);
        }else{
            $ARR = $fc_arr;
        }
    }
    unset($fc_arr);
    unset($fcBet);
    //体育
    $spBet = array();
    $mapSp = array();
    $mapSp['agent_id'] = array('in','('.$agents['agents'].')');
    $mapSp['status'] = array('in','(1,2,4,5)');
    $mapSp['update_time'] = array(
                      array('>=',$start_date),
                      array('<=',$end_date)
                      );

    $spBet = M('k_bet', $db_config)
            ->field("sum(bet_money) as sp_bet,username")
            ->where($mapSp)->group('uid')
            ->select('username');
    if (!empty($spBet)) {
        foreach ($spBet as $sk => $sv) {
            $sk = SITEID.$sk;
            $sp_arr[$sk] = $sv; 
        }
        if (!empty($ARR)) {
            $ARR = array_merge_recursive($ARR,$sp_arr);
        }else{
            $ARR = $sp_arr;
        } 
    }
    unset($sp_arr);
    unset($spBet);
    //体育串关
    unset($mapSp['status']);
    $mapSp['status'] = array('in','(1,2)');
    $spc_bet = M('k_bet_cg_group', $db_config)
             ->field("sum(bet_money) as sp_cg_bet,username")
             ->where($mapSp)->group('uid')
             ->select('username');
    if (!empty($spc_bet)) {
        foreach ($spc_bet as $sck => $scv) {
            $sck = SITEID.$sck;
            $spc_arr[$sck] = $scv; 
        }
        if (!empty($ARR)) {
            $ARR = array_merge_recursive($ARR,$spc_arr);
        }else{
            $ARR = $spc_arr;
        } 
    }
    //优惠返点
    $map_dis = array();
    $map_dis['site_id'] = SITEID;
    if (!empty($index_id)) {
        $map_dis['index_id'] = $index_id;
    }
    $discount = M('k_user_discount_set', $db_config)
                    ->where($map_dis)
              ->order('count_bet desc')
              ->select();
    // 层级条件
    $map_level = array();
    $map_level['site_id'] = SITEID;
    if (!empty($_POST['level'])) {
        $levelStr;
        $levelStr = implode(',', $_POST['level']);
        //$map_level = "level_id in ($levelStr) and ";
        $map_level['level_id'] = array('in','('.$levelStr.')');
    }
    //层级信息
    $level_arr = M('k_user_level',$db_config)
                 ->field("id,level_des")
                 ->where("site_id = '".SITEID."'")->select('id');
    //数据处理iundhj999
    $user_obj = M('k_user',$db_config);
    $i = 0;
    $map_level['agent_id'] = array('in','('.$agents['agents'].')');
    foreach ($ARR as $key => $val) {
        $tmp_user = str_replace(SITEID,'',$key);
        $map_level['username'] = $tmp_user;
        $user_data = array();
        $user_data = $user_obj->field("level_id,uid,agent_id")->where($map_level)->find();
        if (empty($user_data)) {
            continue; 
        }
        $userIds[$i]['username'] = $tmp_user;
        $userIds[$i]['level_id'] = $user_data['level_id'];
        $userIds[$i]['level_des'] = $level_arr[$user_data['level_id']]['level_des'];
        $userIds[$i]['uid'] = $user_data['uid'];
        $userIds[$i]['agent_user'] = $agents_arr[$user_data['agent_id']]['agent_user'];
        $userIds[$i]['betall'] = $val['fc_bet']+$val['sp_bet']
                      +$val['mg_bet']+$val['ag_bet']+$val['og_bet']
                      +$val['ct_bet']+$val['lebo_bet']+$val['bbin_bet']
                      +$val['sp_cg_bet'];

        foreach ($discount as $dt => $dv) {
            if ($dv['count_bet'] <= $userIds[$i]['betall']) {
                $re = $dv;
                break;
            }
        }
        
        unset($val['player_name']);
        unset($val['member']);
        unset($val['account_number']);
        unset($val['member_id']);
        $userIds[$i]['fc_bet'] = $val['fc_bet']+0;
        $userIds[$i]['sp_bet'] = $val['sp_bet']+$val['sp_cg_bet']+0;
        $userIds[$i]['mg_bet'] = sprintf("%.2f",($val['mg_bet']+0));
        $userIds[$i]['ag_bet'] = sprintf("%.2f",($val['ag_bet']+0));
        $userIds[$i]['og_bet'] = sprintf("%.2f",($val['og_bet']+0));
        $userIds[$i]['ct_bet'] = sprintf("%.2f",($val['ct_bet']+0));
        $userIds[$i]['lebo_bet'] = sprintf("%.2f",($val['lebo_bet']+0));
        $userIds[$i]['bbin_bet'] = sprintf("%.2f",($val['bbin_bet']+0));
        $userIds[$i]['fc_fd'] = sprintf("%.2f",$val['fc_bet']*$re['fc_discount']*0.01);
        $userIds[$i]['sp_fd'] = sprintf("%.2f",$userIds[$i]['sp_bet']*$re['sp_discount']*0.01);
        $userIds[$i]['mg_fd'] = sprintf("%.2f",$val['mg_bet']*$re['mg_discount']*0.01);
        $userIds[$i]['ag_fd'] = sprintf("%.2f",$val['ag_bet']*$re['ag_discount']*0.01);
        $userIds[$i]['og_fd'] = sprintf("%.2f",$val['og_bet']*$re['og_discount']*0.01);
        $userIds[$i]['ct_fd'] = sprintf("%.2f",$val['ct_bet']*$re['ct_discount']*0.01);
        $userIds[$i]['lebo_fd'] = sprintf("%.2f",$val['lebo_bet']*$re['lebo_discount']*0.01);
        $userIds[$i]['bbin_fd'] = sprintf("%.2f",$val['bbin_bet']*$re['bbin_discount']*0.01);
        $userIds[$i]['total_e_fd'] =  $userIds[$i]['fc_fd'] + 
                                      $userIds[$i]['sp_fd'] + 
                                      $userIds[$i]['mg_fd'] + 
                                      $userIds[$i]['ag_fd'] + 
                                      $userIds[$i]['ct_fd'] + 
                                      $userIds[$i]['og_fd'] + 
                                      $userIds[$i]['bbin_fd']+ 
                                      $userIds[$i]['lebo_fd'];
        $i++;
    }


    
} elseif ($_POST['wtype'] == 2) {
    if (! empty($_POST['members'])) {
        $usernames = $_POST['members'];
        $usernames = "'" . str_replace(",", "','", $usernames) . "'"; // 字符串元素加单引号
        $map = '';
        $map = "k_user.site_id = '".SITEID."' and k_user.shiwan = 0 and k_user.username in ($usernames) ";
        $userIds = M('k_user', $db_config)->join("join k_user_level on k_user.level_id = k_user_level.id")
            ->field("k_user.uid,k_user.agent_id,k_user_level.level_des,k_user.username,k_user.money")
            ->where($map)
            ->select();
        if (!empty($userIds)) {
            $dataArr = array($_POST['date_start'],$_POST['date_end']);
            $g_type = array('ag','og','mg','ct','bbin','lebo');
            include("../../class/Discount.class.php");
            $userIds = Discount::DayDiscount($userIds,$dateArr,$g_type);
        }else{    
            message('会员账号不存在！');
        }
    } else {
        message('请输入会员账号!');
    }
}


?>

<?php require("../common_html/header.php");?>
<body>
	<script type="text/javascript" src="../public/js/report_func.js"></script>
	<script>

function ckall(){
    for (var i=0;i<document.myFORM.elements.length;i++){
      var e = document.myFORM.elements[i];
    if (e.name != 'checkall' ) 
      e.checked = document.myFORM.checkall.checked;
  }
}
$(document).ready(function(){
  $("#ckzero").click( function () { 
      $(".m_cen").each(function () {
          if (parseFloat($(this).find('td:last').text())<=0) {
            if($('#ckzero').attr("checked")==undefined){
              $(this).find(':checkbox').attr("checked",false);
            }else{
              $(this).find(':checkbox').attr("checked",true);
            } 
            //$(this).css("color","red");
          }
      })
  });
  
  $("#cknozero").click( function () { 
      $(".m_cen").each(function () {
          if (parseFloat($(this).find('td:last').text())>0) {
            if($('#cknozero').attr("checked")==undefined){
              $(this).find(':checkbox').attr("checked",false);
            }else{
              $(this).find(':checkbox').attr("checked",true);
            } 
            //$(this).css("color","red");
          }
      })
  });
});
function check(){
    var len = document.myFORM.elements.length;
  var num = false;
    for(var i=0;i<len;i++){
    var e = document.myFORM.elements[i];
        if(e.checked && e.name=='uid[]'){
      num = true;
      break;
    }
    }
  if(num){
    if($("#remark").val()=="")
    {
      alert("請輸入事件名稱！");return false; 
    }
  }else{
        alert("您未选中任何复选框");
        return false;
    }
}

</script>
	<div id="con_wrap">
		<div class="input_002">優惠統計</div>
		<div class="con_menu">
			<a href="./discount_index.php" style="color: red;">優惠統計</a> <a
				href="./discount_search.php">優惠查詢</a> <a href="discount_set.php">返點優惠設定</a>
			<a href="./reg_discount_set.php">申請會員優惠設定</a> <a
				href="javascript:window.history.go(-1)">返回上一頁</a>
		</div>
	</div>
    <div id="progress"></div>
	<div class="content">
		<form method="post" name="myFORM" action="./discount_count_w.php" onsubmit="return check();">
		<input type="hidden" name="save" value="true">
        <input type="hidden" name="level_str" value="<?=$_POST['level_str']?>"> 
        <input type="hidden" name="date_start" value="<?=$_POST['date_start']?>"> 
        <input type="hidden" name="agent_id" value="<?=$_POST['agent_name']?>"> 
             <input type="hidden"
				name="date_end" value="<?=$_POST['date_end']?>">
			<table width="99%" class="m_tab">
				<tbody>
					<tr class="m_title">
						<td colspan="26" height="27" align="center">日期：<?=$_POST['date_start']?> ~ <?=$_POST['date_end']?>  </td>
					</tr>
					<tr>
						<td colspan="26" align="center" class="table_bg1">事件名稱：&nbsp;&nbsp;<input
							type="text" value="<?=$_POST['date_start'].'~'.$_POST['date_end'].$level_title?>" name="remark"
							id="remark" class="za_text" size="30">&nbsp;&nbsp;&nbsp;
							綜合打碼量：&nbsp;&nbsp;<input type="text" name="zhbet"
							id="normality" value="0" class="za_text"
							style="min-width: 50px; width: 50px">倍&nbsp;&nbsp;&nbsp; <input
							type="submit" name="submit" class="za_button" value="存入"></td>
					</tr>
					<tr>
						<td colspan="26">所有:<input type="checkbox" name="checkall"
							id="checkall" title="所有" onclick="return ckall();"> 0:<input
							type="checkbox" name="ckzero" title="等于0" id="ckzero"> 大于0:<input
							type="checkbox" name="cknozero" title="大于0" id="cknozero"></td>
					</tr>
					<tr class="m_title">
						<td rowspan="2">選擇</td>
						<td rowspan="2">代理商</td>
						<td rowspan="2">會員</td>
						<td rowspan="2">層級</td>
						<td rowspan="2">有效總<br>投注
						</td>
						<td colspan="8">有效投注</td>
						<td colspan="8">返點</td>
						<td rowspan="2">返點小計</td>
					</tr>
					<tr class="m_title">
						<td>體育</td>
						<td>彩票</td>
						<td>AG視訊</td>
						<td>MG視訊</td>
						<td>OG視訊</td>
						<td>CT視訊</td>
						<td>LEBO視訊</td>
						<td>BBIN視訊</td>
						<td>體育</td>
						<td>彩票</td>
						<td>AG視訊</td>
            <td>MG視訊</td>
            <td>OG視訊</td>
            <td>CT視訊</td>
            <td>LEBO視訊</td>
            <td>BBIN視訊</td>
					</tr>
      
      <?php
if (!empty($userIds)) {
        $redis_dis = 'discount'.CLUSTER_ID.'_'.SITEID;
        foreach ($userIds as $key => $val) {
            $totalBet += $val['betall'];
            $toSpBet  += $val['sp_bet'];
            $toFcBet  += $val['fc_bet'];
            $toMgBet  += $val['mg_bet'];
            $toAgBet  += $val['ag_bet'];
            $toOgBet  += $val['og_bet'];
            $toCtBet  += $val['ct_bet'];
            $toLeboBet += $val['lebo_bet'];
            $toBbinBet += $val['bbin_bet'];
            //$toBbdzBet += $val['bbdz_bet'];//bbin电子
           // $toMgdzBet += $val['mgdz_bet'];//mg电子
            
            $toSpfd += $val['sp_fd'];
            $toFcfd += $val['fc_fd'];
            $toMgfd += $val['mg_fd'];
            $toAgfd += $val['ag_fd'];
            $toOgfd += $val['og_fd'];
            $toCtfd += $val['ct_fd'];
            $toLebofd += $val['lebo_fd'];
            $toBbinfd += $val['bbin_fd'];
            //$toBbdzfd += $val['bbdz_fd'];
            //$toMgdzfd += $val['mgdz_fd'];
            // 根据下注返回对应返点   
            $countJson = json_encode($val, JSON_UNESCAPED_UNICODE);
            $countJson = str_replace('"', '-', $countJson);
            $hset = array();
            $hset[$val['uid']] = $countJson;

            if ($key == ($i-1)) {
               $redis->hmset($redis_dis,$hset);
            }
            $total_fd += $val['total_e_fd'];
            ?>
	
    <tr class="m_cen">
		<td align="center">
        <?php
           if ($_POST['wtype'] == 2) {
               $userTmpdate = md5($_POST['date_start'].$_POST['date_end']);//时间字符串
            //查询此时间段所有返水
               $disArr = M('k_user_discount_count',$db_config)
                       ->field('id')
                       ->where("date = '".$userTmpdate."' and uid='".$val['uid']."'")
                       ->find();
               if ($disArr) {
                  $stateCg = "已返水";
               }
           }


            if (!empty($stateCg) && empty($level_str)) {
                echo $stateCg;
            }else{
        ?>
            <input name="uid[]" type="checkbox" value="<?=$countJson?>">
        <?php }?>
        </td>
						<td nowrap="nowrap"><?=$val['agent_user']?></td>
						<td nowrap="nowrap"><?=$val['username']?></td>
						<td nowrap="nowrap"><?=$val['level_des']?></td>
						<td nowrap="nowrap"><?=$val['betall']?></td>
						<td><?=$val['sp_bet']?></td>
						<td><?=$val['fc_bet']?></td>
						<td><?=$val['ag_bet']?></td>
						<td><?=$val['mg_bet']?></td>
						<td><?=$val['og_bet']?></td>
						<td><?=$val['ct_bet']?></td>
						<td><?=$val['lebo_bet']?></td>
						<td><?=$val['bbin_bet']?></td>
						<td><?=$val['sp_fd']?></td>
						<td><?=$val['fc_fd']?></td>
						<td><?=$val['ag_fd']?></td>
						<td><?=$val['mg_fd']?></td>
						<td><?=$val['og_fd']?></td>
						<td><?=$val['ct_fd']?></td>
						<td><?=$val['lebo_fd']?></td>
						<td><?=$val['bbin_fd']?></td>
						<td><?=$val['total_e_fd']?></td>
					</tr>
   <?php
            $iNum ++;
        }
    }
    ?>
    <tr class="m_title">
						<td align="center">總計：</td>
						<td colspan="3">總人數：<?=$iNum?>人</td>
						<td><?=$totalBet?></td>
						<td><?=$toSpBet?></td>
						<td><?=$toFcBet?></td>
						<td><?=$toAgBet?></td>
						<td><?=$toMgBet?></td>
						<td><?=$toOgBet?></td>
						<td><?=$toCtBet?></td>
            <td><?=$toLeboBet?></td>
						<td><?=$toBbinBet?></td>
						<td><?=$toSpfd?></td>
						<td><?=$toFcfd?></td>
						<td><?=$toAgfd?></td>
						<td><?=$toMgfd?></td>
						<td><?=$toOgfd?></td>
						<td><?=$toCtfd?></td>
            <td><?=$toLebofd?></td>
						<td><?=$toBbinfd?></td>
						<td><?=$total_fd?></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
