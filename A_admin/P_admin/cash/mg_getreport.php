

<?php
$title = "MG管理";
require "../common_html/header.php";
?>
<body>
<div id="con_wrap">
<div class="input_002">MG管理</div>
<div class="con_menu">
</div>
</div>
<div class="content">
<div style="text-align:center;margin-top: 10px;font-size: 14px;">
<a href="mg_getreport.php?fun=APIGetBetInfoDetailsByAccount" style="color:black">
会员下注明细
</a>&nbsp;|&nbsp;
<a href="mg_getreport.php?fun=CreditTransferByAccount" style="color:black">
会员转账明细
</a>&nbsp;|&nbsp;
<a href="mg_getreport.php?fun=APICasinoProfitByGameTypeReport" style="color:black">
游戏报表
</a>&nbsp;|&nbsp;
<a href="mg_getreport.php?fun=GetAccountDetails" style="color:black">
会员状态（加解锁）
</a>&nbsp;|&nbsp;
<a href="mg_getreport.php?fun=Index" style="color:black">
登陆MG（辅助）
</a>
</div>


<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
</div>

<!-- 公共尾部 -->
<?php require "../common_html/footer.php";?>
