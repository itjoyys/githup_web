<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
//批量存款
if (!empty($_POST['username'])) {
	//判断存款优惠前的checkbox 是否被选中
	if(empty($_POST['ifSp'])){
		unset($_POST['sp_other']);
	}
	//判断匯款優惠前的checkbox 是否被选中
	if(empty($_POST['ifSp_other'])){
		unset($_POST['spAmount']);
	}
	//判断綜合打碼量稽核前的checkbox 是否被选中
	if(empty($_POST['isComplex'])){
		unset($_POST['ComplexValue']);
	}

    $catmArray = array('人工存入','存款優惠','负数额度归零','取消出款','其他','体育投注余额','返点优惠','活动优惠');
	$username = $_POST['username'];
	$usernames = "'".str_replace(",","','",$username)."'";//字符串元素加单引号
	$usernames = strtr($usernames, array(' '=>''));//清除空格
	$uids = M('k_user',$db_config)->field('username,uid,level_id,agent_id')->where("username in ($usernames) and site_id = '".SITEID."' and shiwan = '0' and is_delete = '0'")->select();

	if(!empty($_POST['amount'])){
		 $amount = $_POST['amount'];//存款金额
	}else{
		 $amount = 0;
	}
    $deposit_money = $_POST['amount'];//存款总金额
	     //存款优惠
    if($_POST['ifSp'] =="1"){
		$deposit_money += $_POST['sp_other'];	
		$give_count = $_POST['sp_other'];
		$data['catm_give'] = $_POST['sp_other'];//存款优惠		
	}
    //汇款优惠
	if ($_POST['ifSp_other'] == '1') {
		$deposit_money += floatval($_POST['spAmount']);	
		$data['atm_give'] = $_POST['spAmount'];//汇款优惠
		$give_count +=  $_POST['spAmount'];
	}

	//综合打码量
	if ($_POST['isComplex'] == '1') {
		$is_code_count = $_POST['isComplex'];
		$code_count = $_POST['ComplexValue'];//综合打码量
	}else{
		$is_code_count = 0;
		$code_count = 0;
	}
    //常态打码
	if (!empty($_POST['isnormality'])) {
	    $isnormality = $_POST['isnormality']*$_POST['amount'];
	    $is_isnormality = 1;
	}else{
		$is_isnormality = 0;
		$isnormality = 0;
	}

    $catmT = $_POST['type_memo']-1;
	$remark = $catmArray[$catmT].','.$_POST['c_remark'].'操作者：'.$_SESSION['login_name'];//备注

  //统一时间
     $now_date = date('Y-m-d H:i:s');
     $kUser = M("k_user", $db_config);
     $kUser->begin();
   $log_state = 0;
   $log_user = '';
   foreach ($uids as $key => $val) {
     try{
    /**
      * 事务开始
     */ 
      $data_u = array();
      $data_u['money'] = array('+',$deposit_money);
      $log_1 = $kUser->setTable('k_user')->where("uid = '".$val['uid']."'")
             ->update($data_u);//更新会员余额
      //获取当前余额
      $unowmoney = M('k_user',$db_config)
                 ->where("uid = '".$val['uid']."'")
                 ->getField('money');
      if(empty($unowmoney)){
    	//未获取到或者之前余额为负数回滚
   	    $kUser->rollback();
    	message("入款失败,错误代码CP001");
      }
    
      //写入人工记录
      $data_ca = array();
      $data_ca['uid'] = $val['uid'];
      $data_ca['agent_id'] = $val['agent_id'];//代理商id
      $data_ca['site_id'] = SITEID;
      $data_ca['username'] = $val['username'];
      $data_ca['type'] = 1;//存款
      $data_ca['balance'] = $unowmoney;
      $data_ca['catm_money'] = $amount;//存款金额
      $data_ca['catm_give'] = $_POST['sp_other'];//存款优惠
      $data_ca['atm_give'] = $_POST['spAmount'];//汇款优惠
      $data_ca['is_code_count'] = $is_code_count;//是否有综合打码
      $data_ca['code_count'] = $code_count;//综合打码
      $data_ca['routine_check'] = $isnormality;//常态打码
      $data_ca['catm_type'] = $_POST['type_memo'];//存款项目
      $data_ca['updatetime'] = $now_date;
      $data_ca['is_rebate'] = $_POST['c_is_rebate'];//是否退佣
      $data_ca['remark'] = $remark;//备注
      $data_ca['do_admin_id']=$_SESSION["login_name"];//操作人
      $log_2 = $kUser->setTable('k_user_catm')->add($data_ca);

        //写入现金系统
        $for_what = 12;
        $dtype = 1;
          //人工存入现金记录那边对应的是人工存入 还有取消出款
        if ($_POST['type_memo'] == 1 or $_POST['type_memo'] == 4) {
            $dtype = 3;
        }elseif($_POST['type_memo'] == 7){
            $for_what = 9;//表示现金系统的优惠退水
        }
       $data_c=array();
       $data_c['source_id'] = $log_2;
       $data_c['source_type'] = 3;
       $data_c['site_id'] = SITEID;
       $data_c['uid'] = $val['uid'];
       $data_c['username'] = $val['username'];
       $data_c['cash_date'] = $now_date;
       $data_c['cash_type'] = $for_what;
       $data_c['cash_do_type'] = $dtype;
       $data_c['cash_balance'] = $unowmoney;//当前余额
       $data_c['cash_num'] = $amount;//存款金额
       $data_c['discount_num'] = $_POST['sp_other']+$_POST['spAmount'];//优惠总额
       $data_c['remark'] = $remark;//备注
       $log_4 = $kUser->setTable("k_user_cash_record")
	          ->add($data_c);
      
      //稽核写入
      //更新上一个稽核的终止时间
       $l_audit = M('k_user_audit',$db_config)
              ->field("max(id) as maxid")
              ->where("uid = '".$val['uid']."' and type = '1' and site_id = '".SITEID."'")
              ->find();
       if ($l_audit['maxid']) {
       	  //存在更新终止时间
       	  $data_l = array();
       	  $data_l['end_date'] = $now_date;
       	  $log_5 = $kUser->setTable("k_user_audit")
       	         ->where("id = '".$l_audit['maxid']."'")
       	         ->update($data_l);
       	  if(empty($log_5)){
        	//失败回滚
       	    $kUser->rollback();
        	message("入款失败,错误代码CP002");
          }
       }
      $data_a = array();
      $data_a['site_id'] = SITEID;
      $data_a['uid'] = $val['uid'];
      $data_a['username'] = $val['username'];
      $data_a['begin_date'] = $now_date;
      $data_a['deposit_money'] = $amount;//存款金额
      $data_a['atm_give'] = $_POST['spAmount'];
      $data_a['catm_give'] = $_POST['sp_other'];
      $data_a['source_id'] = $log_2;
      $data_a['source_type'] = 1;//表示存入与取出
      $data_a['type'] = 1;
      $data_a['is_ct'] = $is_isnormality;//是否常态稽核
      $data_a['normalcy_code'] = $isnormality;//常态稽核
      $data_a['is_zh'] = $is_code_count;//是否综合稽核
      $data_a['type_code_all'] = $code_count;//综合稽核量
      $data_a['relax_limit'] = 10;
      $data_a['expenese_num'] = 50;
      $log_6 = $kUser->setTable("k_user_audit")
   	         ->add($data_a);

   	  //事务提交
   	  if($log_1 && $log_2 && $log_4 && $log_6){
   	  	  $log_state++;
   	  	  $log_user .= $val['username'].',';
   	  }else{
   	  	 $kUser->rollback(); //数据回滚
   	  	 message("加款失败,错误代码CP003");
   	  }
    }catch(Exception $e){
		$mysqlt->rollback(); //数据回滚
	    message("加款失败,错误代码CP004");
	} 
  }
  $cclog;
  $cclog = count($uids);
  if($log_state == $cclog){
  	 $kUser->commit(); //事务提交
	 $do_log = '会员'.$log_user.':批量人工存款操作';
     admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
     message("加款成功");
  }else{
  	$kUser->rollback(); //数据回滚
	message("加款失败,错误代码CP005");
  }

}

?>
<?php require("../common_html/header.php");?>
<title>批量存款</title>
</head>
<body>
<div id="con_wrap">
  <div class="input_002">批量存款</div>
  <div class="con_menu">
    <form method="post" name="search_form">
      <input  type="button" value="历史查詢" onclick="document.location='catm_record.php'"  class="button_e">&nbsp;
      <input type="button" value="單筆存款" onclick="location.href='catm.php'" class="button_e">
    </form>
  </div>
</div>
<div class="content">
  <form method="post" name="withdrawal_form">
    <table class="m_tab" style="width:520px">
      <tbody><tr class="m_title">
        <td colspan="2" height="25" align="center">批量存款</td>
      </tr>
      <tr>
        <td height="25" align="center" style="width:150px" class="table_bg1 textTr">帳號</td>
        <td><textarea name="username" id="username" style="height: 80px; width:350px" class="za_text"></textarea><br>
        <font color="red">多個會員，請用(英文) , 號區分 如：test1,test2</font></td>
      </tr>
 <tr id="ck_tr">
        <td height="18" align="center" class="table_bg1 textTr" id="sp_name">存款金額</td>
        <td colspan="3"><input maxlength="20" value="0" size="21" class="za_text" name="amount" id="amount"></td>
      </tr>
      <tr id="sp_tr">
        <td height="18" align="center" class="table_bg1 textTr">存款優惠</td>
        <td colspan="3"><input name="ifSp" id="ifSp" value="1" type="checkbox">
          存入，優惠金額：
          <input type="input" class="za_text" style="min-width: 80px !important; width: 50px !important" value="0" id="spAmount" name="sp_other"></td>
      </tr>
      <tr id="hk_tr">
        <td height="18" align="center" class="table_bg1 textTr">匯款優惠</td>
        <td colspan="3"><input name="ifSp_other" id="ifSp_other" value="1" type="checkbox">
          存入，優惠金額：
          <input type="input" class="za_text" style="min-width: 80px !important; width: 50px !important" value="0" id="sp_other" name="spAmount"></td>
      </tr>
      <tr id="sp_tr2">
        <td height="18" align="center" class="table_bg1 textTr">綜合打碼量稽核</td>
        <td colspan="3">
        <input name="isComplex" id="isComplex" value="1" type="checkbox">
          稽核，打碼量：
          <input type="text" class="za_text" style="min-width: 80px !important; width: 50px !important" value="0" id="ComplexValue" name="ComplexValue"></td>
      </tr>
      <tr id="sp_tr3">
        <td height="18" align="center" class="table_bg1 textTr">常態性稽核</td>
        <td colspan="3"><input name="isnormality" id="isnormality" value="1" type="checkbox">
          稽核</td>
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr"  id="type_name">存款项目</td>
        <td colspan="3">
           <select  name="type_memo" id="type_memo">
     
           </select>
        </td>
      </tr>
      <tr  id="sp_tr4">
        <td  height="18"  align="center"  class="table_bg1 textTr">是否退拥</td>
        <td colspan="3"><input  name="c_is_rebate"  type="radio"  value="1"  checked=""  class="op_type">
          写入
          <input  name="c_is_rebate"  type="radio"  value="0"  class="op_type">
          取消</td>
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">備注</td>
        <td colspan="3"><textarea  name="c_remark"  style="height: 50px; width:350px"  class="za_text"></textarea></td>
      </tr>
      <tr  align="center">
        <td  colspan="4"  class="table_bg1">
        <input  value="確定"  id="savebtn"  name="savebtn"  type="submit"  class="button_a">
          &nbsp;&nbsp;
         <input  type="reset"  value="重置"  class="button_a"></td>
      </tr>
    </tbody></table>
  </form>
</div>
<script  language="JavaScript"  type="text/JavaScript">
var spLimit = "<?=$discount['line_other_discount_num']?>";//优惠标准
var spRate = "<?=$discount['line_other_discount_per']?>";//存款优惠百分比
var spMax = "<?=$discount['line_discount_max']?>";//存款优惠上限
var Audit_COMPLEX = "<?=$discount['line_zh_audit']?>";//打码量倍数
var AbsorbLimit = '';//汇款优惠标准
var AbsorbRate = '';//汇款百分比
var AbsorbMax = '';//汇款优惠百分比
var depositType = {"1":"\u4eba\u5de5\u5b58\u5165","2":"\u5b58\u6b3e\u4f18\u60e0","3":"\u8d1f\u6570\u989d\u5ea6\u5f52\u96f6","4":"\u53d6\u6d88\u51fa\u6b3e","5":"\u5176\u4ed6","6":"\u4f53\u80b2\u6295\u6ce8\u4f59\u989d","7":"\u8fd4\u70b9\u4f18\u60e0","8":"\u6d3b\u52a8\u4f18\u60e0"};
var withdrawalType = {"1":"\u91cd\u590d\u51fa\u6b3e","2":"\u516c\u53f8\u5165\u6b3e\u5b58\u8bef","3":"\u516c\u53f8\u8d1f\u6570\u56de\u51b2","4":"\u624b\u52a8\u7533\u8bf7\u51fa\u6b3e","5":"\u6263\u9664\u975e\u6cd5\u4e0b\u6ce8\u6d3e\u5f69","6":"\u653e\u5f03\u5b58\u6b3e\u4f18\u60e0","7":"\u5176\u4ed6","8":"\u4f53\u80b2\u6295\u6ce8\u4f59\u989d"};


function showsTypeMemo(type_id){
	$('#type_memo').empty();  
	var memo_list = '';
	if(type_id==1){
		memo_list = depositType;
	}else{
		memo_list = withdrawalType;
	}
	
	$.each(memo_list,function(i, n){
		$("#type_memo").append("<option value='"+i+"'>"+n+"</option>"); 
	});
}
$(function(){
	showsTypeMemo(1);
	$("#type_memo").change(function(){

		type_change();
	})
})

function type_change()
{

    $("#amount").val("0");//归0
    $("#ComplexValue").val("0");//归0
	$("#sp_other").val("0");//归0
	$("#spAmount").val("0");//归0
	if($("#type_memo").val()=="2" || $("#type_memo").val()=="7" || $("#type_memo").val()=="8")	
	{
		// $("#amount").val("0");//归0
		// $("#ComplexValue").val("0");//归0
		// $("#sp_other").val("0");//归0
		$("#isnormality").attr("checked",false);
		$("#ck_tr").hide();
        $("#de_audit").hide();
		$("#hk_tr").hide();
		$("#ifsp_other").attr("checked",false);
		$("#sp_tr3").hide();
		$("#ifSp").attr("checked",true);	
		$("#isComplex").attr("checked",true);
		
	}
	else
	{
		$("#de_audit").hide();
		$("#ck_tr").show();
		$("#hk_tr").show();
		$("#sp_tr3").show();
		$("#ifsp_other").attr("checked",false);	
		$("#ifSp").attr("checked",false);	
		$("#isComplex").attr("checked",false);	
		$('#isnormality').attr("checked",true);
	}
}
function Calculate(){	
	var sp = 0;
	var Absorb = 0;
	var money=parseFloat($('#amount').val());
	if(money>0){
		//if($('#ifsp').attr("checked")=="checked"){
			if(money>=parseFloat(spLimit)){
				sp = (money*parseFloat(spRate)/100)>parseFloat(spMax)?spMax:money*parseFloat(spRate)/100;
			}
			$('#sp_other').val(sp);
			if(money>=parseFloat(AbsorbLimit)){
				Absorb = (money*parseFloat(AbsorbRate)/100)>parseFloat(AbsorbMax)?AbsorbMax:money*parseFloat(AbsorbRate)/100;
			}
			$('#spAmount').val(Absorb);
	}
}
function Calculate_Complex(){
	var money = parseFloat($('#amount').val());
	var sp = parseFloat($('#spAmount').val());
	var Absorb = parseFloat($('#sp_other').val());
	var ComplexValue = (money+sp+Absorb)*parseFloat(Audit_COMPLEX)
	if(isNaN(ComplexValue))$('#ComplexValue').val(0);
	else $('#ComplexValue').val(ComplexValue);
}
$(document).ready(function() {
	showsTypeMemo(1);
	 $(".op_type").change(function() {
	 var selectedvalue = $("input[name='op_type']:checked").val();
	 showsTypeMemo(selectedvalue);
	 if (selectedvalue == 1) {
		 $("#sp_tr").show();
		 $("#de_audit").hide();
		 $("#hk_tr").show();
		 $("#sp_tr2").show();
		 $("#sp_tr3").show();
		 $("#sp_tr4").show();
		 $("#sp_name").text('存款金額');
		 $("#type_name").text('存款项目');
		 $('#amount').val(0);
		 $('#amount').removeAttr("readonly");
	 }
	 });	 
	$("#savebtn").bind("click",function(e){
		var op_type=$("input[name='op_type']:checked").val();
			if($('#username').val()=="")
			{
				alert("請先输入會員！");return false;	
			}
			if($('#amount').val()<=0 || isNaN($('#amount').val()))
			{
				var type_memo=parseFloat($('#type_memo').val());
				
				if(type_memo == "2" || type_memo == "7" || type_memo == "8"){
					if($('#sp_other').val() <=0 && $('#spAmount').val() <=0){
                       alert('优惠金额不对');
                       return false;
					}
				}else{
					alert($("#sp_name").text()+"不正确！");$('#amount').focus();return false;
				}
					
			}	
			var money=parseFloat($('#amount').val());
			if(money<0 || money==NaN)
			{
				alert($("#sp_name").text()+"不能<=0");$('#amount').focus();return false;	
			}	
			if($("#sp_name").text()=='取款金額'){

					if(parseFloat($("#money1").html())<money){
						alert("餘額不足！");$('#amount').focus();return false;			
					}
			}
			if(op_type==2){
				return window.confirm('您確定要取款'+money+'元嗎！');
			}else{
				var ty_name = '';
				var str = '';
				var spamount = parseFloat($('#spAmount').val());
				var sp_other = parseFloat($('#sp_other').val());
				var type_memo = parseFloat($('#type_memo').val());
				var total = 0;
				if(type_memo!=2){
					if(money > 0){
                      total+=money;				
					  str += $("#sp_name").text()+'：'+money+'元\n\n';
					}
					
				}
				if($('#ifSp').attr("checked")=="checked" ){
					str += '存款優惠：'+spamount+'元\n\n';
					total+= spamount ;
				}
				if($('#ifSp_other').attr("checked")=="checked"){
					str += '匯款優惠：'+sp_other+'元\n\n';
					total+= sp_other;
				}
				if($('#isComplex').attr("checked")=="checked"){
					str += '打碼量：'+$('#ComplexValue').val()+'元\n\n';
				}
				str += '总共存入：'+total+'元\n\n';
				var ifcheck = '';
				if($('#isnormality').attr("checked")=="checked")ifcheck = '是';
				else ifcheck = '否';
				str += '常態性稽核：'+ifcheck;
				return window.confirm(str);
				
			}
			
		}); 
});


</script> 
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>