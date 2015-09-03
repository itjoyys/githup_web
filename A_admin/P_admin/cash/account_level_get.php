<?php
include("../../include/config.php");
include("../common/login_check.php");


$u = M('k_user_level',$db_config);
//站点所有层级
switch ($_GET['ltype']) {
  case 'lvset':
    //分层
    if (!empty($_GET['id'])) {
       //不为空
        $lid = $_GET['id'];
        $level_edit = $u->field("id,level_name,level_des")
                      ->where("is_delete = 0 and site_id = '".SITEID."' and id !='".$_GET['id']."'")
                      ->order("id asc")
                      ->select();
        
        foreach ($level_edit as $k => $v) {
          $Levelmain .= '<tr><td align="center"><input type="checkbox" name="level_ids[]" value="'.$v['id'].'" class="idlist"></td>
                <td>'.$v['level_name'].'</td>
                <td>'.$v['level_des'].'</td>
              </tr>';
        }
$level_html = <<<EOF
<form action="account_level_up.php" method="post">
 <input name="level_set" value="user_level" type="hidden">
<input name="level_id_1" id="level_id_1" value="$lid" type="hidden">
<div style="overflow:hidden;overflow-y:auto;height:400px">
<table width="100%" class="m_tab" style="margin:0;width:300px;">
<tbody>
    <tr class="m_title">
      <td height="27" class="table_bg" colspan="3" align="left">
      <span id="title">会员分层</span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
      </td>
    </tr>
    <tr class="m_title">
      <td class="table_bg" width="60">选择层级</td>
      <td class="table_bg">名稱</td>
      <td class="table_bg">描述</td>
    </tr>
    $Levelmain
    <tr align="center">
      <td class="table_bg1" colspan="13"><input value="確定" id="savebtn" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
    <!-- <input type="reset" value="重置" class="button_d"> &nbsp;&nbsp;&nbsp; -->
    <input type="button" value="返回" onclick="easyDialog.close();" class="button_d"></td>
    </tr>
EOF;
    echo $level_html;
    exit; 
    }else{
      echo "数据非法";
    }
    break;
}
?>

