<?php

//读取赔率
$dat = M("c_odds_7",$db_config);
$data_bet = $dat->field("rate,id,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '".$ids."'")->select();

//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(7,$db_config);

 $f_state = $array['f_state']; // 封盘状态判断时间

 $o_state = $array['o_state']; // 开盘状态判断时间
 // p($array);
//特码色波
function tm_sebo($str){

$arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
$arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
$arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
  if($str != 0){

    if(in_array($str,$arr1)){
      echo "r";
    }else if(in_array($str,$arr2)){
      echo "b";
    }else if(in_array($str,$arr3)){
      echo "g";
    }
  }
}
 ?>
  <div id="kq_box_001" style="display:none;background: #E9E9E9 url(./public/images/loading11.gif) no-repeat center 120px;">
  <div id="kq_box_num" style="margin:50px auto 0">
     距离 第 <b><font color="#7c3838"> <?=dq_qishu(7,$db_config)?> </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span id="close_time" style="float:none;display:inline"></span></strong>
  </div>
</div>
<script>
$(function(){
	    var o_stime = <?=$array['o_t_stro']?>;
  var f_stime = <?=$array['f_t_stro']?>;

 var interval = 1000;
function ShowCountDown(leftsecond,divname){
  var day1=Math.floor(leftsecond/(60*60*24));
  var hour=Math.floor((leftsecond-day1*24*60*60)/3600);
  var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60);
  var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60);
  var cc = document.getElementById(divname);
  if(day1>0){
    cc.innerHTML = day1+"天"+hour+"小时"+minute+"分"+second+"秒";
  }else{
    cc.innerHTML = hour+"小时"+minute+"分"+second+"秒";
  }
    if(hour==0 && minute==0 && second==0){
      window.location.reload();
    }
    if (f_o_state ==1) {
       f_stime = f_stime-1;
    }else{
       o_stime = o_stime-1;
    }

}
  if (f_o_state == 1) {  //距离封盘时间
      window.setInterval(function(){ShowCountDown(f_stime,'time_over');}, interval);
  }else{   //距离开盘时间
      window.setInterval(function(){ShowCountDown(o_stime,'close_time');}, interval);
  }

})
</script>
<tr style="background:url(public/images/title_bg_004.gif) repeat-x left top;" class="all_body1" style="display:none">
	<td>
	<span class="font_sty001"><strong style="height:16px;line-height:30px;color:#fff; padding-left:10px;"><?=$_GET['leixing'] ?>下注</strong></span>
		<ul style="padding:3px 0 0 0;margin:0px;list-style:none;float:right">
			<li style="float:left;height:26px;line-height:26px;color:#fff;">第 <b><font class="colorDate"><?=$Current_KitheTable['nn']  ?></font></b> 期开奖结果：</li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n1']) ?>"><?=$Current_KitheTable['n1'] ?></li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n2']) ?>"><?=$Current_KitheTable['n2'] ?></li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n3']) ?>"><?=$Current_KitheTable['n3'] ?></li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n4']) ?>"><?=$Current_KitheTable['n4'] ?></li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n5']) ?>"><?=$Current_KitheTable['n5'] ?></li>
      <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['n6']) ?>"><?=$Current_KitheTable['n6'] ?></li>
      <li align="right" style="float: left;height: 24px;
width: 24px;text-align: center;line-height: 24px;font-weight: bold;
margin: 0 3px;color: #fff;">+</li>
       <li align="right" class="kjjg_li_h ball_<?php tm_sebo($Current_KitheTable['na']) ?>"><?=$Current_KitheTable['na'] ?></li>
      </ul>
	</td>
</tr></tbody></table>
<TABLE cellSpacing=1 cellPadding=0 width=780 border=0 class="Ball_List all_body"  >
 	 <tr class="tbtitle">
    <td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b><?=dq_qishu(7,$db_config)?> </b></font> 期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font> <font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over" style="color:#7AFF00;font-size:bold;"></span><!-- 时间储存 -->
      <script language="javascript">
</script></td>
</tr>
<SCRIPT language=JAVASCRIPT>
//加载完成后更改球号的波色类
$(function(){
  var arr1=[01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46];//红
  var arr2=[03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48];//蓝
  var arr3=[05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49];//绿
  $("td[class='ball_b']").each(function(){
    if($.inArray(eval($(this).text()),arr1) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_r");
    }else if($.inArray(eval($(this).text()),arr2) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_b");
    }else if($.inArray(eval($(this).text()),arr3) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_g");
    }
  })
  $("td[class='ball_g']").each(function(){
    if($.inArray(eval($(this).text()),arr1) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_r");
    }else if($.inArray(eval($(this).text()),arr2) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_b");
    }else if($.inArray(eval($(this).text()),arr3) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_g");
    }
  })
  $("td[class='ball_r']").each(function(){
    if($.inArray(eval($(this).text()),arr1) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_r");
    }else if($.inArray(eval($(this).text()),arr2) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_b");
    }else if($.inArray(eval($(this).text()),arr3) != -1){
      $(this).removeClass($(this).attr('class')).addClass("ball_g");
    }
  })
})
</script>





