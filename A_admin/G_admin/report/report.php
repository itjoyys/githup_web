<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../include/public_config.php");

//期数
$periods_old = period(date('Y')-1);
$periods = period(date('Y'));
$periods_now = $periods[date('n')-1];
?>
<?php $title="报表明细"; require("../common_html/header.php");?>
<SCRIPT>
<!--
 function onSubmit()
 {
  kind_obj = document.getElementById("report_kind");
  form_obj = document.getElementById("myFORM");
  if(kind_obj.value == "A")
   form_obj.action = "report_all.php";
  else
   form_obj.action = "report_class.php";
  return true;
 }
-->
</SCRIPT>
<script language="JavaScript">
var tmp_date;
function chg_date(range,num1,num2){ 

//alert(num1+'-'+num2);
if(range=='t' || range=='w' || range=='r'){
 FrmData.date_start.value ='<?=date("Y-m-d")?>';
 FrmData.date_end.value =FrmData.date_start.value;}

if(range!='t'){
 if(FrmData.date_start.value!=FrmData.date_end.value){ FrmData.date_start.value ='<?=date("Y-m-d",strtotime("-12 hour"))?>'; FrmData.date_end.value =FrmData.date_start.value;}
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
</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<div  id="con_wrap">
  <div  class="input_002">報表查詢</div>
  <div  class="con_menu">  
  </div>
</div>
<div class="content">
  <div style="float:left;width:60%;">
<form action="./report_result.php" method="POST"  name="FrmData" id="FrmData">
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
              <input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=date("Y-m-d",strtotime("last Monday"))?>';FrmData.date_end.value='<?=date("Y-m-d",strtotime("-12 hour"))?>'" value="本星期">&nbsp;&nbsp;
              <input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=date("Y-m-d",strtotime("last Monday")-604800)?>';FrmData.date_end.value='<?=date("Y-m-d",strtotime("last Monday")-86400)?>'" value="上星期">&nbsp;&nbsp;
             <!--  <input name="Submit" type="Button" class="za_button" onClick="FrmData.date_start.value='<?=$periods_now['sdate']?>';FrmData.date_end.value='<?=date("Y-m-d")?>'" value="本期"> --></td>
          </tr>
        </table></td>
      </tr>
      <tr class="m_cen">
          <td width="100" class="m_title_re">游戏選擇：</td>
          <td colspan="3" style="text-align:left">
           <input type="checkbox" name="sp" class="game" checked="checked" id="zq" value="1">
            &nbsp;體育&nbsp;
            <input type="checkbox" name="cp" class="game" checked="checked" id="cp" value="2">
            &nbsp;彩票&nbsp;
            <input type="checkbox" name="g_type[]" class="game" checked="checked" id="mg" value="mg">
            &nbsp;MG&nbsp;
            <input type="checkbox" name="g_type[]" class="game" checked="checked" id="ct" value="ct">
            &nbsp;CT&nbsp;
            <input type="checkbox" name="g_type[]" class="game" checked="checked" id="bb" value="bbin">
            &nbsp;BBIN&nbsp;
            <input type="checkbox" name="g_type[]" class="game" checked="checked" id="ag" value="ag">
            &nbsp;AG&nbsp;
            <input type="checkbox" name="g_type[]" class="game" checked="checked" id="og" value="og">
            &nbsp;OG&nbsp; 
             <input type="checkbox" name="g_type[]" class="game" checked="checked" id="lebo" value="lebo">
            &nbsp;LEBO&nbsp; 
             <input type="checkbox" name="g_type[]" class="game" checked="checked" id="ct" value="mgdz">
            &nbsp;MG电子&nbsp;
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
          <td colspan="3" style="text-align:left">
          <select name="is_res">
              <option value="0">全部</option>
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
   </td>
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
<?php include("../common_html/footer.php"); ?>