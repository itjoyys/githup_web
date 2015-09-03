<?php
// p($_GET);
// exit;


//用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
  echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
  echo '<script type="text/javascript">top.location.href="/";</script>';
  exit;
}
$arr_zuhe;
$num_arr_num;
	foreach ($_GET as $k => $v) {
		$class = explode('num', $k);
			if($class[1]!=''){

			$arr_zuhe[]=$v;

		}
	}
$arr_zuhe_num = count($arr_zuhe);
  if($_GET['rtype'] == '三全中'){
    $num_arr_num =  3;

  }elseif($_GET['rtype'] == '三中二'){
    $num_arr_num =  3;

  }elseif($_GET['rtype'] == '二全中'){
    $num_arr_num =  2;

  }elseif($_GET['rtype'] == '二中特'){
    $num_arr_num =  2;

  }elseif($_GET['rtype'] == '特串'){
    $num_arr_num =  2;

  }elseif($_GET['rtype'] == '四中一'){
    $num_arr_num =  4;

  }






// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }

$zs=0;


if ($_GET['pabc']==1 or $_GET['pabc']==2 ){
$n=0;
for ($t=1;$t<=49;$t=$t+1){
if ($_GET['num'.$t]!=""){
$number1.=intval($_GET['num'.$t]).",";
$n=$n+1;
}
}
}



if ($_GET['pabc']==3){

switch ($_GET['pan1']){
case "12":
$n1=4;
$number1="12,24,36,48";
break;
case "11":
$n1=4;
$number1="11,23,35,47";
break;
case "10":
$n1=4;
$number1="10,22,34,46";
break;
case "9":
$n1=4;
$number1="9,21,33,45";
break;
case "8":
$n1=4;
$number1="8,20,32,44";
break;
case "7":
$n1=4;
$number1="7,19,31,43";
break;
case "6":
$n1=4;
$number1="6,18,30,42";
break;
case "5":
$n1=4;
$number1="5,17,29,41";
break;
case "4":
$n1=4;
$number1="4,16,28,40";
break;
case "3":
$n1=4;
$number1="3,15,27,39";
break;
case "2":
$n1=4;
$number1="2,14,26,38";
break;
case "1":
$number1="1,13,25,37,49";

$n1=5;
break;
default:
 break;
}

switch ($_GET['pan2']){
case "12":
$m1=4;
$number2="12,24,36,48";
break;
case "11":
$m1=4;
$number2="11,23,35,47";
break;
case "10":
$m1=4;
$number2="10,22,34,46";
break;
case "9":
$m1=4;
$number2="9,21,33,45";
break;
case "8":
$m1=4;
$number2="8,20,32,44";
break;
case "7":
$m1=4;
$number2="7,19,31,43";
break;
case "6":
$m1=4;
$number2="6,18,30,42";
break;
case "5":
$m1=4;
$number2="5,17,29,41";
break;
case "4":
$m1=4;
$number2="4,16,28,40";
break;
case "3":
$m1=4;
$number2="3,15,27,39";
break;
case "2":
$m1=4;
$number2="2,14,26,38";
break;
case "1":
$number2="1,13,25,37,49";

$m1=5;
break;
 default:
 break;
}
$n=$n1+$m1;
}

if ($_GET['pabc']==5){

switch ($_GET['pan1']){
case "12":
$n1=4;
$number1="12,24,36,48";
break;
case "11":
$n1=4;
$number1="11,23,35,47";
break;
case "10":
$n1=4;
$number1="10,22,34,46";
break;
case "9":
$n1=4;
$number1="9,21,33,45";
break;
case "8":
$n1=4;
$number1="8,20,32,44";
break;
case "7":
$n1=4;
$number1="7,19,31,43";
break;
case "6":
$n1=4;
$number1="6,18,30,42";
break;
case "5":
$n1=4;
$number1="5,17,29,41";
break;
case "4":
$n1=4;
$number1="4,16,28,40";
break;
case "3":
$n1=4;
$number1="3,15,27,39";
break;
case "2":
$n1=4;
$number1="2,14,26,38";
break;
case "1":
$number1="1,13,25,37,49";

$n1=5;
break;
default:
 break;
}

switch ($_GET['pan3']){
case "9":
$m1=5;

$number2="9,19,29,39,49";
 break;
case "8":
$m1=5;

$number2="8,18,28,38,48";
 break;
case "7":
$m1=5;

$number2="7,17,27,37,47";
 break;
case "6":
$m1=5;

$number2="6,16,26,36,46";
 break;
case "5":
$m1=5;

$number2="5,15,25,35,45";
 break;
case "4":
$m1=5;

$number2="4,14,24,34,44";
 break;
case "3":
$m1=5;

$number2="3,13,23,33,43";
 break;
case "2":
$m1=5;

$number2="2,12,22,32,42";
 break;
case "1":
$number2="1,11,21,31,41";
$m1=5;
 break;
case "0":
$number2="10,20,30,40";
$m1=4;
 break;
 default:
 break;
}
$n=$n1+$m1;
}

if ($_GET['pabc']==4){

switch ($_GET['pan3']){
case "9":
$n1=5;

$number1="9,19,29,39,49";
 break;
case "8":
$n1=5;

$number1="8,18,28,38,48";
 break;
case "7":
$n1=5;

$number1="7,17,27,37,47";
 break;
case "6":
$n1=5;

$number1="6,16,26,36,46";
 break;
case "5":
$n1=5;

$number1="5,15,25,35,45";
 break;
case "4":
$n1=5;

$number1="4,14,24,34,44";
 break;
case "3":
$n1=5;

$number1="3,13,23,33,43";
 break;
case "2":
$n1=5;

$number1="2,12,22,32,42";
 break;
case "1":
$number1="1,11,21,31,41";
$n1=5;
 break;
case "0":
$number1="10,20,30,40";
$n1=4;
 break;
 default:
 break;
}


switch ($_GET['pan4']){
case "9":
$m1=5;

$number2="9,19,29,39,49";
 break;
case "8":
$m1=5;

$number2="8,18,28,38,48";
 break;
case "7":
$m1=5;

$number2="7,17,27,37,47";
 break;
case "6":
$m1=5;

$number2="6,16,26,36,46";
 break;
case "5":
$m1=5;
$number2="5,15,25,35,45";
 break;
case "4":
$m1=5;

$number2="4,14,24,34,44";
 break;
case "3":
$m1=5;

$number2="3,13,23,33,43";
 break;
case "2":
$m1=5;

$number2="2,12,22,32,42";
 break;
case "1":
$number2="1,11,21,31,41";
$m1=5;
 break;
case "0":
$number2="10,20,30,40";
$m1=4;
 break;
 default:
 break;
}



$n=$n1+$m1;
}
$number3=$number1;

switch ($_GET['rtype']){


case "三全中":

if ($_GET['pabc']==2){


$mama="1,1,1";
$number2=$_GET['dm1'].",".$_GET['dm2'];
$mu=explode(",",$number1);

for ($t=0;$t<count($mu)-1;$t=$t+1){
$mama.="/".$mu[$t].",".$number2;
}


$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


for ($k=0;$k<=2;$k=$k+1){

for ($t=0;$t<=1;$t=$t+1){

if (intval($un[$t])>intval($un[$t+1])){

$tmp=$un[$t+1];
$un[$t+1]=$un[$t];
$un[$t]=$tmp;
}
}
}
$x=$un[0].",".$un[1].",".$un[2];
$zzz=$zzz."/".$x;
}


}else{



$mu=explode(",",$number1);





$mamama="1,1,1";


for ($f=0;$f<count($mu)-1;$f=$f+1){
$t=0;
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}


$ma=explode("/",$mama);




for ($f=0;$f<count($mu)-1;$f=$f+1){

for ($t=0;$t<count($ma)-1;$t=$t+1){

$ma11=explode(",",$ma[$t]);

if ($ma11[0]!=$mu[$f] and $ma11[1]!=$mu[$f]){
if ($f!=$t and $f<$t){
$mamama=$mamama."#".$ma[$t].",".$mu[$f];
}
}
}
}

$ff=explode("#",$mamama);
for ($p=0;$p<count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
for ($k=0;$k<=2;$k=$k+1){
for ($f=0;$f<=1;$f=$f+1){
if ($un[$f]>$un[$f+1]){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[1].",".$un[2];
}

for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){

}else{
$zzz=$zzz."/".$ff[$f];

}
}
}



break;


case "三中二":


if ($_GET['pabc']==2){


$mama="1,1,1";
$number2=$_GET['dm1'].",".$_GET['dm2'];
$mu=explode(",",$number1);

for ($t=0;$t<count($mu)-1;$t=$t+1){
$mama.="/".$mu[$t].",".$number2;
}


$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


for ($k=0;$k<=2;$k=$k+1){

for ($t=0;$t<=1;$t=$t+1){

if (intval($un[$t])>intval($un[$t+1])){

$tmp=$un[$t+1];
$un[$t+1]=$un[$t];
$un[$t]=$tmp;
}
}
}
$x=$un[0].",".$un[1].",".$un[2];
$zzz=$zzz."/".$x;
}


}else{



$mu=explode(",",$number1);





$mamama="1,1,1";


for ($f=0;$f<count($mu)-1;$f=$f+1){
$t=0;
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}


$ma=explode("/",$mama);




for ($f=0;$f<count($mu)-1;$f=$f+1){

for ($t=0;$t<count($ma)-1;$t=$t+1){

$ma11=explode(",",$ma[$t]);

if ($ma11[0]!=$mu[$f] and $ma11[1]!=$mu[$f]){
if ($f!=$t and $f<$t){
$mamama=$mamama."#".$ma[$t].",".$mu[$f];
}
}
}
}

$ff=explode("#",$mamama);
for ($p=0;$p<count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
for ($k=0;$k<=2;$k=$k+1){
for ($f=0;$f<=1;$f=$f+1){
if ($un[$f]>$un[$f+1]){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[1].",".$un[2];
}

for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){

}else{
$zzz=$zzz."/".$ff[$f];

}
}
}

break;

case "二全中":
if ($_GET['pabc']==1){
$mama="1,1";
$mu=explode(",",$number1);


for ($f=0;$f<count($mu)-1;$f=$f+1){
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}

$ff=explode("/",$mama);

for ($p=0;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
if (intval($un[$f])>intval($un[$f+1])){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


if ($_GET['pabc']==2){
$mama="1,1";
$mu=explode(",",$number1);
$number2=$_GET['dm1'];

for ($f=0;$f<count($mu)-1;$f=$f+1){
$mama=$mama."/".$mu[$f].",".$_GET['dm1'];
}

$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}
$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;

}
}

if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$mu=explode(",",$number1);
$mu1=explode(",",$number2);
$mama="1,1";

for ($f=0;$f<=count($mu)-1;$f=$f+1){
for ($t=0;$t<=count($mu1)-1;$t=$t+1){

if ($mu[$f]!=$mu1[$t]){

$mama=$mama."/".$mu[$f].",".$mu1[$t];
}
}
}


$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


break;

case "二中特":
if ($_GET['pabc']==1){


$mama="1,1";
$mu=explode(",",$number1);


for ($f=0;$f<count($mu)-1;$f=$f+1){
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}

$ff=explode("/",$mama);

for ($p=0;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
if (intval($un[$f])>intval($un[$f+1])){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


if ($_GET['pabc']==2){
$mama="1,1";
$mu=explode(",",$number1);
$number2=$_GET['dm1'];

for ($f=0;$f<count($mu)-1;$f=$f+1){
$mama=$mama."/".$mu[$f].",".$_GET['dm1'];
}

$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}
$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;

}
}

if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$mu=explode(",",$number1);
$mu1=explode(",",$number2);
$mama="1,1";

for ($f=0;$f<=count($mu)-1;$f=$f+1){
for ($t=0;$t<=count($mu1)-1;$t=$t+1){

$mama=$mama."/".$mu[$f].",".$mu1[$t];
}
}

$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


break;
case "特串":



if ($_GET['pabc']==1){


$mama="1,1";
$mu=explode(",",$number1);


for ($f=0;$f<count($mu)-1;$f=$f+1){
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}

$ff=explode("/",$mama);

for ($p=0;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
if (intval($un[$f])>intval($un[$f+1])){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


if ($_GET['pabc']==2){
$mama="1,1";
$mu=explode(",",$number1);
$number2=$_GET['dm1'];

for ($f=0;$f<count($mu)-1;$f=$f+1){
$mama=$mama."/".$mu[$f].",".$_GET['dm1'];
}

$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}
$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;

}
}

if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$mu=explode(",",$number1);
$mu1=explode(",",$number2);
$mama="1,1";

for ($f=0;$f<=count($mu)-1;$f=$f+1){
for ($t=0;$t<=count($mu1)-1;$t=$t+1){

$mama=$mama."/".$mu[$f].",".$mu1[$t];
}
}

$ff=explode("/",$mama);
$zzz="/1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


if ($un[0]>$un[1]){
$tmp=$un[1];
$un[1]=$un[0];
$un[0]=$tmp;
}

$x=$un[0].",".$un[1];
$zzz=$zzz."/".$x;
}
}


break;


case "四中一":

if ($_GET['pabc']==2){

$mama="1,1,1,1";
$number2=$_GET['dm1'].",".$_GET['dm2'];
$mu=explode(",",$number1);

for ($s=0;$s<count($mu)-2;$s=$s+1){
for ($t=$s+1;$t<count($mu)-1;$t=$t+1){
$mama.="/".$mu[$s].",".$mu[$t].",".$number2;
}
}

$ff=explode("/",$mama);
$zzz="/1,1,1,1";
for ($p=1;$p<=count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);


for ($k=0;$k<=3;$k=$k+1){

for ($t=0;$t<=2;$t=$t+1){

if (intval($un[$t])>intval($un[$t+1])){

$tmp=$un[$t+1];
$un[$t+1]=$un[$t];
$un[$t]=$tmp;
}
}
}
$x=$un[0].",".$un[1].",".$un[2].",".$un[3];
$zzz=$zzz."/".$x;
}


}else{

$mu=explode(",",$number1);

$mamama="1,1,1,1";

for ($d=0;$d<count($mu)-1;$d=$d+1){

for ($f=0;$f<count($mu)-1;$f=$f+1){
$t=0;
for ($t=0;$t<count($mu)-1;$t=$t+1){
if ( $d<$f  and $f<$t){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t];
}
}
}
}

$ma=explode("/",$mama);

for ($d=0;$d<count($mu)-1;$d=$d+1){
for ($f=0;$f<count($mu)-1;$f=$f+1){

for ($t=0;$t<count($ma)-1;$t=$t+1){

$ma11=explode(",",$ma[$t]);

if ($ma11[0]!=$mu[$f] and $ma11[1]!=$mu[$f] and $ma11[2]!=$mu[$f] and $ma11[1]!=$mu[$d] and $ma11[2]!=$mu[$d] and $ma11[3]!=$mu[$d]){
if ( $f<$t and $d<$f){
$mamama=$mamama."#".$ma[$t].",".$mu[$f].",".$mu[$d];
}
}
}
}
}

$ff=explode("#",$mamama);
for ($p=0;$p<count($ff);$p=$p+1){

$un=explode(",",$ff[$p]);
for ($k=0;$k<=3;$k=$k+1){
for ($f=0;$f<=2;$f=$f+1){
if ($un[$f]>$un[$f+1]){

$tmp=$un[$f+1];
$un[$f+1]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[1].",".$un[2].",".$un[3];
}

for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){

}else{
$zzz=$zzz."/".$ff[$f];
}
}
}
break;




}

$number1=$number1;


if ($_GET['pabc']==2){

if ($_GET['dm1']!=""){
$number1=$number1.",".$_GET['dm1'];
}
if ($_GET['dm2']!=""){
$number1=$number1.",".$_GET['dm2'];
}
}
if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5 ){
$number1=$number1.",".$number2;
}

switch ($_GET['rtype']){

case "三全中":
$rtype="三全中";
$rate_id=617;
if ($_GET['pabc']==2){
$zs=$n;
}else{
$zs=$n*($n-1)*($n-2)/6;
}
$R=14;
break;

case "四中一":
$rtype="四中一";
$rate_id=808;
if ($_GET['pabc']==2){
$zs=$n*($n-1)/2;
}else{
$zs=$n*($n-1)*($n-2)*($n-3)/24;
}
$R=45;
break;



case "三中二":
$R=15;
$rtype="三中二";

if ($_GET['pabc']==2){

$zs=$n;
}else{
$zs=$n*($n-1)*($n-2)/6;
}
$rate_id=619;
break;
case "二全中":
$R=13;
$rtype="二全中";
if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$zs=$n1*$m1;
}elseif ($_GET['pabc']==2) {
$zs=$n;
}else{
$zs=$n*($n-1)/2;
}

$rate_id=613;
break;
case "二中特":
$R=16;


if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$zs=$n1*$m1;
}elseif ($_GET['pabc']==2) {
$zs=$n;

}else{
$zs=$n*($n-1)/2;
}

$rate_id=615;
$rtype="二中特";
break;
case "特串":
$R=17;

if ($_GET['pabc']==3 or $_GET['pabc']==4 or $_GET['pabc']==5){
$zs=$n1*$m1;
}elseif ($_GET['pabc']==2) {
$zs=$n;

}else{
$zs=$n*($n-1)/2;
}
$rate_id=616;

$rtype="特串";
break;
}

// $sql3 = "select sum(sum_m) as sum_mm from c_odds_7 where kithe='".$Current_Kithe_Num."' and  username='".$_SESSION['kauser']."' and class1='连码' and class2='".$rtype."'  order by id desc";

// $result=$mysqlt->query($sql3);
// $ka_guanuserkk1=$result->fetch_array();
// $sum_money=$ka_guanuserkk1[0];



if ($zs==0){
echo "<script>alert('请选择相应码数!');parent.location.href='main_left.php';</script>";
exit;
}

$XF=21;
//判断是否胆拖
if(!empty($_GET['pabc']) && $_GET['pabc'] == 2){
  $arr_zuhe_1 = array();
  $arr_zuhe[] = $_GET['dm1'];
  $arr_zuhe[] = $_GET['dm2'];
  $arr_zuhe = array_filter($arr_zuhe);
  if(!empty($arr_zuhe)){  //所有组合
    $arr_zuhe_temp=get_zuhe($arr_zuhe,$num_arr_num);
  }

  foreach ($arr_zuhe_temp as $k => $v) {
    if($_GET['dm1'] && $_GET['dm2']){
      if(strpos($v,$_GET['dm1']) && strpos($v,$_GET['dm2'])){

        $arr_zuhe_1[]=$v;
      }
    }else if($_GET['dm1']){
      if(strpos($v,$_GET['dm1'])){

        $arr_zuhe_1[]=$v;
      }
   }else if($_GET['dm2']){
      if(strpos($v,$_GET['dm2'])){
        $arr_zuhe_1[]=$v;
      }
   }
  }

}else if($_GET['pabc'] == 3 || $_GET['pabc'] == 4 || $_GET['pabc'] == 5){
  $arr_zuhe_1 = array();
  $number2_arr = explode(',', $number2);
  $number3_arr = explode(',', $number3);
  foreach ($number2_arr as $k => $v) {
    foreach ($number3_arr as $kk => $vv) {
      $arr_zuhe_1[] = $v.','.$vv;
    }
  }

}else{ //一般下法


  $arr_zuhe_xiao = explode(',', $number1);
  $arr_zuhe_xiao = array_filter($arr_zuhe_xiao);

  if(!empty($arr_zuhe_xiao)){  //所有组合
    $arr_zuhe_1=get_zuhe($arr_zuhe_xiao,$num_arr_num);
  }

}



  $arr_count = count($arr_zuhe_1);

$beted = beted_limit(7,$_GET['class2'],$db_config);
//查询该玩法当前期数已下注金额

$ball_limit_num = $beted['sum(money)'];
?>
<HTML>
<HEAD>


<link rel="stylesheet" href="./public/css/xp.css">
 <link rel="stylesheet" href="./public/css/mem_order_ft.css">
 <link rel="stylesheet" href="./public/css/mem_body_olympics.css" type="text/css">
 <!-- <link rel="stylesheet" href="./public/css/mem_order_sel.css?=" type="text/css"> -->
 <script src="../public/js/jquery-1.8.3.min.js"></script>
 <script>

$(function(){
   var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
     var single_note_max = $("#single_note_max").text();//单注金额上限20000
     var single_note_min = $("#single_note_min").text();
    var ball_limit_num = <?=$ball_limit_num ?>;//已投注的金额
    var zuhe_num =    <?=$arr_count ?>;//组合数
        // alert(ball_limit_num);
     // alert(single_note_max);
       $(".single_note_max").text(single_note_max);
       $(".single_note_min").text(single_note_min);
     $(".single_field_max").text(single_field_max);
	//下单验证
	if($("#gold").val()>0 && $("#bet_val")){
		var zs = $("#zs_div").text();
		var bet_val3=$("#bet_val").text();
		var thi_val=$("#gold").val();
		new_val3 = thi_val*bet_val3;
		$("#total").text(new_val3);
	}
	$("#gold").change(function() {
		var zs = $("#zs_div").text();
		var val2 = $(this).val();
    var val1=val2.replace(/\b(0+)/,"");
		$("#danzhu_money").val(val1);
		if(val1 != ""){
			 var reg = /([`~!@#$%^&*()_+<>?:"{},\/;\'[\]])/ig;
		     var reg1 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;
		     var reg2= /([a-zA-Z]*)/ig;
		     $(this).val(val1.replace(reg, ""));
		     $(this).val(val1.replace(reg1, ""));
		     $(this).val(val1.replace(reg2, ""));
		     val1 = $(this).val();
			if(val1<0 || val1 >parseInt(single_note_max) || val1 < parseInt(single_note_min)){
				alert("金额格式错误或超过上限！");
				$(this).val(0);
				$("#total").text(0);
				$("#pc").text(0);
			}else{
				var bet_val=parseInt($("#bet_val").text());
				var new_val=parseInt(val1*bet_val);
        var all_val=parseInt(val1*zuhe_num);

				if(parseInt(val1) >=parseInt(single_note_min) && parseInt(val1) <=parseInt(single_note_max) && all_val<=parseInt(single_field_max) && (parseInt(all_val)+parseInt(ball_limit_num)) <=parseInt(single_field_max)){
				    new_val+="";
          Math.round(new_val);
            $("#jq").val(parseInt(new_val));
            $("#pc").text(parseInt(zs*val1));
          $("#total").text(parseInt(new_val));
				}else{
          alert("金额格式错误或超过上限！");
          $(this).val(0);
          $("#total").text(0);
          $("#pc").text(0);

				}
			}
		}else{
			$("#total").text('0');
		}
	});


	//点击赔率下注
	$("#order_form").submit(function(event) {
		var val = $("#gold").val();
    if(val=="" || val ==0){
      alert("请填写下注金额！");
        return false;
    }
		var val1 = $("#total").text();
		var msg = "下注金额："+val1+"\n"+"确定进行下注吗？";
		if(isNaN(val) || isNaN(val1)){
			alert("金额错误！");
				return false;
		}
		if(!confirm(msg)){
			return false;
		}else{
			if(isNaN($("#total").text())){
				alert("金额错误！");
				return false;
			}
		}
	});
	//点击下注下注
	$("#order_form_1").submit(function(event) {
		var val = $("#total_sum").text();
		var val1 = $("#total").text();
		var msg = "下注金额："+val1+"\n"+"确定进行下注吗？";
		if(isNaN(val) || isNaN(val1)){
			alert("金额错误！");
				return false;
		}
		if(!confirm(msg)){
			return false;
		}else{
			if(isNaN($("#total").text())){
				alert("金额错误！");
				return false;
			}
		}
	});

})
 </script>
<style type="text/css">
<!--
body,td,th {
	font-size: 9pt;
}
.STYLE3 {color: #FFFFFF}
.STYLE4 {color: #000}
.STYLE2 {}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></HEAD>
<?php if($_GET['show'] ==1){

 ?>
<script>
	window.onload=function(){
		document.getElementById("form1").submit();
	}
</script>

 <?php } ?>
<SCRIPT>



var count_win=false;
function CheckKey(){
if(event.keyCode == 13) return true;
if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function SubChk(){




document.all.btnCancel.disabled = true;
document.all.btnSubmit.disabled = true;
document.LAYOUTFORM.submit();
}
function CountWinGold(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
//document.all.pc.innerHTML=Math.round(document.all.gold.value * document.all.ioradio.value /1000 - document.all.gold.value);
document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
count_win=true;
}
}
function CountWinGold1(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
count_win=true;
}
}
</SCRIPT>

<body>
<div id="zs_div" style="display:none"><?=$arr_count ?></div>
<div id="bet_val" style="display:none"><?=ka_bl($rate_id,"rate")?></div>
<div  style="display:none;">
<table height="13" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><!-- <img src="/images/b002.jpg"> --></td>
        </tr>
      </tbody></table>
<TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=100% border=0>

<tr>
  <td valign="top" class="Left_Place"><TABLE class=t_list cellSpacing=1 cellPadding=0 width=100% border=0>
      <tr>
        <td height="25" colspan="2" align="center" bgcolor="#504a16" style="color:#FFF">确认下注</td>
      </tr>

      <FORM name="LAYOUTFORM"  onSubmit="return false" action="liuhecai.php?action=k_tanlm&class2=<?=$_GET['class2']?>" method="post" id="form1">
<input type="hidden" name="rtype" value="<?=$_GET['rtype'] ?>">
        <tr>
          <td width="35%" colspan="2" height="25" class="t_td_caption_1" align="center" style="LINE-HEIGHT: 22px"><div align="center"><FONT color="#cc0000">连码&nbsp;
          <?=ka_bl($rate_id,"class2")?>
                  <? if (ka_bl($rate_id,"class2")=="三中二" or ka_bl($rate_id,"class2")=="三中二") {
				  echo "之".ka_bl($rate_id,"class3");
				  }

				  ?>
	<?php foreach ($arr_zuhe_1 as  $v) {

      ?>
         <input type="hidden" name="number1[]" value="<?=$v  ?>">


    <?php } ?>
          </FONT> @ <strong><FONT
color="#ff0000"><?=ka_bl($rate_id,"rate")?></FONT></strong> </div>
<?=$number1?></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><div align="center"><strong>组合共&nbsp;<font color=ff0000><?=$arr_count?></font>&nbsp;组</strong></div></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;下注金额:
            <INPUT
name="gold" id= onKeyPress="return CheckKey()"
onkeyup="return CountWinGold()" value="<?=$_GET['jq']?>" size=8 maxLength=8></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;总下注金额: <strong><FONT id="pc1" color="#ff0000"><?=$_GET['jq']*$arr_count?>&nbsp;</FONT></strong></td>
        </tr>
        <TR>
          <TD height="25" colspan="2" bgcolor="#ffffff">&nbsp;单注限额: <span class="single_note_max">20000</span></TD>
        </TR>
        <TR>
          <TD height="25" colspan="2" bgcolor="#ffffff">&nbsp;单项限额: <span class="single_field_max">200000</span></TD>
        </TR>
        <tr>
          <td height="30" colspan="2" align="center" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><INPUT type='hidden' Name=rate_id value='<?=$rate_id?>'>

              <input  class="button_a"  onClick="self.location='liuhecai.php?action=k_lm'" type="button" value="放弃" name="btnCancel" />

            &nbsp;&nbsp;
            <input  class="button_a"   type="submit" value="确定" onclick=SubChk(); name="btnSubmit" />         </td>
        </tr>
        <INPUT type=hidden
value=SP11 name=concede>
        <INPUT type=hidden value='<?=ka_bl($rate_id,"rate")*1000?>' name=ioradio>
        <INPUT type=hidden value='<?=$zs?>' name="ioradio1">


        <INPUT type=hidden value='<?=$number2?>' name="number2">
        <INPUT type=hidden value='<?=$number3?>' name="number3">
        <INPUT type=hidden value='<?=$_GET["danzhu_money"]?>' name="danzhu_money" >
      </FORM>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="3"></td>
      </tr>
    </table></td>
</tr>
</table>
</div>


<!-- 显示下注信息 -->
<?php
if($_GET['show'] != 1){
 ?>
 <form autocomplete="off" name="order_form" id="order_form" method="get" action="liuhecai.php">
<input type="hidden" name="show" value="1">
<input type="hidden" name="action" value="<?=$_GET['action'] ?>">
<input type="hidden" name="class2" value="<?=$_GET['class2'] ?>">
<input type="hidden" name="ids" value="<?=$_GET['ids'] ?>">
<input type="hidden" name="rtype" value="<?=$_GET['rtype'] ?>">
<input type="hidden" name="xiazhu_money" value="<?=$_GET['ids'] ?>">
<input type="hidden" name="jq" id="jq" value="">
<input type="hidden" name="rrtype" value="<?=$_GET['rrtype'] ?>">
<input type="hidden" name="pabc" value="<?=$_GET['pabc'] ?>">
<input type="hidden" name="pabc2" value="<?=$_GET['pabc2'] ?>">
<input type="hidden" name="pan1" value="<?=$_GET['pan1'] ?>">
<input type="hidden" name="pan2" value="<?=$_GET['pan2'] ?>">
<input type="hidden" name="pan3" value="<?=$_GET['pan3'] ?>">
<input type="hidden" name="pan4" value="<?=$_GET['pan4'] ?>">
<input type="hidden" name="dm1" value="<?=$_GET['dm1'] ?>">
<input type="hidden" name="dm2" value="<?=$_GET['dm2'] ?>">
<input type="hidden" name="danzhu_money" value="" id="danzhu_money">
	<?php
foreach ($_GET as $k => $v) {
		$class = explode('num', $k);
			if($class[1]!=''){

			echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';

		}
	}
	 ?>



<div class="ord">
		<div class="title"><h1>连码-<?=$_GET['rtype']  ?></h1></div>
		<div class="main">

		  <p class="team" style="text-align:center">
			<?php foreach ($arr_zuhe_1 as  $v) {

			?>


				 <?=$v ?> @ <font color="#ff0000"><?=ka_bl($rate_id,"rate")?></font><br>

		<?php } ?>
		  -----------------------<br>组合 共 <font color="red"><?=$arr_count?></font> 组</p>
		  <p class="error" style="display: none;"></p>
		  <div class="betdata">
			  <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10" value="<?=$_GET['jq']?>"></p>
			  <p class="mayWin"><span class="bet_txt">总下注金额：</span><font id="pc"><?=$_GET['jq']*$arr_count?></font></p>
             <!--  <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="total"><?=$_GET['jq']*$zs?></font></p> -->
			  <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">20000</span></p>
			 <p class="maxBet"><span class="bet_txt">单注限额：<span class="single_note_max">20000</span></span></p>
        <p class="maxBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">200000</span></span></p>
		</div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="window.parent.showOrder();" class="no">
			<input type="submit" name="btnSubmit" value="确定交易"  class="yes">
		</div>
	</div>
	</form>
	<?php } ?>
</BODY></HTML>
