<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(0);
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../common/user_set.php");
include_once("../../class/Level.class.php");

$type=$_GET['type'];

$uid	=	'';
if($_GET['username']){
    $sql		=	"select uid from k_user where  site_id='".SITEID."' and username='".$_GET['username']."'  limit 1";
    $query	=	$mysqlt->query($sql);
    if($rows	=	$query->fetch_array()){
        $uid=	$rows['uid'];
    }
    else{
        $uid = 0;
    }
}
$sql='';
$sql_union="(select * from k_bet union
SELECT `gid`,uid,'串关',      '','','','','','','','','','',bet_money,'','',bet_win,win,bet_time,'','',`status`,'1',update_time,'','','',`number`,is_jiesuan,balance,'','',assets,www,match_coverdate,fs,'',site_id,username, agent_id from k_bet_cg_group
) as bet";
/*echo  $sql="select b.*,u.username,u.is_daili,u.top_uid,u.money,u.agent_id,u.login_ip from (select * from k_bet
union
SELECT gid,uid,'串关',      '','','','','','','','','','',bet_money,'','',bet_win,win,bet_time,'','',`status`,'1',update_time,'','','','',is_jiesuan,balance,'','',assets,www,match_coverdate,fs,'',site_id from k_bet_cg_group
) b,k_user u where b.uid=u.uid    and b.site_id='".SITEID."' order by $order desc";
 $sql	=	"select b.*,u.username,u.is_daili,u.top_uid,u.money,u.agent_id,u.login_ip
*/
$sql_all	=	"select *  from $sql_union where ";
if(isset($_GET["lose_ok"])){
    $sql.=" lose_ok>=0 ";
}else{
    if($_GET['ltype']!=3) $sql.=" lose_ok=1 ";
}
//if($_GET["lose_ok"]=='all') $sql.=" lose_ok>=0 ";
//注单状态
if(isset($_GET["ltype"])){
    switch ($_GET['ltype']){
        case '0':
            $sql .= ' and status=0'; break;
        case '1':
            $sql .= 'and status!=0'; break;
        case '2':
            $sql .= ' and status in (3,6,7)'; break;
        case '3':
            $sql .= '  lose_ok=0 '; break;
        default:
            break;
    }
}
//默认当天00:00开始，23:59结束
$date_start=$date_end=date('Y-m-d');
$hour_start=$minute_start=$second_start='00';
$hour_end='23';
$minute_end=$second_end='59';
//时间区间选择
if(!empty($_GET['date_start'])){
    $date_start = $_GET['date_start'];
    if(isset($_GET['hour_start'])){
        $hour_start = $_GET['hour_start'];
        if(isset($_GET['minute_start'])){
            $minute_start = $_GET['minute_start'];
            if(isset($_GET['second_start'])){
                $second_start = $_GET['second_start'];
            }
        }
    }
}
if(!empty($_GET['date_end'])){
    $date_end = $_GET['date_end'];
    if(isset($_GET['hour_end'])){
        $hour_end = $_GET['hour_end'];
        if(isset($_GET['minute_end'])){
            $minute_end = $_GET['minute_end'];
            if(isset($_GET['second_end'])){
                $second_end = $_GET['second_end'];
            }
        }
    }
}

//下注金额
if($_GET['match_id']){
    if(isset($_GET["date_start"])) $sql.=" and bet_time>='".$_GET["date_start"]." 00:00:00'";
    if(isset($_GET["date_end"])) $sql.=" and bet_time<='".$_GET["date_end"]." 23:59:59'";
}
else{
    $sql.=" and bet_time between '".$date_start." 00:00:00' and '".$date_end." 23:59:59'";

}


if($_GET["number"]>=0 && $_GET["number"]!='' )  $sql="  `number` = '".$_GET["number"]."'";
if(!empty($_GET["money"])){
    $sql .= ' and bet_money>'.$_GET["money"];
}

if($type) $sql.=" and ball_sort like('$type%')";
if($_GET["uid"]) $uid = $_GET["uid"];
if($uid>=0 && $uid!='') {$sql.=" and uid=".$uid;}
if(!empty($_GET["match_id"])) $sql.=" and match_id=".$_GET["match_id"];
if(isset($_GET["match_name"])) $sql.=" and match_name='".urldecode($_GET["match_name"])."'";
if(isset($_GET["ball_sort"])) $sql.=" and ball_sort='".urldecode($_GET["ball_sort"])."'";
if(!empty($_GET["point_column"])) $sql.=" and point_column='".strtolower($_GET["point_column"])."'";
if(isset($_GET["column_like"])) $sql.=" and point_column like'%".strtolower($_GET["column_like"])."%'";
if(isset($_GET["match_type"])) $sql.=" and match_type=".intval($_GET["match_type"]);
if(isset($_GET["www"])) $sql.=" and www=".$_GET["www"];

//if($_GET["match_id"] && isset($_GET["date_start"]) && isset($_GET["date_end"])) $sql.=" and match_coverdate between '".date('Y-'.$_GET["date_start"])." 00:00:00' and '".date('Y-'.$_GET["date_start"])." 23:59:59'";
//else if( !isset($_GET["date_start"]) && !isset($_GET["date_end"]))$sql.=" and bet_time between '".date('Y-'.$_GET["date_start"])." 00:00:00' and '".date('Y-'.$_GET["date_start"])." 23:59:59'";
if(isset($_GET["status"]))  $sql.=" and `status` in (".$_GET["status"].")";

if($_GET['tf_id']) $sql.=" and number='".$_GET['tf_id']."'";
if($_GET['bet_time']) $sql.=" and bet_time like('".$_GET['bet_time']."%')";
$order = 'bid';
$sql_allwin_=$sql." and `site_id`='".SITEID."' ";
if($_GET['order']) $order = $_GET['order'];

$testagent = M('k_user_agent', $db_config)->where("agent_type = 'a_t' and is_demo = 1 and site_id = '".SITEID."'")->getField('id');

$sql.="  and `site_id`='".SITEID."' and agent_id!='$testagent'  order by $order desc ";
$sql_allwin="select sum(`win`) from $sql_union where  $sql " ;
//echo '<br>';
$sql_all_betwin="select sum(`bet_win`) from $sql_union where  $sql " ;
$sql_all_betmoney="select sum(`bet_money`) from $sql_union where  $sql " ;
//echo '<br>';

$thisPage	=	1;
if($_GET['page']){
    $thisPage	=	$_GET['page'];
}
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20;


$thisPage_start=($thisPage-1)*$pagenum;
$limit=" limit $thisPage_start,$pagenum";
$sql_select=$sql=$sql_all.$sql;
$query	=	$mysqlt->query($sql);
$sum		=	$mysqlt->affected_rows; //总页数
$totalPage=ceil($sum/$pagenum);
//echo $sql;


//echo $sql_allwin;
$row_win[0]=0;
if($_GET['ltype']>0 && !$_REQUEST['number']){
    $query_win	=	$mysqlt->query($sql_allwin);
    $row_win = $query_win->fetch_array();//统计结算金额
}



$query_allbet_win	=	$mysqlt->query($sql_all_betwin);
$row_allbet_win = $query_allbet_win->fetch_array();//统计可赢金额

$query_allbet_money	=	$mysqlt->query($sql_all_betmoney);
$row_allbet_money = $query_allbet_money->fetch_array(); //统计投注金额
$bid		=	'1';
?>
<?php $title="體育詳細注單"; require("../common_html/header.php");?>
    <body>
    <script language="javascript">
        function winopen(bid,status){
            window.open("set_score.php?bid="+bid+"&status="+status,"list","width=440,height=180,left=50,top=100,scrollbars=no");
        }
        function windowopen(url){
            window.open(url,"wx","width=600,height=500,left=50,top=100,scrollbars=no");
        }
        function go(value){
            if(value != "") location.href=value;
            else return false;
        }

        function check(){
            if($("#tf_id").val().length > 5){
                $("#status").val("8,0,1,2,3,4,5,6,7");
            }
            return true;
        }
    </script>
    <script language="JavaScript" type="text/JavaScript">
        $(document).ready(function(){
            $('#ltype').val('<?=$_GET['ltype']?>');
        })
    </script>
    <div  id="con_wrap">
        <form name="myFORM" id="myFORM" action="<?=$_SERVER["REQUEST_URI"]?>" method="get">
            <div id="jszd" style="display: <?=$_REQUEST['match_id']?'none':'block';?>;">
            <input type="hidden" name="match_id" value="<?=$_GET['match_id']?>" id="match_id"/>
            <input type="hidden" name="point_column" value="<?=$_GET['point_column']?>" id="point_column"/>
            <div  class="input_002">详细注單管理 </div>
            <div  class="con_menu">
                <div>
                    &nbsp;線上操盤：<a href="list.php">详细注單</a>&nbsp
                    <select  id="ltype"  name="ltype" onchange="document.getElementById('myFORM').submit()"  class="za_select">
                        <option  value="0" <?if($_GET['ltype']==0){echo 'selected="selected"';}?>>未結算</option>
                        <option  value="1" <?if($_GET['ltype']==1){echo 'selected="selected"';}?>>已結算</option>
                        <option  value="2" <?if($_GET['ltype']==2){echo 'selected="selected"';}?>>已取消</option>
                        <option  value="3" <?if($_GET['ltype']==3){echo 'selected="selected"';}?>>滾球未確認</option>
                        <option  value="4"  style="DISPLAY: none;">滾球已確認</option>
                        <option  value="5"  style="DISPLAY: none;">滾球已取消</option>
                    </select> &nbsp;
                </div>
                <div>

                    会员帐号：<input  type="TEXT"  name="username"  id="username"  value="<?if($_GET['username']){echo $_GET['username'];}?>"  size="20"  maxlength="20"  class="za_text"  style="width:75px;min-width:75px">
                    大於此金額：<input  type="TEXT"  name="money"  id="money"   value="<?if($_GET['money']){echo $_GET['money'];}?>"  size="10"  maxlength="10"  class="za_text"  style="width:50px;min-width:50px">
                    注单号：<input  type="TEXT"  name="number"  id="number"   value="<?if($_GET['number']){echo $_GET['number'];}?>"     class="za_text"  style="width:110px;min-width:50px">
                    &nbsp;&nbsp;日期：

                    <input id="date_start" class="za_text Wdate" type="text" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"   value="<?=$date_start?>" name="date_start">

                    <input type="text" name="date_end" id="date_end" value="<?=$date_end?>"  size="10"  class="za_text Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">

                    <input  type="submit"  name="Submit32" class="za_button" value="查询">

                </div>
        </div>
            </div>
            <div style="padding:5px;">

            每页记录数：
            <select name="page_num" id="page_num" onchange="$('#page').val(1);document.getElementById('myFORM').submit()" class="za_select">
                <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
                <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
                <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
                <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
            </select>
            &nbsp;頁數：
            <select id="page" name="page" class="za_select" onchange="document.getElementById('myFORM').submit()">
                <?php

                for($i=1;$i<=$totalPage;$i++){
                    if($i==$thisPage){
                        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
                    }else{
                        echo  '<option value="'.$i.'">'.$i.'</option>';
                    }
                }
                ?>
            </select> <?php echo  $totalPage ;?> 頁&nbsp;
            重新整理：
            <select name="reload" id="reload" onchange="timeout(this.value);">
                <option value="">不自動更新</option>
                <option value="5" <?if($_GET['reload']==5){ echo 'selected="select"';}?>>5秒</option>
                <option value="10" <?if($_GET['reload']==10){ echo 'selected="select"';}?>>10秒</option>
                <option value="15" <?if($_GET['reload']==15){ echo 'selected="select"';}?>>15秒</option>
                <option value="30" <?if($_GET['reload']==30){ echo 'selected="select"';}?>>30秒</option>
                <option value="60" <?if($_GET['reload']==60){ echo 'selected="select"';}?>>60秒</option>
                <option value="120" <?if($_GET['reload']==120){ echo 'selected="select"';}?>>120秒</option>
            </select>
            <span id="lblTime" style="color:red"></span>
            </div>
        </form>
    <div  class="content">
        <form  name="REFORM"  action=""  method="post">

            <table  class="m_tab"  id="glist_table"  cellspacing="0"  cellpadding="0"  width="100%"  border="0">
                <tbody>
                <tr  class="m_title">
                    <td>序號</td>
                    <td>下注時間</td>
                    <td>所屬上級</td>
                    <td>下注限制</td>
                    <td>下注帳號</td>
                    <td>方式</td>
                    <td>內容</td>
                    <td>下注金額</td>
                    <td>可贏金額</td>
                    <td>結果</td>
                    <!--  <td>状态</td>-->

                </tr>
                <?php
//echo $sql_select;
                   // $sql	=	"select b.*,u.username,u.is_daili,u.top_uid,u.money,u.agent_id,u.login_ip from k_bet b,k_user u where b.uid=u.uid and bid in($bid)   and b.site_id='".SITEID."' order by $order desc";
                   // echo $sql_select.$limit;
                    $query	=	$mysqlt->query($sql_select.$limit);


                    $allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."'")->select();

                    $bet_money=$win=0;
                    while ($rows = $query->fetch_assoc()) {
                       // $userdata=M('k_user',$db_config)->field('agent_id,username,money')->where("uid='".$rows['uid']."'")->select();
                       // print_r($userdata);
                        $ii++;

                        $daili=Level::getParents($allagent,$rows['agent_id']);
                        $rows=array_merge($rows,$daili);

                        //p($rows);
                        $status='';

                        if($rows["status"]==0)  $status='未结算';
                        else if($rows["status"]==1)  {
                            $status='<span style="color:#FF0000;">赢</span>';
                        }
                        else if($rows["status"]==2)  $status='<span style="color:#00CC00;">输</span>';
                        else if($rows["status"]==8)  $status='和局';
                        else if($rows["status"]==3)  $status='注单无效';
                        else if($rows["status"]==4)  $status='<span style="color:#FF0000;">赢一半</span>';
                        else if($rows["status"]==5)  $status='<span style="color:#00CC00;">输一半</span>';
                        else if($rows["status"]==6)  $status='进球无效';
                        else if($rows["status"]==7)  $status='红卡取消';
                        $bet_money+=$rows["bet_money"];
                        $bet_win+=$rows["bet_win"];
                        if($_REQUEST['number']) $win=0;
                        else $win+=$rows["win"];
                        $color = "#FFFFFF";
                        $over	 = "#EBEBEB";
                        $out	 = "#ffffff";

                        if(($rows["balance"]*1)<0 || round($rows["assets"]-$rows["bet_money"],2) != round($rows["balance"],2)){ //投注后用户余额不能为负数，投注后金额要=投注前金额-投注金额
                            $over = $out = $color = "#FBA09B";
                        }elseif($rows["match_type"]==1 && strtotime($rows["bet_time"])>=strtotime($rows["match_endtime"])){ //不是滚球，抽注时间不能>=开赛时间
                            $over = $out = $color = "#FBA09B";
                        }elseif(double_format($rows["bet_money"]*($rows["ben_add"]+$rows["bet_point"])) !== double_format($rows["bet_win"])){
                            $over = $out = $color = "#FBA09B";
                        }
                        ?>
                        <tr  class="m_cen">
                            <td><?=$rows["bid"]?></td>
                            <td><?=substr($rows["bet_time"],0,10)?><br/><?=substr($rows["bet_time"],11,19)?></td>
                            <td>股东：<?=$rows["s_h"]?><br/>总代理：<?=$rows["u_a"]?><br/>代理商：<?=$rows["a_t"]?></td>
                            <td>最低:10<br>單注:20000<br>單場:200000</td>
                            <td><?=$rows["username"]?><br><font  color="#cc0000"><?=$rows["assets"]?></font><br>現金</td>

                            <td>
                                <font color="<? if ($rows["ball_sort"]=="足球滚球"){echo "#0066FF";}else{echo "#336600";}?>">
                                    <b><?=$rows["ball_sort"]?></b>
                                </font><br/>
                                <font color="#0000cc"><?=$rows["match_id"]?></font><br>
                                <?=$rows["number"]?><Br>
                                <?=$rows['match_coverdate']?>
                            </td>
                            <td>
                                <? if($rows['ball_sort']=='串关'){
                                        $gid	=	$rows['bid'];
                                        $sql_cg	=	"select gid,bid,bet_info,match_name,master_guest,bet_time,MB_Inball,TG_Inball,status from k_bet_cg where gid in ($gid) order by bid desc";
                                        $query_cg	=	$mysqlt->query($sql_cg);
                                        $html='';
                                        while($rows_cg	    =	$query_cg->fetch_array()){
                                            $html.= '<font color="#CC0000">'.$rows_cg['match_name'].'</font><br>';
                                            $html.= $rows_cg['master_guest'].'<br>';
                                            $html.='<font color="#FF0033">'. $rows_cg['bet_info'].'</font><br>';
                                            if($rows_cg['MB_Inball'] !=null && $rows_cg['TG_Inball'] !=null) $html.= '['.$rows_cg['MB_Inball'].':'.$rows_cg['TG_Inball'].']<br>';
                                            $html.='<div style="height:1px; width:99%; background:#ccc; overflow:hidden;"></div>';
                                        }
                                    echo $html;
                                }else{
                                ?>
                                <font color="#CC0000"><?=$rows["match_name"]?></font><br><?=$rows['master_guest']?>
                                <font style="color:#FF0033">
                                   <?
                                     if($rows["point_column"]==="match_jr" || $rows["point_column"]==="match_gj") echo $rows["bet_info"];
                                    else echo str_replace("-","<br>",$rows["bet_info"]);
                                  // if($rows['ball_sort']=='足球滚球') echo '['.$rows['match_nowscore'].']';
                                    ?>
                                </font>
                                <? if($rows["status"]!=0 && $rows["status"]!=3 &&  $rows["status"]!=6 && $rows["status"]!=7)
                                    if($rows["MB_Inball"]!=''){?>
                                        [<?=$rows["MB_Inball"]?>:<?=$rows["TG_Inball"]?>]
                                    <? } ?>	<br/><?=$rows["login_ip"]?>
                             <? } ?>
                            </td>
                            <td><?=$rows["bet_money"]?></td>
                            <td><?=round($rows["bet_win"],2)?></td>
                            <td><?=$status?><br><?=$rows['status']==0?0:$rows["win"]?></td>
                        </tr>
                    <?
                    }

                ?>
                <tr class="m_cen" style="background-Color:#EBF0F1">
                    <td colspan="6" style="text-align:right">&nbsp;小計：</td>
                    <td align="center"><?php if($pageStr){echo $pagenum;}else{echo $ii;}?>笔</td>
                    <td align="center"><?php echo double_format($bet_money);?></td>
                    <td align="center"><?php echo double_format($bet_win);?></td>
                    <td align="center"><?php echo double_format($_GET['ltype']>0?$win:0)?></td>
                </tr>
                <tr class="m_cen" style="background-Color:#EBF0F1">
                    <td colspan="6" style="text-align:right">&nbsp;總計：</td>
                    <td align="center"><?php if($pageStr){echo $pagenum;}else{echo $sum;}?>笔</td>
                    <td align="center"><?php echo double_format($row_allbet_money[0]);?></td>
                    <td align="center"><?php echo double_format($row_allbet_win[0]);?></td>
                    <td align="center"><?php echo double_format($row_win[0])?></td>
                </tr>
                </tbody></table>
        </form>
    </div>
        </div>
        <div style="clear: both"></div>
    <script>
        var i='<?=$_GET["reload"]?>';
        if(i==''){
            var i=0;
        }
        myTimer=0;
        $(document).ready(function(){


            if(i!=0){
                setTimeout("timeout(i)",1000);
            }

        });
        function timeout(time){
            if(myTimer)  window.clearInterval(myTimer);
            i = time;
            var reload=i;
            myTimer=setInterval("refresh()",1000);

        }

        function refresh(){
            if(i <=0){
                var reload=$("#reload").val();
                var ltype=$("#ltype").val();
                var username=$("#username").val();
                var money=$("#money").val();
                var match_id=$("#match_id").val();
                var point_column=$("#point_column").val();
                window.location.href='list.php?reload='+reload+'&ltype='+ltype+'&username='+username+'&money='+money+'&match_id='+match_id+'&point_column='+point_column;//调转
            }else{
                $('#lblTime').html('还有'+i+'秒更新');
                i--;
            }
        }

    </script>
<?php require("../common_html/footer.php");?>