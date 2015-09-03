<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//读取银行设定
$bank_all = M('k_bank',$db_config)->where("site_id = '".SITEID."' and is_delete = '0'")->select();

//读取第三方平台设定
$payment_all = M('pay_set',$db_config)->where("site_id = '".SITEID."' and is_delete = '0'")->select();

//初始化
$cash_f = M('k_cash_config',$db_config)->where("type=1 and site_id = '".SITEID."'")->select();

//公司自定义设置
$cash_z = M('k_cash_config',$db_config)->where("site_id = '".SITEID."' and is_delete = 0 and type_name not in ('RMB','HKD','USD','MYR','SGD','THB','GBP','JPY','EUR','IDR')")->select();

//本站点 全部种类
$cash_all = M('k_cash_config',$db_config)
          ->where("site_id = '".SITEID."' and is_delete = 0")
          ->select();

//添加新设定
 if(!empty($_POST['action']) && $_POST['action']=='add'){

 	if(empty($_POST['name'])){
 		 message("名称不能为空！","./pay_detail_set.php");
		 if( $_POST['name']==$cash_all['type_name']){
		 	 message("该名称已经存在！","pay_detail_set.php");
		 }
  	}else{
 		$data['name']=$_POST['name'];
 		//如无选择则默认复制A001设定
 		if(empty($_POST['cp_id'])){
			$data['cp_id']=$cash_f['0']['id'];
		 }else{
		 	$data['cp_id']=$_POST['cp_id'];
		 }
		 //查询原始设置值
		 $cash_config = M('k_cash_config',$db_config)->where("id= '".$data['cp_id']."'")->select();
		 $cash_config['0']['type_name']=$data['name'];
		 $cash_config['0']['type']= 0;
		 $cash_config['0']['id']="";//初始化覆盖值为空


		 $cash_config_ol = M('k_cash_config_ol',$db_config)->where("cash_id= '".$data['cp_id']."'")->select();
		  $cash_config_ol['0']['id']="";


		 $cash_config_line = M('k_cash_config_line',$db_config)->where("cash_id= '".$data['cp_id']."'")->select();
		  $cash_config_line['0']['id']="";

		//添加

		 $cashid = M('k_cash_config',$db_config)->add($cash_config['0']);
		 $cash_config_ol['0']['cash_id']= $cashid;
		 $cash_config_line['0']['cash_id']= $cashid;

		 $log_1 = M('k_cash_config_ol',$db_config)->add($cash_config_ol['0']);
		 $log_2 = M('k_cash_config_line',$db_config)->add($cash_config_line['0']);
		 if ($log_1 && $log_2 && $cashid) {
		 	 $do_log="添加支付参数设定:".$cashid ;
			 admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
			 message("添加成功");
		 }else{
		 	  $do_log="添加支付参数设定失败" ;
			 admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
			 message("添加失败");
		 }
        
  	}
	
 }

 //支付平台选择表单处理
 if($_POST['action'] == 'add_pay' & !empty($_POST['cash_id'])){
    if (empty($_POST['payment']) || empty($_POST['bank'])) {
    	message('请选择支付平台信息');
    }else{
 	if(empty($_POST['check_bet1'])){

    	$data['bank_set'] = trim($_POST['check_bet'],',');
 	}else{
 		$data['bank_set'] = trim($_POST['check_bet1'],',').','.trim($_POST['check_bet'],',');
 	}

 	// echo $data['bank_set'];
    	$data['bank_set'] = explode(',',$data['bank_set']);

    	$data['bank_set']= array_flip(array_flip($data['bank_set']));
    	// p($data['bank_set']);
   	
    	$data['bank_set'] = implode(',',$data['bank_set']);//银行
    	// echo $data['bank_set'];
    	$data['payment_set'] = implode(',', $_POST['payment']);//支付平台
        $state = M('k_cash_config',$db_config)->where("id = '".$_POST['cash_id']."'")->update($data);
        if ($state) {
        	$do_log="设置支付平台成功";
		    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
        	message('设置支付平台成功');
        }
    }
 }

?>
<?php $title="在線支付設定"; require("../common_html/header.php");?>
<body>
<script>

$(function(){
	$("#add_form").submit(function(event) {
		$("#cate").val("");
	});

	$('#add').click(function(){
		var content = $('#add_form').html();
	    $("#imgBox").css('display','block');
	});
	$(".close_b").click(function(){
		$("#imgBox").css('display','none');
	})
	$("#close_door").click(function(){
	    $("#imgBox").css('display','none');
	});

	//下拉菜单选择银行分类
	$("#cate").change(function(event) {
		var kong = "";
		$(".bank_set_1").each(function(index, el) {
			
			if($(this).attr('checked')==undefined){
				kong += ','+$(this).val();
			}
		});

		if (kong.substr(0,1)==',') kong=kong.substr(1);
		if(kong.length>1){
			kong=kong.split(",");
		}else{
			kong = [kong];
		}
		// alert(kong);
		// console.log(kong);
		var old_val = $("#check_bet1").val();
	
		if(old_val){

			$("#check_bet1").val($("#check_bet").val()+','+old_val);
		}else{
			$("#check_bet1").val($("#check_bet").val());
		}
		

		var check_bet1_arr = $("#check_bet1").val();
		if (check_bet1_arr.substr(0,1)==',') check_bet1_arr=check_bet1_arr.substr(1);
		// alert(check_bet1_arr);
		check_bet1_arr = check_bet1_arr.split(",");
		// console.log(check_bet1_arr);
		var arr_all=new Array();

		var ii=0;
		var i = 0;
		for(ii;ii<check_bet1_arr.length;ii++){
			
			var key=0;
			var jj = 0
			for (jj; jj < kong.length; jj++) {
				
				if(check_bet1_arr[ii] == kong[jj]){
					key++;
				}
			}
		
			 if(key==0){
				
				arr_all[i]=check_bet1_arr[ii];
				i++;
			 }
		}
		// console.log(arr_all);
		arr_all = arr_all.join(",");
		$("#check_bet1").val(arr_all);
		// alert($("#check_bet1").val());

		var cate_id=$("#cate").val();
	
		var bank_set = $("#bank_set").text();
		// alert(bank_set);
		$.ajax({  
		type: "post",  
		url: "./pay_detail_set_ajax.php",  
		dataType: "json",  
		data: {id:cate_id,action:'bank',bank_set:arr_all},  
		success: function(msg){  
		
			var html1='';
			for (var i = 0; i < msg.length; i++){
			
			html1 += '<input class="bank_set_1" value="'+msg[i].id+'" type="checkbox" id="bank_'+msg[i].id+'" name="bank[]" '+msg[i].val+'>'+msg[i].bank_type+msg[i].card_ID+'</br>';

			}
	
			$(".bank_checkbox").html(html1);

			//确认选中状态
		
			// $(".bank_checkbox input").each(function() {

			// 	if($("#check_bet1").val() !=''){
			// 	var str = $("#check_bet1").val();
			// 	var old = $(this).val();

			// 		if(str.indexOf(old) > 0 ){
			// 			// alert("yes");
			// 			$(this).attr('checked', 'checked');
						
			// 		}else{
			// 			// alert("no");
			// 			$(this).attr('checked',false);
			// 		}
			// 	}
			// });

		}


              
	});  
		
		
	});

	$(".bank_checkbox").click(function() {
		var check_bet='';
		$(".bank_checkbox input").each(function() {
			
			if($(this).attr('checked')){
				
				check_bet += ','+$(this).val();
			}
		});
		

		$("#check_bet").val(check_bet);
		
	});

    $(".close_x").click(function(){
		$("#payBox").css('display','none');
		$("#cate").val("");
		$("#check_bet").val("");
		$("#check_bet1").val("");
	})
});
function checkform(){
	if(!$.trim($('#name').val())){
		alert('请输入名称');
		return false;
	}
	return true;
}

</script>

<style>
	#imgBox,#payBox{position:absolute; top:35%; left:50%;margin:-100px 0 0 -200px;}
</style>
<div  id="con_wrap">
  <div  class="input_002">在線支付設定</div>
  <div  class="con_menu"><a href="javascript:history.go(-1);">返回上一頁</a></div>
</div>

<div  class="content">

<div  style="float:left;width:50%;">
	<table  width="99%"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td  height="27"  class="table_bg"  colspan="3"  align="center">各类别预设设定(注：新建公司时将以以下设定值为该公司生成设定)</td>
		</tr>
		<tr  class="m_title_over_co">
		    <td width="150">币别</td>
			<td width="150">代码</td>
			<td width="150">进行设定</td>
		</tr>
		<?php if (!empty($cash_f)) {
			foreach ($cash_f as $key => $val) {
        ?>
		<tr>
		    <td  align="middle"><?=rmb_type($val['type_name'])?></td>
			<td  align="middle"><?=$val['type_name']?></td>
			<td><a href="cash_config.php?cash_id=<?=$val['id']?>&type=set">详细設定</a></td>
		</tr>
    <?php
	}
	}
	?>																							
	</tbody></table>	
</div>
<div  style="float:left;width:50%;">
<table  width="99%"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td  height="27"  class="table_bg"  colspan="12"  align="center">公司自訂設定<span  style="float:right;">[<a id="add"  href="javascript:void(0);" class="run">新增</a>]</span></td>
		</tr>
		<tr  class="m_title_over_co">
			<td>编号</td>
			<td>名称</td>
			<td>进行设定</td>
		</tr>

		<?php  
		if (!empty($cash_z)) {
		     foreach ($cash_z as $key => $val) {?>
		<tr>
			<td  align="middle"><?=$val['id']?></td>
			<td  align="middle"><?=$val['type_name']?></td>
			<td> 
			<a  class="button_b" href="cash_config.php?cash_id=<?=$val['id']?>&type=set">设定</a>&nbsp;&nbsp;
				<a class="button_b" onclick="return confirm('确定删除');" href="cash_config.php?cash_id=<?=$val['id']?>&type=del">删除</a>
			</td>
		</tr>
		<?php }}?>
							
	</tbody></table>	
</div>

</div>
<!-- 公司新增设定弹窗 -->
<div  id="imgBox"  style="display:none"  class="con_menu">
<form  action=""  method="post"  name="add_form">
	<input  name="action"  value="add"  type="hidden">
	<table  class="m_tab"  style="width:300px">
		<tbody><tr  class="m_title_over_co">
			<td  colspan="2"  height="27"  class="table_bg"  align="left">
			新增设定
			<span  style="float:right;"><a  style="color:white;"  href="javascript:void(0)"  title="关闭窗口" id="close_door">×</a></span>
			</td>
		</tr>
		<tr>
			<td>名称</td>
			<td><input  type="text"  name="name"  id="name"></td>
		</tr>
		<tr>
			<td>複製資料</td>
			<td>如无选择则默认复制A001设定</td>
		</tr>
		<tr>
			<td>选择预设</td>
			<td>
			<select  name="cp_id">
			<option  value="0">-</option>			
		    <?php if (!empty($cash_all)) {
		    	foreach ($cash_all as $key => $val) {
            ?>
		    <option  value="<?=$val['id']?>"><?=rmb_type($val['type_name'])?>(<?=$val['type_name']?>)</option>
		    <?php
		       		    	}
		    }
		    ?>
			</select></td>
		</tr>
		<tr>
			<td  colspan="2"  align="center">
				<input  type="submit"  value="提交" >
				<button type="reset" class="close_b">关闭</button>
			</td>
		</tr>
	</tbody></table>
</form>
</div>

<!-- 公司支付平台设定 -->
<div  id="payBox"  style="display:none"  class="con_menu">
<form  action=""  method="post"  name="add_form"  id="add_form">
	<input  name="action"  value="add_pay"  type="hidden">
	<input name="cash_id" id="cashid" type="hidden" value="">
	<input name="check_bet" id="check_bet" type="hidden" value="">
	<input name="check_bet1" id="check_bet1" type="hidden" value="">
	<table  class="m_tab"  style="width:300px">
		<tbody><tr  class="m_title_over_co">
			<td  colspan="2"  height="27"  class="table_bg"  align="left">
			支付平台设定
			<span  style="float:right;"><a  style="color:000;"  href="javascript:void(0)"  title="关闭窗口" id="close_door" class="close_x">X</a></span>
			</td>
		</tr>
		<tr>
			<td width="20%;">选择银行</td>
			<td>
			<div>
			<?php 
			$bank_arr =M('k_bank',$db_config)->field("distinct cate")->where("is_delete = '0' and site_id = '".SITEID."'")->order("cate asc")->select();
			
			 ?>
				<select name="cate" id="cate" >
				<option value="" id="">选择银行</option>
				<?php 
				if(!empty($bank_arr)){
					foreach ($bank_arr as $k => $v) {				
				 ?>
					<option value="<?=$v['cate'] ?>"><?=$v['cate'] ?>类</option>
					<?php }} ?>
				</select>
			</div>
			<span class="bank_checkbox">
		
			</span><br>
			</td>
		</tr>
		<tr>
			<td>选择支付</td>
			<td>
			 <?php if (!empty($payment_all)) {
		    	foreach ($payment_all as $key => $val) {
            ?>
		    <input value="<?=$val['id']?>" id="payment_<?=$val['id']?>"type="checkbox" name="payment[]">
		     <?=payment_type($val['pay_type'])?><?=$val['pay_id']?>
		     <br>
		    <?php
		      }
		    }
		    ?>
			</td>
		</tr>
		<tr>
			<td  colspan="2"  align="center">
				<input  type="submit"  value="提交" class="button_b">
				<button type="reset" class="close_x button_b" >关闭</button>
			</td>
		</tr>
	</tbody></table>
</form>
</div><div id="bank_set" style="display:none;"></div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>