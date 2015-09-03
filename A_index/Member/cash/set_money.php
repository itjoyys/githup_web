<?
    include_once("../../include/config.php");
    include_once("../../common/login_check.php");
    include_once("../../class/user.php");

    $map['site_id']=SITEID;
    if($_SESSION['uid']){
        $map['uid']=$_SESSION['uid'];
        $user=M('k_user',$db_config)->field("pay_num")->where($map)->find();
    }

    $userinfo=user::getinfo($_SESSION["uid"]);
    $level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$userinfo['level_id']."'")  ->find();
    $aud = M('k_cash_config_view',$db_config)
        ->where("id = '".$level_des['RMB_pay_set']."'")
        ->find();

    if($userinfo['shiwan'] == 1){
        echo "<script>";
        echo 'alert("试玩账号不能存取款，请注册正式账号！")';
        echo "</script>";
        exit();
    }

//读取文案
$deposit = M('info_deposit_use',$db_config)
           ->where("site_id = '".SITEID."' and state = 1")
           ->order('sort DESC')->select();
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
    </head>
    <style type="text/css">
       .pagelink{
          text-decoration: underline;
       }
    </style>
    <body style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;">
        <div id="MAMain">
            <div id="MACenter-content">
                <div id="MACenterContent">
                    <div id="MNav"> <a  target="k_memr" href="zr_money.php" class="mbtn">额度转换</a>
                        <div class="navSeparate"></div>
                        <span class="mbtn">线上存款</span>
                        <div class="navSeparate"></div>
                        <a target="k_memr" href="get_money.php" class="mbtn">线上取款</a> 
                    </div>
                    <div style="margin-top: 8px;overflow-y:scroll; height:370px">
  <table class="MMain MNoBorder">
     <tbody>
      <?php foreach ($deposit as $k => $v): 
         if ($v['type'] == 1) {
             $deposit_url = 'hk_money_online.php';
         }elseif ($v['type'] == 2) {
             $deposit_url = 'bank.php';
         }
      ?>
      <tr>
        <td nowrap="" class="">
          <a class="pagelink" href="javascript:void(0);" onclick="open_bw('<?=$deposit_url?>');"><?=$v['title']?></a>
        </td>
         </tr>
         <tr>
           <td class="MNotice">
                <div id="notices">
                 <div><?=$v['content']?></div>
                                    </div>
                                </td>
                            </tr>
     <?php endforeach ?>
                           
                            <tr>
                              
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<style>
.input_bg{background:url(../public/images/input_bg.png) no-repeat;width:83px;height:27px;display:block;line-height:27px;text-decoration:none;text-align:center;color:#fff;margin:-5px 0 0 10px;}
.input_bg:hover{color:#f00;}
TABLE.MMain TD A{float:left;}
</style>

<script>
    function open_bw(url){
        window.open(url,'newwindow','height=580,width=800,top=20,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no')
    }
</script>
