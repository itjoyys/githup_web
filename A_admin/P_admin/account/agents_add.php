<?php  
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/setCenter.class.php");
// var_dump($_GET);
 $u = M('k_user_agent',$db_config);
    //编辑查询
  if($_GET['aid']){
    $agent = $u->where("id='".$_GET['aid']."'")->find();
  }

  $up_agents = $u->where("agent_type = 's_h' and site_id='".SITEID."' and is_demo = '0'")->select();
// p($agent);
// p($up_agents);
    //添加或者编辑处理
  if (!empty($_POST['agent_pwd'])) {
      check_name($_POST['agent_name']);
      check_name($_POST['agent_user']);
        $data['agent_name'] = $_POST['agent_name'];//股东名字
        $data['video_scale'] = $_POST['video_scale'];//视讯占成
        $data['sports_scale'] = $_POST['sports_scale'];//体育占成
        $data['lottery_scale'] = $_POST['lottery_scale'];//彩票占成
        if (strlen($_POST['agent_pwd']) != 6) {
           //表示密码修改
           if ($_POST['agent_pwd'] != $_POST['agent_pwd2']) {
               message('两次密码不对','agents_add.php');
           }else{
               if($_POST['agent_pwd']!='******'||$_POST['agent_pwd2']!='******'){
                check_pass($_POST['agent_pwd']);
                check_pass($_POST['agent_pwd2']);
               $data['agent_pwd'] = md5(md5($_POST['agent_pwd']));
             }
           }
        }
       if (!empty($_POST['id'])) {
        if($data['agent_name']==''){
              message('请完善表单！','agents_add.php?aid='.$_GET['aid']);
            }
           //表示编辑
            if ($u->where("id = '".$_POST['id']."'")->update($data)) {
                   admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"更新了总代理".$agent['agent_user']."的资料");
                  message('更新总代成功','up_agent.php');
              }else{
                message('更新总代失败或未修改！','up_agent.php');
              }
      }else{
          //表示添加
                $data['site_id'] = SITEID;
                if($data['agent_name']==''){
                  message('请完善表单！','agents_add.php');
                }
                $data['pid'] = $_POST['shareholder'];
                $data['agent_user'] = AGENT_PRE.$_POST['agent_user'];//股东账号
                 $data['add_date'] =date('Y-m-d H:i:s');//添加时间
                 $data['agent_company'] =COMPANY_NAME;//所属公司
                 $data['agent_type'] = 'u_a';//表示总代
                 if (empty($_POST['agent_user'])) {
                    message('账号不能为空','agents_add.php');
                 }else{
                    $state = only_f("agent_user",$data['agent_user'],"k_user_agent",$db_config);//判断账号是否唯一
                    if (!$state) {
                        if ($aid=$u->add($data)) {
                           $state = setCenter::agent_setAdd($aid,$data['pid']);
                        if (empty($state)) {
                            $do_log = "设定参数错误,";
                        }
   
                          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"添加了总代理".$data['agent_user']);
                          message('添加总代成功','up_agent.php');
                      }
                     }else{
                          message('已经存在该账号,请使用其它账号');
                     }
                    
                 }
               
        }
  } 

  //站成设定
  $video_scale=0;
  $sports_scale=0;
  $lottery_scale=0;
  $up_agents_1 = $u->where("agent_type = 's_h' and id = '".$agent['pid']."'")->select();

  if(!empty($up_agents_1)){
    $video_scale = $up_agents_1[0]['video_scale'];
    $sports_scale = $up_agents_1[0]['sports_scale'];
    $lottery_scale = $up_agents_1[0]['lottery_scale'];

 
   
  }
    
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
      data: {id:id,action:'zongdai'},  
      success: function(msg){  
        location.href='agents_add.php?video='+msg.video+'&sports='+msg.sports+'&lottery='+msg.lottery+'&id='+id;

      }
                
      });  

    });
  })
  $(document).ready(function(){
  
  var sports_scale='<?=$agent['sports_scale']?>';
  $("#sports_scale option[value='"+sports_scale+"']").attr("selected",true);
  var lottery_scale='<?=$agent['lottery_scale']?>';
  $("#lottery_scale option[value='"+lottery_scale+"']").attr("selected",true);
  var video_scale='<?=$agent['video_scale']?>';
  $("#video_scale option[value='"+video_scale+"']").attr("selected",true);
  
});
</script>
<body> 
<div  id="con_wrap">
  <div  class="input_002">總代理帳號</div>
</div>
</div>
<div  class="content">
  <form  id="myFORM"  name="myFORM"  action="agents_add.php"  method="POST">
  
  <input  name="id" type="hidden" value="<?php echo $agent['id']; ?>">
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>
      <?php if (empty($_GET['aid'])) {
      ?>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed"  width="120">所屬股東：</td>
        <td  width="200">
      <select name="shareholder" errormsg="所屬股東!" datatype="*">
        <option value="">请选择所屬股東</option>
      <?php
        if (!empty($up_agents)) {
           foreach ($up_agents as $key => $row) {
            if(!empty($_GET['id']) && $row['id'] == $_GET['id']){

             echo '<option value="'.$row['id'].'" selected=selected>'.$row['agent_user'].'</option>';
            }else{              

               echo '<option value="'.$row['id'].'">'.$row['agent_user'].'</option>';
              
            }
           }
        }
      ?>  
      </select>
        </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <?php
        }
      ?>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">帳號：</td>
        <td> 
         <?php if (empty($agent['id'])) {
  echo AGENT_PRE."<input name=\"agent_user\" type=\"text\" value=\"\" class=\"za_text\" errormsg=\"帳號只能有數字(0-8)，及英文大小寫字母(a-Z)\" nullmsg=\"請輸入帳號!\"  ajaxurl=\"user_check.php\" datatype=\"s4-8\">";}else{echo $agent['agent_user'];
 
}
          ?>
     </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  value="******" name="agent_pwd" class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD" value="******" name="agent_pwd2"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">總代理名稱：</td>
        <td><input  type="TEXT"  name="agent_name" value="<?php echo $agent['agent_name']; ?>"  datatype="*"  nullmsg="請輸入總代理名稱"  value=""  size="10"  maxlength="10"  class="za_text"></td>
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

   <?php if(empty($_GET['aid'])){
   ?>   
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">體育-總代理占成數：</td>
        <td>

        <select  name="sports_scale" id="sports_scale"  class="za_select">     

        <?php 
        for ($i=0; $i <=$_GET['sports']*10  ; $i+=0.5) { 
         
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
          } ?>       
          </select>

          </td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">彩票-總代理占成數：</td>
        <td><select  name="lottery_scale"  id="lottery_scale"  class="za_select">
           <?php 
        for ($i=0; $i <=$_GET['lottery']*10  ; $i+=0.5) { 
         
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
        
          } ?>       
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>

      <tr  class="m_bc_ed">
        <td  class="m_co_ed">視訊-總代理占成數：</td>
        <td><select  name="video_scale" id="video_scale"  class="za_select">
          <?php 
        for ($i=0; $i <=$_GET['video']*10  ; $i+=0.5) { 
         
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
        
          } ?> 
        
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
<?php }else{ ?>
        <tr  class="m_bc_ed">
        <td  class="m_co_ed">體育-代理占成數：</td>
        <td><select  name="sports_scale" id="sports_scale"  class="za_select">       
                 
        <?php 
        for ($i=0; $i <=number_format($sports_scale,2)*10  ; $i+=0.5) { 
         
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
        
          } ?>       
                  
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">彩票-代理占成數：</td>
        <td><select  name="lottery_scale"  id="lottery_scale"  class="za_select">
       
        <?php 
        for ($i=0; $i <=number_format($lottery_scale,2)*10  ; $i+=0.5) { 
         
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
        
          } ?>       
          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>


      <tr  class="m_bc_ed">
        <td  class="m_co_ed">視訊-代理占成數：</td>
        <td><select  name="video_scale" id="video_scale"  class="za_select">         
        <?php
        for ($i=0; $i <=number_format($video_scale,2)*10  ; $i+=0.5) { 
        echo "<option  value=\"".number_format(($i*0.1),2)."\" >".$i."成</option>";
        
          } ?>      

          </select></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
<?php } ?>


      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td"><input  type="SUBMIT"  name="saveBtn"  value="確定"  class="za_button">
          &nbsp;&nbsp;&nbsp;
          <a href="shareholder_index.php"><input  type="button"  id="FormsButton2"  name="FormsButton2"  value="取消"   class="za_button"></a></td>
      </tr>
    </tbody></table>
  </form>
</div>
  <table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  style="clear:both">
    <tbody><tr>
      <td  align="center"  height="50">版權所有 利博國際 建議您以 IE 8.0 1024 X 768 以上高彩模式瀏覽本站  </td>
    </tr>
  </tbody></table>

<script>

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
        if(confirm("是否確定寫入总代理帳號?")) {
          form.get(0).submit();
      }return false;
    }
  });
  
});

</script>
<!-- <script  src="js/yhinput.js"  type="text/javascript"></script> -->
</body></html>