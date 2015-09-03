<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/Level.class.php");

$table = M('k_user_video_set',$db_config);
$data1 = $table->where("site_id = '".SITEID."'")->order("min ASC")->select();
$data = array();
//数组变形
foreach ($data1 as $k => $v) {
  if($v['type'] == 'OG'){
    $data['OG'][] = $v;
  }elseif ($v['type'] == 'AG') {
   $data['AG'][] = $v;
  }
}


$user_table  =  M('k_user',$db_config);
$agent_table =  M('k_user_agent',$db_config);
$agent = $agent_table->field("*")->select();
if($_GET['uid']>0 && $_GET['aid']>0){
  //会员入口
  $user_table  =  M('k_user',$db_config);

  $id_column = 'uid';
  $ids = $_GET['uid'];
  $user_video = $user_table->field("og_limit,ag_limit")->where($id_column." = ".$_GET['uid'])->find();
  $og_limit = $user_video['og_limit'];
  $ag_limit = $user_video['ag_limit'];

  if(empty($og_limit)){
    $og_limit = $agent_table->field("og_limit")->where("id = ".$_GET['aid'])->find();
    $og_limit = $og_limit['og_limit'];
  }
  if(empty($ag_limit)){
    $ag_limit = $agent_table->field("ag_limit")->where("id = ".$_GET['aid'])->find();
    $ag_limit = $ag_limit['ag_limit'];
  }

}elseif($_GET['aid']>0){
  //代理以上入口
  $user_table  =  M('k_user_agent',$db_config);

  $id_column = 'id';
  $ids = $_GET['aid'];
  $user_video = $user_table->field("og_limit,ag_limit")->where($id_column." = ".$_GET['uid'])->find();
  $og_limit = $user_video['og_limit'];
  $ag_limit = $user_video['ag_limit'];


}
$levels =  Level::getParents($agent,$_GET['aid']);
//如果没有设置限红 递归查上级
for ($i=1; $i <=2 ; $i++) {
  switch ($i) {
    case 1:
      $limit_x = 'og_limit';
      break;
    case 2:
      $limit_x = 'ag_limit';
      break;
  }

  if(empty($$limit_x)){
    $user_video = $agent_table->field($limit_x)->where("agent_user = '".$levels['u_a']."'")->find();//查总代
    $$limit_x = $user_video[$limit_x];

    if(empty($$limit_x)){
      $user_video = $agent_table->field($limit_x)->where("agent_user = '".$levels['s_h']."'")->find();//查股东
      $$limit_x = $user_video[$limit_x];

    }
  }
}

//修改
if(!empty($_POST['type']) && $_POST['type']=='update_og'){
  $value=array();
  $value['og_limit'] = $_POST['video_radio'][0];
  $edit = $user_table->where($id_column." = ".$ids)->update($value);
  if($edit>0){
    $do_log = '成功编辑视讯og设定:'.$id_column.'='.$ids;
    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
    message("修改成功");
  }else{
    $do_log = '失败编辑视讯og设定:'.$id_column.'='.$ids;
    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
    message("修改失败");
  }

}
if(!empty($_POST['type']) && $_POST['type']=='update_ag'){
  $value=array();
  $value['ag_limit'] = $_POST['video_radio'][0];
  $edit = $user_table->where($id_column." = ".$ids)->update($value);
  if($edit>0){
    $do_log = '成功编辑视讯ag设定:'.$id_column.'='.$ids;
    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
    message("修改成功");
  }else{
    $do_log = '失败编辑视讯ag设定:'.$id_column.'='.$ids;
    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
    message("修改失败");
  }
}

$typeC = 'video';
?>
<?php require("../common_html/header.php");?>
<style type="text/css">
	#rs_window{
		width:380px;
		left: 50%;
		margin-left: -190px;
		position: fixed;
		top: 180px;
		display: none;
	}
  .m_tab{
    width: 60%;
  }
</style>
<script type="text/javascript">

   function show_win(title,wb,sfm,snm,min,id,type_id,aid){
      var title = '请输入'+title+'设定';
      $("#id").val(id);
      $("#aid").val(aid);
      $("#charges_a").val(wb);//退水
      $("#single_field_max").val(sfm);
      $("#single_note_max").val(snm);
      $("#min").val(min);
      $("#r_title").html(title);
      $("#type_id").val(type_id);//玩法类别id
      $("#rs_window").css("display","block");
   }

           //下拉选项自动跳转
$(document).ready(function(){
   $("#stype").change(function(){
       var cp_url=$("#stype").val();
       window.location.href=cp_url;
   });

 // 单项设置弹窗关闭
  	$("#set_close").click(function(){
		$("#rs_window").css("display","none");
	});
})
</script>
<body>
<div  id="con_wrap">
<div  class="input_002">設定-视讯</div>
<div  class="con_menu">
設定類型：<select  name="stype" id="stype">
			<option  value="user_set.php?aid=<?=$_GET['aid']?>&uid=<?=$_GET["uid"]?>" <?php select_ed($typeC,'sport,');?>>體育</option>
			<option  value="user_fc_set.php?aid=<?=$_GET['aid']?>&uid=<?=$_GET["uid"]?>" <?php select_ed($typeC,'fc');?>>彩票</option>
      <option value="user_video_set.php?aid=<?=$_GET['aid']?>&uid=<?=$_GET["uid"]?>" <?php select_ed($typeC,'video');?>>视讯</option>
		</select>
</div>
</div>
<div class="content">

<!-- og -->
<form action="#?aid=<?=$_GET['aid']?>&uid=<?=$_GET["uid"]?>" method="post">
  <input type="hidden" id="type"  name="type"  value="update_og">
<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab" style="width:500px;">
<tbody>
  <tr class="m_title_over_co">
    <td>OG</td>
    <td>ID</td>
    <td>下限</td>
    <td>上限</td>
    <td>筹码组</td>
  </tr>
<?php foreach ($data['OG'] as $key => $value) { ?>
  <tr>
    <td style="width:20px;">
      <input type="radio" name="video_radio[]" value="<?=$value['video_id'] ?>" id="<?=$value['video_id'] ?>" <?php if(($og_limit == $value['video_id']) ||($og_limit ==0 && $value['default'] == 1)){echo "checked='checked'";} ?>>
    </td>
    <td><?=$key+1 ?></td>
    <td><?=$value['min'] ?></td>
    <td><?=$value['max'] ?></td>
    <td><?=$value['bet_arr'] ?></td>
  </tr>
<?php } ?>
<tr>
<td></td>
<td></td> <td></td>
  <td><input type="submit" value="修改"></td>
  <td><input type="reset" value="取消"></td>

</tr>
</tbody>
</table>
</form>

<!-- ag -->
<form action="#?aid=<?=$_GET['aid']?>&uid=<?=$_GET["uid"]?>" method="post">
  <input type="hidden" id="type"  name="type"  value="update_ag">
<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab" style="width:500px;">
<tbody>
  <tr class="m_title_over_co">
    <td>AG</td>
    <td>ID</td>
    <td>下限</td>
    <td>上限</td>

  </tr>
<?php foreach ($data['AG'] as $key => $value) { ?>
  <tr>
    <td style="width:20px;">
      <input type="radio" name="video_radio[]" value="<?=$value['video_id'] ?>" id="<?=$value['video_id'] ?>" <?php if(($ag_limit == $value['video_id']) ||($ag_limit ==0 && $value['default'] == 1)){echo "checked='checked'";} ?>>
    </td>
    <td><?=$value['video_id'] ?></td>
    <td><?=$value['min'] ?></td>
    <td><?=$value['max'] ?></td>

  </tr>
<?php } ?>
<tr>
<td></td>
<td></td>
  <td><input type="submit" value="修改"></td>
  <td><input type="reset" value="取消"></td>

</tr>
</tbody>
</table>
</form>




</div>
<?php require("../common_html/footer.php");?>