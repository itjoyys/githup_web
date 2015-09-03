<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/userCash.class.php");


$u = M('k_user',$db_config);
$lev = M('k_user_level',$db_config);
$cash = M('k_user_cash_record',$db_config);
$lev_data=$lev->field("id,level_des")->where("site_id = '".SITEID."'")->select();

if(!empty($_GET['type'])){
	$map="is_delete = 0 and site_id = '".SITEID."' and level_id = '".$_GET['type']."' and shiwan = 0";
}else{
	$map="is_delete = 0 and site_id = '".SITEID."' and shiwan = 0";
}

//多会员查询
$fuser = trim($_GET['username']);
if(!empty($fuser) && $_GET['findUser'] == '1'){
	if(!empty($_GET['username'])){
		$uids;//uid字符串in查询用: 1,2,3
		$usernames=$_GET['username'];
		$usernames=explode(",", $usernames);

		if(!empty($usernames)){
			foreach ($usernames as $k => $v) {
				$datas = $u->field("uid")->where("is_delete = 0 and site_id = '".SITEID."' and shiwan = '0' and username = '".$v."'")->find();
				if(!empty($datas)){
				    $uids.=",".$datas['uid'];
				}
			}
			
		}	
		if(!empty($uids)){
			$uids=trim($uids,",");	
			$map.=" and uid in (".$uids.")";
		}else{
			message("未查询到结果！");
		}	
		
	}
}


   //分页
   $count=$u->field("uid")->where($map)->count();
   $perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
    $totalPage=ceil($count/$perNumber); //计算出总页数
 	$page=isset($_GET['page'])?$_GET['page']:1;
 	if ($page > $totalPage) {
 		$page = 1;//如果当前页超过总页,跳到1
 	}
    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
	$limit=$startCount.",".$perNumber;
    $data = $u->field("*")->where($map)->limit($limit)->order("uid desc")->select();
    $page = $u->showPage($totalPage,$page);

//会员修改
    if($_GET['action'] == 'edit'){
    	//p($_GET);exit;
    	foreach ($_GET['id'] as $k => $v) {
    		$data2 = array();
    		if (empty($_GET['is_locked'][$v])) {
    			$_GET['is_locked'][$v] = 0;
    		}

    		//判断锁定是否改动
    		if ($_GET['is_locked'][$v] != $_GET['l_locked'][$v]) {
    			$data2['is_locked'] = $_GET['is_locked'][$v];
    		}
    		//判断层级是否改动
    		if ($_GET['new_level'][$v] != $_GET['l_level']) {
    			$data2['level_id'] = $_GET['new_level'][$v];
    		}

    		if (!empty($data2)) {
    			$do_str .= $v.',';
    			$edit= $u->where("uid = '".$v."'")->update($data2);
    		}	
    	}
    	   $do_log = $_SESSION['login_name'].'对'.$do_str.'进行了层级编辑操作'; 
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
    	   message("修改成功！","./account_level.php");    	
    }

 ?>
<?php $title="层级会员"; require("../common_html/header.php");?>
<script>
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			var page=this.value;
		window.location.href="level_member.php?page="+page;
		}
		 //分页跳转
		document.getElementById("page").onchange=function(){
     	 document.getElementById('myFORM').submit()
  		}
	}

	function delevel(id){
	$.get("level_set.php?id="+id, function(json){
		$('#context').html(json);
			easyDialog.open({
				  container : 'delevel'
				});
		});
}
</script>
<body>
<div id="con_wrap">
  	<div class="input_002">会员查询</div>
  	<div class="con_menu">
  	
  	 <form method="get" name="action_form" action="" id="myFORM"> 
    <input type="hidden" name="ordercol" value="">
    <input type="hidden" name="type" value="<?=$_GET['type']?>">
    <input type="hidden" name="chaxun" value="15">
    <input type="hidden" name="username" value="<?=$_GET['username'] ?>">
  	<input type="button" value="会员查询" onclick="document.location='./member_search.php'" class="button_a"> 
  			<input type="button" value="返回" onclick="document.location='./account_level.php'" class="button_d">
  			<input type="hidden" name="level" value="271">			
			每页记录数：
	<select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
		<option value="20" <?=select_check(20,$perNumber)?>>20条</option>
		<option value="30" <?=select_check(30,$perNumber)?>>30条</option>
		<option value="50" <?=select_check(50,$perNumber)?>>50条</option>
		<option value="100" <?=select_check(100,$perNumber)?>>100条</option>
	</select>
	&nbsp;<?=$page?>&nbsp;
	</div>
	</div>
	</form>
	
<form method="get" action="" id="form1" name="form1">
<input type="hidden" name="action" value="edit">

<table width="99%" class="m_tab">
		<tbody><tr class="m_title">
		  <td class="table_bg">會員編號</td>
		  <td class="table_bg">會員帳號</td>
		  <td class="table_bg">加入時間</td>
		  <td class="table_bg">最后登录</td>
		  <td class="table_bg">存款次數</td>
		  <td class="table_bg">存款總額</td>
		  <td class="table_bg">最大存款額度</td>
		  <td class="table_bg">提款次數</td>
		  <td class="table_bg">提款總額</td>
		  <td class="table_bg">分層</td>
		  <td class="table_bg">鎖定<input type="checkbox" name="checkall" id="checkall" onclick="ckall()" /></td>
  </tr>
		<?php 
		if(!empty($data)){

		foreach ($data as $k => $v) {
		
		 ?>
		 <input type="hidden" name="id[]" value="<?=$v['uid'] ?>">
		 <input type="hidden" name="l_locked[<?=$v['uid']?>]" value="<?=$v['is_locked'] ?>">
		 <input type="hidden" name="l_level" value="<?=$v['level_id']?>">
		    	<tr>
	    	<td><?=$v['uid'] ?></td>
			<td><?=$v['username'] ?></td>
			<td><?=$v['reg_date'] ?></td>
			<td><?=$v['login_time'] ?></td>
			<?php 
			//获取会员的存款次数 金额
			$userBankin = userCash::userCashAll($v['uid']);
			 ?>
			<td><?=$userBankin['num']?></td>
	        <td><?=$userBankin['money'];?></td>
	        <td><?=$userBankin['max_money'];?></td>
			<?php 
			//出款
			$userBankout = userCash::userOutAll($v['uid']);
			 ?>
	        <td><?=$userBankout['num'];?></td> 
	        <td><?=$userBankout['money'];?></td>
	        <td>
	        <select name="new_level[<?=$v['uid']?>]">	  
			<?php 
			if(!empty($lev_data)){
			foreach ($lev_data as $kk => $vv) {
			?>
			<option value="<?=$vv['id']; ?>" <?php 
			 select_check($vv['id'],$v['level_id']);?>><?=$vv['level_des'] ?></option>
			<?php }}?>				
			</select>
	        </td>
				
	        <td>
	        <input value="1" type="checkbox" id="lock" name="is_locked[<?=$v['uid']?>]" <?php check_box($v['is_locked'],1);?> >
				</td>
    	</tr>
		<?php } }?>
				<tr align="center">
			<td class="table_bg1" colspan="13">
			<input value="确定" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
		<!-- <input type="reset" value="重置" class="button_d"> &nbsp;&nbsp;&nbsp; -->
		<input type="button" value="返回" onclick="document.location='level_member.php'" class="button_d"></td>
			
		</tr>
</tbody></table>
</form>
<script>
function ckall(){
    for (var i=0;i<document.form1.elements.length;i++){
	    var e = document.form1.elements[i];
		if (e.name != 'checkall') e.checked = document.form1.checkall.checked;
	}
}

</script>

<?php require("../common_html/footer.php");?>