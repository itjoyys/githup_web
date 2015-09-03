<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include("../../include/pager.class.php");
include_once("../../class/user.php");
include_once("../../lib/class/model.class.php");
$c = M('k_bet',$db_config);
$map = "uid = '".$_GET['uid']."'";
if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
   $map = $map." and bet_time >'".$_POST['start_date']."' and bet_time < '".$_POST['end_date']."'";
}elseif (!empty($_POST['start_date'])) {
   $map = $map." and bet_time >'".$_POST['start_date']."'";
}elseif (!empty($_POST['end_date'])) {
   $map = $map." and bet_time <'".$_POST['end_date']."'";
}
$record = $c->where($map)->select();
?>
<?php require("../common_html/header.php");?>
<?php require("member_record_com.php");?>
<div  class="content">

  <table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab"  bgcolor="#3B2D1B">
      <tbody>
      <tr class="m_title" align="center">
        <td width="5%"><strong>编号</strong></td>
        <td width="15%"><strong>联赛名</strong></td>
        <td width="15%"><strong>编号/主客队</strong></td>
        <td width="25%"><strong>投注详细信息</strong></td>
        <td width="5%"><strong>下注</strong></td>
        <td width="5%"><strong>结果</strong></td>
        <td width="5%"><strong>可赢</strong></td>
        <td width="10%"><strong>投注/开赛时间</strong></td>
        <td width="10%"><strong>返水</strong></td>
        <td width="5%"><strong>状态</strong></td>
      </tr>
         <?php
		 if(!empty($record)){
           foreach ($record as $key => $val) {  
        ?>
          <tr align="center">
        <td><?=$val['bid']?></td>
        <td><?=$val['match_name']?></td>
        <td><?=$val['master_guest']?></td>
        <td ><?=$val['bet_info']?></td>
        <td ><?=$val['bet_money']?></td>
        <td ><?=$val['bid']?></td>
        <td ><?=$val['bid']?></td>
        <td ><?=$val['bet_time']?></td>
        <td ><strong><?=$val['fs']?></strong></td>
        <td ><strong><?=$val['status']?></strong></td>
      </tr>
      <?php
         }}
      ?>
<!--       <tr  class="m_rig"  style="display:;">
        <td  height="70"  align="center"  colspan="8"><font  color="#3B2D1B">暫無數據。</font></td>
      </tr> -->
      
      <tr  class="m_rig"  align="left"  style="display:none;">
        <td  align="center"></td>
        <td  align="center"> <font  color="#CC0000"></font></td>
        <td  align="center"  nowrap=""><br><font  color="#CC0000"><b></b></font></td>
        <td  align="center"></td>
        <td></td>
        <td>0.0</td>
        <td>0.0</td>
        <td></td>
      </tr>
      
      <tr  class="m_rig"  style="background-Color:#EBF0F1;display:none;">
        <td  colspan="3"  align="right">&nbsp;小計：</td>
        <td>0筆</td>
        <td>0.0</td>
        <td>0.0</td>
        <td>0.0</td>
        <td><font  color="#000">0.0</font></td>
      </tr>
      <tr  class="m_rig"  style="background-Color:#EBF0F1;display:none;">
        <td  colspan="3"  align="right">&nbsp;总計：</td>
        <td>0筆</td>
        <td>0.0</td>
        <td>0.0</td>
        <td>0.0</td>
        <td><font  color="#000">0.0</font></td>
      </tr>
  </tbody></table>
</div>

<script  language="javascript">
var vtimeCashList = 0;
var timeGoCashList = null;
function SetTimeCashList(otime)
{
    vtimeCashList=otime;
    if(vtimeCashList>0)
    {
        window.clearTimeout(timeGoCashList);
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';  
        if(otime!=0)
        {
            timeGoCashList=setInterval("timeCashList("+otime+")",1000);
        }
    }
    else 
    {
        document.getElementById("lblTime").innerHTML="";
        window.clearTimeout(timeGoCashList);
    }
}
function timeCashList(otime)
{
    if(vtimeCashList<=0)
    {
        document.getElementById("lblTime").innerHTML=""; 
        window.clearTimeout(timeGoCashList); 
    }
    else 
    {
        vtimeCashList=vtimeCashList-1;
        if(vtimeCashList<=0)
        {             
          getdata();            
            vtimeCashList=otime;
        }
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';
        
    }
}
function getdata(page){
  form_obj = document.getElementById("myFORM");
  form_obj.action = "index.php";
  form_obj.submit();
}
var reload = -1;
$(document).ready(function(){
  if(reload>0){
    SetTimeCashList(reload);
  }
  $("#reload").val(reload);
  $("#page_num").val('20');
  $("#page").val('0');  
});
</script>
<?php require("../common_html/footer.php");?>  
 </body></html>