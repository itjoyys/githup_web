<?php  
include_once("../../include/config.php");
include_once("../common/login_check.php");

$u = M('k_user_agent',$db_config);
//编辑查询
if($_GET['aid']){
   $agent = $u->where("id='".$_GET['aid']."'")->find();
}

//读取上级
if ($_GET['atype'] == 's_h') {
   $tit = '總代';
}elseif ($_GET['atype'] == 'u_a') {
   $tit = '代理';
}

//占成设定
if (isset($_POST['act']) && $_POST['act'] == 'scale' && !empty($_POST['id'])) {
    $dataA['video_scale'] = $_POST['video_scale'];
    $dataA['sports_scale'] = $_POST['sports_scale'];
    $dataA['lottery_scale'] = $_POST['lottery_scale'];

    $state = $u->where("id = '".$_POST['id']."'")->update($dataA);
    if ($state) {
        $do_log = '设定代理占成:'.$_POST['agent_user'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
        message("设定代理占成参数成功");
    }else{
        $do_log = '设定代理占成失败:'.$_POST['agent_user'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
        message("设定代理占成参数失败");
    }
}

?>

<?php require("../common_html/header.php");?>
<script>
  $(function(){
      var id = <?=$agent['pid']?>;
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

  })

</script>
<body> 
<div  id="con_wrap">
  <div  class="input_002">代理帳號</div>
</div>
</div>
<div  class="content">
  <form  id="myFORM"  name="myFORM"  action="agent_add_do.php"  method="POST">
    <input  name="atype" type="hidden" value="<?=$_GET['atype']?>">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">帳號：</td>
        <td><?=$agent['agent_user'];?>
     </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  value="******" name="agent_pwd" class="za_text"></td>
        <td><div  class="Validform_checktip">*长度(6-12位)</div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD" value="******" name="agent_pwd2"  class="za_text"></td>
        <td><div  class="Validform_checktip">*长度(6-12位)</div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">代理名稱：</td>
        <td><input  type="text"  name="agent_name" value="<?=$agent['agent_name']?>"  nullmsg="請輸入總代理名稱" class="za_text"></td>
        <td><div  class="Validform_checktip">*</div></td>
      </tr>
</tbody></table>
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
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
           <?=$agent['sports_scale']*100?>%
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">彩票-代理占成數：</td>
        <td><?=$agent['lottery_scale']*100?>%</td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">視訊-代理占成數：</td>
        <td><?=$agent['video_scale']*100?>%</td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>

      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td">
          <input  name="agent_user" type="hidden" value="<?=$agent['agent_user']?>">
         <input  name="id" type="hidden" value="<?=$_GET['aid']?>">
        <input  type="SUBMIT"  name="saveBtn"  value="確定"  class="za_button">
       </td>
      </tr>
    </tbody>
    </table>
    </form>


    <form  id="scale"  name="myFORM"  action=""  method="POST">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">占成预設</td>
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
         <input  name="act" type="hidden" value="scale">
         <input  name="agent_user" type="hidden" value="<?=$agent['agent_user']?>">
         <input  name="id" type="hidden" value="<?=$agent['id']?>">
        <input  type="SUBMIT"  name="saveBtn"  value="確定"  class="za_button"></td>
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

$("#scale").Validform({
tiptype:2,
callback:function(form){
  if(confirm("是否確定寫入代理占成设定?")) {
    form.get(0).submit();
    }return false;
  }

});
</script>
</body></html>