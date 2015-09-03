<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");


//读取代理商
$agentList = M('k_user_agent',$db_config)
           ->field("id,agent_name,agent_user,intr")
           ->where("agent_type = 'a_t' and is_delete = '0' and is_demo = '0' and site_id = '".SITEID."'")->select();

//添加
if(!empty($_POST['agent'])){
		//$pinfo=pathinfo($destination);
		//$_POST['ad_img']=$pinfo['basename'];
		$data['web_site']=$_POST['web_site'];
		$data['ad_url']=$_POST['ad_url'];
		$data['agent_id'] = $_POST['agent'];
		$data['connection_url']=$_POST['connection_url'];
	   
	    //空添加
        if (empty($_POST['id'])) {
        	 $data['site_id'] = SITEID;
        	 $data['creator']=$_SESSION['login_name'];
        	 $data['addtime']=date('Y-m-d H:i:s');
        	 if(M('k_agent_ad',$db_config)->add($data)){
	    	   $do_log = '添加代理广告:'.$data['web_site'];
               admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 	
	    	   message('添加成功','agent_ad.php');
			}else{
			   message('添加失败');
			}
        }else{
        	//编辑
        	if(M('k_agent_ad',$db_config)->where("id = '".$_POST['id']."'")->update($data)){
	        	$do_log = '编辑代理广告:'.$data['web_site'];
                admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		        message('修改成功','agent_ad.php');
			}else{
				message('修改失败');
			}
        }
	
}

//点击编辑读取
if(!empty($_GET['id']) && $_GET['type'] == 'e1'){
	$map_e['id']=$_GET['id'];
	$map_e['site_id'] = SITEID;
	$ad=M('k_agent_ad',$db_config)->where($map_e)->find();
}


?>
<?php require("../common_html/header.php");?>
<script>
$(function(){
$("#myform").submit(function(){

	var web_site=$("#web_site").val();
	if(web_site==''){
		$("#web_sites").html("请输入網站名稱！");
		return false;
	}else{
		$("#web_sites").html("");
	}

	var ad_url=$("#ad_url").val();
	if(ad_url==''){
		$("#ad_urls").html("请输入投放網址！");
		return false;
	// }else{
	// 	var reg=/^[0-9a-z][a-z0-9]*\.[a-z]{2,3}$/;
	// 	var result=reg.test(ad_url);
	// 	if(!result){
	// 		$("#ad_urls").html("投放網址格式错误！");
	// 		return false;
	// 	}else{
	// 		$("#ad_urls").html("");
	// 	}
	 }
	

	var connection_url=$("#connection_url").val();
	if(connection_url==''){
		$("#connection_urls").html("请输入連接網址！");
		return false;
	}else{
		$("#connection_urls").html("");
	}
	
	// var addtime=$("#addtime").val();
	// if(addtime==''){
	// 	$("#addtimes").html("请选择加入时间！");
	// 	return false;
	// }else{
	// 	$("#addtimes").html("");
	// }
	});

});
</script>
<div  id="con_wrap">
<div  class="input_002">代理廣告</div>
<div  class="con_menu">
	<a  href="agent_ad.php"  style="color:red">代理廣告</a>
    <a  href="agent_ad_statistics.php">廣告統計</a>
    &nbsp;&nbsp;<a  href="agent_ad.php" >返回到列表</a>
</div>
</div>
<div  class="content"  style="width:500px;">
	<form  name="addForm"  action="agent_ad_add.php"  method="post"  id="myform"  class="vform" enctype="multipart/form-data" >
	<input type="hidden" name="id" value="<?=$ad['id']?>">
	<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab"  style="border:0px;">
		<tbody><tr  class="m_title_over_co">
			<td  colspan="2">新增廣告</td>
		</tr>
		<tr>
		    <td class="table_bg1"  align="right">选择代理</td>
			<td>
				     <select  name="agent" id="utype"  class="za_select">
				     <option  value="">请选择代理商</option>
				     <?php foreach ($agentList as $key => $val): ?>
				     	<option  value="<?=$val['id']?>" <?=select_check($val['id'],$ad['agent_id'])?>><?=$val['agent_user']?>(<?=$val['agent_name']?>)</option>
				     <?php endforeach ?>
		            </select>
			</td>
		</tr>
		<tr>
			<td  class="table_bg1"  align="right">網站名稱：</td>
			<td>
				<div  style="float:left"><input  type="text" id="web_site"  class="za_text"  name="web_site"  value="<?=$ad['web_site']?>"></div>
				<div  class="Validform_checktip" id="web_sites" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td  class="table_bg1"  align="right">投放網址：</td>
			<td>
				<div  style="float:left"><input  type="text"  class="za_text"  name="ad_url" id="ad_url"  style="width:250px;"  value="<?=$ad['ad_url']?>"></div>
				<div  class="Validform_checktip" id="ad_urls"  style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td  class="table_bg1"  align="right">連接網址：</td>
			<td>
				<div  style="float:left"><input  type="text"  class="za_text"  name="connection_url" id="connection_url" style="width:250px;"  value="<?=$ad['connection_url'] ?>"></div>
				<div  class="Validform_checktip" id="connection_urls"  style="float:left"></div>
			</td>
		</tr>						
<!-- 		<tr>
			<td  class="table_bg1"  align="right">加入日期：</td>
			<td>
				<div  style="float:left"><input  type="text" onClick="WdatePicker()"  class="za_text Wdate"  style="width:170px;"  name="addtime" id="addtime" value="<?=$ad['addtime']?>"   readonly="readonly"></div>
				<div  class="Validform_checktip" id="addtimes" style="float:left"></div>
			</td>
		</tr> -->
		<tr>
			<td  colspan="2"  align="center">
				<input  type="submit"   name="subbtn"   class="button_a"  value=" 確 定 ">&nbsp;&nbsp;&nbsp;&nbsp;
				<input  type="reset"  name="cls"  value="重 置 "  class="button_a">
			</td>
		</tr>			
	</tbody></table>
	</form>
</div>


<?php require("../common_html/footer.php");?>

