<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//查询会员信息
if (!empty($_POST['user_name'])) {
    require ("../video/getallbalance.php");
	$c = M('k_user',$db_config);
	// $user = $c->join('left join k_user_games on k_user.uid = k_user_games.uid')->where("k_user.username = '".$_POST['user_name']."' and k_user.site_id = '".SITEID."' and is_delete = 0" )->find();
    $user = $c->where("username = '".$_POST['user_name']."' and site_id = '".SITEID."'" )->find();

    $userV = M('k_user',$db_config)->field("og_money,ag_money,mg_money,bbin_money,lebo_money,ct_money")->where("uid = '".$user['uid']."'" )->find();
	if (empty($user)) {
		message('该账户不存在','catm.php');
	}else{
       //读取支付设定优惠信息
       $pay_id = M('k_user_level',$db_config)
              ->where("id='".$user['level_id']."'")
              ->getField('RMB_pay_set');
       $discount = M('k_cash_config_view',$db_config)
                 ->where("id = '".$pay_id."'")
                 ->find();   
	}
}elseif (empty($_POST['user_name']) && $_POST){
	message('请输入会员账号！');
}

?>
<?php $title="存款與取款"; require("../common_html/header.php");?>
<body>
<style type="text/css">
	#de_audit{
		display: none;
	}
</style>
<div  id="con_wrap">
  <div  class="input_002">存款與取款</div>
  <div  class="con_menu">
    <form  method="post"  name="search_form" >
      <input  type="button"  value="历史查詢" onclick="document.location='catm_record.php'"  class="button_e">&nbsp;<input type="button"  value="批量存款" onclick="document.location='cash_operation.php'"  class="button_e">
      <input  type="hidden"  value="search"  name="search">
      會員帳號：
      <input  size="20"  name="user_name"  class="za_text"  value="<?=$user['username']?>">
      &nbsp;&nbsp;
      <input  class="button_d"  value="查詢"  type="submit">
    </form>
  </div>
</div>
<div  class="content">
  <form  method="POST"  action="catm_do.php" name="withdrawal_form">
    <input  type="hidden"  id="userid" name="userid"  value="<?=$user['uid']?>">
    <input  type="hidden"  id="agent_id" name="agent_id"  value="<?=$user['agent_id']?>">
    <input  type="hidden"  id="expenese_num" name="expenese_num"  value="<?=$discount['line_ct_xz_audit']?>">
    <input  type="hidden"  id="relax_limit" name="relax_limit"  value="<?=$discount['line_ct_fk_audit']?>">
    <input  type="hidden"  name="username"  value="<?=$user['username']?>">
    <table  class="m_tab"  style="width:550px">
      <tbody><tr  class="m_title">
        <td  colspan="4"  height="18"  align="center">人工存取款</td>
      </tr>
      <tr>
        <td  height="18"  align="center"  style="width:120px"  class="table_bg1 textTr">帳號</td>
        <td style="width: 150px;"><?=$user['username']?></td>
        <td  height="18"  align="center"  style="width:120px"  class="table_bg1 textTr">姓名</td>
        <td style="width: 120px;"><?=$user['pay_name']?></td>
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">系统余額</td>
        <td colspan="3"><span  id="money1"><?=$user['money']?></span> 元</td>
      
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">CT余額</td>
        <td><span  id="money1"><?=$userV['ct_money']?></span> 元</td>
        <td  height="18"  align="center"  class="table_bg1 textTr">MG余額</td>
        <td><span  id="money1"><?=$userV['mg_money']?></span> 元</td>
      </tr>
       <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">BBIN余額</td>
        <td><span  id="money1"><?=$userV['bbin_money']?></span> 元</td>
         <td  height="18"  align="center"  class="table_bg1 textTr">AG余額</td>
        <td><span  id="money1"><?=$userV['ag_money']?></span> 元</td>
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">OG余額</td>
        <td><span  id="money1"><?=$userV['og_money']?></span> 元</td>
          <td  height="18"  align="center"  class="table_bg1 textTr">LB余額</td>
        <td><span  id="money1"><?=$userV['lebo_money']?></span> 元</td>
      
      </tr>
      <tr>
      	  <td  height="18"  align="center"  class="table_bg1 textTr">操作類型</td>
        <td colspan="3"><input  name="op_type"  type="radio"  value="1"  checked=""  class="op_type">
          存款
          <input  name="op_type"  type="radio"  value="2"  class="op_type">
          取款</td>
      </tr>
       <tr id="ck_tr">
        <td height="18" align="center" class="table_bg1 textTr" id="sp_name">存款金額</td>
        <td colspan="3"><input onkeydown="return Yh_Text.CheckNumber();" onblur="Calculate();Calculate_Complex();" maxlength="20" value="0" size="21" class="za_text" name="amount" id="amount"></td>
      </tr>
      <tr id="sp_tr">
        <td height="18" align="center" class="table_bg1 textTr">存款優惠</td>
        <td colspan="3"><input name="ifSp" id="ifSp" value="1" type="checkbox">
          存入，優惠金額：
          <input type="input" class="za_text" onblur="Calculate_Complex();" style="min-width: 80px !important; width: 50px !important" onkeydown="return Yh_Text.CheckNumber()" value="0" id="spAmount" name="sp_other"></td>
      </tr>
      <tr id="hk_tr">
        <td height="18" align="center" class="table_bg1 textTr">匯款優惠</td>
        <td colspan="3"><input name="ifSp_other" id="ifSp_other" value="1" type="checkbox">
          存入，優惠金額：
          <input type="input" class="za_text" onblur="Calculate_Complex();" style="min-width: 80px !important; width: 50px !important" onkeydown="return Yh_Text.CheckNumber()" value="0" id="sp_other" name="spAmount"></td>
      </tr>
      <tr id="sp_tr2">
        <td height="18" align="center" class="table_bg1 textTr">綜合打碼量稽核</td>
        <td colspan="3"><input name="isComplex" id="isComplex" value="1" type="checkbox">
          稽核，打碼量：
          <input type="text" class="za_text" style="min-width: 80px !important; width: 50px !important" onkeydown="return Yh_Text.CheckNumber2()" value="0" id="ComplexValue" name="ComplexValue"></td>
      </tr>
      <tr id="sp_tr3">
        <td height="18" align="center" class="table_bg1 textTr">常態性稽核</td>
        <td colspan="3"><input name="isnormality" id="isnormality" value="<?=$discount['line_ct_audit']?>" type="checkbox" >
          稽核</td>
      </tr>
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr"  id="type_name">存款项目</td>
        <td colspan="3"><select  name="type_memo" id="type_memo">
     
             </select>
        </td>
      </tr>
       <tr id="de_audit">
        <td  height="18"  align="center"  class="table_bg1 textTr"  id="type_name">清除稽核</td>
       <td colspan="3">
          <input name="delete_audit" id="delete_audit" value="1" type="checkbox" >
          是
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
      <tr>
        <td  height="18"  align="center"  class="table_bg1 textTr">會員備注</td>
        <td colspan="3"><?=$user['about']?></td>
      </tr>
      <tr  align="center">
        <td  colspan="4"  class="table_bg1">
        <input type="hidden" name="line_ct_xz_audit" value="<?=$discount['line_ct_xz_audit']?>">
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
	if($("input[name='op_type']:checked").val()=="2")
	{
		$("#ck_tr").show();
		if($("#type_memo").val()=="4"){
           $("#de_audit").show();
		}else{
		   $("#de_audit").hide();
		}
		return true;		
	}else{
		$("#de_audit").hide();
	}
	if($("#type_memo").val()=="2" || $("#type_memo").val()=="7" || $("#type_memo").val()=="8")	
	{
		// $("#amount").val("0");//归0
		// $("#ComplexValue").val("0");//归0
		// $("#sp_other").val("0");//归0
		$("#ck_tr").hide();
        $("#de_audit").hide();
		$("#hk_tr").hide();
		$("#ifsp_other").attr("checked",false);
		$("#sp_tr3").hide();
		$("#ifSp").attr("checked",true);	
		$("#isComplex").attr("checked",true);
		$('isnormality').attr("checked",false);
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
	 }else {
		 $("#ck_tr").show();
		 $("#sp_tr").hide();
		 $("#hk_tr").hide();
		 $("#sp_tr2").hide();
		 $("#sp_tr3").hide();
		 $("#sp_tr4").hide();
		 $("#ifsp").attr("checked",false);
		 $("#ifsp_other").attr("checked",false);
		 $("#isComplex").attr("checked",false);
		 $("#isnormality").attr("checked",false);
		 $('#sp_other').val(0);
		 $('#spAmount').val(0);
		 $('#ComplexValue').val(0);
		 $("#sp_name").text('取款金額');
		 $("#type_name").text('取款项目');
	 }
	 });	 
	$("#savebtn").bind("click",function(e){
		var op_type=$("input[name='op_type']:checked").val();
			if($('#userid').val()=="")
			{
				alert("請先查詢會員！");return false;	
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