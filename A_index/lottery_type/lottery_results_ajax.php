<?php
include_once ("../include/config.php");
include ("../include/public_config.php");


$type = $_GET['type'];
$num = $_GET['num'] ? $_GET['num'] : 0;
$getqishu = $_GET['qishu'];
if ($type) {
    $qishu = M('c_auto_' . $type, $db_config);
    $date = $qishu->field("*")
        ->order("datetime desc")
        ->find();

    if ($getqishu == $date['qishu']) {
        echo 1;
    } else {
        $echo = '<ul id ="lottery"
				style="padding: 3px 0 0 0; margin: 0px; list-style: none; float: right">
                <li style="float: left; height: 26px; line-height: 26px">第 <b><font
									class="colorDate">' . $date['qishu'] . '</font></b> 期开奖结果：
							</li>';
        if($type == 8){
            $jianju = " style='margin: 0px -1px;'";
        }
        for ($i = 1; $i <= $num; $i ++) {
            $echo .= '<li align="right" class="kjjg_li" ' .$jianju . '>' . $date['ball_' . $i] . '</li>';
        }
        $echo .="</ul>";
        echo $echo;
    }
}



$usermoney = $_GET['money'];
if($usermoney){
    $db_config['dbname']  = 'cyj_private';
    $user_money = M('k_user', $db_config)->where("username = '" . $_SESSION['username'] . "' and site_id ='".SITEID."'")
    ->field('money')
    ->find();

        echo $user_money['money'];

}