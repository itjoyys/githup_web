<?
include_once("../include/config.php");
include("../include/private_config.php");
include_once("../lib/class/model.class.php");
$d = M('k_bet', $db_config)->join('join k_user on k_user.uid = k_bet.uid')->where("k_user.uid = '" . $_SESSION['uid'] . "' and k_bet.status=0")->order('k_bet.bid DESC')->limit(10)->select();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>最近十笔交易</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="public/css/mem_order_ft.css?=29" type="text/css">
    <style type="text/css">

        html {
            margin: 0px;
            padding: 0px;
        }

        .his_div {
            border: #cea438 solid 1px;
            background: #f0db9c;
            padding: 5px;
            margin-bottom: 3px;
        }

        .his_l1 {
            font-weight: bold;
        }

        .his_l3 {
            color: #d9252b;
            line-height: 20px;
        }

        .his_l4 {
            font-weight: bold;
        }

        }
    </style>
    <script language="JavaScript">

        window.onload = function () {
            parent.onloadSet(document.body.scrollWidth, document.body.scrollHeight, "rec_frame");
        }
        function re_load() {
            window.location.href = window.location;
        }

    </script>
</head>
<body id="OHIS">

<div style='display:'>
    <div class="ord" style="height: 150px;">

        <div class="show" style="height:150px; overflow-y: scroll;">
            <?
            if ($d) {
                foreach ($d as $k => $r) {
                    if ($r["status"] == 0) {
                        $js = '未结算';
                    } elseif ($r["status"] == 1) {
                        $js = '<span style="color:#FF0000;">赢</span>';
                    } elseif ($r["status"] == 2) {
                        $js = '<span style="color:#00CC00;">输</span>';
                    } elseif ($r["status"] == 8) {
                        $js = '和局';
                    } elseif ($r["status"] == 3) {
                        $js = '注单无效';
                    } elseif ($r["status"] == 4) {
                        $js = '<span style="color:#FF0000;">赢一半</span>';
                    } elseif ($r["status"] == 5) {
                        $js = '<span style="color:#00CC00;">输一半</span>';
                    } elseif ($r["status"] == 6) {
                        $js = '进球无效';
                    } elseif ($r["status"] == 7) {
                        $js = '红卡取消';
                    }
                    $gqqr='';
                    if($r['ball_sort']=='足球滚球'){
                         $r['lose_ok']==1 ? $gqqr='<span style="color:#00CC00;">[已确认]</span>':$gqqr='<span style="color:#FF0000;">[确认中]</span>';
                    }
                    echo '<div class="his_div">
                    <div class="his_l1">' . $r['ball_sort'] . '</div>
                    <div class="his_l2">' . $r['master_guest'] . '</div>
                    <div class="his_l3">' . $r['bet_info'] . '</div>
                    <div class="his_l4">RMB：' . $r['bet_money'] . ' ' . $js . ' '.$gqqr.'</div>
                    </div>';
                }
            }

            ?>

        </div>
    </div>
</div>

</body>
</html>
