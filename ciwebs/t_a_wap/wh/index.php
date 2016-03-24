<?
include_once dirname(__FILE__) . '/../include/site_config.php';
include_once(dirname(__FILE__)."/site_state.php");
$url_=GetSiteStatus($SiteStatus,100,'',1);
$domain=$_SERVER['HTTP_HOST'];
//测试111
$kefu['cun']='http://messenger.providesupport.com/messenger/0g7bvcz4ht7cu1h0yibf6sn3n7.html';
$kefu['dun']='http://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=383500&configID=53288&jid=4093764230';
$kefu['eun']='http://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=525445&configID=53736&jid=4672220706';
$kefu['fun']='http://www.providesupport.com?messenger=05e6niol1dlb506amkw02jw4kd';
$kefu['wun']='http://www.providesupport.com?messenger=09jrgw3fanrx61lo5iwrdqgc78';
$kefu['iun']='http://messenger.providesupport.com/messenger/0awgb1jkqn2ai0eyvr55bbh635.html';
$kefu['oun']='http://messenger.providesupport.com/messenger/1fj5y04l5s4bo1s9hlf0k7n8ur.html';
$kefu['mun']='https://messenger.providesupport.com/messenger/04xx3ksguo72319wt0b5i1ljy0.html';
$kefu['nun']='http://messenger.providesupport.com/messenger/02yf4eznw6stm0t9c05mjyeoa8.html';
$kefu['zun']='http://f88op.live800.com/live800/chatClient/chatbox.jsp?companyID=508223&configID=128224&jid=4269066882';
$kefu['run']='http://messenger.providesupport.com/messenger/00b1rj12je15s1o3ip8k4g6qtt.html';
//$kefu['jun']='http://www.providesupport.com?messenger=16nymfaf0whyu07ss9nddpf78j';
$kefu['aaa']='http://kefu.qycn.com/vclient/chat/?websiteid=107206&amp;clerkid=1201607';
$kefu['aae']='http://www.providesupport.com?messenger=1qchytrubwvsn15n4t97y4nsaz';
$kefu['aad']='http://messenger.providesupport.com/messenger/00zqgaqcez9k90decbt8q74ziy.html';
$kefu['aag']='http://messenger.providesupport.com/messenger/0melfervs4l1e1ec1v0rdnsoay.html';
$kefu['aaf']='http://messenger3.providesupport.com/messenger/1plyxhnoc3fj61meq1qn4dyw8x.html';
$kefu['aai']='http://chat16.live800.com/live800/chatClient/chatbox.jsp?companyID=555884&configID=86057&jid=9846221996';
$kefu['aao']='http://kefu.qycn.com/vclient/chat/?websiteid=108217';

if(SITEID == 'hun'){
    if(INDEX_ID =='a'){
        $kefu1 = 'http://kf1op.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=536522&configID=53867&jid=5564524854';
    }elseif(INDEX_ID =='b'){
        $kefu1 = 'http://kf1op.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=536531&configID=53945&jid=3224622316';
    }elseif(INDEX_ID =='c'){
         $kefu1 = 'http://kf1op.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=536532&configID=53947&jid=8191076567';
    }elseif(INDEX_ID =='d'){
         $kefu1 = 'http://kf1op.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=536533&configID=53967&jid=3953236357';
    }elseif(INDEX_ID =='e'){
         $kefu1 = 'http://kf1op.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=536534&configID=53977&jid=9573653299';
    }elseif(INDEX_ID =='f'){
         $kefu1 = 'http://messenger.providesupport.com/messenger/1k2mfvqzjgpbr0w3f5f7fubp9u.html';
    }elseif(INDEX_ID =='g'){
         $kefu1 = '1';
    }elseif(INDEX_ID =='h'){
         $kefu1 = '1';
    }elseif(INDEX_ID =='i'){
         $kefu1 = '1';
    }elseif(INDEX_ID =='j'){
         $kefu1 = '1';
    }
}elseif(SITEID == 'jun'){
    if(INDEX_ID =='a'){
        $kefu1 = 'http://www.providesupport.com?messenger=16nymfaf0whyu07ss9nddpf78j';
    }elseif(INDEX_ID =='b'){
        $kefu1 = 'http://chat7.livechatvalue.com/chat/chatClient/chatbox.jsp?companyID=130888&configID=44174&jid=6153845713&skillId=2707';
    }
}
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>维护中</title>
<body>
            <?if(@$url_['webhome']!=1){
            ?>
            <link href="images/whCss.css" type="text/css" rel="stylesheet"></head>
<div id="wrap-header">
</div>
<?} ?>
<div id="wrap-content">
	<div class="content-main mCenter">
		<ul class="clearfix">
            <?if(@$url_['webhome']==1){ ?>
<style>
html,body{height:90%;overflow:hidden}
body{margin:0px;background:url("images/bg.png");font-size:12px}
.content{width:95%;height:100%;text-align:center;background:url("images/bg2.png") no-repeat center;vertical-align:middle;}
.css1{height:317px;width:621px;border:1px solid #ff0000;}
.css2{padding-left:200px;height:100px;margin-top:120px}
.loading{padding-left:130px;margin-top:10px;}
.time{padding-left:150px;font-weight:bold;margin-top:10px;height:20px;line-height:20px}
.right{width:525px;text-align:right;height:20px;line-height:20px;margin:0 auto}
.bottom{padding-left:140px;font-weight:bold;margin-top:30px;height:20px;line-height:20px;text-align:center;color:#938888}
.kf{color: #ff0000}
</style>
            <div class="content">
<table border=0 width="100%" height="100%">
<tr>
	<td>
    <div class="css2"><img src="images/0.png" /></div>
    <div class="loading"><img src="images/loading11.gif" /></div>
    <div class="time">維護時間：<font color=red><?=$url_['webhome_msg']?> </font> </div>
    <?php if(SITEID == 'hun'){?>
    <div class="right"><a href="<?=$kefu1?>" target="_blank" class="kf">联系客服</a> <a href="http://<?=$domain?>" >回到首页</a> </div>
    <?php }else{?>
     <div class="right"><a href="<?=$kefu[SITEID]?>" target="_blank" class="kf">联系客服</a> <a href="http://<?=$domain?>" >回到首页</a> </div>
    <?php }?>
  <div class="bottom"> </div>
    </td>
</tr>
</table>
</div>



            <? } else {
                ?>
                <li>
                    <?if(@$url_['sport']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-zq mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['sport_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="http://<?=$domain?>/index.php?a=sports" target="mem_index" class="sub-zq mDisBlock sub-pos"><span></span></a>
                    <?  }?>
                </li>

                <li>
                    <?if(@$url_['lottery']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-cq mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['lottery_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:open_lottery('6')" class="sub-cq mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
                <li>
                    <?if(@$url_['bbin']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-bbin mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['bbin_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=bbin')" class="sub-bbin mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
                <li>
                    <?if(@$url_['lebo']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-lebo mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['lebo_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=lebo')" class="sub-lebo mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
                <li>
                    <?if(@$url_['mg']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-mg mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['mg_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=mg')" class="sub-mg mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>

                <li>
                    <?if(@$url_['ct']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-ct mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['ct_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=ct')" class="sub-ct mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
                <li>
                    <?if(@$url_['ag']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-ag mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['ag_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=ag')" class="sub-ag mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
                <li>
                    <?if(@$url_['og']==1){
                        ?>
                        <a href="javascript:void(0)" class="sub-og mDisBlock sub-pos"><span></span></a>
                        <div class="sub-wh sub-pos">
                            <h3><strong>维护信息：</strong></h3>
                            <p><?=@$url_['og_msg']?><br></p>
                        </div>
                    <?
                    }else{ ?>
                        <a href="javascript:opengeme('../video/games/login.php?g_type=og')" class="sub-og mDisBlock sub-pos"><span></span></a>
                    <?  }?>

                </li>
            <?
            }?>


					</ul>
	</div>
</div>

<div id="wrap-footer">
	<p class="copyRight mCenter">
		<!--Copyright ©   Reserved.-->
	</p>
</div>

<script>
    o_state=1;
    function open_lottery(type){
        var iHeight = window.screen.height;
        // var title=$(this).attr('type');
        var iWidth = window.screen.width-8;
        if (o_state) {
            window.open("../lottery_type/lottery.php?type="+type,"blank",'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width='+iWidth+',height='+iHeight);

        };
    }
    function opengeme(url){
        newWin=window.open(url,'','fullscreen=1,scrollbars=0,location=no');
        window.opener=null;//出掉关闭时候的提示窗口
        window.open('','_self'); //ie7
        window.close();
    }
</script>
</body></html>