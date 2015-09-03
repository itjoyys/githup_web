
<html lang="en" style="height:426px;">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<script>
  window.onload=function(){
    var a_arr = document.getElementsByTagName("a");
    var i;
    var j;
    for(i=0;i<a_arr.length;i++){
       
      a_arr[i].onclick=function(){
        for(j=0;j<a_arr.length;j++){
          a_arr[j].style.backgroundPosition='left top';
          a_arr[j].style.color='#fff';  
        }
        this.style.backgroundPosition='left bottom';
        this.style.color='#000';        
      }
    }
  }
</script>

<body>
  

<link rel="stylesheet" href="./public/css/index_main.css" />
<div id="MAContent" style="BACKGROUND: url(./public/images/content_bg_1.jpg) repeat-y left top;height:426px;">
    <div id="MALeft">
      <div id="welcome">会员中心</div>
      <div class="sidebar">
        <div id="sidebar-mem">会员专区</div>
        <a target="k_memr" id="memberData" href="account/userinfo.php" <?php if($_GET['url']=='4'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';} ?>>．&nbsp;我的帐户</a> 
		<a target="k_memr" id="bankSavings" href="cash/set_money.php" <?php if($_GET['url']=='1'||$_GET['url']=='2'||$_GET['url']=='3'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';}?>>．&nbsp;银行交易</a> 
		<a target="k_memr" id="betrecord" href="trading_log/record_ds.php" <?php if($_GET['url']=='5'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';} ?> >．&nbsp;交易记录</a> 
    <a target="k_memr" id="betrecord" href="bet/count.php"<?php if($_GET['url']=='6'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';} ?> >．&nbsp;报表统计</a> 
    </div>
      <div class="sidebar">
        <div id="sidebar-msg">信息公告</div>
        <a target="k_memr" id="hotNews" href="notice/news.php">．&nbsp;最新信息</a> 
		<a target="k_memr" id="msg" href="notice/sms.php"<?php if($_GET['url']=='8'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';} ?> >．&nbsp;个人信息</a> 
		<a target="k_memr" id="news" href="notice/sports_news.php" <?php if($_GET['url']=='9'){echo 'style="background-position: left bottom; color: rgb(0, 0, 0);"';} ?>>．&nbsp;游戏公告</a> </div>
    </div>

    </body>
</html>