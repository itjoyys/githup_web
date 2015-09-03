<?php  
include_once("../../include/config.php");
include_once("../common/login_check.php");

$u = M('k_user_agent',$db_config);

//读取上级
if ($_GET['atype'] == 'u_a') {
   $map_u['agent_type'] = 'u_a';
   $tit = '總代';
}elseif ($_GET['atype'] == 's_h') {
   $map_u['agent_type'] = 's_h';
   $tit = '股东';
}

$map_u['site_id'] = SITEID;
$map_u['is_demo'] = 0;
$up_agents = $u->where($map_u)->select();
  
?>

<?php require("../common_html/header.php");?>
<script>
  $(function(){
    $("select[name='shareholder']").change(function(event) {
      var id = $("select[name='shareholder']").val();
      $.ajax({  
            type: "post",  
            url: "./agent_add_ajax.php",  
            dataType: "json",  
            data: {id:id,atype:'ua'},  
            success: function(msg){  
                $("#sports_scale").html(msg.sports_scale);
                $("#lottery_scale").html(msg.lottery_scale);
                $("#video_scale").html(msg.video_scale);
            }     
      });  
    });
  })

</script>
<body> 
<div  id="con_wrap">
  <div  class="input_002">代理帳號</div>
</div>
</div>
<div  class="content">
  <form  id="myFORM"  name="myFORM"  action="agent_add_do.php"  method="POST">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>

      <tr  class="m_bc_ed">
        <td  class="m_co_ed"  width="120">所屬<?=$tit?>：</td>
        <td  width="200">
      <select name="shareholder" datatype="*" nullmsg="请选择所屬上级!">
       <option value="">请选择所屬<?=$tit?></option>
       <?php if (!empty($up_agents)){
          foreach ($up_agents as $k => $v) {?>
            <option value="<?=$v['id']?>"><?=$v['agent_user']?></option>

       <?php } }?>
      </select>
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">帳號：</td>
        <td> 
        <input name="agent_user" id="agent_user" type="text"  ajaxurl="user_check.php" datatype="s4-14" value="<?=$_GET['r_user']?>" class="za_text">
     </td>
        <td><div  class="Validform_checktip">*數字及字母(a-z)长度(6-12位)</div></td>
      </tr>
      <?php if (empty($_GET['appid'])) {?>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  value="" name="agent_pwd" class="za_text"></td>
        <td><div  class="Validform_checktip">*數字及字母(a-z)长度(6-12位)</div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD" value="" name="agent_pwd2"  class="za_text"></td>
        <td><div  class="Validform_checktip">*數字及字母(a-z)长度(6-12位)</div></td>
      </tr>
      <?php }?>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">代理名稱：</td>
        <td><input  type="TEXT"  name="agent_name" value="<?=$_GET['r_name']?>"  datatype="*"  nullmsg="請輸入總代理名稱" class="za_text"></td>
        <td><div  class="Validform_checktip">*</div></td>
      </tr>
</tbody></table>
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed"  style="display:none">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">下注資料設定</td>
      </tr>
    </tbody></table>
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">占成設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">體育-代理占成數：</td>
        <td>
           <select  name="sports_scale" id="sports_scale"  class="za_select">           
          </select>
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">彩票-代理占成數：</td>
        <td><select  name="lottery_scale"  id="lottery_scale"  class="za_select">
       
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">視訊-代理占成數：</td>
        <td><select  name="video_scale" id="video_scale"  class="za_select">            

          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>

      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td">
           <input  type="hidden" value="<?=$_GET['atype']?>" name="atype"  class="za_text">
           <input  type="hidden" value="<?=$_GET['appid']?>" name="appid"  class="za_text">
           <input  type="hidden" value="<?=$_GET['app_pwd']?>" name="app_pwd"  class="za_text">
           <input  type="SUBMIT"  name="saveBtn"  value="確定"  class="za_button">
          </td>
      </tr>
    </tbody></table>
  </form>
</div>
<script>
$("#myFORM").Validform({
tiptype:2,
callback:function(form){
  if(confirm("是否確定寫入代理帳號?")) {
    form.get(0).submit();
    }return false;
  }

});
</script>
<!-- 公共尾部 -->
<?php require "../common_html/footer.php";?>