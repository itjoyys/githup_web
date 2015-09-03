<?
if (ka_memuser("stat")==1){
echo "<script>alert('对不起,该用户已被禁止!');top.location.href='index.php?action=logout';</script>"; 
exit;}


if($_SESSION['username']){
	

$guanguan1=$_SESSION['username'];
$result = $mysqlt->query("select count(*) from ka_mem  where kauser='".$guanguan1."'  order by id desc");   
//echo "select count(*) from ka_mem  where kauser='".$guanguan1."'  order by id desc";exit;
// $num = mysql_result($result,"0");
// $num = $result['0'];
// if($num!=0){}else{
// //print_r($_SESSION);exit;
//    echo "<script>alert($result);top.location.href='/index.php?action=logout';</script>"; 
//   exit;
// }



$guanguan1=ka_memuser("guan");
 $result=$mysqlt->query("select * from ka_guan where kauser='".$guanguan1."'  order by id"); 
$row=$result->fetch_array();
if ($row['stat']==1){
echo "<script>alert('对不起,该上级用户已被禁止,有问题请联系你上级!');top.location.href='/index.php?action=logout';</script>"; 
exit;
}


$zongzong1=ka_memuser("zong");
 $result=$mysqlt->query("select * from ka_guan where kauser='".$zongzong1."'  order by id"); 
$row=$result->fetch_array();
if ($row['stat']==1){
echo "<script>alert('对不起,该上级用户已被禁止,有问题请联系你上级!');top.location.href='/index.php?action=logout';</script>"; 
exit;}


$dandan1=ka_memuser("dan");
 $result=$mysqlt->query("select * from ka_guan where kauser='".$dandan1."'  order by id"); 
$row=$result->fetch_array();
if ($row['stat']==1){
echo "<script>alert('对不起,该上级用户已被禁止,有问题请联系你上级!');top.location.href='/index.php?action=logout';</script>"; 
exit;}


$result=$mysqlt->query("select * from tj where  username='".$_SESSION['username']."' and ip='".$ip."'  order by id"); 
$row=$result->fetch_array();
if ($row['tr']==1){
// echo "<script>top.location.href='index.php?action=logout';</script>"; 
// exit;
}


$resultff = $mysqlt->query("select * from tj where username='".$_SESSION['username']."' and ip<>'".$ip."'  order by id");   
while($imageff = $resultff->fetch_array()){
$exe=$mysqlt->query("update tj set tr=1 where id='".$imageff['id']."' ");
}


}

?>
