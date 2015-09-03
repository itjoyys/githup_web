<?php
include("../../include/config.php");
include("../common/login_check.php");

//标题
$fcTitle = array(
             'fc_3d'=>'福彩3D','pl_3'=>'排列三',
             'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
             'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
             'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
             'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩',
             'jl_k3'=>'吉林快三','js_k3'=>'江苏快三',
             'liuhecai'=>'六合彩'
            );
//站点彩票退水限额
$fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8','bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3','liuhecai');
$Fcgame = M('fc_games_view',$db_config);
$Uagent = M('k_user_agent_fc_set',$db_config);


//单项设置处理
if ($_POST['type'] == 'setOne' && !empty($_POST['id'])) {
   $idS = $_POST['id'];
   $up_id = M('k_user_agent',$db_config)
          ->where("id = '".$_POST['aid']."'")
          ->getField('pid');//上一级id
   unset($_POST['aid']);
   unset($_POST['id']);//去掉不需要的数据
   unset($_POST['type']);
   //上一级数据上限
   $updata = array();
   if (empty($up_id)) {
      $updata = $Uagent->where("is_default = '1' and site_id = '".SITEID."' and type_id = '".$_POST['type_id']."'")
           ->find();
   }else{
      $updata = $Uagent->where("aid = '".$up_id."' and site_id = '".SITEID."' and type_id = '".$_POST['type_id']."'")
           ->find();
   }

   if ($_POST['min'] < $updata['min'] || $_POST['charges_a'] > $updata['charges_a'] || $_POST['single_field_max'] > $updata['single_field_max'] || $_POST['single_note_max'] > $updata['single_note_max']) {
      message("您设定的数据超出上级范围,设置上级数据!");
      exit();
   }
   $spSta = $Uagent->where("id = '".$idS."'")
          ->update($_POST);
   if ($spSta) {
       $do_log = '成功编辑彩票设定:'.$_POST['type_id'];
       admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
       message("修改成功");
   }else{
      $do_log = '失败编辑彩票设定:'.$_POST['type_id'];
      admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
      message("修改失败");
   }
}else{
  foreach ($fcArr as $key => $val) {
   $fcType[$val] = $Fcgame->join("join k_user_agent_fc_set on k_user_agent_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_agent_fc_set.aid = '".$_GET['aid']."'")->select();
}
}

$typeC = 'fc';
?>
<?php $title='限額/退水'; require("../common_html/header.php");?>
<body>
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
 // 单项设置弹窗关闭
    $("#set_close").click(function(){
    $("#rs_window").css("display","none");
  });
})
</script>
<div id="con_wrap">
<div class="input_002">設定-彩票</div>
<div class="con_menu">設定類型：
<select onchange="document.location=this.value" name="stype">
  <option value="agent_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'sp');?>>體育</option>
  <option value="agent_fc_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'fc');?>>彩票</option>
  <option value="user_video_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'video');?>>视讯</option>

</select>
</div>
<div class="content">
<?php foreach ($fcType as $key => $fcval): ?>

<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab">
  <tbody>
  <tr class="m_title_over_co">
    <td height="25"><?=$fcTitle[$key]?> </td>
    <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['name']?></td>
    <?php endforeach ?>
  </tr>
  <tr>
   <td nowrap="" align="center">退水設定</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['charges_a']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單場限額:</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['single_field_max']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單注限額::</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['single_note_max']?></td>
    <?php endforeach ?>
  <tr>
       <tr>
   <td nowrap="" align="center">最低限额::</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['min']?></td>
    <?php endforeach ?>
  <tr>
         <tr>
   <td nowrap="" align="center"><a href=""></a></td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><a href="javascript:void(0)" onclick="show_win('<?=$fcTitle[$key]?>-<?=$v['name']?>','<?=$v['charges_a']?>','<?=$v['single_field_max']?>','<?=$v['single_note_max']?>','<?=$v['min']?>','<?=$v['id']?>','<?=$v['type_id']?>','<?=$v['aid']?>');"> 修改</a></td>
    <?php endforeach ?>
  <tr>
</tbody></table>

<?php endforeach ?>
<br>

<!--弹框-->
<div id="rs_window">
  <form  name="rs_form" action=""  method="POST" >
    <input  type="hidden" id="type"  name="type"  value="setOne">
    <input  type="hidden" id="id"  name="id"  value="">
      <input  type="hidden" id="aid"  name="aid"  value="">
    <input  type="hidden" id="type_id"  name="type_id"  value="">
    <ul class="acc_ul_box"  style="width:355px">
      <li class="acc_li_first">
      <span  class="title_sp"  id="r_title"></span>
        <span  class="x_sp"><a id="set_close" style="display:block;" href="javascript:void(0);"><img src="../public/images/mem_icon.gif" width="16"  height="14"></a></span>
      </li>
      <li>
        <span  class="title_sp_sub">退水設定:</span>
        <span  class="title_sp_sub2">
        <select class="z_select2" id="s_water_break"  name="charges_a">
          <option vaule="0">0</option>
        </select></span>
      </li>
      <li>
        <span  class="title_sp_sub">單場限額:</span>
        <span  class="title_sp_sub2"><input  type="text"  id="single_field_max"  name="single_field_max"  value=""  size="12"  maxlength="10"  class="za_text" >人民幣</span>
      </li>

      <li>
        <span  class="title_sp_sub">單注限額:</span>
        <span  class="title_sp_sub2"><input  type="TEXT"  id="single_note_max"  name="single_note_max"  value=""  size="12"  maxlength="10"  class="za_text">人民幣</span>
      </li>
      <li>
        <span  class="title_sp_sub">最低限額:</span>
        <span  class="title_sp_sub2"><input  type="text"  id="min"  name="min"  value=""  size="12"  maxlength="8"  class="za_text">人民幣</span>
      </li>
      <li  class="acc_li_btnBox">
        <input  type="submit"  value="確定"  class="za_button">
      </li>
    </ul>
  </form>
</div>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
