<?php
include_once("../include/config.php");
include ("../include/public_config.php");

include ("./include/function.php");
$publicdb = $db_config;

$style = 'bj_10';
//重庆时时彩赔率读取
if (!empty($_GET['type'])) {
    $bj_pk10_odds = M('c_odds_3',$db_config)->where("type='".$_GET['type']."'")->find();

    switch ($_GET['type']) {
        case 'ball_11':
            $title_3d = '冠、亚军和';
            break;
        case 'ball_1':
            $title_3d = '冠军';
            break;
        case 'ball_2':
            $title_3d = '亚军';
            break;
        case 'ball_3':
            $title_3d = '第三名';
            break;
        case 'ball_4':
            $title_3d = '第四名';
            break;
        case 'ball_5':
            $title_3d = '第五名';
            break;
        case 'ball_6':
            $title_3d = '第六名';
            break;
        case 'ball_7':
            $title_3d = '第七名';
            break;
        case 'ball_8':
            $title_3d = '第八名';
            break;
        case 'ball_9':
            $title_3d = '第九名';
            break;
        case 'ball_10':
            $title_3d = '第十名';
            break;
        case 'ball_12':
            $title_3d = '龍虎';
            $bj_pk10_odds = M('c_odds_3',$db_config)->where("type= 'ball_1' or type= 'ball_2' or type= 'ball_3' or type= 'ball_4' or type= 'ball_5' ")->select();
            break;
    }
  $is_lock = M("fc_games_type",$db_config)->field("*")->where("state = 0 and fc_type = '" . $title_3d . "' and wanfa = '" . $style . "'")->select();
  if($is_lock){
    echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");history.go(-1);</script>';
    exit;
  }
}

//查当前玩法已下注的总额
$beted = beted_limit(3,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];

//查询期数,中奖号
$qishu=M('c_auto_3',$db_config);
$num=$qishu->field("*")->order("datetime desc")->find();
//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(3,$db_config);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>webcom</title>
<link rel="stylesheet" href="public/css/reset.css" type="text/css">
<link rel="stylesheet" href="public/css/xp.css" type="text/css">
<link rel="stylesheet" href="public/css/default.css" type="text/css">
<script src="public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="public/js/bet.js" type="text/javascript"></script>
<?php  include("./include/common_limit.php");  ?>
<script type="text/javascript">
var o_stime = <?=$array['o_t_stro']?>;
var f_stime = <?=$array['f_t_stro']?>;
</script>
<script src="public/js/orderFunc.js" type="text/javascript"></script>
</head>
<body marginwidth="3" marginheight="3" id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">
<div id="title_3d" style="display:none"><?=$title_3d?></div>
<div id="style" style="display:none"><?=$style?></div>
<div id="fc_type" style="display:none">3</div>

   <div id="kq_box_001" style="display:none;">
  <div id="kq_box_num" style="margin:50px auto 0">
     距离 第 <b><font color="#7c3838"> <?=dq_qishu(3,$publicdb)+1?></font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span id="close_time" style="float:none;display:inline"></span></strong>
  </div>
</div>

<div class="wrapCss_004" style="display:block;" id="all_body">
<?php
if (strtotime($array['c_time']) > strtotime($array['o_state']) && strtotime($array['c_time']) < strtotime($array['f_state'])) {
    echo '<script>document.getElementById("all_body").style.display="block";document.getElementById("kq_box_001").style.display="none";var f_o_state = 1;</script>';
} else {
    // 封盘时切换显示
    echo '<script>document.getElementById("all_body").style.display="none";document.getElementById("kq_box_001").style.display="block";var f_o_state = 2;</script>';
}
 ?>

 <script type="text/javascript">

	setInterval(function(){lottery(3,10,<?=$num['qishu']?>);},30000);


</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tab_001">
<tbody><tr style="background:url(public/images/title_bg_004.gif) repeat-x left top">
<td>
<span class="font_sty001"><strong><?=$title_3d?>下注</strong></span>
</td>
	<td conspan="2">
		<ul   id ="lottery" style="padding:3px 0 0 0;margin:0px;list-style:none;float:right">
		<li style="float:left;height:26px;line-height:26px">第 <b><font class="colorDate"><?=$num['qishu'] ?></font></b> 期开奖结果：</li>
       <li align="right" class="kjjg_li"><?=$num['ball_1'] ?></li>
      <li align="right" class="kjjg_li"><?=$num['ball_2'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_3'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_4'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_5'] ?></li>
         <li align="right" class="kjjg_li"><?=$num['ball_6'] ?></li>
      <li align="right" class="kjjg_li"><?=$num['ball_7'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_8'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_9'] ?></li>
       <li align="right" class="kjjg_li"><?=$num['ball_10'] ?></li>
                            		</ul>
	</td>
</tr>

</tbody></table><table border="0" cellpadding="0" cellspacing="1" class="game_table">
  <tbody><tr class="tbtitle">
    <td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b><?=dq_qishu(3,$publicdb)?></b></font> 期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font> <font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over" style="color:#7AFF00;font-size:bold;"></span><!-- 时间储存 -->
</td>
  </tr>

       <form autocomplete="off" name="orders" id="order_form"  method="get" target="k_meml" action="main_left.php" onsubmit="return check_submit();">
          <input type="hidden" name="type" value="3">
    <input type="hidden" name="qishu" value="<?=dq_qishu(3,$publicdb)?>">
            <tr class="tbtitle2">
    <th colspan="15"><div style="float:left;line-height:30px;text-align:center;width:100%" id="title_3d"><?=$title_3d?></div>

      </th>
<?php
if($_GET['type'] ==='ball_12' ){

 ?>
  <tr class="tbtitle">
    <td colspan="3">1V10 龙虎</td>
    <td colspan="3">2V9 龙虎</td>
    <td colspan="3">3V8 龙虎</td>
    <td colspan="3">4V7 龙虎</td>
    <td colspan="3">5V6 龙虎</td>
  </tr>
<?php } ?>
  </tr>
   <tr class="tbtitle2">
  <?php if($_GET['type'] == 'ball_11'){  ?>

    <th>总和</th>
    <th>赔率</th>
    <th>金额</th>
    <th>总和</th>
    <th>赔率</th>
    <th>金额</th>
    <th>总和</th>
    <th>赔率</th>
    <th>金额</th>
    <th>总和</th>
    <th>赔率</th>
    <th>金额</th>
  <?php }else{ ?>


    <th>号码</th>
    <th>赔率</th>
    <th>金额</th>
    <th>号码</th>
    <th>赔率</th>
    <th>金额</th>
    <th>号码</th>
    <th>赔率</th>
    <th>金额</th>
    <th>号码</th>
    <th>赔率</th>
    <th>金额</th>
    <th>号码</th>
    <th>赔率</th>
    <th>金额</th>
<?php } ?>
  </tr>
  <?php if ($_GET['type'] ==='ball_1' || $_GET['type'] ==='ball_2' || $_GET['type'] ==='ball_3' || $_GET['type'] === 'ball_4' || $_GET['type'] ==='ball_5' || $_GET['type'] ==='ball_6' || $_GET['type'] ==='ball_7' || $_GET['type'] ==='ball_8' || $_GET['type'] ==='ball_9' || $_GET['type'] ==='ball_10'){
  ?>
<!------------------------------------------   第几名 ------------------------------------------------------>
          <tr>    <td class="ball_bg" style="background-image:url(./public/images/Ball_3/1.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_1"><?=$bj_pk10_odds['h1']?></span></td>
    <td class="ball_ff"><input pid="0" num="0"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_1" name="<?=$_GET['type'] ?>_1"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/2.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_2"><?=$bj_pk10_odds['h2']?></span></td>
    <td class="ball_ff"><input pid="0" num="1"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_2" name="<?=$_GET['type'] ?>_2"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/3.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_3"><?=$bj_pk10_odds['h3']?></span></td>
    <td class="ball_ff"><input pid="0" num="2"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_3" name="<?=$_GET['type'] ?>_3"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/4.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_4"><?=$bj_pk10_odds['h4']?></span></td>
    <td class="ball_ff"><input pid="0" num="3"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_4" name="<?=$_GET['type'] ?>_4"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/5.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_5"><?=$bj_pk10_odds['h5']?></span></td>
    <td class="ball_ff"><input pid="0" num="4"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_5" name="<?=$_GET['type'] ?>_5"></td>
    </tr>


      <tr>    <td class="ball_bg" style="background-image:url(./public/images/Ball_3/6.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_6"><?=$bj_pk10_odds['h6']?></span></td>
    <td class="ball_ff"><input pid="0" num="5"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_6" name="<?=$_GET['type'] ?>_6"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/7.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_7"><?=$bj_pk10_odds['h7']?></span></td>
    <td class="ball_ff"><input pid="0" num="6"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_7" name="<?=$_GET['type'] ?>_7"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/8.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_8"><?=$bj_pk10_odds['h8']?></span></td>
    <td class="ball_ff"><input pid="0" num="7"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_8" name="<?=$_GET['type'] ?>_8"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/9.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_9"><?=$bj_pk10_odds['h9']?></span></td>
    <td class="ball_ff"><input pid="0" num="8"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_9" name="<?=$_GET['type'] ?>_9"></td>


        <td class="ball_bg" style="background-image:url(./public/images/Ball_3/10.png);background-repeat: no-repeat;width:58px;background-position:center;"></td>
    <td class="ball_ff"><span rate="true" id="bl_10"><?=$bj_pk10_odds['h10']?></span></td>
    <td class="ball_ff"><input pid="0" num="9"   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_10" name="<?=$_GET['type'] ?>_10"></td>
    </tr>
   	<?php }elseif($_GET['type'] === 'ball_12') { ?>
    <!------------------------------- 龙虎 ------------------------------------------------------>
    <tr class="tbtitle">       <td class="ball_bg" align="center" valign="middle">
        龙
        </td>
      <td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_pk10_odds[0]['h15']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_274" name="ball_1_15"></td>

                      <td class="ball_bg" align="center" valign="middle">
       龙
        </td>
      <td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_pk10_odds[1]['h15']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_275" name="ball_2_15"></td>

                      <td class="ball_bg" align="center" valign="middle">
        龙
        </td>
      <td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_pk10_odds[2]['h15']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_276" name="ball_3_15"></td>

                      <td class="ball_bg" align="center" valign="middle">
        龙
        </td>
      <td class="ball_ff"><span rate="true" id="bl_277"><?=$bj_pk10_odds[3]['h15']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_277" name="ball_4_15"></td>

                      <td class="ball_bg" align="center" valign="middle">
        龙
        </td>
      <td class="ball_ff"><span rate="true" id="bl_278"><?=$bj_pk10_odds[4]['h15']?></span></td>
     <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_277" name="ball_5_15"></td>
      </tr>
       <tr class="tbtitle">       <td class="ball_bg" align="center" valign="middle">
        虎
        </td>
      <td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_pk10_odds[0]['h16']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_274" name="ball_1_16"></td>

                      <td class="ball_bg" align="center" valign="middle">
       虎
        </td>
      <td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_pk10_odds[1]['h16']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_275" name="ball_2_16"></td>

                      <td class="ball_bg" align="center" valign="middle">
       虎
        </td>
      <td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_pk10_odds[2]['h16']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_276" name="ball_3_16"></td>

                      <td class="ball_bg" align="center" valign="middle">
        虎
        </td>
      <td class="ball_ff"><span rate="true" id="bl_277"><?=$bj_pk10_odds[3]['h16']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_277" name="ball_4_16"></td>

                      <td class="ball_bg" align="center" valign="middle">
        虎
        </td>
      <td class="ball_ff"><span rate="true" id="bl_278"><?=$bj_pk10_odds[4]['h16']?></span></td>
     <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_277" name="ball_5_16"></td>
      </tr>
       </tbody></table>
       <input type="hidden" name="longhu" value="longhu">
<!------------------------------------------- 冠、亚军和（冠军车号+亚军车号） ---------------------------------------------->
      <?php }else if($_GET['type'] ==='ball_11' ){

        for ($i=0; $i < 4; $i++) {
          $h1 = 'h'.($i*4+1);
          $h2 = 'h'.($i*4+2);
          $h3 = 'h'.($i*4+3);
          $h4 = 'h'.($i*4+4);
                 ?>
   <tr class="tbtitle">
    <td class="ball_bg" align="center" valign="middle">
        <?=$i*4+3 ?>
        </td>
      <td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_pk10_odds[$h1]?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_274" name="<?=$_GET['type'] ?>_<?=$i*4+1 ?>"></td>

                      <td class="ball_bg" align="center" valign="middle">
        <?=$i*4+4 ?>
        </td>
      <td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_pk10_odds[$h2]?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_275" name="<?=$_GET['type'] ?>_<?=$i*4+2 ?>"></td>

                      <td class="ball_bg" align="center" valign="middle">
        <?=$i*4+5 ?>
        </td>
      <td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_pk10_odds[$h3]?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_276" name="<?=$_GET['type'] ?>_<?=$i*4+3 ?>"></td>

                      <td class="ball_bg" align="center" valign="middle">
        <?=$i*4+6 ?>
        </td>
      <td class="ball_ff"><span rate="true" id="bl_277"><?=$bj_pk10_odds[$h4]?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_277" name="<?=$_GET['type'] ?>_<?=$i*4+4 ?>"></td>
    </tr>
  <?php } ?>


        <tr class="tbtitle">       <td class="ball_bg" align="center" valign="middle">
              19
        </td>
      <td class="ball_ff"><span rate="true" id="bl_279"><?=$bj_pk10_odds['h17']?></span></td>
      <td class="ball_ff"><input   type="text" js='js' style="height:18px" class="input1" size="4" id="Num_279" name="<?=$_GET['type'] ?>_17"></td>
                 <td class="ball_bg" colspan="9" align="center" valign="middle">

        </td>
              </tr>
  	<?php } ?>


  <?php if ($_GET['type'] ==='ball_1' || $_GET['type'] ==='ball_2' || $_GET['type'] ==='ball_3' || $_GET['type'] ==='ball_4' || $_GET['type'] ==='ball_5' || $_GET['type'] ==='ball_6' || $_GET['type'] ==='ball_7' || $_GET['type'] ==='ball_8' || $_GET['type'] ==='ball_9' || $_GET['type'] ==='ball_10') {
?>
<!-- <table width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:5px" class="game_table"> -->
  <!-- <tbody> -->
<!------------------------------------------- 大小单双 ------------------------------------------------>

     <tr class="tbtitle">
 <td class="ball_bg" align="center" valign="middle">
        大
        </td>
      <td class="ball_ff"><span rate="true" id="bl_21"><?=$bj_pk10_odds['h11']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_21" name="<?=$_GET['type'] ?>_11"></td>

                      <td class="ball_bg" align="center" valign="middle">
        小
        </td>
      <td class="ball_ff"><span rate="true" id="bl_22"><?=$bj_pk10_odds['h12']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_22" name="<?=$_GET['type'] ?>_12"></td>

                      <td class="ball_bg" align="center" valign="middle">
        单
        </td>
      <td class="ball_ff"><span rate="true" id="bl_23"><?=$bj_pk10_odds['h13']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_23" name="<?=$_GET['type'] ?>_13"></td>

                      <td class="ball_bg" align="center" valign="middle">
        双
        </td>
      <td class="ball_ff"><span rate="true" id="bl_24"><?=$bj_pk10_odds['h14']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_24" name="<?=$_GET['type'] ?>_14"></td>


                <td colspan="3"></td>
      </tr>
     <?php }?>

<?php if ($_GET['type'] ==='ball_11' ){
  ?>

<!------------------------------------------- 冠亚军和  大小单双 ------------------------------------------------>

        <tr class="tbtitle">
 <td class="ball_bg" align="center" valign="middle">
        冠亚大
        </td>
      <td class="ball_ff"><span rate="true" id="bl_21"><?=$bj_pk10_odds['h18']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_21" name="<?=$_GET['type'] ?>_18"></td>

                      <td class="ball_bg" align="center" valign="middle">
        冠亚小
        </td>
      <td class="ball_ff"><span rate="true" id="bl_22"><?=$bj_pk10_odds['h19']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_22" name="<?=$_GET['type'] ?>_19"></td>

                      <td class="ball_bg" align="center" valign="middle">
        冠亚单
        </td>
      <td class="ball_ff"><span rate="true" id="bl_23"><?=$bj_pk10_odds['h20']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_23" name="<?=$_GET['type'] ?>_20"></td>

                      <td class="ball_bg" align="center" valign="middle">
        冠亚双
        </td>
      <td class="ball_ff"><span rate="true" id="bl_24"><?=$bj_pk10_odds['h21']?></span></td>
      <td class="ball_ff"><input

 type="text" js='js' style="height:18px" class="input1" size="4" id="Num_24" name="<?=$_GET['type'] ?>_21"></td>
      </tr>
     <?php } ?>
</tbody></table>
<table width="100%" border="0" cellpadding="0" cellspacing="1"  class="game_table">
  <tbody>
          <tr class="hset2">
    <td colspan="13" class="tbtitle">
        <div style="float:right!important">
         <input name="btnSubmit"  type="submit" class="button_a" id="btnSubmit" value="下注">
        <input type="reset" onclick="ClearData();" class="button_a" name="Submit3" value="重置">

      </div></td>
  </tr>
    </form>

</tbody></table>

<iframe src="about:blank" width="100%" height="1" frameborder="0" marginheight="1" marginwidth="1"></iframe>
</div>


<!-- 快速金额设定 -->
<script src="./public/js/js.js" type="text/javascript"></script>
<div class="aui_state_focus" id="aui_state_focus" style="position: absolute; left: 300px; top: 90px; width: 220px; z-index: 1987;display:none;"><div class="aui_outer"><table class="aui_border"><tbody><tr><td class="aui_nw"></td><td class="aui_n"></td><td class="aui_ne"></td></tr><tr><td class="aui_w"></td><td class="aui_c"><div class="aui_inner"><table class="aui_dialog"><tbody><tr><td colspan="2" class="aui_header"><div class="aui_titleBar"><div class="aui_title" style="cursor: move;">快速金额设定</div>

<a class="aui_close" onclick="document.getElementById('aui_state_focus').style.display='none'";>×</a>

</div></td></tr><tr><td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: none;"></div></td><td class="aui_main" style="width: 200px; height: 250px;"><div class="aui_content aui_state_full" style="padding: 20px 25px;"><div class="aui_loading" style="display: none;"><span></span></div>
<iframe src="./include/set_money.php" name="OpenartDialog14271810369650" frameborder="0" allowtransparency="true" style="width: 100%; height: 100%; border: 0px none;"></iframe>
</div></td></tr><tr><td colspan="2" class="aui_footer"><div class="aui_buttons" style="display: none;"></div></td></tr></tbody></table></div></td><td class="aui_e"></td></tr><tr><td class="aui_sw"></td><td class="aui_s"></td><td class="aui_se" style="cursor: se-resize;"></td></tr></tbody></table></div></div>
</body></html>