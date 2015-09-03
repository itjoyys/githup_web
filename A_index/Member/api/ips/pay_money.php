<?php 
include_once("../../../include/config.php");
include_once("../../../class/user.php");

//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

$userinfo=user::getinfo($_SESSION["uid"]);

 ?>

<html xmlns="http://www.w3.org/1999/xhtml"><head runat="server">
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
/*Mem2Bank*/
body{
    font-size:12px;
	margin:0 auto;
    width: 100%;
    height: 100%;
    background-color:#004181;
}
.za_button {
    font-family:"Arial"; 
	font-size:12px; 
	height:25px; 
	margin-right:10px;
	padding-right:1px;
	padding-top:1px;
	float:right;
}

/*線上支付完成*/
#mainBody{float:left;width:800px;height:582px;margin:0 auto; background:url(../public/images/bg.jpg) no-repeat top left;}
#main{ float:left;padding:170px 0px 0 200px}
#titTx{ margin-left:170px;line-height:12px;height: 17px;width: 158px;padding-top: 6px;}
#data{ float:left;width:340px;height:180px;padding:30px 50px 0 50px;}
#data .list{ background:url(../public/images/line.gif) bottom repeat-x; height:28px;}
#data .tx1{ float:left;width:100px; text-align:right; line-height:12px; color:#18438E;height: 18px;padding-top: 7px;}
#data .tx2{ float:left;width:195px; text-align:left; line-height:26px; font-family:Verdana, Geneva, sans-serif; font-size:12px; padding-left:5px}
#copyright{ float:left; width:380px; margin:5px 0 0 10px; color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:10px}
</style>
    </head>
    <body>

    	        <div id="mainBody">
            <div id="main">
                <div id="titTx">您的资料如下：</div>
                <div id="data">
	<form id="frm_payment" action="redirect.php" method="post" class="democss" target="_top">  
     
            <input type="hidden" name="Billno" value="<?=$_POST['order_num']?>" />
            <input type="hidden" name="Amount" value="<?=$_POST['s_amount']?>" />



            

            <input type="hidden" name="active" value="go_save">
            <input type="hidden" name="email" value="<?=$_POST['s_eml']  ?>">
            <input type="hidden" name="tel" value="<?=$_POST['s_tel']  ?>">
            <input type="hidden" name="username" value="<?php echo $userinfo["username"]; ?>">
            <input type="hidden" name="order_num" value="<?=$_POST['order_num'] ?>">
                  	  
        <div class="list"><div class="tx1">商家订单号:</div><div class="tx2"><?=$_POST['order_num'] ?></div></div>
        <div class="list"><div class="tx1">会员帐号:</div><div class="tx2"><?php echo $userinfo["username"]; ?></div></div>
        <div class="list"><div class="tx1">额度:</div><div class="tx2"><?=$_POST['s_amount']  ?></div></div>
        <!--<div class="list"><div class="tx1">联络电话:</div><div class="tx2"><?=$_POST['s_tel']  ?></div></div>
        <div class="list"><div class="tx1">E-mail:</div><div class="tx2"><?=$_POST['s_eml']  ?></div></div>-->
        <input type="submit" value="确定送出"  class="za_button">
     </form>

                </div>
                <div id="copyright">Copyright © <?=$copy_right['copy_right']?> Reserved</div>
            </div>
        </div>
        
        <script language="javascript">
        <!--//
            function cancelMouse(){return false;}
            document.oncontextmenu = cancelMouse;
        //-->
        </script>
    
</body></html>