<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
/*
*(out_status):
*出款状态,0：待审核 1：已出款 2已拒绝 3已取消 4 正在出款
*
*
*/
$b = M('k_user_bank_out_record',$db_config);

// var_dump($_GET);
$lev=M('k_user_level',$db_config);
$map['site_id'] = SITEID;
$l=$lev->field("level_des,id")->where($map)->select();

//修改手续费
if($_POST['type']=='charge' && !empty($_POST['id'])){
	$Money=$b->field("charge,outward_money")->where("id = '".$_POST['id']."'")->find();
	if($_POST['charge']<0){
		message('手续费不能小于0！');
	}
	if($_POST['charge'] > ($Money['charge']+$Money['outward_money'])){
		message('手续费不能大于真正出款额度');
	}
	$data_cha = array();
	$data_cha['outward_money']=$Money['outward_money']-$_POST['charge']+$Money['charge'];
	$data_cha['charge']=$_POST['charge'];
	$state_cha=$b->where("id='".$_POST['id']."'")->update($data_cha);
	if($state_cha){
		$do_log="修改手续费成功,出款id:".$_POST['id'];
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
		message('手续费修改成功！');
	}else{
		$do_log="修改手续费失败,出款id:".$_POST['id'];
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
		message('手续费修改失败！');
	}
}

//修改行政费
if($_POST['type']=='expenese_num' && !empty($_POST['id'])){
	$Money=$b->field('outward_num,outward_money,favourable_out,favourable_num,expenese_num')->where("id = '".$_POST['id']."'")->find();
	if($_POST['expenese_num']<0){
		message('行政费不能小于0！');
	}

	if($_POST['expenese_num'] > ($Money['expenese_num']+$Money['outward_money'])){
		message('行政费不能大于真正出款额度');
	}

	//判断是否取消优惠
	if ($Money['favourable_out'] == '1' && $_POST['is_fav'] == 0) {
		$data_e['outward_money'] = $Money['outward_money'] + $Money['favourable_num'];
		$data_e['favourable_out'] = 0;
		$data_e['favourable_num'] = 0;
	}else{
		$data_e['outward_money'] = $Money['outward_money'];
	}

	$data_e['outward_money'] = $data_e['outward_money']-$_POST['expenese_num']+$Money['expenese_num'];
	$data_e['expenese_num']=$_POST['expenese_num'];
	$state_ex=$b->where("id='".$_POST['id']."'")->update($data_e);
	if($state_ex){
		$do_log="修改行政费成功,出款id为".$_POST['id'];
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
		message('行政费修改成功！');
	}else{
		$do_log="修改行政费失败,出款id为".$_POST['id'];
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
		message('行政费修改失败！');
	}
}
$reload=intval(@$_REQUEST['reload']);
//修改状态确认
if(!empty($_GET['id']) && $_GET['oc'] == 'od'){//确定出款

	//提交状态参数
	$out_data=$b->field('out_status,uid,order_num,charge,favourable_num,expenese_num,outward_money,balance,out_balance,outward_num,audit_id,username,out_time,admin_user')->where("id = '".$_GET['id']."'")->find();
    if ($out_data['out_status'] == '1') {
    	message("该笔出款已经确定成功！");
    }elseif($out_data['out_status'] == '2'){
    	message("该笔出款已经拒绝成功");
    }else{
	$agent_id = M('k_user',$db_config)
		        ->where("uid = '".$out_data['uid']."'")
		        ->getField('agent_id');
    if (!empty($out_data['admin_user']) && $out_data['admin_user'] != $_SESSION['login_name']) {
    	message('请勿点击别人的预备出款!!!');
    	exit();
    }

    //出款 拒绝 取消类型判断
    switch ($_GET['btn']) {
    	case 'sc':
    		//出款
    		$out_status = 1;
    		break;
    	case 's2':
    		//拒绝出款
    		$out_status = 2;
    		break;
    }

	//事务开始 现金记录 更新状态
	$now_date = date('Y-m-d H:i:s');
    $kUser = M("k_user_cash_record", $db_config);
    $kUser->begin();
    try {
       	//会员当前余额
	   $unow_m = M('k_user',$db_config)
		        ->where("uid = '".$out_data['uid']."'")
		        ->getField('money');
       if($_GET['btn'] == 's2'){
	   	   $data_c['remark'] = $out_data['order_num'].',拒绝出款';//备注
           $log_1 = $kUser->setTable('k_user_cash_record')
                  ->where("source_id = '".$_GET['id']."' and source_type = '4' and site_id = '".SITEID."'")->update($data_c);
	   }else{
	   	   $log_1 = 1;
	   }

       //出款状态更新
       $data_o = array();
       $data_o['out_status'] = $out_status;
       $data_o['do_time'] = $now_date;
       $data_o['admin_user'] = $_SESSION['login_name'];
       $log_2 = $kUser->setTable('k_user_bank_out_record')
              ->where("id = '".$_GET['id']."'")
              ->update($data_o);
       //稽核状态更新
       if(!empty($out_data['audit_id'])){
	       $data_a = array();
	       $data_a['type'] = 2;
	       $log_3 = $kUser->setTable('k_user_audit')
	              ->where("uid = '".$_GET['uid']."' and type=1 and id in(".$out_data['audit_id'].") ")
	              ->update($data_a);
	       //更新稽核最后一笔结束时间
	       $pos = strpos($out_data['audit_id'],',');
	       if(!empty($pos)){
              $maxId = substr($out_data['audit_id'],0,$pos);
	       }else{
	       	  $maxId = $out_data['audit_id'];
	       }
           $data_al = array();
           $data_al['end_date'] = $out_data['out_time'];
	       $log_o = $kUser->setTable('k_user_audit')
	              ->where("id = '".$maxId."'")
	              ->update($data_al);
	   }else{
	   	  $log_3 = 1;
	   	  $log_o = 1;
	   }
       //发送消息
       if ($_GET['btn'] == 'sc') {
       	   //只有成功出款才发消息
	       $dataM = array();
	       $dataM['type'] = 3;//表示出入款类型
		   $dataM['site_id'] = SITEID;
		   $dataM['uid'] = $out_data['uid'];
		   $dataM['level'] = 2;
		   $dataM['msg_title'] = "出款成功";
		   $dataM['msg_info'] = $out_data['order_num'].",出款金额:".$out_data['outward_num'].'扣除金额:'.($out_data['outward_num']-$out_data['outward_money']).", 祝您游戏愉快！";
	       $log_4 = $kUser->setTable('k_user_msg')
	              ->add($dataM);
	   }else{
	   	   $log_4 = 1;
	   }
       if ( $log_o && $log_1 && $log_2 && $log_3 && $log_4) {
       	   $kUser->commit();
		   $do_log = '线上取款,确定出款:'.$out_data['order_num'];
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
           if ($_GET['op'] == 'cop') {
		    	//ajax监控
		    	sleep(1);//延迟0.01秒
		    	echo json_encode(1);
		    	exit();
		    }else{
		    	sleep(1);//延迟0.01秒
		        message("成功！",'./out_record.php?reload='.$reload);
		    }
       }else{
       	   $kUser->rollback();
		   $do_log = '线上取款,出款失败:'.$out_data['order_num'];
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
           if ($_GET['op'] == 'cop') {
		    	//ajax监控
		    	echo json_encode(2);
		    	exit();
			}else{
			     message("失败！错误代码CK002",'./out_record.php?reload='.$reload);
		    }
       }
    } catch (Exception $e) {
    	$kUser->rollback();
    	$do_log = '线上取款,出款失败:'.$out_data['order_num'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
	    if ($_GET['op'] == 'cop') {
	    	//ajax监控
	    	echo json_encode(2);
	    	exit();
		}else{
		     message("失败！,错误代码CK003");
	    }
    }
  }
}elseif(!empty($_GET['id']) && $_GET['btn'] == 's1'){
    //预备出款
    $dataY= array();
    $dataY['out_status'] = 4;
    $dataY['do_time'] = date('Y-m-d H:i:s');
    $dataY['admin_user'] = $_SESSION["login_name"];

    $ostate = M('k_user_bank_out_record',$db_config)
            ->where("id = '".$_GET['id']."' ")
            ->update($dataY);
    if ($ostate) {
    	if ($_GET['op'] == 'cop') {
			//ajax监控
			echo json_encode(1);
			exit();
		}else{
			message("预备出款成功！",'./out_record.php?reload='.$reload);
		}
    }else{
    	if ($_GET['op'] == 'cop') {
			//ajax监控
			echo json_encode(1);
			exit();
		}else{
			$do_log = '预备出款失败！:'.$_GET['id'];
            admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
            message("预备出款失败！");
		}
    }

}elseif(!empty($_GET['id']) && $_GET['btn'] == 's3'){
	//取消出款 需要退还金额
		//会员当前余额
    $out_data=$b->field('out_status,uid,order_num,charge,favourable_num,expenese_num,outward_money,balance,out_balance,outward_num,audit_id,username,admin_user')->where("id = '".$_GET['id']."'")->find();
    if ($out_data['out_status'] == '3') {
    	message("取消出款成功",'./out_record.php?reload='.$reload);
    }elseif($out_data['out_status'] == '0' || $out_data['out_status'] == '4'){
	$agent_id = M('k_user',$db_config)
	          ->where("uid = '".$out_data['uid']."'")
	          ->getField('agent_id');
	//事务开始 现金记录 更新状态
	$now_date = date('Y-m-d H:i:s');
    $kUser = M("k_user", $db_config);
    $kUser->begin();
    try {

        //会员余额更新
       $data_u = array();
       $data_u['money'] = array('+',$out_data['outward_num']);
       $log_3 = $kUser->where("uid = '".$out_data['uid']."'")
              ->update($data_u);

       $unow_m = M('k_user',$db_config)
	        ->where("uid = '".$out_data['uid']."'")
	        ->getField('money');

       $data_c=array();
       $data_c['source_id'] = $_GET['id'];
       $data_c['site_id'] = SITEID;
       $data_c['uid'] = $out_data['uid'];
       $data_c['agent_id'] = $agent_id;
       $data_c['username'] = $out_data['username'];
       $data_c['cash_date'] = $now_date;
       $data_c['cash_type'] = 23;
       $data_c['cash_do_type'] = 1;
       $data_c['cash_balance'] = $unow_m;//当前余额
       $data_c['cash_num'] = $out_data['outward_num'];
       $data_c['remark'] = $out_data['order_num'].',操作者：'.$_SESSION['login_name'];//备注
       $log_1 = $kUser->setTable('k_user_cash_record')->add($data_c);

       //出款状态更新
       $data_o = array();
       $data_o['out_status'] = 3;
       $data_o['do_time'] = $now_date;
       $data_o['admin_user'] = $_SESSION['login_name'];
       $log_2 = $kUser->setTable('k_user_bank_out_record')
              ->where("id = '".$_GET['id']."'")
              ->update($data_o);
       if ($log_1 && $log_2 && $log_3) {
       	   $kUser->commit();
		   $do_log = '取消出款:'.$out_data['order_num'];
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
           if ($_GET['op'] == 'cop') {
		    	//ajax监控
		    	usleep(10000);//延迟0.01秒
		    	echo json_encode(1);
		    	exit();
		    }else{
		    	usleep(10000);//延迟0.01秒
		        message("取消出款成功！",'./out_record.php?reload='.$reload);
		    }
       }else{
       	   $kUser->rollback();
		   $do_log = '取消出款失败:'.$out_data['order_num'];
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
           if ($_GET['op'] == 'cop') {
		    	//ajax监控
		    	echo json_encode(2);
		    	exit();
			}else{
			     message("失败！错误代码CK002");
		    }
       }
    } catch (Exception $e) {
    	$kUser->rollback();
    	$do_log = '取消出款失败:'.$out_data['order_num'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
	    if ($_GET['op'] == 'cop') {
	    	//ajax监控
	    	echo json_encode(2);
	    	exit();
		}else{
		     message("失败！,错误代码CK003");
	    }
    }
   }
}

$map_out = "k_user_bank_out_record.site_id = '".SITEID."' ";

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
$map_out .= " and k_user_bank_out_record.out_time >= '".$start_date."' and k_user_bank_out_record.out_time <= '".$end_date."' ";

if(!empty($_GET['small'])){
	$map_out .=" and k_user_bank_out_record.outward_money > '".$_GET['small']."'";
}
if(!empty($_GET['big'])){
	$map_out .=" and k_user_bank_out_record.outward_money < '".$_GET['big']."'";
}

if (!empty($_GET['level_id'])) {
   $map_out .= " and k_user_bank_out_record.level_id = '".$_GET['level_id']."'";
}
if (!empty($_GET['out_status'])) {
	$map_out .= " and k_user_bank_out_record.out_status = '".$_GET['out_status']."'";
}
if (!empty($_GET['account'])) {
   $map_out .= " and k_user_bank_out_record.username = '".$_GET['account']."'";
}

$dat=$b->field("id")->where($map_out)->select();

//分页
   $perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
   $page=isset($_GET['page'])?$_GET['page']:1;
    $count=count($dat); //获得记录总数
    $totalPage=ceil($count/$perNumber); //计算出总页数


    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
    $limit=$startCount.",".$perNumber;

//查询表信息
$data=$b->join("left join k_user_level on k_user_level.id = k_user_bank_out_record.level_id")->field("k_user_bank_out_record.*,k_user_level.level_name,k_user_level.level_des")->where($map_out)->limit($limit)->order("out_time desc")->select();
//判断是否有新出款
$new_out_state = $b->field("id")
				   ->where("out_status = '0' and site_id = '".SITEID."'")
				   ->find();
if (!empty($new_out_state)) {
	$new_out_state = 1;
}else{
	$new_out_state = 0;
}
$page = $b->showPage($totalPage,$page);

 ?>
 <?php $title="出款管理"; require("../common_html/header.php");?>
 <style>
	.payBox td{color: #333;}
	.payBox_1 td{color: #333;}
	.m_title_a{text-align: center;}
 </style>
<body>
<script type="text/javascript">
var issound = <?=$new_out_state?>;
$(document).ready(function(){

	if(issound==1){
     	$('body').append('<embed src="../public/sound/chukuan.swf" autostart="true" width=0 height=0 loop="false">');

	}
});
function sure1(a){
	if(confirm("是否确认？")){
        reloadtime=$('#retime').val()

        if(reloadtime>0){
           location.href=a+'&reload='+reloadtime
        }else{
            location.href=a
        }

	}else{
		return false;
	}
}


function show_config(id,complex,yh){
		//var content = $('#add_form').html();
		$("#level_id").val(id);
		$("#complex").val(complex);
		$("#yh").val(yh);
		if(yh==0) $("#yh_panel").hide();
		easyDialog.open({
			  container : 'currency_box'
			});
}
function show_pre(id,complex){
	//var content = $('#add_form').html();
	$("#order_id").val(id);
	$("#pre_amount").val(complex);
	easyDialog.open({
		  container : 'pre_box'
		});
}

function show_currency(level_id,username){
	//var content = $('#add_form').html();

	$.getJSON("out_count.php?level_id="+level_id, function(json){
		$.each(json, function(i,rs){
			$("#"+i).html(rs);
		});
	});
	$("#username").html(username);
	easyDialog.open({
		  container : 'imgBox'
		});
}



var EventUtil = {
	    getEvent: function (event) {
	        return event ? window.event : event;
	    },
	    getTarget: function (event) {
	        return event.target || event.srcElement;
	    },
	    preventDefault: function (event) {
	        if (event.preventDefault) {
	            event.preventDefault();
	        } else {
	            event.returnValue = false;
	        }
	    },
	    stopPropagation: function (event) {
	        if (event.stopPropagation) {
	            event.stopPropagation();
	        } else {
	            event.cancelBubble = true;
	        }
	    }
	};


function show_div(event,divid,iden)
{
	var event = event ? event : window.event;
	var top = left = 0;
	var div = document.getElementById(divid);

	$("[id^=bankinfo]").hide();
	$("[id^=memoinfo]").hide();
	if(iden == 'bankinfo')
	{
		top = document.body.scrollTop + event.clientY - 10;
		left = document.body.scrollLeft + event.clientX + 30;
	}
	else if(iden == "memoinfo")
	{
		top = document.body.scrollTop + event.clientY + 15;
		left = document.body.scrollLeft + event.clientX - 250;
	}
	div.style.top = top + "px";
	div.style.left = left + "px";
	div.style.display = "block";
}

function setRefresh()
{
	$('#queryform').submit();
}
function send(aid, memo)
{
	document.location.href="out_record.php?iden=1&aid=" + aid + "&memo=" + memo+urlPar;
}

</script>
<script type="text/javascript">
function myFunc(){
    //code
    //执行某段代码后可选择移除disabled属性，让button可以再次被点击
    $("#subBtn").removeAttr("disabled");
}
$("#subBtn").click(function(){
    //让button无法再次点击
    $(this).attr("disabled","disabled");
    //执行其它代码，比如提交事件等
    myFunc();
});
</script>
<script>
//分页跳转
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			document.getElementById('queryform').submit()
		}
		document.getElementById("level_id").onchange=function(){
			document.getElementById('queryform').submit()
		}
		document.getElementById("area").onchange=function(){
			document.getElementById('queryform').submit()
		}
	}
	$('#page').val(<?=$_GET['page']?>);
</script>
<script>
	$(function(){
		function type_lei(str){
			str = eval(str);
			switch(str)
			{
			case 1:
			  return "額度轉換";
			  break;
			case 5:
			   return "入款";
			  break;
			case 6:
			   return "入款";
			  break;
			case 7:
			   return "入款";
			  break;
			case 9:
			   return "优惠退水";
			  break;
			case 10:
			   return "在线存款";
			  break;
			case 11:
			   return "公司入款";
			  break;
			case 12:
			   return "存入取出";
			  break;
			case 13:
			   return "优惠冲销";
			  break;


			}
		}
	//用户名弹窗
	$(".username_tan").click(function(){
		$(".payBox").css('display', 'block');
		//初始化数据
		$("#a_1").text('');
		$("#a_2_1").text('');
		$("#a_2_2").text('');
		$("#a_3_1").text('');
		$("#a_3_2").text('');
		$("#a_4_1").text('');
		$("#a_5_1").text('');
		$("#a_5_2").text('');
		$("#a_6_1").text('');
		$("#a_6_2").text('');
		$("#a_7_1").text('');
		$("#a_7_2").text('');
		var test_id = $(this).siblings("td[class='test_id']").text();
		var username_id = $(this).text();
		$.ajax({
			type: "POST",
			url: "./out_record_ajax.php",
			dataType:'json',
			data: {test_id:test_id,action:'user'},
			success: function(msg){
				if(msg.count_in == null){
					msg.count_in =0;
				}
				if(msg.count_out == null){
					msg.count_out =0;
				}
				$("#a_1").text(username_id);
				$("#a_2_1").text(msg.in_all_money);
				$("#a_2_2").text(msg.count_in+"笔");
				$("#a_3_1").text(msg.out_all_money);
				$("#a_3_2").text(msg.count_out+"笔");
				$("#a_4_1").text(msg.owen_money);
				$("#a_5_1").text(msg.data1.cash_num);
				$("#a_5_2").text(type_lei(msg.data1.cash_type));
				$("#a_6_1").text(msg.data2.cash_num);
				$("#a_6_2").text(type_lei(msg.data2.cash_type));
				$("#a_7_1").text(msg.data3.cash_num);
				$("#a_7_2").text(type_lei(msg.data3.cash_type));

			}
		});


	});
	$(".button_a_b").click(function() {
		$(".payBox").css('display', 'none');
		$(".payBox_1").css('display', 'none');
	});

	//出款金额弹窗
	$(".outward_money_show").click(function() {
		$(".payBox_1").css('display', 'block');
		$(".b_1_1").text('');
		$(".b_2_1").text('');
		$(".b_3_1").text('');
		$(".b_4_1").text('');
		$(".b_5_1").text('');
		$(".b_6_1").text('');
		$(".b_7_1").text('');
		$(".b_8_1").text('');
		var test_id_1 = $(this).siblings(".test_id").text();
		var username_id_1=$(this).siblings(".username_tan").find('a').text();

		$.ajax({
			type: "post",
			url: "./out_record_ajax.php",
			dataType: "json",
			data: {test_id:test_id_1,action:'bank'},
			success: function(msg){
				/*var reg_address = msg.reg_address;
				var city_arr1 = reg_address.split("市");
				var city_arr = city_arr1["0"].split("省");*/
				$(".b_1_1").text(username_id_1);
				$(".b_2_1").text(msg.pay_name);
				$(".b_3_1").text(msg.pay_card);
				$(".b_4_1").text(msg.pay_num);
				$(".b_5_1").text(msg.city[0]);
				$(".b_6_1").text(msg.city[1]);
				$(".b_7_1").text(msg.mobile);
				$(".b_8_1").text(msg.about);

			}
		});

	});

})
</script>
<div id="con_wrap">
<div class="input_002">出款管理</div>
<div class="con_menu" style="width:90%;height:70px;">
<form name="queryform" action="./out_record.php" method="get" id="queryform">
	<input type="hidden" name="uid" value="">
	&nbsp;
	重新整理:
 	<select name="reload" id="retime" onchange="setTimeout(setRefresh(), this.value*1000)">
		<option value="-1" <?=select_check(-1,$_GET['reload'])?>>不更新</option>
        <option value="30" <?=select_check(30,$_GET['reload'])?>>30秒</option>
		<option value="60" <?=select_check(60,$_GET['reload'])?>>60秒</option>
		<option value="120" <?=select_check(120,$_GET['reload'])?>>120秒</option>
        <option value="180" <?=select_check(180,$_GET['reload'])?>>180秒</option>
	</select>

  	层级:
  	<select name="level_id" style="width: 92px" id="level_id">
  			<option value="">全部</option>
  			<?php
  			foreach ($l as $key => $value) {
  				if($l[$key]['id'] == $_GET['level_id']){ ?>
  					<option value="<?=$l[$key]['id'] ?>" selected=selected ><?=$l[$key]['level_des'] ?></option>
				<?php }else{ ?>
  					<option value="<?=$l[$key]['id'] ?>"><?=$l[$key]['level_des'] ?></option>
				<?php } ?>



	       <?php } ?>
	</select>
		时区:
	<select name="timearea" id="area">
  	<option value="0" <?=select_check(0,$_GET['timearea'])?>>美东</option>
  	<option value="12" <?=select_check(12,$_GET['timearea'])?>>北京</option>
  	</select>
  		状态:
	<select name="out_status" id="area" onchange="document.getElementById('queryform').submit()">
	<option value="">全部</option>
  	<option value="4" <?=select_check(4,$_GET['out_status'])?>>處理中</option>
  	<option value="1" <?=select_check(1,$_GET['out_status'])?>>已確認</option>
  	<option value="3" <?=select_check(3,$_GET['out_status'])?>>已取消</option>
  	<option value="2" <?=select_check(2,$_GET['out_status'])?>>已拒绝</option>
  	</select>
	日期:
     <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$s_date?>"  style="width:110px;min-width:110px;" name="start_date">
      ~
	 <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$e_date?>"  style="width:110px;min-width:110px;" name="end_date">

金額：
	<input type="text" name="small" class="za_text" style="min-width:50px;width:50px" value="<?=$_GET['small'] ?>" size="5">~
	<input type="text" name="big" class="za_text" style="min-width:50px;width:50px" value="<?=$_GET['big'] ?>" size="5">&nbsp;&nbsp;
		<br>
	帳號:
	<input type="text" name="account" class="za_text" value="<?=$_GET['account'] ?>" style="min-width:80px!important;width:80px!important">&nbsp;
	<!-- 排序：
	<select id="sort" name="sort" onchange="document.queryform.submit();" class="za_select">
								<option value="insert_time" >出款时间</option>
								<option value="amount" >金额</option>
	</select>
	<select id="orderby" name="orderby" onchange="document.queryform.submit()" class="za_select">
								<option value="desc" >由大到小</option>
								<option value="asc" >由小到大</option>
							</select> -->
	<input type="submit" name="searchBtn" id="searchBtn" value="查詢" class="button_d">
	每页记录数：
	<select name="page_num" id="page_num" onchange="document.getElementById('queryform').submit()" class="za_select">
		<option value="20" <?=select_check(20,$perNumber)?>>20条</option>
		<option value="30" <?=select_check(30,$perNumber)?>>30条</option>
		<option value="50" <?=select_check(50,$perNumber)?>>50条</option>
		<option value="100" <?=select_check(100,$perNumber)?>>100条</option>
	</select>
	&nbsp;<?=$page?>&nbsp;
	 <input type="button" name="btn1" onclick="window.open('cash.php?type=out')" style="color:red" id="btn1" value="監控" class="button_d"> <font color="red">为了避免多客服给同一会员出款，在给会员出款前可以先点预备出款，可以先看到操作者来区别（<strong>可以不点</strong>）</font>
</form>
</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>站别</td>
			<td>層級</td>
			<td>代理商</td>
			<td>會員帳號</td>
            <td>出款狀況</td>
			<td>提出額度</td>
			<td>手續費</td>
			<td>優惠金額</td>
			<td>行政費</td>
			<td>出款金額</td>
            <td>账户余额</td>
			<td>優惠扣除</td>
			<td>出款日期</td>
			<td>查看稽核</td>
			<td>已出款</td>
			<td>操作者</td>
			<td>備註</td>
		</tr>
		<?php
		$num;$deposit_num;$favourable_num;$other_num;$deposit_money;
		$num=0;
		if (!empty($data)) {
		foreach ($data as $k => $v) {
			$num+=1;
			//各项总金额
		$deposit_num+=$v['charge'];
		$favourable_num+=$v['favourable_num'];
		$other_num+=$v['expenese_num'];
		$deposit_money+=$v['outward_money'];
		 ?>

		<tr class="m_cen">
			<td><?=$v['do_url']?></td>
			<td  class="test_id" style="display:none;"><?=$v['uid']?></td>
			<td><?=$v['level_des'] ?></td>
			<td><?=$v['agent_user'] ?></td>
			<td class="username_tan"><a href="###" class="uname"><?=$v['username'] ?></a></td>
			<td><?
			if($v['outward_style']==1){
				echo "首次出款";
			}
			 ?></td>
            <td><?=$v['outward_num'] ?></td>
			<td><a href="###" <?php if($v['out_status']==0) echo "onclick=\"show_config1(".$v['id'].",".$v['charge'].",'charge')\"";?>> <?=$v['charge'] ?></a></td>
			<td><?=$v['favourable_num'] ?></td>
			<td><a  href="###" <?php if($v['out_status']==0) echo "onclick=\"show_config2(".$v['id'].",".$v['expenese_num'].",'expenese_num',".$v['favourable_out'].")\"";?>><?=$v['expenese_num'] ?></a> </td>

			<td style="cursor:pointer" class="outward_money_show"><a href="###"><?=$v['outward_money'] ?></a></td>
			<td><?=$v['balance'] ?></td>
            <td><?
            if($v['favourable_out']==1){
            	echo "是";
            }else{
            	echo "否";
            }
            ?>
            </td>
            <td><?=$v['out_time'] ?>(美东)</td>
			<td><a href="./audit.php?uname=<?=$v['username']?>&id=<?=$v['id']?>&auditstr=<?=$v['audit_id']?>">查看稽核</a></td>
			<td>

			<?
            if($v['out_status']==4){
            	echo "<button name='' class=\"button_d\" >正在出款</button>";
            	echo "<a  style='color:#f00;' onClick=\"sure1('out_record.php?oc=od&btn=sc&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >确定</button></a>";
            	echo "<a   style='color:#f00;' onClick=\"sure1('out_record.php?btn=s3&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >取消</button></a>";
 echo "<a   style='color:#f00;' onClick=\"sure1('out_record.php?oc=od&btn=s2&uid=".$v['uid']."&id=".$v['id']."&un=".$v['username']."')\"><button name='' class=\"button_d\" >拒绝</button></a>";
            }else if($v['out_status']==0){
            	echo "<a   style='color:#f00;' onClick=\"sure1('out_record.php?btn=s1&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >预备出款</button></a>";
            	echo "<a  style='color:#f00;' onClick=\"sure1('out_record.php?oc=od&btn=sc&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >确定</button></a>";
            	echo "<a  style='color:#f00;' onClick=\"sure1('out_record.php?btn=s3&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >取消</button></a>";
            	echo "<a   style='color:#f00;' onClick=\"sure1('out_record.php?oc=od&btn=s2&uid=".$v['uid']."&id=".$v['id']."')\"><button name='' class=\"button_d\" >拒绝</button></a>";
            }elseif($v['out_status']==1) {
            	echo "已出款";
            }elseif($v['out_status']==2)  {
            	echo "已拒绝";
            }elseif($v['out_status']==3)  {
            	echo "取消";
            }
            ?>

			</td>
			<td><?=$v['admin_user'] ?></td>
			<td ><?=$v['remark'] ?></td>
		</tr>

			<?php }} ?>
		<tr align="center">
			<td colspan="17">总计:
			笔数：<font class="fontsty1"><?=$num ?></font>
			手续费：<font class="fontsty1"><?=$deposit_num ?></font>
			優惠金額：<font class="fontsty2"><?=$favourable_num ?></font>
			行政费：<font class="fontsty3"><?=$other_num ?></font>
			总出款金額：<font class="fontsty4"><?=$deposit_money ?></font>
			</td>
		</tr>
	<!-- 	 <tr align="center">
			<td colspan="17">
			<input name="tableTitel" type="hidden" value="<?=$start_date?><?=$end_date?>出款">
			<input type="submit" name="submit"  value="导出表格" class="button_d"></td>
		</tr> -->

	</tbody></table>
</div>
 <!-- 出款金额弹窗 -->
<div class="payBox_1" style="margin: -216px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top:50%; left: 50%; display: none;"><div id="currency_box" style="display: block; margin: 0px;" class="con_menu">

  <input name="level_id_1" id="level_id_1" value="" type="hidden">
  <table class="m_tab" style="width:250px;margin:0;">
    <tbody><tr class="m_title">
      <td colspan="2" height="27" class="table_bg" align="left">
      <span id="title">會員 <span class="b_1_1"></span> 的銀行帳戶資料</span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" class="button_a_b">×</a></span>
      </td>
    </tr>
    <tr class="m_title_a">
      <td>會員姓名</td>
      <td width="90" class="b_2_1">
</td>
    </tr>
      <tr>

      <td height="0" style="text-align:center;">銀行名稱</td>
      <td id="RMB_1" style="text-align:center;" class="b_3_1"></td>

    </tr>
  		<tr class="m_title_a">
  			<td width="70">銀行帳號</td>
  			<td  class="b_4_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td width="70">省份</td>
  			<td  class="b_5_1"></td>
  		</tr>

  		<tr class="m_title_a">
  			<td>城市</td>
  			<td  class="b_6_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td>聯繫電話</td>
  			<td  class="b_7_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td>備注</td>
  			<td  class="b_8_1"></td>

  		</tr>

        <tr>
      <td colspan="3" align="center">
        <input type="button" value="关闭" class="button_a close_x button_a_b">
      </td>
    </tr>
  </tbody></table>

</div></div>


 <!-- 用户名弹窗 -->
<div class="payBox" style="margin: -216px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;"><div id="currency_box" style="display: block; margin: 0px;" class="con_menu">

  <input name="level_id_1" id="level_id_1" value="" type="hidden">
  <table class="m_tab" style="width:250px;margin:0;">
    <tbody><tr class="m_title">
      <td colspan="3" height="27" class="table_bg" align="left">
      <span id="a_1"></span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" class="button_a_b">×</a></span>
      </td>
    </tr>
    <tr class="m_title_a">
      <td>入款总额</td>
      <td width="90" id="a_2_1"></td>
      <td width="90" id="a_2_2"></td>
    </tr>

      <tr>

      <td height="0" style="text-align:center;">出款总额</td>
      <td  id="a_3_1" style="text-align:center;"></td>
      <td style="text-align:center;" id="a_3_2"></td>
    </tr>
  		<tr class="m_title_a">
  			<td width="70">盈利情况</td>
  			<td colspan="2" id="a_4_1"></td>

  		</tr>
  		<tr class="m_title">
  			<td colspan="3">最近3笔入款金额</td>
  		</tr>

  		<tr class="m_title_a">
  			<td>入款一</td>
  			<td id="a_5_1"></td>
  			<td id="a_5_2"></td>
  		</tr>
  		<tr class="m_title_a">
  			<td>入款二</td>
  			<td id="a_6_1"></td>
  			<td id="a_6_2"></td>
  		</tr>
  		<tr class="m_title_a">
  			<td>入款三</td>
  			<td id="a_7_1"></td>
  			<td id="a_7_2"></td>
  		</tr>

        <tr>
      <td colspan="3" align="center">
        <input type="button" value="关闭" class="button_a close_x button_a_b">
      </td>
    </tr>
  </tbody></table>

</div></div>




<script type="text/javascript">
//var retime = "" + -1;
var retime = $('#retime').val();
$(document).ready(function()
{
	var time = (retime == 0 || retime == -1) ? -1 : "" + retime;
	if(time != -1)
	{
		setTimeout("setRefresh()", time * 1000);
	}
})




//修改手续费
function show_config1(id,charge,lx){
		//var content = $('#add_form').html();
		$("#id").val(id);
		$("#charge").val(charge);
		$("#complex_lx").val(lx);
		easyDialog.open({
			  container : 'currency_box1'
			});
}


//修改行政费
function show_config2(id,expenese_num,lx,is_fav){
		//var content = $('#add_form').html();
		$("#expenese_id").val(id);
		$("#expenese_num").val(expenese_num);
		$("#expenese_lx").val(lx);
		if (is_fav > 0) {
           $("#favnum").val(is_fav);
		}else{
           $("#favnum").hide();
		}

		easyDialog.open({
			  container : 'currency_box2'
			});
}

</script>

<!--修改手续费-->
<div id="easyDialogBox" style="margin: -54.5px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;">
	<div id="currency_box1" style="display: block; margin: 0px;" class="con_menu">
<form action="" method="post" name="add_form">
	<input name="id" id="id" value="" type="hidden">
    <input name="type" id="complex_lx" value="" type="hidden">
	<table class="m_tab" style="width:300px;margin:0;">
		<tbody><tr class="m_title">
			<td colspan="2" height="27" class="table_bg" align="left">
			<span id="title">修改手续费</span>
			<span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
			</td>
		</tr>
		<tr class="m_title">
			<td>手续费</td>
			<td><input name="charge" id="charge" value=""></td>
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
<!--修改手续费-->


<!--修改行政费-->
<div id="easyDialogBox" style="margin: -54.5px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;">
	<div id="currency_box2" style="display: block; margin: 0px;" class="con_menu">
<form action="" method="post" name="add_form">
	<input name="id" id="expenese_id" value="" type="hidden">
    <input name="type" id="expenese_lx" value="" type="hidden">
	<table class="m_tab" style="width:300px;margin:0;">
		<tbody><tr class="m_title">
			<td colspan="2" height="27" class="table_bg" align="left">
			<span id="title">修改行政费</span>
			<span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
			</td>
		</tr>
		<tr class="m_title">
			<td>行政费</td>
			<td><input name="expenese_num" id="expenese_num" value=""></td>
		</tr>
		<tr class="m_title" id="favnum">
			<td>优惠金额</td>
			<td style="padding-left: 25px;">
				<select style="float: left;" id="isfav" name="is_fav">
					<option value="1">是</option>
					<option value="0">否</option>
				</select>
			</td>
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
<!--修改行政费-->





<div id="sound"></div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>