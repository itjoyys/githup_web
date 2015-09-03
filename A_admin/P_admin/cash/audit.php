<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/audit.class.php");

$audit = A($_GET['id']);
$audit_data = $audit->audit_get($_GET['auditstr']);
$total = $audit_data['fee_all'];
$total = sprintf("%.2f", $total); 

?>

<?php $title="稽核查看"; require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">取款稽核查詢</div>
  <div class="con_menu">
      <a href="javascript:history.go(-1);" style="color:#f00;" onclick="return sure1()" ;=""><button name="" class="button_d">返回上一页</button></a>
  </div>
</div>

<div class="content">
<div style="line-height:25px;font-weight:bold">
<span style="color:#0202FE">账号：<?=$_GET['uname']?><br></span>
<span style="color:#0202FE">稽核扣除：<?=$total?><br></span>
<div style="width: 100%;">
    <?=$audit_data['content']?>
</div>
</div>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>