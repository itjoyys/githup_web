<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");

$u = M('k_user_level',$db_config);


$data['site_id'] = SITEID;
$data['level_name'] = $_POST['level_name'];//层级名称
$data['level_des'] = $_POST['level_des'];//层级描述
$data['deposit_num'] = $_POST['deposit_num'];
$data['start_date'] = $_POST['start_date'];//起始时间
$data['end_date'] = $_POST['end_date'];//结束时间
$data['deposit_count'] = $_POST['deposit_count'];
$data['max_deposit_count'] = $_POST['max_deposit_count'];
$data['min_out'] = $_POST['min_out'];
$data['draw_num'] = $_POST['draw_num'];
$data['draw_count'] = $_POST['draw_count'];
$data['max_draw_count'] = $_POST['max_draw_count'];
$data['highest_deposit_count'] = $_POST['highest_deposit_count'];
$data['day_draw_count'] = $_POST['day_draw_count'];
$data['remark'] = $_POST['remark'];

//获取默认人民币设定
$map_L['site_id'] = SITEID;
$map_L['type'] = 1;
$map_L['type_name'] = 'RMB';
$rmbSet = M('k_cash_config',$db_config)->where($map_L)->getField('id');
if (empty($_POST['level_id']) && $_POST['action'] == 'post_action') {
   //为空表示新增
   $data['RMB_pay_set'] = $rmbSet;
  if($u->add($data)){
      message('添加成功','account_level.php');
  }else{
      message('添加失败','account_level.php');
  }
}elseif (!empty($_POST['level_id']) && $_POST['action'] == 'post_action') {
   if($u->where("id = '".$_POST['level_id']."'")->update($data)){
       message('更新成功','account_level.php');
   }else{
       message('更新失败','account_level.php');
   }
}

//编辑
if ($_GET['action'] == 'edit') {
    $edit = $u->where("id = '".$_GET['id']."'")->find();
}else{
   //获取本站点层级名字
    $edit = array();
    $siteLevel = $u->field("level_name")->where("site_id = '".SITEID."'")->order("id desc")->find();
    //添加层级处理
    $b=preg_match_all('/\d+/',$siteLevel['level_name'],$arr);
    $edit['level_name'] = 'A'.substr(strval($arr[0][0]+10001),1,4);
}

?>

<?php require("../common_html/header.php");?>
<body>
<script type="text/javascript">
  $(document).ready(function(){

     $("#submit_a").click(function(){
         var deposit_num = $('input[name=deposit_num]').val();
         var deposit_count = $('input[name=deposit_count]').val();
         var draw_num = $('input[name=draw_num]').val();
         var draw_count = $('input[name=draw_count]').val();
         var highest_deposit_count = $('input[name=highest_deposit_count]').val();
         var lowest_deposit_count = $('input[name=lowest_deposit_count]').val();
         var day_draw_count = $('input[name=day_draw_count]').val();
         if ( deposit_num=='' || deposit_count=='' ||draw_num=='' || draw_count=='' || highest_deposit_count==''||lowest_deposit_count=='' || day_draw_count=='') {
            alert('数据不能为空');
         }else{
            $('#level_form').submit();
         }
     })
  })

</script>
<div  id="con_wrap">
  <div  class="input_002">添加层级</div>
  <div class="con_menu"> 
    <input type="button" value="返回上一頁" onclick="javascript:history.go(-1);" class="button_e">
  </div>
</div>

<div  class="content">
<form  method="post"  action="account_level_add.php" name="withdrawal_form"  id="level_form">
<table  style="width:500px"  class="m_tab">
  <tbody><tr  class="m_title">
    <td  height="25"  align="center"  colspan="2">添加层级</td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1"  width="150">层级名称</td>
    <td>
      <div  style="float:left">
      <input  name="level_name" id="code" readonly="true" type="text" value="<?=$edit['level_name']?>" class="za_text"></div>
      <div  class="Validform_checktip"  style="float:left">固定编号</div>
    </td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1">描述</td>
        <td>
          <div  style="float:left">
          <input  name="level_des"  id="name"  type="text"  value="<?=$edit['level_des']?>"  class="za_text"></div>
          <div  class="Validform_checktip"  style="float:left">选填</div>
        </td>
  </tr>
  <tr>
    <td height="25" align="right" class="table_bg1">會員加入時間</td>
        <td>
          <div style="float:left"><input class="za_text Wdate" style="width:140px;min-width:140px" name="start_date" id="time_start" value="<?=$edit['start_date']?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
          ~
          <input class="za_text Wdate" style="width:140px;min-width:140px" name="end_date" id="time_end" value="<?=$edit['end_date']?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></div>
         <div class="Validform_checktip" style="float:left"></div>
         </td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1">存款次數</td>    
    <td>
      <div  style="float:left">
      <input  class="za_text" nullmsg="請輸入存款次數！"  errormsg="存款次數必須至少1位數字，最多6個字數字"  name="deposit_num"  id="sequence"   value="<?=$edit['deposit_num']?>"></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示无要求</div>
    </td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1">存款总额</td>    
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入存款總額！"  name="deposit_count"  id="amount"  value="<?=$edit['deposit_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示无要求</div>
    </td>
  </tr>

  <tr  id="sp_tr">
    <td  height="25"  align="right"  class="table_bg1">提款次數</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入提款次數！"  errormsg="提款次數必須至少至少1位數字，最多9個字數字"  name="draw_num"  id="out_sequence"  value="<?=$edit['draw_num']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示无权限</div>
    </td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1">提款总額</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入提款總額！"  errormsg="提款總額必須至少1位數字，最多9個字數字"  name="draw_count"  id="out_amount"  value="<?=$edit['draw_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示无权限</div>
    </td>
  </tr>
    <tr>
    <td  height="25"  align="right"  class="table_bg1">单次最大存款</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入最低存款額！"  name="max_deposit_count"  id="min_amount"  value="<?=$edit['max_deposit_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示不限制</div>
    </td>
  </tr> 
      <tr>
    <td  height="25"  align="right"  class="table_bg1">单次最大提款</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入单次最大提款額！"  name="max_draw_count"  id="min_amount"  value="<?=$edit['max_draw_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示不限制</div>
    </td>
  </tr> 
        <tr>
    <td  height="25"  align="right"  class="table_bg1">单次最低提款</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入最低存款額！"  name="min_out"  id="min_amount"  value="<?=$edit['min_out']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示不限制</div>
    </td>
  </tr> 
    <tr>
    <td  height="25"  align="right"  class="table_bg1"  id="up_amount">最高存款额度</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"  nullmsg="請輸入最大存款額度！"  errormsg="最大存款額度必須至少至少1位數字，最多9個字數字"  name="highest_deposit_count"  id="up_amount"  value="<?=$edit['highest_deposit_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示无限制</div>
    </td>
  </tr>

  <tr  id="sp_tr">
    <td  height="25"  align="right"  class="table_bg1">当日提款限额</td>
    <td>
      <div  style="float:left"><input  class="za_text"  datatype="n1-9"    name="day_draw_count"  id="day_limit_online"  value="<?=$edit['day_draw_count']?>" ></div>
      <div  class="Validform_checktip"  style="float:left">必填整数0表示不限制</div>
    </td>
  </tr>
  <tr>
    <td  height="25"  align="right"  class="table_bg1">备注</td>
    <td>
      <div  style="float:left">
      <textarea  name="remark"  id="memo"  style="height:50px;width:400px"  class="za_text">
        <?=$edit['remark']?>
      </textarea></div>
      <div  class="Validform_checktip"  style="float:left"></div>
    </td>
  </tr>
  <tr  align="center">
    <td  colspan="3"  class="table_bg1">
    <input name="level_id" value="<?=$edit['id']?>" type="hidden">
    <input name="action" value="post_action" type="hidden">
    <a href="javascript:void(0);" id="submit_a" class="button_d">確定</a>
  </tr>
</tbody></table> 
</form>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
</body>
</html>