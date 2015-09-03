<?php  
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include("../../class/setCenter.class.php");
 $u = M('k_user_agent',$db_config);
 	//编辑查询
unset($agent);
if(intval($_GET['uid'])>0){
  $agent = $u->where("id='".$_GET['uid']."'")->find();
}

if ($_POST['act'] == 'sh') {
    //添加或者编辑处理
	if (!empty($_POST['agent_pwd'])) {
      if(empty($_POST['agent_name']) || empty($_POST['agent_pwd']) || empty($_POST['agent_pwd2']) || empty($_POST['agent_user'])){
         message('请完善表单！','agent_add.php');
      }
      if ($_POST['agent_pwd'] != $_POST['agent_pwd2']) {
          message('两次密码不对','shareholder_add.php');
       }

       $is_name = $u->where("agent_login_user = '".$_POST['agent_user']."'")->find();
       if ($is_name) {
           message('账号已存在','shareholder_add.php');
        }
        $data['agent_name'] = $_POST['agent_name'];//股东名字
        $data['video_scale'] = $_POST['video_scale'];//视讯占成
        $data['sports_scale'] = $_POST['sports_scale'];//体育占成
        $data['lottery_scale'] = $_POST['lottery_scale'];//彩票占成
        $data['site_id'] = SITEID;
        $data['agent_user'] = SITEID.$_POST['agent_user'];
        $data['add_date'] =date('Y-m-d H:i:s');//添加时间
        $data['agent_company'] =COMPANY_NAME;//所属公司
        $data['agent_type'] = 's_h';//表示股东
 
       if ($aid = $u->add($data)) {
           $state = setCenter::agent_setAdd($aid);
            if (empty($state)) {
                $do_log = "设定参数错误,";
            }else{
               $do_log = '添加股东账号:'.$data['agent_user'];
               admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
                message('添加股东成功','shareholder_index.php');
            }
       }         
		}
}
?>
<?php require("../common_html/header.php");?>
<body> 
<script>
<!--
/*$(document).ready(function(){
	$("#ov_corprator_id").val('389945');
	document.getElementById("LoadLayer").style.display = "none";
});*/

$(document).ready(function(){
	var currency='<?=$agent['money_type']?>';
	$("#currency option[value='"+currency+"']").attr("selected",true);
	
	var sports_scale='<?=$agent['sports_scale']?>';
	$("#sports_scale option[value='"+sports_scale+"']").attr("selected",true);
	var lottery_scale='<?=$agent['lottery_scale']?>';
	$("#lottery_scale option[value='"+lottery_scale+"']").attr("selected",true);
	var video_scale='<?=$agent['video_scale']?>';
	$("#video_scale option[value='"+video_scale+"']").attr("selected",true);
	
});
-->
</script>
<div  id="con_wrap">
  <div  class="input_002">修改股東帳號</div>
  <div  class="con_menu"> <a  href="javascript:history.go(-1);">返回上一頁</a> </div>

</div>
<div  class="content">
  <form  id="myFORM"  name="myFORM"  action=""  method="POST">
	  <input  name="id" type="hidden" value="<?=$agent['id']?>">
    <input  name="act" type="hidden" value="sh">
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">股东帳號：</td>
        <td><input name="agent_user" type="text" value="<?=$agent['agent_user']?>" class="za_text"  errormsg="用户名至少6个字符,最多12个字符！"  nullmsg="請輸入帳號!" sucmsg="验证通过！" datatype="s6-12" ajaxurl="user_check.php"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  value="" name="agent_pwd" class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD" value="" name="agent_pwd2"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">股東名稱：</td>
        <td><input  type="TEXT"  name="agent_name" value="<?=$agent['agent_name']?>"  datatype="*"  nullmsg="請輸入股東名稱"  value=""  size="10"  maxlength="10"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
    </tbody></table>
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed"  style="display:none">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">下注資料設定</td>
      </tr>
    </tbody></table>
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">占成設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">體育-股東占成數：</td>
          <td><select  name="sports_scale" id="sports_scale"  class="za_select">       
				<option  value="1.00" <?php if($agent['sports_scale'] == 1.00) echo "selected"; ?>>全成</option>
				<option  value="0.95" <?php if($agent['sports_scale'] == 0.95) echo "selected"; ?>>9.5 成</option>
				<option  value="0.90" <?php if($agent['sports_scale'] == 0.90) echo "selected"; ?>>9 成</option>
				<option  value="0.85" <?php if($agent['sports_scale'] == 0.85) echo "selected"; ?>>8.5 成</option>
				<option  value="0.80" <?php if($agent['sports_scale'] == 0.80) echo "selected"; ?>>8 成</option>
				<option  value="0.75" <?php if($agent['sports_scale'] == 0.75) echo "selected"; ?>>7.5 成</option>
				<option  value="0.70" <?php if($agent['sports_scale'] == 0.70) echo "selected"; ?>>7 成</option>
				<option  value="0.65" <?php if($agent['sports_scale'] == 0.65) echo "selected"; ?>>6.5 成</option>
				<option  value="0.60" <?php if($agent['sports_scale'] == 0.60) echo "selected"; ?>>6 成</option>
				<option  value="0.55" <?php if($agent['sports_scale'] == 0.55) echo "selected"; ?>>5.5 成</option>
				<option  value="0.50" <?php if($agent['sports_scale'] == 0.50) echo "selected"; ?>>5 成</option>
				<option  value="0.45" <?php if($agent['sports_scale'] == 0.45) echo "selected"; ?>>4.5 成</option>
				<option  value="0.40" <?php if($agent['sports_scale'] == 0.40) echo "selected"; ?>>4 成</option>
				<option  value="0.35" <?php if($agent['sports_scale'] == 0.35) echo "selected"; ?>>3.5 成</option>
				<option  value="0.30" <?php if($agent['sports_scale'] == 0.30) echo "selected"; ?>>3 成</option>
				<option  value="0.25" <?php if($agent['sports_scale'] == 0.25) echo "selected"; ?>>2.5 成</option>
				<option  value="0.20" <?php if($agent['sports_scale'] == 0.20) echo "selected"; ?>>2 成</option>
				<option  value="0.15" <?php if($agent['sports_scale'] == 0.15) echo "selected"; ?>>1.5 成</option>
				<option  value="0.10" <?php if($agent['sports_scale'] == 0.10) echo "selected"; ?>>1 成</option>
				<option  value="0.05" <?php if($agent['sports_scale'] == 0.05) echo "selected"; ?>>0.5 成</option>
				<option  value="0" <?php if($agent['sports_scale'] == 0) echo "selected"; ?>>0 成</option>
									
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">彩票-股東占成數：</td>
        <td><select  name="lottery_scale"  id="lottery_scale"  class="za_select">
            	<option  value="1.00" <?php if($agent['lottery_scale'] == 1.00) echo "selected"; ?>>全成</option>
				<option  value="0.95" <?php if($agent['lottery_scale'] == 0.95) echo "selected"; ?>>9.5 成</option>
				<option  value="0.90" <?php if($agent['lottery_scale'] == 0.90) echo "selected"; ?>>9 成</option>
				<option  value="0.85" <?php if($agent['lottery_scale'] == 0.85) echo "selected"; ?>>8.5 成</option>
				<option  value="0.80" <?php if($agent['lottery_scale'] == 0.80) echo "selected"; ?>>8 成</option>
				<option  value="0.75" <?php if($agent['lottery_scale'] == 0.75) echo "selected"; ?>>7.5 成</option>
				<option  value="0.70" <?php if($agent['lottery_scale'] == 0.70) echo "selected"; ?>>7 成</option>
				<option  value="0.65" <?php if($agent['lottery_scale'] == 0.65) echo "selected"; ?>>6.5 成</option>
				<option  value="0.60" <?php if($agent['lottery_scale'] == 0.60) echo "selected"; ?>>6 成</option>
				<option  value="0.55" <?php if($agent['lottery_scale'] == 0.55) echo "selected"; ?>>5.5 成</option>
				<option  value="0.50" <?php if($agent['lottery_scale'] == 0.50) echo "selected"; ?>>5 成</option>
				<option  value="0.45" <?php if($agent['lottery_scale'] == 0.45) echo "selected"; ?>>4.5 成</option>
				<option  value="0.40" <?php if($agent['lottery_scale'] == 0.40) echo "selected"; ?>>4 成</option>
				<option  value="0.35" <?php if($agent['lottery_scale'] == 0.35) echo "selected"; ?>>3.5 成</option>
				<option  value="0.30" <?php if($agent['lottery_scale'] == 0.30) echo "selected"; ?>>3 成</option>
				<option  value="0.25" <?php if($agent['lottery_scale'] == 0.25) echo "selected"; ?>>2.5 成</option>
				<option  value="0.20" <?php if($agent['lottery_scale'] == 0.20) echo "selected"; ?>>2 成</option>
				<option  value="0.15" <?php if($agent['lottery_scale'] == 0.15) echo "selected"; ?>>1.5 成</option>
				<option  value="0.10" <?php if($agent['lottery_scale'] == 0.10) echo "selected"; ?>>1 成</option>
				<option  value="0.05" <?php if($agent['lottery_scale'] == 0.05) echo "selected"; ?>>0.5 成</option>
				<option  value="0" <?php if($agent['lottery_scale'] == 0) echo "selected"; ?>>0 成</option>
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">視訊-股東占成數：</td>
        <td><select  name="video_scale" id="video_scale"  class="za_select">
            	<option  value="1.00" <?php if($agent['video_scale'] == 1.00) echo "selected"; ?>>全成</option>
				<option  value="0.95" <?php if($agent['video_scale'] == 0.95) echo "selected"; ?>>9.5 成</option>
				<option  value="0.90" <?php if($agent['video_scale'] == 0.90) echo "selected"; ?>>9 成</option>
				<option  value="0.85" <?php if($agent['video_scale'] == 0.85) echo "selected"; ?>>8.5 成</option>
				<option  value="0.80" <?php if($agent['video_scale'] == 0.80) echo "selected"; ?>>8 成</option>
				<option  value="0.75" <?php if($agent['video_scale'] == 0.75) echo "selected"; ?>>7.5 成</option>
				<option  value="0.70" <?php if($agent['video_scale'] == 0.70) echo "selected"; ?>>7 成</option>
				<option  value="0.65" <?php if($agent['video_scale'] == 0.65) echo "selected"; ?>>6.5 成</option>
				<option  value="0.60" <?php if($agent['video_scale'] == 0.60) echo "selected"; ?>>6 成</option>
				<option  value="0.55" <?php if($agent['video_scale'] == 0.55) echo "selected"; ?>>5.5 成</option>
				<option  value="0.50" <?php if($agent['video_scale'] == 0.50) echo "selected"; ?>>5 成</option>
				<option  value="0.45" <?php if($agent['video_scale'] == 0.45) echo "selected"; ?>>4.5 成</option>
				<option  value="0.40" <?php if($agent['video_scale'] == 0.40) echo "selected"; ?>>4 成</option>
				<option  value="0.35" <?php if($agent['video_scale'] == 0.35) echo "selected"; ?>>3.5 成</option>
				<option  value="0.30" <?php if($agent['video_scale'] == 0.30) echo "selected"; ?>>3 成</option>
				<option  value="0.25" <?php if($agent['video_scale'] == 0.25) echo "selected"; ?>>2.5 成</option>
				<option  value="0.20" <?php if($agent['lottery_scale'] == 0.20) echo "selected"; ?>>2 成</option>
				<option  value="0.15" <?php if($agent['video_scale'] == 0.15) echo "selected"; ?>>1.5 成</option>
				<option  value="0.10" <?php if($agent['video_scale'] == 0.10) echo "selected"; ?>>1 成</option>
				<option  value="0.05" <?php if($agent['video_scale'] == 0.05) echo "selected"; ?>>0.5 成</option>
				<option  value="0" <?php if($agent['video_scale'] == 0) echo "selected"; ?>>0 成</option>		
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td">
        <input  type="SUBMIT"  name="saveBtn"  value="確定"  class="za_button">
          </td>
      </tr>
    </tbody></table>
  </form>
</div>
<script language="javascript">

$(function(){
  $("#myFORM").Validform({
      tiptype:2,
      datatype:{
        'credit':function(gets,obj){
          if(gets)
          {
            if(gets>parseInt(obj.attr('max')) || gets<(parseInt(obj.attr('miny'))+parseInt(obj.attr('minn'))))
            {
              return '超过可用额度或小于下级信用总额';
            }
            return true;
          }
          return false;
        }
      },  
      callback:function(form){
        if(confirm("是否確定寫入股東帳號?")) {
          form.get(0).submit();
      }return false;
      
    }
  });
  $("#myFORM2").Validform({
    tiptype:2,
    callback:function(form){
      dfwinloss_s=$("select[name='dfwinloss_s']").val();
      cp_dfwinloss_s=$("select[name='cp_dfwinloss_s']").val();
      zr_dfwinloss_s=$("select[name='zr_dfwinloss_s']").val();
      if((dfwinloss_s=="-" ||cp_dfwinloss_s=="-" || zr_dfwinloss_s=="-")){
          alert("預設成數不可有 [ - ] 號");
          $("select[name='dfwinloss_s']").focus();
          return false;
        }   
      if(dfwinloss_s!="-" || cp_dfwinloss_s!="-" || zr_dfwinloss_s!="-"){
        if(confirm("預設的成數將在 "+dfday+" 後生效!!確認預設嗎?")){
          form.get(0).submit();
        }
          return false;
      }
    }
  });
});
</script>
<?php require("../common_html/footer.php");?>