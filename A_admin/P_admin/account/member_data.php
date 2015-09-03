<?php
/**

   会员详细资料

*/
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../class/user.php");
include_once "../comm_menu.php";


$User = M('k_user',$db_config);
//获取对应会员资料
if (!empty($_GET['uid'])) {
    $user_data = $User
               ->where("uid = '".$_GET['uid']."'")
               ->find();
    $assoc=explode("-",$user_data['pay_address']);
}
$quanxian = trim($_SESSION["quanxian"]);
$qx_arr = explode(",", $quanxian);
//更新会员处理
if(!empty($_POST['uid'])){
//     $quanxian=array('g02','g04','g06','g08','g10','g12','g14','g16');
//     if (!check_purview($quanxian)){
//         message('你没有权限修改会员资料！');exit();
//     }
    //银行卡
    if(in_array('g04',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    if(!empty($_POST['pay_num'])){
        if(preg_match('/^[\d]{16,19}$/',$_POST["pay_num"])==0){
            message('银行卡号是16-19位纯数字！','member_data.php?uid='.$_GET['uid']);
        }

        $stateP = $User->where("pay_num = '".$_POST['pay_num']."' and site_id = '".SITEID."' and shiwan = 0")
                ->getField("uid");
             if(!empty($stateP)){
               if($stateP!=intval($_POST['uid'])){
                   message('该银行账号已经绑定！');exit();
                }

             }  
       $data['pay_num']=$_POST['pay_num'];
       
    }
    }
    if(in_array('g14',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['birthday']=$_POST['birthday'];
    }
    if(in_array('g08',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['mobile']=$_POST['mobile'];
    }
    if(in_array('g12',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['qq']=$_POST['qq'];
    }
    if(in_array('g10',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['email']=$_POST['email'];
    }
    $data['pay_card']=$_POST['pay_card'];
    if(in_array('g02',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['pay_name'] = $_POST['pay_name'];
    }
    if(in_array('g06',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['qk_pwd']=$_POST['password']; 
    }
    $data['about']=$_POST['about'];   
    $data['pay_address']=$_POST['province'].'-'.$_POST['city'];
    $data['reg_address']=$_POST['reg_address']; 
    if(in_array('g16',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){
    $data['passport']=$_POST['passport'];
    }
    $result = $User->where("uid = '".$_POST['uid']."'")->update($data);
    if($result){
       admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"修改了会员".$user_data['username']."的详细资料");
       message("修改成功!","member_index.php");
    }else{
       message("修改失败或未修改!","member_index.php");
    }
}else{
    $quanxian=array('g01','g03','g05','g07','g09','g11','g13','g15');
    if (!check_purview($quanxian)){
        message('你没有权限查看会员资料！');exit();
    }  
}
?>
<?php require("../common_html/header.php");?>
<body> 

<div  id="con_wrap">
  <div  class="input_002">修改會員資料</div>
  <div  class="con_menu"> <a  href="javascript:history.back(-1);">返回上一頁</a> </div>
</div>
<div  class="content">
  <form  name="myFORM"  id="myFORM"  action="#"  method="POST" onsubmit="return check()">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <input type="hidden" name="uid" value="<?=$user_data['uid'] ?>">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料編輯</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed"  width="70">帳號：</td> 
        <td style="text-align: left;"><?=$user_data['username']?></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <?php 
      if(in_array('g01',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
            <tr  class="m_bc_ed">
        <td  class="m_mem_ed">真實姓名：</td>
        <td style="text-align: left;">
        <input  type="text" name="pay_name" size="20" class="za_text"  value="<?=$user_data['pay_name']?>">
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
            <?}?>

    <!--   <tr  class="m_bc_ed">
        <td  class="m_mem_ed">英文名稱：</td>
        <td><input  type="TEXT"  datatype="*"  name="en_name"  size="20"  class="za_text"  value=""></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> -->
      <!-- <tr  class="m_bc_ed">
        <td  class="m_mem_ed">昵稱：</td>
        <td><input  type="TEXT"  datatype="*"  name="nickname"  size="20"  class="za_text"  value="<?=$user_data['pay_name']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>       -->
            <?php if(in_array('g13',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
          
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">出生日期：</td>
        <td><input  type="TEXT"  readonly='' name="birthday"  size="20"  onclick="WdatePicker()"  class="za_text"  value="<?=$user_data['birthday']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
        <?}?>
       <tr  class="m_bc_ed">
        <td  class="m_mem_ed">國家：</td>
        <td><input  type="TEXT"  datatype="*"  name="reg_address"  size="20"  class="za_text"  value="<?=$user_data['reg_address']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>   
            <?php if(in_array('g15',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
         
     <tr  class="m_bc_ed">
        <td  class="m_mem_ed">身份證號：</td>
        <td><input  type="TEXT"  datatype="*"  nullmsg="請輸入英文姓名"  name="passport"  size="20"  class="za_text"  value="<?=$user_data['passport']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>  
         <?}?>
            <?php if(in_array('g07',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
         
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">手機：</td>
        <td><input  type="TEXT"  datatype="*"  nullmsg="請輸入手機號碼"  name="mobile"  size="20"  class="za_text"  value="<?=$user_data['mobile']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>  
         <?}?>  
            <?php if(in_array('g11',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
                     
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">QQ：</td>
        <td><input  type="TEXT"  datatype="*"  name="qq"  size="20"  class="za_text"  value="<?=$user_data['qq']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> 
        <?}?>   
            <?php if(in_array('g09',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
           
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">E-mail：</td>
        <td><input  type="TEXT"  datatype="*"  name="email"  size="20"  class="za_text"  value="<?=$user_data['email']?>">&nbsp;&nbsp;<span style="color:red;">例如：xxx@163.com</span></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
       <?}?>

      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">取款銀行：</td>
        <td>
        	<select  name="pay_card" class="za_text">
            <option value="" selected="selected">请选择银行</option> 
            <option value="1" <?php select_check($user_data['pay_card'],'1');?>>中國銀行</option>
            <option value="2" <?php select_check($user_data['pay_card'],'2');?>>中國工商銀行</option>
            <option value="3" <?php select_check($user_data['pay_card'],'3');?>>中國建設銀行</option>
            <option value="4" <?php select_check($user_data['pay_card'],'4');?>>中國招商銀行</option>
            <option value="5" <?php select_check($user_data['pay_card'],'5');?>>中國民生銀行</option>
            <option value="7" <?php select_check($user_data['pay_card'],'7');?>>中國交通銀行</option>
            <option value="8" <?php select_check($user_data['pay_card'],'8');?>>中國郵政銀行</option>
            <option value="9" <?php select_check($user_data['pay_card'],'9');?>>中國农业銀行</option>
            <option value="10" <?php select_check($user_data['pay_card'],'10');?>>華夏銀行</option>
            <option value="11" <?php select_check($user_data['pay_card'],'11');?>>浦發銀行</option>
            <option value="12" <?php select_check($user_data['pay_card'],'12');?>>廣州銀行</option>
            <option value="13" <?php select_check($user_data['pay_card'],'13');?>>北京銀行</option>
            <option value="14" <?php select_check($user_data['pay_card'],'14');?>>平安銀行</option>
            <option value="15" <?php select_check($user_data['pay_card'],'15');?>>杭州銀行</option>
            <option value="16" <?php select_check($user_data['pay_card'],'16');?>>溫州銀行</option>
            <option value="17" <?php select_check($user_data['pay_card'],'17');?>>中國光大銀行</option>
            <option value="18" <?php select_check($user_data['pay_card'],'18');?>>中信銀行</option>
            <option value="19" <?php select_check($user_data['pay_card'],'19');?>>浙商銀行</option>
            <option value="20" <?php select_check($user_data['pay_card'],'20');?>>漢口銀行</option>
            <option value="21" <?php select_check($user_data['pay_card'],'21');?>>上海銀行</option>
            <option value="22" <?php select_check($user_data['pay_card'],'22');?>>廣發銀行</option>
            <option value="23" <?php select_check($user_data['pay_card'],'23');?>>农村信用社</option>
            <option value="24" <?php select_check($user_data['pay_card'],'24');?>>深圳发展银行</option>
            <option value="25" <?php select_check($user_data['pay_card'],'25');?>>渤海银行</option>
            <option value="26" <?php select_check($user_data['pay_card'],'26');?>>东莞银行</option>
            <option value="27" <?php select_check($user_data['pay_card'],'27');?>>宁波银行</option>
            <option value="28" <?php select_check($user_data['pay_card'],'28');?>>东亚银行</option>
            <option value="29" <?php select_check($user_data['pay_card'],'29');?>>晋商银行</option>
            <option value="30" <?php select_check($user_data['pay_card'],'30');?>>南京银行</option>
            <option value="31" <?php select_check($user_data['pay_card'],'31');?>>广州农商银行</option>
            <option value="32" <?php select_check($user_data['pay_card'],'32');?>>上海农商银行</option>
            <option value="33" <?php select_check($user_data['pay_card'],'33');?>>珠海农村信用合作联社</option>
            <option value="34" <?php select_check($user_data['pay_card'],'34');?>>顺德农商银行</option>
            <option value="35" <?php select_check($user_data['pay_card'],'35');?>>尧都区农村信用联社</option>
            <option value="36" <?php select_check($user_data['pay_card'],'36');?>>浙江稠州商业银行</option>
            <option value="37" <?php select_check($user_data['pay_card'],'37');?>>北京农商银行</option>
            <option value="38" <?php select_check($user_data['pay_card'],'38');?>>重庆银行</option>
            <option value="39" <?php select_check($user_data['pay_card'],'39');?>>广西农村信用社</option>
            <option value="40" <?php select_check($user_data['pay_card'],'40');?>>江苏银行</option>
            <option value="41" <?php select_check($user_data['pay_card'],'41');?>>吉林银行</option>
            <option value="42" <?php select_check($user_data['pay_card'],'42');?>>成都银行</option>
            <option value="50" <?php select_check($user_data['pay_card'],'50');?>>兴业银行</option>
            <option value="100" <?php select_check($user_data['pay_card'],'100');?>>支付宝</option>

        	</select>
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
            <?php if(in_array('g03',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
           
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">銀行帳號：</td>
        <td><input  type="TEXT"  datatype="*"  name="pay_num"  size="20"  class="za_text"  value="<?=$user_data['pay_num']?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> 
       <?}?>
    <tr  class="m_bc_ed">
        <td  class="m_mem_ed">省份：</td>
        <td><input  type="TEXT"  datatype="*"  name="province"  size="20"  class="za_text"  value="<?=$assoc[0]?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> 
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">縣市：</td>
        <td><input  type="TEXT"  datatype="*"  name="city"  size="20"  class="za_text"  value="<?=$assoc[1]?>"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> 
            <?php if(in_array('g05',$qx_arr)||$_SESSION["quanxian"]=='sadmin'){?>
      
 
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">取款密碼：</td>
        <td><input  type="text"  id="qk_pwd" name="password"  size="20"  class="za_text"  value="<?=$user_data['qk_pwd']?>"> &nbsp;&nbsp;<span id="tip"></span></td>
        <td></td>
      </tr>   
                 <?}?>   
       <tr  class="m_bc_ed">
        <td  class="m_mem_ed">備註：</td>
        <td><textarea style="width:208px;height: 56px;" class="za_text" name="about"><?=$user_data['about']?></textarea></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr> 
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">注册IP：</td>
        <td style="text-align: left;"><a style="color:blue;" href="./member_index.php?search_type=4&search_name=<?=$user_data['reg_ip']?>"><?=$user_data['reg_ip']?></a></td>
        <td></td>
      </tr>
	  <tr  class="m_bc_ed">
        <td  class="m_mem_ed">注册时间：</td>
        <td style="text-align: left;"><a style="color:blue;"><?=$user_data['reg_date']?></a></td>
        <td></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">最后登录时间：</td>
        <td style="text-align: left;"><a style="color:blue;"><?=$user_data['login_time']?></a></td>
        <td></td>
      </tr> 
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">最后登录IP：</td>
        <td style="text-align: left;"><a style="color:blue;" href="../other/member_login_log.php?strip=<?=$user_data['reg_ip']?>&username=<?=$user_data['username']?>"><?=$user_data['login_ip']?></a></td>
        <td></td>
      </tr> 
    <tr  align="center"  class="m_bc_ed">
        <td  class="m_bc_td"  colspan="3">
			<input  type="SUBMIT"  value="確定"  name="submitbtn"  class="za_button">
			&nbsp;&nbsp;&nbsp;
			<input  type="button"  name="cancelbtn"  value="取消"  id="FormsButton2"   class="za_button">
		</td>
	</tr>        
    </tbody></table> 
    <input type="hidden" name="add_ziliao" value="1">
   </form> 
</div>

</body></html>
<script type="text/javascript">

function check(){
	var val=$('#qk_pwd').val();
	var reg=/\D/;
	var result=reg.test(val);
	if(val.length!=32){
		if(!result&&val.length==4){
			return true;
		}else{
			$('#tip').html('<font color="red">取款密码为四位数字</font>');
			return false;
		}
		
	}
}
$('#qk_pwd').focus(function(){
	$('#tip').html('');
})

</script>
<?php include("../common_html/footer.php");?>