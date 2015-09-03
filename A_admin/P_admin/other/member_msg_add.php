<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

/**
   *
   * msgtype  1表示普通通知 2优惠通知 3出入款通知
   *
**/
$map['site_id']=SITEID;
$user_level=M('k_user_level',$db_config)->where($map)->select();

if(!empty($_POST['msg_title'])||!empty($_POST['msg_info'])){
	// p($_POST);
	$data=array();
	if($_POST['wtype']==1){
		$data['msg_from']='系统消息';
		$data['msg_title']=$_POST['msg_title'];
		$data['msg_info']=$_POST['msg_info'];
		$data['site_id']=SITEID;
		$data['type']=1;
		$data['level']=0;
		$data['level_id']='-1';
	}elseif($_POST['wtype']==2){
		$data['msg_from']='系统消息';
		$data['msg_title']=$_POST['msg_title'];
		$data['msg_info']=$_POST['msg_info'];
		$data['site_id']=SITEID;
		$data['type']=1;
		$data['level']=1;
		$level_str='';
		if(!empty($_POST['level'])){
			foreach ($_POST['level'] as $k => $v) {
				$level_str = $level_str.','.$v;	
			}
		}

		$level_str=trim($level_str,",");
		$data['level_id']=$level_str;
	
	}elseif($_POST['wtype']==3){
		$user=explode(',',$_POST['user']);
		$uid='';
		foreach($user as $v){
			$mab['username']=$v;
			$mab['site_id']=SITEID;
			$users=M('k_user',$db_config)->where($mab)->select();//print_r($users);exit;
			if($users){
				if($uid==''){
					$uid=$users[0]['uid'];
				}else{
					$uid.=','.$users[0]['uid'];
				}
			}
		}
		if($uid!=''){
			$data['msg_from']='系统消息';
			$data['msg_title']=$_POST['msg_title'];
			$data['msg_info']=$_POST['msg_info'];
			$data['site_id']=SITEID;
			$data['uid']=$uid;
			$data['type']=1;
			$data['level']=2;
		}
	}
	if(!empty($data)){
		if(M('k_user_msg',$db_config)->add($data)){
			message('发布成功！','member_msg.php');
		}
	}else{
		message('没有该会员账号！','member_msg_add.php');
	}

	//print_r($_POST);exit;
}
// print_r($user_level);exit;
// if($_GET['id']){
	// if(M('k_agent_ad',$db_config)->where("id = '".$_GET['id']."'")->delete($_POST)){
		// message('删除成功','agent_ad.php');
	// }
// }
//print_r($agent_ad);exit;
?>
<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">發佈消息</div>
  <div class="con_menu"><input type="button" value="返回上一頁" onclick="javascript:history.go(-1);" class="button_d"></div>
</div>


<form method="post" name="withdrawal_form" action="member_msg_add.php" id="vform">
<input type="hidden" name="id" value=""> 
<table class="m_tab" style="width:100%;">
	<tbody><tr class="m_title">
		<td height="25" align="center" colspan="2">發送消息</td>
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1">體系選擇</td>
          <td>
          	<select id="wtype" name="wtype" class="za_select" onchange="wtypechange();">
				<option value="1">全部会员</option>
				<option value="2">层级会员</option>
				<option value="3">会员帐号</option>
			</select>
			&nbsp;&nbsp;
		<!-- 	<span id="levels" style="display:none">
			<select id="level" name="level" class="za_select">
				<?foreach($user_level as $v){?>
					<option value="<?=$v['id']?>"><?=$v['level_name']?></option>
				
				<?}?>
			</select>
			</span> -->
			<span id="levels" style="display:none">
			<?php foreach($user_level as $v){ ?>
				<input type="checkbox" name="level[]"  id="level" value="<?=$v['id']?>"><?=$v['level_des']?>&nbsp;
			<?php } ?>
			</span>
          </td>
	</tr>
	<tr id="members_tr" style="display:none">
		<td height="25" align="center" class="table_bg1" width="30%">会员帐号</td>
          <td>
          	<div style="float:left">
          	<textarea class="za_text" name="user" id="user" style="width:300px;height:80px;"></textarea>
          	多個人請用,(英文逗號)區分,留空则等同于选择所有会员</div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1" width="30%">标题</td>
          <td>
          	<div style="float:left"><input class="za_text" datatype="*2-200" nullmsg="請輸入标题！" errormsg="标题必須至少2個字元長，最多200個字元長" name="msg_title" id="title" value=""></div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1">内容</td>
		<td>
			<div style="float:left"><textarea datatype="*2-20000" nullmsg="請輸入備註！" errormsg="備註必須至少2個字元長，最多20000個字元長" class="za_text" name="msg_info" id="content" style="width:500px;height:150px;"></textarea></div>
			<div class="Validform_checktip" style="float:left"></div>
		</td>
	</tr>
	
	<tr align="center">
		<td colspan="2" class="table_bg1">
			<input value="確定" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
			<input type="reset" value="重置" class="button_d"> &nbsp;&nbsp;&nbsp;	
		</td>
	</tr>
</tbody></table> 
</form>

<script language="JavaScript" type="text/JavaScript">
var wtype = '';
$('#wtype').val(wtype);

function wtypechange()
{
	var type = $('#wtype').val();
	if(type==2){
		$('#members_tr').hide();
		$('#levels').show();
	}else if(type==3){
		$('#members_tr').show();
		$('#levels').hide();
	}else{
		$('#members_tr').hide();
		$('#levels').hide();
	}
}

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>


<script>
	$(function(){
		$("#vform").submit(function(event) {
			if($("#content").val() == ''){
				alert("内容不能为空！");
				return false;

			}
			if($("#title").val() == ''){
				alert("标题不能为空！");
				return false;
				
			}

		});
	})
</script>
<script src="./member_msg_add_files/yhinput.js" type="text/javascript"></script><!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>