<?php //ini_set("display_errors","on");
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

if(isset($_GET["uid"])){
  $sql  = "select * from k_user where uid=".intval($_GET["uid"])."";
  $rows = M('k_user',$db_config)->where("uid = '".$_GET["uid"]."'")->find();
}

//修改
if(isset($_GET['edit'])){
   // check_name($_POST['pay_name']);
    $data['change_pwd']=$_POST['change_pwd'];

    if($_POST['passwd'] != $_POST['repasswd']){
      message("两次输入的密码必须一样！");
      
    }else{
      if($_POST['passwd']!='******'||$_POST['repasswd']!='******'){
        check_pass($_POST['passwd']);
        check_pass($_POST['repasswd']);
        $data['password']=md5(md5($_POST['passwd']));
      }
    }
    $rows2 = M('k_user',$db_config)->where("uid = '".$_POST["user_id"]."'")->update($data);

    if($rows2){
       //操作记录
        $do_log = '编辑会员:'.$_POST['username'].'资料';
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
      message("修改成功！","member_index.php?1=1");
    }else{
        message("修改失败或未修改！","member_index.php?1=1");
    }
}




?>

<?php require("../common_html/header.php");?>
<body> 
<style type="text/css">
  .za_text{
    float: left;
  }
</style>
<script>
<!--

function Chg_Mcy(a){
  curr=new Array();
  curr_now=new Array();


  if (document.getElementsByName('ratio')[0].value==''){
    tmp=document.getElementsByName('currency')[0].options[document.getElementsByName('currency')[0].selectedIndex].value;
    ratio=eval(curr[tmp]);
    ratio_now=eval(curr_now[tmp]);
  }else{
    ratio=eval(document.getElementsByName('ratio')[0].value);
    ratio_now=eval(document.getElementsByName('ratio_now')[0].value);
  }
  
  if (a=='mx'){
    /*tmp_count=ratio*eval(document.getElementsByName('maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('mcy_mx').innerHTML=tmp_count;
    //
    tmp_count=ratio*eval(document.getElementsByName('cp_maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('cp_mcy_mx').innerHTML=tmp_count;
    //
    tmp_count=ratio*eval(document.getElementsByName('cp_maxkkcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('cp_mcy_mx_kk').innerHTML=tmp_count;
    //
    tmp_count=ratio*eval(document.getElementsByName('zr_maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('zr_mcy_mx').innerHTML=tmp_count;*/
  }
  else if (a=='mc'){
    tmp_count=ratio*eval(document.getElementsByName('MAXCREDIT')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('mcy_mc').innerHTML=tmp_count;
  }
  else if (a=='now'){
    /*document.getElementById('mcy_now').innerHTML=ratio_now;
    //
    tmp_count=ratio*eval(document.getElementsByName('maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('mcy_mx').innerHTML=tmp_count;
    //
    tmp_count=ratio*eval(document.getElementsByName('cp_maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('cp_mcy_mx').innerHTML=tmp_count;
    //
    tmp_count=ratio*eval(document.getElementsByName('zr_maxcredit')[0].value);
    tmp_count=Math.round(tmp_count*100);
    tmp_count=tmp_count/100;
    document.getElementById('zr_mcy_mx').innerHTML=tmp_count;*/
  }
}
$(document).ready(function(){
    $("select[name='type']").val('4');
    show_Line_Date();
    $("#pay_type").bind("change",function(e){
      if($(this).val()=="1")
      {
        $("tr[xy=true]").hide();
        $("tr[xj=true]").show();
      }
      else
      {
        $("tr[xy=true]").show();
        $("tr[xj=true]").hide();
      }
    });
    $("#pay_type").trigger("change");
    Chg_Mcy('mx');
});
  -->
</script>
<div  id="con_wrap">
  <div  class="input_002">修改會員帳號</div>
  <div  class="con_menu"> <a  href="javascript:history.go(-1);">返回上一頁</a> </div>
</div>
<div  class="content">
  <div  id="show_div"  style="position: absolute; z-index: 99; display: none;"></div>
  <div  id="Layer1"  style="position:absolute; width:780px; height:26px; z-index:1; left: 0px; top: 406px; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000"></div>
  <form  name="myFORM"  id="myFORM"  action="member_edit.php?edit=1&uid=<?=$rows['uid'] ?>"  method="POST"  class="myFORM">
    <input  type="hidden"  name="user_id"  value="<?=$rows['uid']?>">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed"  width="70">帳號：</td>
        <td style="text-align: left;"> <font  color="red"><?=$rows['username']?>
       <input  type="hidden" name="username" class="za_text" value="<?=$rows['username']?>"></font></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">密碼：</td>
        <td><input  type="PASSWORD"  name="passwd"  value="******"  size="12"  maxlength="12"  class="za_text"  datatype="s6-12"  nullmsg="請輸入密碼"  errormsg="密碼必須至少6個字元長，最多12個字元長，并只能有數字(0-9)，及英文大小寫字母"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">確認密碼：</td>
        <td><input  type="PASSWORD"  name="repasswd"  size="12"  maxlength="12"  class="za_text"  datatype="*"  value="******"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
      
        <td  class="m_mem_ed">是否可修改密碼：</td>
        <td>是：
          <input  class="za_select_02"  type="radio"  <?php if($rows['change_pwd']==1){echo 'checked=""'; }?>  value="1"  name="change_pwd"  id="pass_sys"  nullmsg="請選擇是否可修改密碼：">
          否：
          <input  class="za_select_02"  type="radio"  value="0" <?php if($rows['change_pwd']==0){echo 'checked=""'; }?>
           name="change_pwd"  id="pass_sys"></td>
        <td><div  class="Validform_checktip"></div></td>
          </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">會員名稱：</td>
        <td>线上注册会员</td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
    </tbody></table>
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">下注資料設定</td>
      </tr>
      
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">匯率設定：</td>
        <td>人民幣 目前匯率：<font  color="#FF0033"  id="mcy_now">1</font>&nbsp;(目前匯率僅供參考)</td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed"><input  type="button"  value="修正"  class="za_button"  id="editpcbtn"   style="display: none;">
          &nbsp;&nbsp;盤口玩法：</td>
        <td><span  id="show_cb"><font  style="color: 960000;">香港盤,馬來盤,印尼盤,歐洲盤</font></span></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed"  style="display:none">
        <td  class="m_mem_ed">投注方式：</td>
        <td><select  name="pay_type"  id="pay_type"  class="za_select">
            <option  value="1">現金</option>
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      
    </tbody></table>
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">體育設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">開放球類：</td>
        <td  width="400"><select  name="type"  id="type"  disabled=""  class="za_select">
            <option  value="1">A盤</option> 
            <option  value="2">B盤</option>
            <option  value="3">C盤</option> 
            <option  value="4"  selected="">D盤</option>
            
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
    </tbody></table>
  <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody> <!--  <tr  class="m_title_edit">
        <td  colspan="3">彩票設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_mem_ed">開放盤口：</td>
        <td><select  name="abcd"  id="abcd"  disabled=""  class="za_select">
            <option  value="A">A盤</option>
            <option  value="B">B盤</option>
            <option  value="C">C盤</option>
            <option  value="D"  selected="">D盤</option>
          </select>
          <input  type="checkbox"  disabled=""  id="allabcd_a"  name="allabcd[]"  value="A">
          A盤 &nbsp;&nbsp;
          <input  type="checkbox"  disabled=""  id="allabcd_b"  name="allabcd[]"  value="B">
          B盤 &nbsp;&nbsp;
          <input  type="checkbox"  disabled=""  id="allabcd_c"  name="allabcd[]"  value="C">
          C盤 &nbsp;&nbsp;
          <input  type="checkbox"  disabled=""  id="allabcd_d"  name="allabcd[]"  value="D"  checked="">
          D盤 &nbsp;&nbsp; </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <input  type="hidden"  name="minorder"  value="0">
    </tbody></table> -->
  <!--   <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">視訊設定</td>
      </tr>
      <tr  class="m_bc_ed"  style="display:none">
        <td  class="m_over_co_ed"  width="120"> 賬號類型：</td>
        <td><input  type="radio"  name="zr_test"  value="0"  id="zr_test"  checked="">正式&nbsp;&nbsp;<input  type="radio"  name="zr_test"  value="1"  id="zr_test">試用</td>
        <td></td>
      </tr> -->
        <input  type="hidden"  name="minorder"  value="0">
    <tr  align="center"  class="m_bc_ed">
        <td  class="m_bc_td"  colspan="3">
          <input  type="SUBMIT"  value="確定"  class="za_button">
          &nbsp;&nbsp;&nbsp;
          <input  type="button"  name="FormsButton2"  value="取消"  id="FormsButton2"   class="za_button">
        </td>
      </tr>
  

  </tbody></table>
 </form>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>