<?php
 /**
*
*  cash_type类型 1額度轉換 2体育下注 3彩票下注 4视讯下注 5彩票派彩    7系统拒绝出款 8系统取消出款 14彩票派彩 15体育派彩
*                 9优惠退水 10线上存款 11公司入款 12存入取出 19线上取款 13优惠冲销 6活动优惠 20和局返本金 21派彩有误 22体育无效注单23系统取消出款 25彩票无效注单
*  27注单取消(彩票) 28注单取消(体育)             
*
*    cash_do_type 1表示存入 2表示取出 3人工存入 4人工取出 5扣除派彩 6返回本金
*    
*    
*
 **/
include_once("../../include/config.php");
include_once("../common/login_check.php"); 


//时间判断
if (!empty($_GET['start_date'])) {
  $s_date = $start_date = $_GET['start_date'];   
}else{
  $s_date = $start_date = date("Y-m-d");   
}

if (!empty($_GET['end_date'])) {
  $e_date = $end_date = $_GET['end_date'];   
}else{
  $e_date = $end_date = date("Y-m-d");   
}
if (empty($_GET['timearea'])) {
   $_GET['timearea'] = 0;
}
$start_date = strtotime($start_date.' 00:00:00')+$_GET['timearea']*3600;
$end_date = strtotime($end_date.' 23:59:59')+$_GET['timearea']*3600;
$start_date = date('Y-m-d H:i:s',$start_date);
$end_date = date('Y-m-d H:i:s',$end_date);
$map = "k_user_cash_record.site_id = '".SITEID."' and k_user_cash_record.cash_date >= '".$start_date."' and k_user_cash_record.cash_date <= '".$end_date."' and k_user.shiwan = '0'";

//方式
if (!empty($_GET['deptype'])) {
  $type;
  $type = $_GET['deptype'];
  $arrType = explode('-', $type);
  if (count($arrType) > 1) {
     //表示检索参数cash_do_type
     $map .= " and ((k_user_cash_record.cash_do_type = '".$arrType[0]."' and k_user_cash_record.cash_type = '".$arrType[1]."' ) or k_user_cash_record.cash_do_type = '".$arrType[2]."') ";
  }else{
        if($type == 1 || $type == 2 || $type == 4 || $type == 3 || $type == 14 || $type == 15){
          $map .= " and k_user_cash_record.cash_type = '".$type."'";
        }elseif($type == 'xsqk'){
          //线上取款 包括拒绝取消
          $map .= " and k_user_cash_record.cash_type in (7,23,19) ";
        }elseif($type == 'in'){
          //入款明细 公司入款 线上入款 人工存入 优惠退水 优惠活动
          $map .= " and (k_user_cash_record.cash_do_type = '3' or k_user_cash_record.cash_type in (6,9,10,11)) ";
        }elseif($type == 'out'){
          //出款明细
          $map .= " and ((k_user_cash_record.cash_do_type in (2,4) and k_user_cash_record.cash_type = '12') or k_user_cash_record.cash_type in (7,19,23)) ";
        }elseif($type == 'ot'){
           //全网活动优惠
          $map .= " and k_user_cash_record.cash_only = '1' ";
        }elseif($type == 'wx'){
          //无效注单
           $map .= " and  k_user_cash_record.cash_type in (22,25,26) ";
        }elseif($type == 'cel'){
          //取消注单
           $map .= " and  k_user_cash_record.cash_type in (27,28) ";
        }else{
           $map .= " and k_user_cash_record.cash_type = '".$type."' ";
        }

  }
}

//账户查询
if (!empty($_GET['username'])) {
    $userd = $_GET['username'];
    $map .= " and k_user_cash_record.username = '".$_GET['username']."' ";
}

//其它链接过来的uid
if (!empty($_GET['uid'])) {
   $map .= "and k_user_cash_record.uid = '".$_GET['uid']."'";
}

$CashR = M('k_user_cash_record',$db_config);
//获得记录总数
$count=$CashR->join("left join k_user on k_user.uid = k_user_cash_record.uid")->where($map)->count();

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$record = array();
$record = $CashR->join("left join k_user on k_user.uid = k_user_cash_record.uid")->where($map)->order('id desc')->limit($limit)->select();
//总计

$all_count = $CashR->join("left join k_user on k_user.uid = k_user_cash_record.uid")->field("sum(k_user_cash_record.cash_num) as Cnum,sum(k_user_cash_record.discount_num) as Dnum")->where($map)->find();

?>
<?php $title="现金系统"; require("../common_html/header.php");?>
<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<div id="con_wrap">
<div class="input_002">現金系統</div>
<form id="myFORM" method="get" name="myFORM">
<div class="con_menu">
重新整理：
  <select name="reload" id="retime" class="za_select" onchange="setTimeout(&#39;setRefresh()&#39;, this.value*1000)">
    <option value="-1"  <?=select_check(-1,$_GET['reload'])?>>不更新</option>
    <option value="10" <?=select_check(10,$_GET['reload'])?>>10秒</option>
    <option value="30" <?=select_check(30,$_GET['reload'])?>>30秒</option>
    <option value="60" <?=select_check(60,$_GET['reload'])?>>60秒</option>
  </select>

帳號:
<input type="text" name="username" style="min-width:120px;width:120px;"class="za_text" value="<?=$userd?>">
時區:
<select name="timearea" id="area">
    <option value="0" <?=select_check(0,$_GET['timearea'])?>>美东</option>
    <option value="12" <?=select_check(12,$_GET['timearea'])?>>北京</option>
    </select>
存入日期: 從
 <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$s_date?>"  name="start_date">至
 <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$e_date?>"  name="end_date">
方式:
 <select name="deptype" class="za_select" onchange="document.getElementById('myFORM').submit()">
  <option value="" >全部方式</option>
   <option value="1" <?=select_check(1,$_GET['deptype'])?>>額度轉換</option>
  <option value="2" <?=select_check(2,$_GET['deptype'])?>>体育下注</option>
  <option value="15" <?=select_check(15,$_GET['deptype'])?>>体育派彩</option>
  <option value="3" <?=select_check(3,$_GET['deptype'])?>>彩票下注</option>
  <option value="14" <?=select_check(14,$_GET['deptype'])?>>彩票派彩</option>
  <option value="wx" <?=select_check('wx',$_GET['deptype'])?>>注单无效</option>
  <option value="cel" <?=select_check('cel',$_GET['deptype'])?>>注单取消</option>
  <option value="10" <?=select_check(10,$_GET['deptype'])?>>线上入款</option>
  <option value="11" <?=select_check(11,$_GET['deptype'])?>>公司入款</option>
  <option value="xsqk" <?=select_check('xsqk',$_GET['deptype'])?>>线上取款</option>
  <option value="9" <?=select_check(9,$_GET['deptype'])?>>优惠退水</option>
  <option value="ot" <?=select_check('ot',$_GET['deptype'])?>>优惠活动</option>
  <option value="1-12-3" <?=select_check('1-12-3',$_GET['deptype'])?>>人工存入</option>
  <option value="2-12-4" <?=select_check('2-12-4',$_GET['deptype'])?>>人工取出</option>
  <option value="12" <?=select_check('12',$_GET['deptype'])?>>人工存款與取款</option>
  <option value="in" <?=select_check('in',$_GET['deptype'])?>>入款明细</option>
  <option value="out" <?=select_check('out',$_GET['deptype'])?>>出款明细</option>
</select>
  <input type="SUBMIT" name="SUBMIT" value="查詢" class="za_button"> 每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
 &nbsp;頁數：
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
  </select> <?=$totalPage?> 頁



 </div>
</form>
</div>

<div class="content">
      <table  cellpadding="0" class="m_tab">
        <tbody><tr class="m_title">
          <td>會員帳號</td>
          <td>幣別</td>
          <td>类型</td>
          <td>交易别</td>
          <td>交易金額</td>
          <td>余额</td>
          <td>交易日期</td>
          <td>備註</td>
        </tr>
        <?php if (!empty($record)) {
          $totalCo=0;
          $Kuser = M('k_user',$db_config);
          foreach ($record as $key => $val) {
            $shiwan = $Kuser->where("uid = '".$val['uid']."'")->getField("shiwan");
            if ($shiwan == '1') {
                continue;
            }
            $count_c +=$val['cash_num']+$val['discount_num'];
            $totalCo += $count_c;
        ?>
       
        <tr class="m_cen"> 
          <td><?=$val['username']?></td>
          <td>人民幣</td>
          <td><?=cash_type_r($val['cash_type'])?></td>
          <td><?=cash_do_type_r($val['cash_do_type'])?></td>
          <td style="text-align:right"><?=$val['cash_num']?>
           <?php 
              if ($val['cash_type'] == 12 || $val['cash_type'] == 11 || $val['cash_type'] == 10 || $val['cash_type'] == 6|| $val['cash_type'] == 9) {
                  echo '(+'.$val['discount_num'].')';
              }
           ?>
          </td>
          <td><?=$val['cash_balance']?></td>
          <td><?=$val['cash_date']?></td>
          <td><?=$val['remark']?></td>
        </tr>
        <?php }
          }else{
            echo " <tr class=\"m_cen\">
          <td colspan=\"9\">目錄沒有記錄</td>
        </tr>
";
          }
        ?>
        <tr class="m_rig2"> 
          <td colspan="4" class="count_td">小計</td>
          <td bgcolor="#CAE8EA" colspan="5"><?=$count_c?></td> 
        </tr>
         <tr class="m_rig2"> 
          <td colspan="4" class="count_td">总计</td>
          <td bgcolor="#CAE8EA" colspan="5"><?=($all_count['Cnum']+$all_count['Dnum'])?></td>
        </tr> 
      </tbody></table>
</div>
<script type="text/javascript">
// var retime_a = -1;
// $('#retime').val(retime_a);
function setRefresh()
{
  $('#myFORM').submit();
}
var retime = $('#retime').val();
$(document).ready(function()
{
  var time = (retime == 0 || retime == -1) ? -1 : "" + retime;
  if(time != -1)
  {
    setTimeout("setRefresh()", time * 1000);    
  }
})

</script>
<?php
//返回类型
function cash_type_r($cash_type){
   switch ($cash_type) {
     case '1':
       return '額度轉換';
       break;
     case '2':
       return '体育下注';
       break;
     case '3':
       return '彩票下注';
       break;
     case '4':
       return '视讯下注';
       break;
     case '5':
       return '彩票派彩';
       break;
     case '6':
       return '活动优惠';
       break;
    case '7':
       return '系统拒绝出款';
       break;
    case '8':
       return '系统取消出款';
       break;
     case '9':
       return '优惠退水';
       break;
     case '10':
       return '在线存款';
       break;
     case '11':
       return '公司入款';
       break;
     case '12':
       return '存入取出';
       break;
     case '13':
       return '优惠冲销';
       break;
     case '14':
       return '彩票派彩';
       break;
     case '15':
       return '体育派彩';
       break;
     case '19':
       return '线上取款';
       break;
     case '20':
       return '和局返本金';
     case '22':
       return '体育无效注单';
     case '23':
       return '系统取消出款';
     case '24':
       return '系统拒绝出款';
     case '25':
       return '彩票无效注单';
     case '26':
       return '彩票无效注单(扣本金)';
     case '27':
       return '注单取消(彩票)';
     case '28':
       return '注单取消(体育)';
   }
}
//返回交易类别
function cash_do_type_r($do_type){
   switch ($do_type) {
     case '1':
       return '存入';
       break;
     case '2':
       return '取出';
       break;
     case '3':
       return '人工存入';
       break;
      case '4':
       return '人工取出';
       break;
      case '5':
       return '扣除派彩';
       break;
      case '6':
       return '返回本金';
       break;
   }
}
?>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
