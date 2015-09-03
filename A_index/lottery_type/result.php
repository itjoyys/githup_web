<?
include_once("../include/config.php");
include_once("../include/public_config.php");
include_once("../common/logintu.php");
include_once("../common/function.php");

include_once("./include/auto_class.php");
$cdate=time()+12*60*60;
$t=($_GET['t']>0)?(intval($_GET['t'])-1):0;

// var_dump($_GET);


function toweek($w){
    switch($w){
        case "1":
            return "星期一";
            break;
        case "2":
            return "星期二";
            break;
        case "3":
            return "星期三";
            break;
        case "4":
            return "星期四";
            break;
        case "5":
            return "星期五";
            break;
        case "6":
            return "星期六";
            break;
        default:
            return "星期日";
            break;
    }
}
$table;
if($_GET['type']){
    switch($_GET['type']){
        case "3D":
            $title="福彩3D";
            $table='c_auto_5';
            $opentime = 'c_opentime_5';
            $pageif =1;
            break;
        case "pl3":
            $title="排列三";
            $table='c_auto_6';
            $opentime = 'c_opentime_6';
            $pageif =1;
            break;
        case "gdsf":
            $title="广东快乐十分";
            $table='c_auto_1';
            $opentime = 'c_opentime_1';
            $pageif =0;
            break;
        case "cqsf":
            $title="重庆十分";
            $table='c_auto_4';
            $opentime = 'c_opentime_4';
            $pageif =0;
            break;
        case "pk10":
            $title="北京赛车PK拾";
            $table='c_auto_3';
            $opentime = 'c_opentime_3';
            $pageif =0;
            break;
        case "kl8":
            $title="北京快乐8";
            $table='c_auto_8';
            $opentime = 'c_opentime_8';
            $pageif =0;
            break;
        case "Cqss":
            $title="重庆时时彩";
            $table='c_auto_2';
            $opentime = 'c_opentime_2';
            $pageif =0;
            break;
        case "Tjss":
            $title="天津时时彩";
            $table='c_auto_10';
            $opentime = 'c_opentime_10';
            $pageif =0;
            break;
        case "Jxss":
            $title="江西时时彩";
            $table='c_auto_11';
            $opentime = 'c_opentime_11';
            $pageif =0;
            break;
        case "Xjss":
            $title="新疆时时彩";
            $table='c_auto_12';
            $opentime = 'c_opentime_12';
            $pageif =0;
            break;
        case "lhc":
            $title="六合彩";
            $table='c_auto_7';
            $opentime = 'c_opentime_7';
            $pageif =1;
            break;
        case "jsk3":
            $title="江苏快3";
            $table='c_auto_13';
            $opentime = 'c_opentime_13';
            $pageif =0;
            break;
        case "jlk3":
            $title="吉林快3";
            $table='c_auto_14';
            $opentime = 'c_opentime_14';
            $pageif =0;
            break;
        default:
            $title="福彩3D";
            $_GET['type']=='3D';
            $table='c_auto_5';
            $opentime = 'c_opentime_5';
            $pageif =1;
            break;
    }

}
$start_time = date("Y-m-d",strtotime("+12 hours")-$t*24*3600).' 00:00:00';
$end_time = date("Y-m-d",strtotime("+12 hours")-$t*24*3600).' 23:59:59';

if($_GET['type']=="3D" || $_GET['type']=="pl3" || $_GET['type']=='lhc'){
    $where="";
}else{
    $where='('.$table.'.datetime > "'.$start_time.'" and '.$table.'.datetime <"'.$end_time.'") ';
}

if(!empty($_POST['qishu']) && $_GET['type']!='lhc'){
    $where="".$table.".qishu='".$_POST['qishu']."'";
}elseif(!empty($_POST['qishu']) && $_GET['type']=='lhc'){
    $where="".$table.".nn='".$_POST['qishu']."'";
}
$field = $table.".*,".$opentime.".kaijiang";
if($_GET['type']=='lhc'){
    $join="left join ".$opentime." on ".$table.".nn = ".$opentime.".qishu";
    $data = M($table,$db_config)->field($field)->where($where)->join($join)->order('nn DESC')->select();
}else{
    $join="left join ".$opentime." on ".$table.".qishu = ".$opentime.".qishu";
    $data = M($table,$db_config)->field($field)->where($where)->join($join)->order('qishu DESC')->select();
}

//分页
if($pageif==1){
    $perNumber=30; //每页显示的记录数
    $count=count($data); //获得记录总数
    $totalPage=ceil($count/$perNumber); //计算出总页数

    if (!isset($_GET['page'])) {
        $page=1;
    }else{
        if($_GET['page']>$totalPage){
            $page=1;
        }else{
            $page=$_GET['page']; //获得当前的页面值
        }
    } //如果没有值,则赋值1

    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
    $limit=$startCount.",".$perNumber;
}else{
    $limit=1000;
}
if($_GET['type']=='lhc'){
    $pk10 = M($table,$db_config)->field($field)->where($where)->join($join)->limit($limit)->order('nn DESC')->select();
}else{
    $pk10 = M($table,$db_config)->field($field)->where($where)->join($join)->limit($limit)->order('qishu DESC')->select();
}

//分页按钮





?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?=$title?></title>
    <link rel="stylesheet" href="public/css/reset.css" type="text/css">
    <link rel="stylesheet" href="public/css/xp.css" type="text/css">


</head>
<body id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">
<noscript>
    <iframe src="*.htm" style="display:none"></iframe>
</noscript>
<script src="js/orderFunc.js" type="text/javascript"></script>
<div style="width:960px;margin:0 auto">
<table border="0" cellpadding="0" cellspacing="1">
    <tbody>
    <tr>
        <td width="10%"><span class="title" style="display:block;width:600px;"><?=$title?>-开奖管理</span></td>
        <td align="left">
            <table>
                <form action="?type=<?=$_GET['type']?>" method="post" name="regstep1" id="regstep1">
                    <input type="hidden" name="type" value="<?=$_GET['type'] ?>">
                    <tbody>
                    <tr>

                        <td colspan="2" align="center" nowrap="nowrap"><p class="STYLE2" align="right">&nbsp;期数：</p></td>
                        <td colspan="6" align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                <tr>
                                    <td><input name="qishu" class="input1" id="qishu" size="10" type="text"></td>
                                    <td align="center" width="80"><input value=" 确定搜索 " name="B1" class="button_a" type="submit" style="height:20px;line-height:12px;"></td>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table></td>
                    </tr>
                    </tbody>
                </form>
            </table>

        </td>
        <td width="37%"><div align="right">
                <select name="lx" id="lx" onChange="document.location=this.value">
                    <option value="result.php?type=lhc" <?if($_GET['type']=='lhc'){echo 'selected="selected"';}?>>六合彩</option>
                    <option value="result.php?type=3D" <?if($_GET['type']=='3D'){echo 'selected="selected"';}?>>福彩3D</option>
                    <option value="result.php?type=pl3" <?if($_GET['type']=='pl3'){echo 'selected="selected"';}?>>排列三</option>
                    <option value="result.php?type=Cqss" <?if($_GET['type']=='Cqss'){echo 'selected="selected"';}?>>重庆时时彩</option>
                    <option value="result.php?type=Tjss" <?if($_GET['type']=='Tjss'){echo 'selected="selected"';}?>>天津时时彩</option>
                    <option value="result.php?type=Jxss" <?if($_GET['type']=='Jxss'){echo 'selected="selected"';}?>>江西时时彩</option>
                    <option value="result.php?type=Xjss" <?if($_GET['type']=='Xjss'){echo 'selected="selected"';}?>>新疆时时彩</option>
                    <option value="result.php?type=kl8" <?if($_GET['type']=='kl8'){echo 'selected="selected"';}?>>北京快乐8</option>
                    <option value="result.php?type=pk10" <?if($_GET['type']=='pk10'){echo 'selected="selected"';}?>>北京赛车PK拾</option>
                    <option value="result.php?type=gdsf" <?if($_GET['type']=='gdsf'){echo 'selected="selected"';}?>>广东快乐十分</option>
                    <option value="result.php?type=cqsf" <?if($_GET['type']=='cqsf'){echo 'selected="selected"';}?>>重庆快乐十分</option>
                    <option value="result.php?type=jsk3" <?if($_GET['type']=='jsk3'){echo 'selected="selected"';}?>>江苏快3</option>
                    <option value="result.php?type=jlk3" <?if($_GET['type']=='jlk3'){echo 'selected="selected"';}?>>吉林快3</option>
                </select>
            </div></td>
    </tr>
    </tbody>
</table>
<table class="game_table" border="0" cellpadding="0" cellspacing="1">
    <?php if($_GET['type']!="3D" && $_GET['type']!="pl3" && $_GET['type']!='lhc'){ ?>
        <tr class="tbtitle3">
            <td height="28" colspan="10" align="left" valign="middle">
                &nbsp;&nbsp;&nbsp;选择日期查看：
                <?
                for($i=1;$i<=7;$i++){

                    if(date("Y-m-d",$cdate-(($i-1)*(24*3600)))==date("Y-m-d",$cdate-($t*24*3600))){
                        echo date("Y-m-d",$cdate-(($i-1)*(24*3600)))."&nbsp;&nbsp;";
                    }else{
                        ?>

                        <a href="javascript:void(0);" style="color:red" onClick="url('?type=<?=$_GET['type']?>&t=<?=$i ?>')"><?=date("Y-m-d",$cdate-(($i-1)*(24*3600))) ?></a>&nbsp;&nbsp;
                    <?
                    }
                }

                ?>
            </td>
        </tr>

    <?} if($_GET['type']=='3D'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="90">开奖球号</td>
        <td align="center" width="120"><div align="center">总和</div></td>
        <td align="center" width="40"><div align="center">龙虎和</div></td>
        <td align="center">三联</td>
        <td align="center">跨度</td>
    </tr>
    <?

    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>	</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?></td>
                <td class="ball_bg2" align="center">
                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3'] ?></span>
                </td>
                <td class="ball_bg2">
                    <?php echo FC3D_Auto($hm , 0);?> /
                    <?php if(FC3D_Auto($hm , 7)=="总和大"){?><font color=red><?php echo str_replace("总和","",FC3D_Auto($hm , 7));?></font>
                    <?php }else{?><?php echo str_replace("总和","",FC3D_Auto($hm , 7));?><?php }?> /
                    <?php if(FC3D_Auto($hm , 8)=="总和双"){?><font color=red><?php echo str_replace("总和","",FC3D_Auto($hm , 8));?></font>
                    <?php }else{?><?php echo str_replace("总和","",FC3D_Auto($hm , 8));?><?php }?>
                </td>
                <td class="ball_bg2">
                    <?php if(FC3D_Auto($hm , 9)=="龙"){?><font color=red><?php echo str_replace("总和","",FC3D_Auto($hm , 9));?></font><?php }else{?><?php echo str_replace("总和","",FC3D_Auto($hm , 9));?><?php }?>
                </td>
                <td class="ball_bg2"><?php echo FC3D_Auto($hm , 10);?></td>
                <td class="ball_bg2"><?php echo FC3D_Auto($hm , 11);?></td>
            </tr>
        <?php }}?>

    </tbody>
</table></td>
</tr>
</tbody>
</table>
<?}elseif($_GET['type']=='jsk3' || $_GET['type']=='jlk3'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="90">开奖球号</td>
        <td align="center" width="120"><div align="center">和值</div></td>
        <!--
        <td align="center" width="40"><div align="center">龙虎和</div></td>
        <td align="center">三联</td>
        <td align="center">跨度</td>
    	-->
    </tr>
    <?



    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>	</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?></td>
                <td class="ball_bg2" align="center">
                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3'] ?></span>
                </td>
                <td class="ball_bg2">
                    <?php echo k3_Auto($hm , 0);?> /
                    <?php if(k3_Auto($hm , 7)=="总和大"){?><font color=red><?php echo str_replace("总和","",k3_Auto($hm , 7));?></font>
                    <?php }else{?><?php echo str_replace("总和","",k3_Auto($hm , 7));?><?php }?> /
                    <?php if(k3_Auto($hm , 8)=="总和双"){?><font color=red><?php echo str_replace("总和","",k3_Auto($hm , 8));?></font>
                    <?php }else{?><?php echo str_replace("总和","",k3_Auto($hm , 8));?><?php }?>
                </td>

            </tr>
        <?php }}?>

    </tbody>
    </table>
<?}elseif($_GET['type']=='pl3'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="90">开奖球号</td>
        <td align="center" width="120"><div align="center">总和</div></td>
        <td align="center" width="40"><div align="center">龙虎和</div></td>
        <td align="center">三联</td>
        <td align="center">跨度</td>
    </tr>
    <?



    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?></td>
                <td class="ball_bg2" align="center">
                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3'] ?></span>
                </td>
                <td class="ball_bg2">
                    <?php echo PL3_Auto($hm , 0);?> /
                    <?php if(PL3_Auto($hm , 7)=="总和大"){?><font color=red><?php echo str_replace("总和","",PL3_Auto($hm , 7));?></font>
                    <?php }else{?><?php echo str_replace("总和","",PL3_Auto($hm , 7));?><?php }?> /
                    <?php if(PL3_Auto($hm , 8)=="总和双"){?><font color=red><?php echo str_replace("总和","",PL3_Auto($hm , 8));?></font>
                    <?php }else{?><?php echo str_replace("总和","",PL3_Auto($hm , 8));?><?php }?>
                </td>
                <td class="ball_bg2">
                    <?php if(PL3_Auto($hm , 9)=="龙"){?><font color=red><?php echo str_replace("总和","",PL3_Auto($hm , 9));?></font><?php }else{?><?php echo str_replace("总和","",PL3_Auto($hm , 9));?><?php }?>
                </td>
                <td class="ball_bg2"><?php echo PL3_Auto($hm , 10);?></td>
                <td class="ball_bg2"><?php echo PL3_Auto($hm , 11);?></td>
            </tr>
        <?php }}?>

    </tbody>
    </table>
<?}elseif($_GET['type']=='Cqss' || $_GET['type']=='Tjss' || $_GET['type']=='Jxss' || $_GET['type']=='Xjss'){?>

    <tbody>


    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="155">开奖球号</td>
        <td align="center" width="120"><div align="center">总和</div></td>
        <td align="center" width="40"><div align="center">龙虎和</div></td>
        <td align="center"><div align="center">前三</div></td>
        <td align="center"><div align="center">中三</div></td>
        <td align="center"><div align="center">后三</div></td>
        <td align="center"><div align="center">斗牛</div></td>
        <td align="center"><div align="center">梭哈</div></td>
    </tr>
    <?

    if($_GET['type']=='Cqss'){
        $c_auto = "c_auto_2";
    }elseif($_GET['type']=='Tjss'){
        $c_auto = "c_auto_10";
    }elseif($_GET['type']=='Jxss'){
        $c_auto = "c_auto_11";
    }elseif($_GET['type']=='Xjss'){
        $c_auto = "c_auto_12";
    };

    $cq_ssc = M($c_auto,$db_config)->field($field)->where($where)->join($join)->limit('120')->order('qishu DESC')->select();
    $c_sum	=	$m_sum	=	$t_sum	=	$f_sum	=	$sxf_sum	=	0;

    //p($where);

    foreach ($cq_ssc as $key => $row) {
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?>	</td>
                <td class="ball_bg2" align="center">

                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_4'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_5'] ?></span>
                </td>
                <td class="ball_bg2">
                    <?=Ssc_Auto($hm,1)?> / <?=(Ssc_Auto($hm,2)=="大")?"<font color=red>大</font>":"".Ssc_Auto($hm,2).""; ?> / <?= (Ssc_Auto($hm,3)=="双")?"<font color=red>双</font>":"单"; ?>
                </td>
                <td class="ball_bg2">
                    <?=(Ssc_Auto($hm,4)=="龙")?"<font color=red>龙</font>":Ssc_Auto($hm,4); ?>
                </td>
                <td class="ball_bg2"><?=Ssc_Auto($hm,5)?></td>
                <td class="ball_bg2" ><?=Ssc_Auto($hm,6)?></td>
                <td class="ball_bg2"><?=Ssc_Auto($hm,7)?></td>
                <td class="ball_bg2" ><?=Ssc_Auto($hm,8)?></td>
                <td class="ball_bg2" ><?=Ssc_Auto($hm,9)?></td>
            </tr>
        <?php }}?>

    </tbody>
    </table>

<?}elseif($_GET['type']=='pk10'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="300">开奖球号</td>
        <td align="center" width="120"><div align="center">冠亚军和</div></td>
        <td align="center" width="40"><div align="center">1V10龙虎</div></td>
        <td align="center"><div align="center">2V9龙虎</div></td>
        <td align="center"><div align="center">3V8龙虎</div></td>
        <td align="center"><div align="center">4V7龙虎</div></td>
        <td align="center"><div align="center">5V6龙虎</div></td>
    </tr>
    <?



    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5']+$row['ball_6']+$row['ball_7']+$row['ball_8']+$row['ball_9']+$row['ball_10'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            $hm[]		= $row['ball_6'];
            $hm[]		= $row['ball_7'];
            $hm[]		= $row['ball_8'];
            $hm[]		= $row['ball_9'];
            $hm[]		= $row['ball_10'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?>		</td>
                <td class="ball_bg2" align="center">

                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3']?></span>
                    <span class="kjjg_li"><?=$row['ball_4']?></span>
                    <span class="kjjg_li"><?=$row['ball_5']?></span>
                    <span class="kjjg_li"><?=$row['ball_6'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_7'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_8']?></span>
                    <span class="kjjg_li"><?=$row['ball_9']?></span>
                    <span class="kjjg_li"><?=$row['ball_10']?></span>
                </td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,1)?>/<?=Pk10_Auto($hm,2)?>/<?=Pk10_Auto($hm,3)?></td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,4)?></td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,5)?></td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,6)?></td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,7)?></td>
                <td class="ball_bg2" ><?=Pk10_Auto($hm,8)?></td>
            </tr>
        <?php }}?>

    </tbody>
    </table>

<?}elseif($_GET['type']=='cqsf'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="245">开奖球号</td>
        <td align="center" width="120"><div align="center">总和</div></td>
        <td align="center" width="40"><div align="center">龙虎和</div></td>
    </tr>
    <?

    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5']+$row['ball_6']+$row['ball_7']+$row['ball_8'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            $hm[]		= $row['ball_6'];
            $hm[]		= $row['ball_7'];
            $hm[]		= $row['ball_8'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?></td>
                <td class="ball_bg2" align="center">

                    <span class="kjjg_li"><?=BuLing($row['ball_1']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_2']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_3']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_4']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_5']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_6']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_7']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_8']) ?></span>
                </td>
                <td class="ball_bg2">
                    <?=C10_Auto($hm,1)?> / <?=(C10_Auto($hm,2)=="大")?"<font color=red>大</font>":"".C10_Auto($hm,2).""; ?> / <?= (C10_Auto($hm,3)=="双")?"<font color=red>双</font>":"单"; ?> / <?= (C10_Auto($hm,4)=="尾大")?"<font color=red>尾大</font>":"尾小"; ?>
                </td>
                <td class="ball_bg2">
                    <?=(C10_Auto($hm,5)=="龙")?"<font color=red>龙</font>":C10_Auto($hm,5); ?>
                </td>
            </tr>
        <?php }}?>

    </tbody>
    </table>

<?}elseif($_GET['type']=='kl8'){?>

    <tbody>
    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="600">开奖球号</td>
        <td align="center"><div align="center">总和</div></td>
        <td align="center"><div align="center">上中下</div></td>
        <td align="center"><div align="center">奇和偶</div></td>
    </tr>
    <?
    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5']+$row['ball_6']+$row['ball_7']+$row['ball_8']+$row['ball_9']+$row['ball_10']+$row['ball_11']+$row['ball_12']+$row['ball_13']+$row['ball_14']+$row['ball_15']+$row['ball_16']+$row['ball_17']+$row['ball_18']+$row['ball_19']+$row['ball_20'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            $hm[]		= $row['ball_6'];
            $hm[]		= $row['ball_7'];
            $hm[]		= $row['ball_8'];
            $hm[]		= $row['ball_9'];
            $hm[]		= $row['ball_10'];
            $hm[]		= $row['ball_11'];
            $hm[]		= $row['ball_12'];
            $hm[]		= $row['ball_13'];
            $hm[]		= $row['ball_14'];
            $hm[]		= $row['ball_15'];
            $hm[]		= $row['ball_16'];
            $hm[]		= $row['ball_17'];
            $hm[]		= $row['ball_18'];
            $hm[]		= $row['ball_19'];
            $hm[]		= $row['ball_20'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?>		</td>
                <td class="ball_bg2" align="center">

                    <span class="kjjg_li"><?=$row['ball_1'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_2'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_3']?></span>
                    <span class="kjjg_li"><?=$row['ball_4']?></span>
                    <span class="kjjg_li"><?=$row['ball_5']?></span>
                    <span class="kjjg_li"><?=$row['ball_6'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_7'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_8']?></span>
                    <span class="kjjg_li"><?=$row['ball_9']?></span>
                    <span class="kjjg_li"><?=$row['ball_10']?></span>
                    <span class="kjjg_li"><?=$row['ball_11'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_12'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_13']?></span>
                    <span class="kjjg_li"><?=$row['ball_14']?></span>
                    <span class="kjjg_li"><?=$row['ball_15']?></span>
                    <span class="kjjg_li"><?=$row['ball_16'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_17'] ?></span>
                    <span class="kjjg_li"><?=$row['ball_18']?></span>
                    <span class="kjjg_li"><?=$row['ball_19']?></span>
                    <span class="kjjg_li"><?=$row['ball_20']?></span>
                </td>
                <td class="ball_bg2"><?=Kl8_Auto($hm,0)?> / <?php if(Kl8_Auto($hm , 1)=="总和大"){?><font color=red><?php echo str_replace("总和","",Kl8_Auto($hm , 1));?></font>
                    <?php }else{?><?php echo str_replace("总和","",Kl8_Auto($hm , 1));?><?php }?> /
                    <?php if(Kl8_Auto($hm , 2)=="总和双"){?><font color=red><?php echo str_replace("总和","",Kl8_Auto($hm , 2));?></font>
                    <?php }else{?><?php echo str_replace("总和","",Kl8_Auto($hm , 2));?><?php }?></td>
                <td class="ball_bg2"><?=Kl8_Auto($hm,3)?></td>
                <td class="ball_bg2"><?=Kl8_Auto($hm,4)?></td>
            </tr>
        <?php }}?>

    </tbody>
    </table>
<?}elseif($_GET['type']=='lhc'){


    ?>
    <table border="0" cellpadding="0" cellspacing="1" class="game_table" >
        <tr class="tbtitle3">
            <td rowspan="2"><div align="center">序号</div></td>
            <td rowspan="2"><div align="center">期数</div></td>
            <td rowspan="2">开奖时间</td>
            <td colspan="7">开奖球号</td>
            <td colspan="3" rowspan="2">特码</td>
            <td colspan="3" rowspan="2">总分</td>

            <td rowspan="2">生肖</td>
            <td colspan="2" rowspan="2">合数</td>
        </tr>
        <tr class="tbtitle2">
            <td >平1</td>
            <td >平2</td>
            <td >平3</td>
            <td >平4</td>
            <td >平5</td>
            <td >平6</td>
            <td >特码</td>
        </tr>
        <?php

        foreach ($pk10 as $k=>$v){
            $week=date('w',strtotime($v['nd']));
            if($week==0) $week='星期日';
            elseif($week==1) $week='星期一';
            elseif($week==2) $week='星期二';
            elseif($week==3) $week='星期三';
            elseif($week==4) $week='星期四';
            elseif($week==5) $week='星期五';
            elseif($week==6) $week='星期六';
            ?>
            <tr class="tbtitle">
                <td class="ball_bg"><?=$k+1 ?></td>
                <td class="ball_bg2"><?=$v['nn'] ?></td>
                <td class="ball_bg2"><?=$week?>  <?=$v['nd']?> </td>
                <td class="ball_bg"><div class="<?=set_style($v['n1']) ?>"><?=$v['n1'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['n2']) ?>"><?=$v['n2'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['n3']) ?>"><?=$v['n3'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['n4']) ?>"><?=$v['n4'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['n5']) ?>"><?=$v['n5'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['n6']) ?>"><?=$v['n6'] ?></div></td>
                <td class="ball_bg"><div class="<?=set_style($v['na']) ?>"><?=$v['na'] ?></div></td>
                <td class="ball_bg2"><?=danshuang($v['na']) ?></td>
                <td class="ball_bg2"><?=tm_daxiao($v['na']) ?></td>
                <td class="ball_bg2"><?=tm_sebo($v['na']) ?></td>
                <td class="ball_bg2"><?=tm_sebo($v['na']) ?></td>
                <td class="ball_bg2"><font color='red'><?=danshuang($v['na']+$v['n1']+$v['n2']+$v['n3']+$v['n4']+$v['n5']+$v['n6']) ?></font></td>
                <td class="ball_bg2"><font color='blue'><?=zongfen_daxiao($v['na']+$v['n1']+$v['n2']+$v['n3']+$v['n4']+$v['n5']+$v['n6']) ?></font></td>
                <td class="ball_bg2"><?=shenxiao($v['nd'],$v['na']) ?></td>
                <td class="ball_bg2"><font color='blue'><?=heshu_daxiao($v['na']) ?></font></td>
                <td class="ball_bg2"><font color='blue'><?=heshu_danshuang($v['na']) ?></font></td>
            </tr>
        <?} ?>



    </table>


<?}elseif($_GET['type']=='gdsf'){?>

    <tbody>

    <tr class="tbtitle3">
        <td><div align="center">期数</div></td>
        <td align="center">开奖时间</td>
        <td align="center" width="245">开奖球号</td>
        <td align="center" width="120"><div align="center">总和</div></td>
        <td align="center" width="40"><div align="center">龙虎和</div></td>
    </tr>
    <?


    foreach($pk10 as $row){
        if(($row['ball_1']+$row['ball_2']+$row['ball_3']+$row['ball_4']+$row['ball_5']+$row['ball_6']+$row['ball_7']+$row['ball_8'])<>0){
            $hm 		= array();
            $hm[]		= $row['ball_1'];
            $hm[]		= $row['ball_2'];
            $hm[]		= $row['ball_3'];
            $hm[]		= $row['ball_4'];
            $hm[]		= $row['ball_5'];
            $hm[]		= $row['ball_6'];
            $hm[]		= $row['ball_7'];
            $hm[]		= $row['ball_8'];
            ?>
            <tr>
                <td class="ball_bg2"><?=str_replace("-","",$row['qishu']) ?>		</td>
                <td class="ball_bg2"><?= toweek(date("w",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])))?>  <?=date("m/d",strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime'])) ?>  <?=date("H:i",(strtotime($row['kaijiang']?$row['kaijiang']:$row['datetime']))) ?></td>
                <td class="ball_bg2" align="center">

                    <span class="kjjg_li"><?=BuLing($row['ball_1']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_2']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_3']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_4']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_5']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_6']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_7']) ?></span>
                    <span class="kjjg_li"><?=BuLing($row['ball_8']) ?></span>
                </td>
                <td class="ball_bg2">
                    <?=G10_Auto($hm,1)?> / <?=(G10_Auto($hm,2)=="大")?"<font color=red>大</font>":"".G10_Auto($hm,2).""; ?> / <?= (G10_Auto($hm,3)=="双")?"<font color=red>双</font>":"单"; ?> / <?= (G10_Auto($hm,4)=="尾大")?"<font color=red>尾大</font>":"尾小"; ?>
                </td>
                <td class="ball_bg2">

                    <?=(G10_Auto($hm,5)=="龙")?"<font color=red>龙</font>":G10_Auto($hm,5); ?>
                </td>
            </tr>
        <?php }}?>

    </tbody>
    </table>
<?}?>
<tr>
    <td colspan="17" style="padding:0" height="25"><table align="center" border="0" cellpadding="1" cellspacing="0" width="100%">
            <tbody>
            <tr class="tbtitle">
                <td nowrap="nowrap" height="26" width="180"><div align="left">
                        <button onClick="javascript:location.reload();" class="button_a" style="width: 60; height: 22" ;=""><img src="public/images/icon_21x21_info.gif" align="absmiddle">刷新</button>
                    </div></td>
                <td height="26">
                    <div align="center">

                        <?php

                        if ($page > 1) { //页数不等于1
                            ?>
                            <a href="result.php?type=<?=$_GET['type']?>&page=1">首页</a>
                            <a href="result.php?type=<?=$_GET['type']?>&page=<?php echo $page - 1;?>">上一页</a> <!--显示上一页-->
                        <?php
                        }
                        for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面

                            if($i>$page-6 && $i<$page+6 && $totalPage>1){
                                ?>
                                <a href="result.php?type=<?=$_GET['type']?>&page=<?php echo $i;?>" <?php if($i==$page){echo 'style=color:#f00';} ?>><?php echo $i ;?></a>
                            <?php
                            }

                        }
                        if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
                            ?>
                            <a href="result.php?type=<?=$_GET['type']?>&page=<?php echo $page + 1;?>">下一页</a>
                            <a href="result.php?type=<?=$_GET['type']?>&page=<?=$totalPage ?>">末页</a>
                        <?php
                        }
                        ?>

                    </div></td>
                <td height="26" width="60"><div align="center">

                    </div></td>
            </tr>
</div>

</body>
</html>

<script>
    function url(u){
        window.location.href=u;
    }
</script>
</html>
<?
function BuLing ( $num ) {
    if ( $num<10 ) {
        $num = '0'.$num;
    }
    return $num;
}
?>

