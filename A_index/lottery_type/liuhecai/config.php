<?

include_once(dirname(__FILE__)."./../../include/public_config.php");
include_once(dirname(__FILE__)."./../include/function.php");
include_once(dirname(__FILE__)."./../include/order_info.php");
//生成订单
// p();
// var_dump($_GET);


///开奖期数


global $Current_Kithe_Num;
$now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));
$data_time = M('c_opentime_7', $db_config)->field("*")->where("ok ='0' and fengpan > '" . $now_time . "'")->order("kaijiang ASC")->find();

$Current_Kithe_Num = BuLings($data_time['qishu']);
   $result=$mysqli->query("Select id,nn,nd,na,n1,n2,n3,n4,n5,n6,lx,kitm,kitm1,kizt,kizt1,kizm,kizm1,kizm6,kizm61,kigg,kigg1,kilm,kilm1,kisx,kisx1,kibb,kibb1,kiws,kiws1,zfb,zfbdate,zfbdate1,best From c_auto_7 where na <> '0' Order By nd Desc LIMIT 1");

 $Current_KitheTable=$result->fetch_array();

//查询当期已经下注的总金额
$now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));
$data_time = $dangqian = M('c_opentime_7', $db_config)->field("*") ->where("ok ='0' and fengpan > '" . $now_time . "'") ->order("kaijiang ASC") ->find();
$qishu_ball = date("Y", strtotime("+12 hours")).BuLings($data_time['qishu']);
$db_config['dbname'] = 'cyj_private';
$data = M('c_bet', $db_config)
  ->field("sum(money)")
  ->where("qishu = '".$qishu_ball."' and mingxi_1 = '".type_action($_GET ['action'])."'")
  ->find();

$ball_limit_num = $data['sum(money)'];
$db_config['dbname'] = 'cyj_public';
   ?>


   </body>
 </html>