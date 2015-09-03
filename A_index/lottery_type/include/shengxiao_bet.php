<?php 






$muname=explode(",",$mu[$t]);

switch ($_GET['class2']){

 case "二肖连中":

switch ($muname[0]){							  

case "鼠":
$r1=1401;
break;
case "虎":
$r1=1402;
break;
case "龙":
$r1=1403;
break;
case "马":
$r1=1404;
break;
case "猴":
$r1=1405;
break;
case "狗":
$r1=1406;
break;
case "牛":
$r1=1407;
break;
case "兔":
$r1=1408;
break;
case "蛇":
$r1=1409;
break;
case "羊":
$r1=1410;
break;
case "鸡":
$r1=1411;
break;
case "猪":
$r1=1412;
break;

}
switch ($muname[1]){							  

case "鼠":
$r2=1401;
break;
case "虎":
$r2=1402;
break;
case "龙":
$r2=1403;
break;
case "马":
$r2=1404;
break;
case "猴":
$r2=1405;
break;
case "狗":
$r2=1406;
break;
case "牛":
$r2=1407;
break;
case "兔":
$r2=1408;
break;
case "蛇":
$r2=1409;
break;
case "羊":
$r2=1410;
break;
case "鸡":
$r2=1411;
break;
case "猪":
$r2=1412;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
if ($rate2<$rate1) $rate1=$rate2;
break;


 case "二肖连不中":

switch ($muname[0]){							  

case "鼠":
$r1=1437;
break;
case "虎":
$r1=1438;
break;
case "龙":
$r1=1439;
break;
case "马":
$r1=1440;
break;
case "猴":
$r1=1441;
break;
case "狗":
$r1=1442;
break;
case "牛":
$r1=1443;
break;
case "兔":
$r1=1444;
break;
case "蛇":
$r1=1445;
break;
case "羊":
$r1=1446;
break;
case "鸡":
$r1=1447;
break;
case "猪":
$r1=1448;
break;

}
switch ($muname[1]){							  

case "鼠":
$r2=1437;
break;
case "虎":
$r2=1438;
break;
case "龙":
$r2=1439;
break;
case "马":
$r2=1440;
break;
case "猴":
$r2=1441;
break;
case "狗":
$r2=1442;
break;
case "牛":
$r2=1443;
break;
case "兔":
$r2=1444;
break;
case "蛇":
$r2=1445;
break;
case "羊":
$r2=1446;
break;
case "鸡":
$r2=1447;
break;
case "猪":
$r2=1448;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
if ($rate2<$rate1) $rate1=$rate2;
break;





 case "三肖连中":

switch ($muname[0]){							  

case "鼠":
$r1=1413;
break;
case "虎":
$r1=1414;
break;
case "龙":
$r1=1415;
break;
case "马":
$r1=1416;
break;
case "猴":
$r1=1417;
break;
case "狗":
$r1=1418;
break;
case "牛":
$r1=1419;
break;
case "兔":
$r1=1420;
break;
case "蛇":
$r1=1421;
break;
case "羊":
$r1=1422;
break;
case "鸡":
$r1=1423;
break;
case "猪":
$r1=1424;
break;

}
switch ($muname[1]){							  
case "鼠":
$r2=1413;
break;
case "虎":
$r2=1414;
break;
case "龙":
$r2=1415;
break;
case "马":
$r2=1416;
break;
case "猴":
$r2=1417;
break;
case "狗":
$r2=1418;
break;
case "牛":
$r2=1419;
break;
case "兔":
$r2=1420;
break;
case "蛇":
$r2=1421;
break;
case "羊":
$r2=1422;
break;
case "鸡":
$r2=1423;
break;
case "猪":
$r2=1424;
break;
}
switch ($muname[2]){							  
case "鼠":
$r3=1413;
break;
case "虎":
$r3=1414;
break;
case "龙":
$r3=1415;
break;
case "马":
$r3=1416;
break;
case "猴":
$r3=1417;
break;
case "狗":
$r3=1418;
break;
case "牛":
$r3=1419;
break;
case "兔":
$r3=1420;
break;
case "蛇":
$r3=1421;
break;
case "羊":
$r3=1422;
break;
case "鸡":
$r3=1423;
break;
case "猪":
$r3=1424;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
break;


 case "三肖连不中":

switch ($muname[0]){							  

case "鼠":
$r1=1449;
break;
case "虎":
$r1=1450;
break;
case "龙":
$r1=1451;
break;
case "马":
$r1=1452;
break;
case "猴":
$r1=1453;
break;
case "狗":
$r1=1454;
break;
case "牛":
$r1=1455;
break;
case "兔":
$r1=1456;
break;
case "蛇":
$r1=1457;
break;
case "羊":
$r1=1458;
break;
case "鸡":
$r1=1459;
break;
case "猪":
$r1=1460;
break;

}
switch ($muname[1]){							  
case "鼠":
$r2=1449;
break;
case "虎":
$r2=1450;
break;
case "龙":
$r2=1451;
break;
case "马":
$r2=1452;
break;
case "猴":
$r2=1453;
break;
case "狗":
$r2=1454;
break;
case "牛":
$r2=1455;
break;
case "兔":
$r2=1456;
break;
case "蛇":
$r2=1457;
break;
case "羊":
$r2=1458;
break;
case "鸡":
$r2=1459;
break;
case "猪":
$r2=1460;
break;
}
switch ($muname[2]){							  
case "鼠":
$r3=1449;
break;
case "虎":
$r3=1450;
break;
case "龙":
$r3=1451;
break;
case "马":
$r3=1452;
break;
case "猴":
$r3=1453;
break;
case "狗":
$r3=1454;
break;
case "牛":
$r3=1455;
break;
case "兔":
$r3=1456;
break;
case "蛇":
$r3=1457;
break;
case "羊":
$r3=1458;
break;
case "鸡":
$r3=1459;
break;
case "猪":
$r3=1460;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
break;


 case "四肖连中":

switch ($muname[0]){							  

case "鼠":
$r1=1425;
break;
case "虎":
$r1=1426;
break;
case "龙":
$r1=1427;
break;
case "马":
$r1=1428;
break;
case "猴":
$r1=1429;
break;
case "狗":
$r1=1430;
break;
case "牛":
$r1=1431;
break;
case "兔":
$r1=1432;
break;
case "蛇":
$r1=1433;
break;
case "羊":
$r1=1434;
break;
case "鸡":
$r1=1435;
break;
case "猪":
$r1=1436;
break;

}
switch ($muname[1]){							  
case "鼠":
$r2=1425;
break;
case "虎":
$r2=1426;
break;
case "龙":
$r2=1427;
break;
case "马":
$r2=1428;
break;
case "猴":
$r2=1429;
break;
case "狗":
$r2=1430;
break;
case "牛":
$r2=1431;
break;
case "兔":
$r2=1432;
break;
case "蛇":
$r2=1433;
break;
case "羊":
$r2=1434;
break;
case "鸡":
$r2=1435;
break;
case "猪":
$r2=1436;
break;
}
switch ($muname[2]){							  
case "鼠":
$r3=1425;
break;
case "虎":
$r3=1426;
break;
case "龙":
$r3=1427;
break;
case "马":
$r3=1428;
break;
case "猴":
$r3=1429;
break;
case "狗":
$r3=1430;
break;
case "牛":
$r3=1431;
break;
case "兔":
$r3=1432;
break;
case "蛇":
$r3=1433;
break;
case "羊":
$r3=1434;
break;
case "鸡":
$r3=1435;
break;
case "猪":
$r3=1436;
break;
}
switch ($muname[3]){							  
case "鼠":
$r4=1425;
break;
case "虎":
$r4=1426;
break;
case "龙":
$r4=1427;
break;
case "马":
$r4=1428;
break;
case "猴":
$r4=1429;
break;
case "狗":
$r4=1430;
break;
case "牛":
$r4=1431;
break;
case "兔":
$r4=1432;
break;
case "蛇":
$r4=1433;
break;
case "羊":
$r4=1434;
break;
case "鸡":
$r4=1435;
break;
case "猪":
$r4=1436;
break;
}

$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
$rate4=ka_bl($r4,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
if ($rate4<$rate1) $rate1=$rate4;
break;

 case "四肖连不中":

switch ($muname[0]){							  

case "鼠":
$r1=1461;
break;
case "虎":
$r1=1462;
break;
case "龙":
$r1=1463;
break;
case "马":
$r1=1464;
break;
case "猴":
$r1=1465;
break;
case "狗":
$r1=1466;
break;
case "牛":
$r1=1467;
break;
case "兔":
$r1=1468;
break;
case "蛇":
$r1=1469;
break;
case "羊":
$r1=1470;
break;
case "鸡":
$r1=1471;
break;
case "猪":
$r1=1472;
break;

}
switch ($muname[1]){							  
case "鼠":
$r2=1461;
break;
case "虎":
$r2=1462;
break;
case "龙":
$r2=1463;
break;
case "马":
$r2=1464;
break;
case "猴":
$r2=1465;
break;
case "狗":
$r2=1466;
break;
case "牛":
$r2=1467;
break;
case "兔":
$r2=1468;
break;
case "蛇":
$r2=1469;
break;
case "羊":
$r2=1470;
break;
case "鸡":
$r2=1471;
break;
case "猪":
$r2=1472;
break;
}
switch ($muname[2]){							  
case "鼠":
$r3=1461;
break;
case "虎":
$r3=1462;
break;
case "龙":
$r3=1463;
break;
case "马":
$r3=1464;
break;
case "猴":
$r3=1465;
break;
case "狗":
$r3=1466;
break;
case "牛":
$r3=1467;
break;
case "兔":
$r3=1468;
break;
case "蛇":
$r3=1469;
break;
case "羊":
$r3=1470;
break;
case "鸡":
$r3=1471;
break;
case "猪":
$r3=1472;
break;
}
switch ($muname[3]){							  
case "鼠":
$r4=1461;
break;
case "虎":
$r4=1462;
break;
case "龙":
$r4=1463;
break;
case "马":
$r4=1464;
break;
case "猴":
$r4=1465;
break;
case "狗":
$r4=1466;
break;
case "牛":
$r4=1467;
break;
case "兔":
$r4=1468;
break;
case "蛇":
$r4=1469;
break;
case "羊":
$r4=1470;
break;
case "鸡":
$r4=1471;
break;
case "猪":
$r4=1472;
break;
}

$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
$rate4=ka_bl($r4,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
if ($rate4<$rate1) $rate1=$rate4;
break;

 case "五肖连中":

switch ($muname[0]){							  

case "鼠":
$r1=1473;
break;
case "虎":
$r1=1474;
break;
case "龙":
$r1=1475;
break;
case "马":
$r1=1476;
break;
case "猴":
$r1=1477;
break;
case "狗":
$r1=1478;
break;
case "牛":
$r1=1479;
break;
case "兔":
$r1=1480;
break;
case "蛇":
$r1=1481;
break;
case "羊":
$r1=1482;
break;
case "鸡":
$r1=1483;
break;
case "猪":
$r1=1484;
break;

}
switch ($muname[1]){							  
case "鼠":
$r2=1473;
break;
case "虎":
$r2=1474;
break;
case "龙":
$r2=1475;
break;
case "马":
$r2=1476;
break;
case "猴":
$r2=1477;
break;
case "狗":
$r2=1478;
break;
case "牛":
$r2=1479;
break;
case "兔":
$r2=1480;
break;
case "蛇":
$r2=1481;
break;
case "羊":
$r2=1482;
break;
case "鸡":
$r2=1483;
break;
case "猪":
$r2=1484;
break;
}
switch ($muname[2]){							  
case "鼠":
$r3=1473;
break;
case "虎":
$r3=1474;
break;
case "龙":
$r3=1475;
break;
case "马":
$r3=1476;
break;
case "猴":
$r3=1477;
break;
case "狗":
$r3=1478;
break;
case "牛":
$r3=1479;
break;
case "兔":
$r3=1480;
break;
case "蛇":
$r3=1481;
break;
case "羊":
$r3=1482;
break;
case "鸡":
$r3=1483;
break;
case "猪":
$r3=1484;
break;
}
switch ($muname[3]){							  
case "鼠":
$r4=1473;
break;
case "虎":
$r4=1474;
break;
case "龙":
$r4=1475;
break;
case "马":
$r4=1476;
break;
case "猴":
$r4=1477;
break;
case "狗":
$r4=1478;
break;
case "牛":
$r4=1479;
break;
case "兔":
$r4=1480;
break;
case "蛇":
$r4=1481;
break;
case "羊":
$r4=1482;
break;
case "鸡":
$r4=1483;
break;
case "猪":
$r4=1484;
break;
}

switch ($muname[4]){							  
case "鼠":
$r5=1473;
break;
case "虎":
$r5=1474;
break;
case "龙":
$r5=1475;
break;
case "马":
$r5=1476;
break;
case "猴":
$r5=1477;
break;
case "狗":
$r5=1478;
break;
case "牛":
$r5=1479;
break;
case "兔":
$r5=1480;
break;
case "蛇":
$r5=1481;
break;
case "羊":
$r5=1482;
break;
case "鸡":
$r5=1483;
break;
case "猪":
$r5=1484;
break;
}





$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
$rate4=ka_bl($r4,"rate");
$rate5=ka_bl($r5,"rate");

if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
if ($rate4<$rate1) $rate1=$rate4;
if ($rate5<$rate1) $rate1=$rate5;
break;

}

switch (ka_memuser("abcd")){							  

	case "A":
$rate5=$rate1;
$Y=1;
break;
	case "B":
$rate5=$rate1-$bmmm;
$Y=4;
	break;
	case "C":
	$Y=5;
$rate5=$rate1-$cmmm;
	break;
	case "D":
	$rate5=$rate1-$dmmm;
$Y=6;
break;
	default:
	$Y=1;
$rate5=$rate1;
break;
}