<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
$u = M('k_user_catm',$db_config);
$map ="site_id = '".SITEID."'";

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
$start_date = strtotime($start_date.' 00:00:00')+$_GET['timearea']*3600;
$end_date = strtotime($end_date.' 23:59:59')+$_GET['timearea']*3600;
$start_date = date('Y-m-d H:i:s',$start_date);
$end_date = date('Y-m-d H:i:s',$end_date);

$map = $map." and updatetime >='".$start_date."' and updatetime <= '".$end_date."'";

// p($map);
if(!empty($_GET['otype'])){
	$typeArr = explode('-', $_GET['otype']);
	//判断第二个type是否存在
	if (!empty($typeArr[2])) {
	   $map.= " and (type = '".$typeArr[0]."' and catm_type in ('".$typeArr[1]."','".$typeArr[2]."','".$typeArr[3]."'))";
	   $_GET['otype'] = '1-1';
	}elseif (empty($typeArr[1])) {
	   $map.= " and type = '".$typeArr[0]."'";
	}else{
	   $map.= " and type = '".$typeArr[0]."' and catm_type='".$typeArr[1]."'";
	}

}
//账号检索
if($_GET['username']){
	$map.= " and username='".$_GET['username']."'";
}

$catm_count=$u->field('sum(catm_money) as catm_moneyc,sum(catm_give) as catm_givec,sum(atm_give) as atm_givec')->where($map)->find();

$count=$u->field('id')->where($map)->count();
//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录

$limit=$startCount.",".$perNumber;
//查询表信息
$data=$u->where($map)->limit($limit)->order('updatetime DESC')->select();
$page = $u->showPage($totalPage,$page);
// p($data);
// p($map);
// die();

//综合稽核修改
if ($_POST['complex_lx'] == 'complex' && !empty($_POST['complex'])) {
	$data_c = $data_a = array();
	$data_c['code_count'] = $_POST['complex'];
	$data_a['type_code_all'] = $_POST['complex'];
    $kUser = M("k_user_audit", $db_config);
	$kUser->begin();
	try{  
	   $log_1 = $kUser ->where("source_id = '".$_POST['level_id']."' and source_type = '1'")->update($data_a);
	   $log_2 = $kUser->setTable("k_user_catm")->where("id = '".$_POST['level_id']."'")->update($data_c);
	   if ($log_1 && $log_2) {
	   	  $kUser->commit(); //事务提交
		  $do_log = '人工存款记录:'.$_POST['level_id'].' 综合稽核修改成功';
		  admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	
	      message("修改综合稽核成功！");
	   }else{
	   	  $kUser->rollback();
		  $do_log = '人工存款记录:'.$_POST['level_id'].' 综合稽核修改失败';
		  admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	
	      message("修改综合稽核失败！错误代码A002");
	   }
	}catch(Exception $e){
        $kUser->rollback(); //数据回滚
		message("修改综合稽核失败！错误代码A001");
	}
}


if ($_POST['complex_lx'] == 'normality' && !empty($_POST['level_id'])) {
	$data_c = $data_a = array();
	$data_c['routine_check'] = $_POST['complex'];
	$data_a['normalcy_code'] = $_POST['complex'];
    $kUser = M("k_user_audit", $db_config);
	$kUser->begin();
	try{  
	   $log_1 = $kUser ->where("source_id = '".$_POST['level_id']."' and source_type = '1'")->update($data_a);
	   $log_2 = $kUser->setTable("k_user_catm")->where("id = '".$_POST['level_id']."'")->update($data_c);
	   if ($log_1 && $log_2) {
	   	  $kUser->commit(); //事务提交
		  $do_log = '人工存款记录:'.$_POST['level_id'].' 常态稽核修改';
		  admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	
	      message("修改常态稽核成功！");
	   }else{
	   	  $kUser->rollback();
		  $do_log = '人工存款记录:'.$_POST['level_id'].' 常态稽核修改失败';
		  admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	
	      message("修改常态稽核失败！错误代码A003");
	   }
	}catch(Exception $e){
        $kUser->rollback(); //数据回滚
		message("修改常态稽核失败！错误代码A004");
	}
}


?>
<?php require("../common_html/header.php");?>
<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<script>

function show_config(id,complex,lx){
		//var content = $('#add_form').html();
		$("#level_id").val(id);
		$("#complex").val(complex);
		$("#complex_lx").val(lx);
		easyDialog.open({
			  container : 'currency_box'
			});
}


$(function(){
	$("#button_e").click(function() {
		window.location.href="catm.php";
	});
})

</script>
<div  id="con_wrap">
  <div  class="input_002">存取款记录查询</div>
  <div  class="con_menu">
  	<form  method="get"  name="action_form" id="myFORM">
			<input  type="button"  value="存款與取款"   class="button_e" id="button_e">
	时区:
	<select name="timearea" id="area">
  	<option value="0" <?=select_check(0,$_GET['timearea'])?>>美东</option>
  	<option value="12" <?=select_check(12,$_GET['timearea'])?>>北京</option>
  	</select>
  	日期：
	<input type="text" name="start_date" value="<?=$s_date?>" id="start_date" style="min-width:90px;width:90px" m class="za_text Wdate"  onClick="WdatePicker()">
	--
	<input type="text" name="end_date" value="<?=$e_date?>" id="end_date" style="min-width:90px;width:90px" class="za_text Wdate"  onClick="WdatePicker()">
			帳號： 
			<input  class="za_text"  style="width:80px;min-width:80px"  name="username"  value="<?=$_GET['username']?>">
            操作類型：
            <select  name="otype"  id="otype" onchange="document.getElementById('myFORM').submit()">
           		<option  value="">全部</option>
                <option  value="1-1" <?=select_check('1-1',$_GET['otype'])?>>人工存入</option>
                <option  value="1-2" <?=select_check('1-2',$_GET['otype'])?>>存款优惠</option>
                <option  value="1-3" <?=select_check('1-3',$_GET['otype'])?>>负数额度归零</option>
                <option  value="1-4" <?=select_check('1-4',$_GET['otype'])?>>取消出款</option>
                <option  value="1-5" <?=select_check('1-5',$_GET['otype'])?>>其他</option>
                <option  value="1-6" <?=select_check('1-6',$_GET['otype'])?>>体育投注余额</option>
                <option  value="1-7" <?=select_check('1-7',$_GET['otype'])?>>返点优惠</option>
                <option  value="1-8" <?=select_check('1-8',$_GET['otype'])?>>活动优惠</option>
                <option  value="2-1" <?=select_check('2-1',$_GET['otype'])?>>重复出款</option>
                <option  value="2-2" <?=select_check('2-2',$_GET['otype'])?>>公司入款存误</option>
                <option  value="2-3" <?=select_check('2-3',$_GET['otype'])?>>公司负数回冲</option>
                <option  value="2-4" <?=select_check('2-4',$_GET['otype'])?>>手动申请出款</option>
                <option  value="2-5" <?=select_check('2-5',$_GET['otype'])?>>扣除非法下注派彩</option>
                <option  value="2-6" <?=select_check('2-6',$_GET['otype'])?>>放弃存款优惠</option>
                <option  value="2-7" <?=select_check('2-7',$_GET['otype'])?>>其他</option>
                <option  value="2-8" <?=select_check('2-8',$_GET['otype'])?>>体育投注余额</option>
            </select>
			<input  class="button_d"  value="查詢"  type="submit">
			每页记录数：
	<select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
		<option value="20" <?=select_check(20,$perNumber)?>>20条</option>
		<option value="30" <?=select_check(30,$perNumber)?>>30条</option>
		<option value="50" <?=select_check(50,$perNumber)?>>50条</option>
		<option value="100" <?=select_check(100,$perNumber)?>>100条</option>
	</select>
	<?=$page?>
			</form>
	</div>
</div>
<div id="easyDialogBox" style="margin: -54.5px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;">
	<div id="currency_box" style="display: block; margin: 0px;" class="con_menu">
<form action="" method="post" name="add_form">
	<input name="level_id" id="level_id" value="" type="hidden">
    <input name="complex_lx" id="complex_lx" value="" type="hidden">
	<table class="m_tab" style="width:300px;margin:0;">
		<tbody><tr class="m_title">
			<td colspan="2" height="27" class="table_bg" align="left">
			<span id="title">修改打码量</span>
			<span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
			</td>
		</tr>
		<tr class="m_title">
			<td>打码量</td>
			<td><input name="complex" id="complex" value=""></td>
		</tr>
		
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="提交" class="button_a">
				<input type="reset" value="关闭" onclick="easyDialog.close();" class="button_a">
			</td>
		</tr>
	</tbody></table>
</form>
</div>
</div>
<table  width="99%"  class="m_tab">
		<tbody><tr  class="m_title">
			<td  class="table_bg">序號</td>
			<td  class="table_bg">會員</td>
			<td  class="table_bg">操作類型</td>
			<td  class="table_bg">交易金額</td>
            <td  class="table_bg">存款優惠</td>
            <td  class="table_bg">匯款优惠</td>
            <td  class="table_bg">餘額</td>
            <td  class="table_bg">綜合打碼量稽核</td>
            <td  class="table_bg">常態性稽核</td>
			<td  class="table_bg">交易日期</td>
			<td  class="table_bg">備注</td>
			<td  class="table_bg">操作人</td>
		</tr>
		<?php 
		//小计
		$catm_money;//交易金額
		$catm_give;//存款優惠
		$atm_give;//匯款优惠
		if(!empty($data)){
		foreach ($data as $k => $v) {		
		$catm_money+=$v['catm_money'];
		$catm_give+=$v['catm_give'];
		$atm_give+=$v['atm_give'];
		 ?>
		<tr  class="m_cen">
	    	<td><?=($k+1)?></td>
			<td><?=$v['username'] ?></td>
			<td>
				<?php 
				if($v['type']==1 && $v['catm_type']==1 ){
					echo "人工存入";
				}else if($v['type']==1 && $v['catm_type']==2 ){
					echo "存款優惠";
				}else if($v['type']==1 && $v['catm_type']==3 ){
					echo "负数额度归零";
				}else if($v['type']==1 && $v['catm_type']==4 ){
					echo "取消出款";
				}else if($v['type']==1 && $v['catm_type']==5 ){
					echo "其他";
				}else if($v['type']==1 && $v['catm_type']==6 ){
					echo "体育投注余额";
				}else if($v['type']==1 && $v['catm_type']==7 ){
					echo "返点优惠";
				}else if($v['type']==1 && $v['catm_type']==8 ){
					echo "活动优惠";
				}else if($v['type']==2 && $v['catm_type']==1 ){
					echo "重复出款";
				}else if($v['type']==2 && $v['catm_type']==2 ){
					echo "公司入款存误";
				}else if($v['type']==2 && $v['catm_type']==3 ){
					echo "公司负数回冲";
				}else if($v['type']==2 && $v['catm_type']==4 ){
					echo "手动申请出款";
				}else if($v['type']==2 && $v['catm_type']==5 ){
					echo "扣除非法下注派彩";
				}else if($v['type']==2 && $v['catm_type']==6 ){
					echo "放弃存款优惠";
				}else if($v['type']==2 && $v['catm_type']==7 ){
					echo "其他";
				}else if($v['type']==2 && $v['catm_type']==8 ){
					echo "体育投注余额";
				}

				 ?>

			</td>
	        <td  align="right"><?=$v['catm_money'] ?></td>
            <td  align="right"><?=$v['catm_give'] ?></td>
            <td  align="right"><?=$v['atm_give'] ?></td>
            <td><?=$v['balance']?></td>
            <td>
            <?php
               if (empty($v['is_code_count'])) {
                 echo "否";
               }else{
               	 echo "<a href=\"#\" onclick=\"show_config(".$v['id'].",".$v['code_count'].",'complex')\">是(打碼量：".$v['code_count'].")</a>";
               }?>
            </td>
            <td><?php 
            if(!empty($v['routine_check'])){
            	 echo "<a href=\"#\" onclick=\"show_config(".$v['id'].",".$v['routine_check'].",'normality')\">是(打碼量：".$v['routine_check'].")</a>";
            }else{
            	echo "否";
            }

             ?></td>
	        <td><?=$v['updatetime'] ?></td>
	        <td><?=$v['remark'] ?></td>
			<td><?=$v['do_admin_id'] ?></td> 			
		</tr>
		<?php }} ?>
	    <tr  class="m_cen">
			<td  colspan="3"  style="text-align:right;">小计:</td>
			<td  align="right"><?=$catm_money+0?></td>
            <td  align="right"><?=$catm_give+0?></td> 
            <td  align="right"><?=$atm_give+0?></td> 
            <td  colspan="6"></td>
		</tr>
		<tr  class="m_cen">
			<td  colspan="3"  style="text-align:right;">总计:</td>
			<td  align="right"><?=$catm_count['catm_moneyc']+0?></td>
            <td  align="right"><?=$catm_count['catm_givec']+0?></td> 
            <td  align="right"><?=$catm_count['atm_givec']+0?></td> 
            <td  colspan="6"></td>
		</tr>
		
</tbody></table>
<?php require("../common_html/footer.php"); ?>