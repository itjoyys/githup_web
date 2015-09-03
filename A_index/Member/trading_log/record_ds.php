<?php
include_once("../../include/config.php");
include_once("../../common/login_check.php");

function double_format($double_num){
	return $double_num>0 ? sprintf("%.2f",$double_num) : $double_num<0 ? sprintf("%.2f",$double_num) : 0;
}

$uid=$_SESSION['uid'];

$sql_union="(select * from k_bet union
SELECT `gid`,uid,'串关',      '','','','','','','','','','',bet_money,'','',bet_win,win,bet_time,'','',`status`,'1',update_time,'','','',`number`,is_jiesuan,balance,'','',assets,www,match_coverdate,fs,'',site_id,agent_id,username from k_bet_cg_group
) as bet";

$map="uid='".$uid."' and site_id='".SITEID."'";

	//时间判断
	if (!empty($_GET['start_date'])) {
	  $s_date = $_GET['start_date'];
	}else{
	  $s_date  = date("Y-m-d");   
	}

	if (!empty($_GET['end_date'])) {
	  $e_date = $_GET['end_date'];   
	}else{
	  $e_date = date("Y-m-d");   
	}
	//订单号查询
	if(empty($_GET['order'])){
		$map .= " and bet_time > '".$s_date." 00:00:00' and bet_time < '".$e_date." 23:59:59'";

		$map.=" order by bet_time desc";
	}else{
		if(preg_match("/^[\W]*$/i",$_GET['order'])){
		    echo '<script>alert("您输入的订单号非法")</script>';
		}else{
			$map .= " and number = '".$_GET['order']."'";
		}
	}
//获得记录总数
$count=M($sql_union,$db_config)->where($map)->count();

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$data = array();
$data = M($sql_union,$db_config)->where($map)->limit($limit)->select();

?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<style>
a{
text-decoration:none;
}
</style>
<link rel="stylesheet" href="../public/css/index_main.css" />
	<link rel="stylesheet" href="../public/css/standard.css" />
	<script  type="text/javascript" src="../public/date/WdatePicker.js"></script>
</head>
<body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
    <div id="MAMain" style="width:767px">
      <div id="MACenter-content">
        <div id="MACenterContent">
          <?php include("common.php") ?>
          <div id="MMainData" style="overflow-y:scroll; height:330px">
            <div class="MControlNav">
            <form id="myFORM" action="" method="GET">
              注单号：<input class="za_text" name="order" value="<?=$_GET['order']?>" onKeyUp="value=value.replace(/[^\w]/ig,'')">
              投注时间：從 <input class="za_text Wdate" readonly="readonly" name="start_date" value="<?=$s_date?>" onclick="WdatePicker()"> 至 <input class="za_text Wdate" readonly="readonly" name="end_date" value="<?=$e_date?>" onclick="WdatePicker()">
              <input type="submit" value=" 查 询 "/>
              <select id="page" name="page" class="za_select"> 
			  <?php  
			    for($i=1;$i<=$totalPage;$i++){
			      if($i==$page){
			        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
			      }else{
			        echo  '<option value="'.$i.'">'.$i.'</option>';
			      }  
			    } 
			   ?>
			  </select> <?=$totalPage?> 頁&nbsp;
			</form>
            </div>
            <!-- 体育今日交易 -->
            <div class="MPanel" style="display: block;">
              <table class="MMain" border="1">
                <tbody>
                  <tr>
                    <th>注单号</th>
                    <th width="80">投注日期</th>
                    <th>投注类型</th>
                    <th>选项</th>
                    <th>投注额</th>
                    <th>可赢金额</th>
                    <th>派彩</th>
                  </tr>
                  <?php 
                  if(!empty($data)){
                  	foreach ($data as $k => $v) {
						$status='';
						if($v["status"]==0)  $status='未结算';
						else if($v["status"]==1)  $status='<span style="color:#FF0000;">赢</span>';
						else if($v["status"]==2)  $status='<span style="color:#00CC00;">输</span>';
                        else if($v["status"]==8)  $status='和局';
                        else if($v["status"]==3)  $status='注单无效';
                        else if($v["status"]==4)  $status='<span style="color:#FF0000;">赢一半</span>';
                        else if($v["status"]==5)  $status='<span style="color:#00CC00;">输一半</span>';
                        else if($v["status"]==6)  $status='进球无效';
                        else if($v["status"]==7)  $status='红卡取消';
                        $bet_money+=$v["bet_money"];
                        $bet_win+=$v["bet_win"];
                        $win+=$v["win"];
                        $color = "#FFFFFF";
                        $over	 = "#EBEBEB";
                        $out	 = "#ffffff";


                        if(($v["balance"]*1)<0 || round($v["assets"]-$v["bet_money"],2) != round($v["balance"],2)){ //投注后用户余额不能为负数，投注后金额要=投注前金额-投注金额
                        $over = $out = $color = "#FBA09B";
                        }elseif($v["match_type"]==1 && strtotime($v["bet_time"])>=strtotime($v["match_endtime"])){ //不是滚球，抽注时间不能>=开赛时间
                            $over = $out = $color = "#FBA09B";
                        }elseif(double_format($v["bet_money"]*($v["ben_add"]+$v["bet_point"])) !== double_format($v["bet_win"])){
                            $over = $out = $color = "#FBA09B";
                        }
                        ?>

						<tr align=center>
							<td><?=$v["number"]?></td>
							<td>
							<?=substr($v["bet_time"],0,10)?>
							<?=substr($v["bet_time"],11,19)?>
							</td>
							<td>
							<a href="javascript:;"><font color="<? if ($v["ball_sort"]=="足球滚球"){echo "#0066FF";}else{echo "#336600";}?>"><b><?=$v["ball_sort"]?></b></font></a>

							</td>
							<td>
								<? if($v['ball_sort']=='串关'){
								$gid	=	$v['bid'];
								$sql_cg	=M('k_bet_cg',$db_config)->where("gid in ($gid)")->order("bid desc")->select();
									$html='';
									foreach ($sql_cg as $key => $value) {
										$html.= '<font color="#CC0000">'.$value['match_name'].'</font><br>';
										$html.= $value['master_guest'].'<br>';
										$html.='<font color="#FF0033">'. $value['bet_info'].'</font><br>';
										if($value['MB_Inball'] !=null && $value['TG_Inball'] !=null) $html.= '['.$value['MB_Inball'].':'.$value['TG_Inball'].']<br>';
										$html.='<div style="height:1px; width:99%; background:#ccc; overflow:hidden;"></div>';
									}
								echo $html;
							}else{?>

							<font color="#CC0000"><?=$v["match_name"]?></font><br><?=$v['master_guest']?>
							<font style="color:#FF0033">
							<?
							if($v["point_column"]==="match_jr" || $v["point_column"]==="match_gj") echo $v["bet_info"];
							else echo '<br>'.str_replace("-","<br>",$v["bet_info"]);
							}
							?>
							</font>
							<? if($v["status"]!=0 && $v["status"]!=3 &&  $v["status"]!=6 && $v["status"]!=7){
							if($v["MB_Inball"]!=''){?>
							[<?=$v["MB_Inball"]?>:<?=$v["TG_Inball"]?>]
							<? } ?>	<br/><?=$v["login_ip"]?>
							<? } ?>
							</td>
							<td><?=$v["bet_money"]?></td>
							<td><?=round($v["bet_win"],2)?></td>
							<td><?=$status?><br><?=$v['status']==0?"0.00":$v["win"]?></td>
							</tr>
							<?php } }else{ ?>
					<tr>
						<td height="70" class="b_cen" style="background-color:#F0F4F6" colspan="7">暂无交易记录。</td>
					</tr>
                 <?php }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
