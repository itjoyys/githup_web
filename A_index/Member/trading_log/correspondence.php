<?php
include_once "../common/member_config.php";
include_once("../../include/config.php");
include_once("../../common/login_check.php");
include_once("../../common/function.php");


//测试
//读取所有记录
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
   $map = "k_user_cash_record.cash_date > '".$_GET['start_date']." 00:00:00' and k_user_cash_record.cash_date < '".$_GET['end_date']." 23:59:59' ";
   $con = "cash_date > '".$_GET['start_date']." 00:00:00' and cash_date < '".$_GET['end_date']." 23:59:59' ";
}elseif (!empty($_GET['start_date'])) {
   $map = "k_user_cash_record.cash_date > '".$_GET['start_date']." 00:00:00' ";
   $con = "cash_date > '".$_GET['start_date']." 00:00:00' ";
}elseif (!empty($_GET['end_date'])) {
   $map = "k_user_cash_record.cash_date < '".$_GET['end_date']." 23:59:59' ";
   $con = "cash_date < '".$_GET['end_date']." 23:59:59' ";
}else{
   $map = "k_user_cash_record.cash_date like '".date('Y-m-d')."%' ";
   $con = "cash_date like '".date('Y-m-d')."%' ";
   $start_date = $end_date = date('Y-m-d');
}
$map .= "and k_user.site_id = '".SITEID."' ";

//账户查询
if (!empty($_GET['username'])) {
   $map .= "and k_user.username = '".$_GET['username']."'";
}

//方式
if (!empty($_GET['deptype'])) {
  $type;
  $type = $_GET['deptype'];
  $arrType = explode('-', $type);
  if (count($arrType) > 1) {
     //表示检索参数cash_do_type
     $map .= " and ((k_user_cash_record.cash_do_type = '".$arrType[0]."' and k_user_cash_record.cash_type = '".$arrType[1]."' ) or k_user_cash_record.cash_do_type = '".$arrType[2]."') ";
     $con .= " and ((cash_do_type = '".$arrType[0]."' and cash_type = '".$arrType[1]."' ) or cash_do_type = '".$arrType[2]."') ";
  }else{
     if($type == 1 || $type == 2 || $type == 4 || $type == 3 || $type == 14 || $type == 15 || $type==19 || $type==7 ||$type==23){
      $map .= " and k_user_cash_record.cash_type = '".$type."'";
      $con .= " and cash_type = '".$type."'";
    }elseif($type == 'in'){
      //入款明细
      $map .= " and (k_user_cash_record.cash_do_type = '3' or k_user_cash_record.cash_type in (10,11)) ";
      $con .= " and (cash_do_type = '3' or cash_type in (10,11)) ";
    }elseif($type == 'out'){
      //出款明细
      $map .= " and ((k_user_cash_record.cash_do_type = '2' and k_user_cash_record.cash_type = '12') or k_user_cash_record.cash_type in (7,8,19)) ";
      $con .= " and ((cash_do_type = '2' and cash_type = '12') or cash_type in (7,8,19)) ";
    }else{
      $map .= " and k_user_cash_record.cash_type = '".$type."' ";
      $con .= " and cash_type = '".$type."' ";
    }

  }
}



//账户

   $map .= "and k_user.uid = '".$_SESSION['uid']."'";
   $con .= "and uid = '".$_SESSION['uid']."' ";

//获得记录总数
$count=M('k_user_cash_record',$db_config)->join("join k_user on k_user.uid = k_user_cash_record.uid")->where($map)->order('k_user_cash_record.id desc')->count();

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;


$record = M('k_user_cash_record',$db_config)->join("join k_user on k_user.uid = k_user_cash_record.uid")->where($map)->order('k_user_cash_record.id desc')->limit($limit)->select();


//总计
$table=M('k_user_cash_record',$db_config);
$cash_num = $table->where($con)->sum('cash_num');
$discount_num = $table->where($con)->sum('discount_num');
$all_count=number_format($cash_num[0]+$discount_num[0]+0,2);

function select_check($field, $val) {
  if ($field == $val) {
    echo "selected=\"selected\"";
  }
}
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>

        <script language="javascript" type="text/javascript" src="../public/js/jquery-1.7.min.js"></script>
        <script  type="text/javascript" src="../public/date/WdatePicker.js"></script>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
    </head>
    <body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
		<div id="MAMain" style="width:767px">
			<div id="MACenter-content">
				<div id="MACenterContent">
					<div id="MNav">
						<a class="mbtn"  target="k_memr" href="record_ds.php">投注记录</a>
						<div class="navSeparate"></div>
						<span class="mbtn">往来记录</span>
					</div>
					<div id="MMainData" style="overflow-y:scroll; height:370px">
						<form id="myFORM" method="get" name="myFORM">
							<div class="MControlNav">
								<span>交易别类</span>:
								<select name="deptype" id="sort_way" onchange="document.getElementById('myFORM').submit()">
									<option value="" >全部方式</option>
									<option value="1" <?=select_check(1,$_GET['deptype'])?>>額度轉換</option>
									<option value="2" <?=select_check(2,$_GET['deptype'])?>>体育下注</option>
									<option value="15" <?=select_check(15,$_GET['deptype'])?>>体育派彩</option>
									<option value="3" <?=select_check(3,$_GET['deptype'])?>>彩票下注</option>
									<option value="14" <?=select_check(14,$_GET['deptype'])?>>彩票派彩</option>
									<option value="10" <?=select_check(10,$_GET['deptype'])?>>线上入款</option>
									<option value="11" <?=select_check(11,$_GET['deptype'])?>>公司入款</option>
									<option value="19" <?=select_check(19,$_GET['deptype'])?>>线上取款</option>
									<option value="9" <?=select_check(9,$_GET['deptype'])?>>优惠退水</option>
									<option value="1-12-6" <?=select_check('1-12-6',$_GET['deptype'])?>>优惠活动</option>
									<option value="1-12-3" <?=select_check('1-12-3',$_GET['deptype'])?>>人工存入</option>
									<option value="2-12-4" <?=select_check('2-12-4',$_GET['deptype'])?>>人工取出</option>
									<option value="23" <?=select_check('23',$_GET['deptype'])?>>系统取消出款</option>
									<option value="7" <?=select_check('7',$_GET['deptype'])?>>系统拒绝出款</option>
									<option value="12" <?=select_check('12',$_GET['deptype'])?>>人工存款與取款</option>
									<option value="in" <?=select_check('in',$_GET['deptype'])?>>入款明细</option>
									<option value="out" <?=select_check('out',$_GET['deptype'])?>>出款明细</option>
								</select>
								时间:<input type="text" id="start_date" name="start_date" value="<?=$start_date?>" maxlength="20" size="10" class=" Wdate" readonly style="width: 98px;margin:10px 0" onclick="WdatePicker()">
								-
								<input type="text" id="end_date" name="end_date" value="<?=$end_date?>" maxlength="20" size="10" class=" Wdate" readonly style="width: 98px;" onclick="WdatePicker()">

								<select id="page" name="page" class="za_select">
								&nbsp;&nbsp;页数：
									<?php
									for($i=1;$i<=$totalPage;$i++){
										if($i==$page){
											echo  '<option value="'.$i.'" selected>'.$i.'</option>';
										}else{
											echo  '<option value="'.$i.'">'.$i.'</option>';
										}
									}
									?>
								</select> <?php echo  $totalPage ;?> 頁&nbsp;
								<input type="submit" name="subbtn" class="button_a" onclick="reload_live_today()" value=" 查 询 ">
							</div>
						</form>
						<div class="MPanel" style="display: block;">
							<table class="MMain" border="1">
								<tbody>
									<tr class="m_title">
										<th width="120" class="his_time">日期</th>
										<th width="70">类型</th>
										<th width="70">交易类别</th>
										<th width="70">交易额度</th>
										<th width="80">现有额度</th>
										<th width="180">备注</th>
									</tr>
									<?php
										$num=count($record);
										if (!empty($record)) {
										$counts='';
										foreach ($record as $key => $val) {
											$counts +=$val['cash_num']+$val['discount_num'];
									?>
									<tr>
										<td style="text-align:center;"><?=$val['cash_date']?></td>
										<td style="text-align:center;"><?=cash_type_r($val['cash_type'])?></td>
										<td style="text-align:center;"><?=cash_do_type_r($val['cash_do_type'])?></td>
										<td style="text-align:center;"><?=number_format($val['cash_num']+$val['discount_num'],2)?></td>
										<td style="text-align:center;"><?=$val['cash_balance']?></td>
										<td style="text-align:center;"><?=str_cut($val['remark'])?></td>
									</tr>
									<?php }
									}else{
										echo " <tr align=\"center\">
										<td colspan=\"6\">目錄沒有記錄</td>
										</tr>
										";
									}
									?>
									<tr align="center">
										<td colspan="3" class="">小计: 笔数：<?=$num?>,交易额度:<?=number_format(!empty($counts)?$counts:0,2);?></td>
										<td colspan="3" class="">总计: 笔数：<?=$count?>,交易额度:<?=$all_count?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>

	<style type="text/css">
	.calendar { background: url(../public/images/calendar.gif?=<?=$STATICS_FILE_VERSION?>) right no-repeat; padding: 2px; cursor: pointer; border: 1px solid #CCCCCC; }
	</style>
	<script language="javascript">
		//下拉框选中
		$(document).ready(function(){
			$("#sort_way").val('<?=$_GET['deptype']?>');
			if('<?=$_GET['start_date']?>'){
				$("#start_date").val('<?=$_GET['start_date']?>');
			}
			if('<?=$_GET['end_date']?>'){
				$("#end_date").val('<?=$_GET['end_date']?>');
			}
		})
	</script>
	<script>
		//分页跳转
		window.onload=function(){
			document.getElementById("page").onchange=function(){
				document.getElementById('myFORM').submit()
			}
		}
	</script>
