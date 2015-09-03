<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/public_config.php");
$data = M("c_odds_7",$db_config)->field("class3,rate")->where("class2 = '特B'")->select();


 ?>
<?php require("../common_html/header.php");?>
<script src="../public/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="../public/js/oddsajax.js" type="text/javascript"></script>
<link  rel="stylesheet"  href="../public/css/xp.css"  type="text/css">
<body>
<div  id="con_wrap">
  <div class="input_002">六合彩</div>
  <div class="con_menu"> 
      &nbsp;&nbsp;<select class="za_select" name="temppid" id="temppid" onchange="var jmpURL=this.options[this.selectedIndex].value;if(jmpURL!=''){window.location=jmpURL;}else{this.selectedIndex=0;}">
    <option value="">请选择彩票类型</option>

        <option value="fc_odds_8.php" selected="selected">六合彩</option> 
        <option value="fc_odds_1.php">福彩3D</option>
        <option value="fc_odds_2.php">排列三</option>
        <option value="fc_odds_3.php">重慶時時彩</option>
                <option value="fc_odds_10.php">天津時時彩</option> 
        <option value="fc_odds_11.php">江西時時彩</option> 
        <option value="fc_odds_12.php">新疆時時彩</option>  
        <option value="fc_odds_4.php">北京快乐8</option>
        <option value="fc_odds_5.php">北京赛车PK拾</option>
        <option value="fc_odds_6.php">廣東快樂十分</option>
        <option value="fc_odds_7.php">重慶快樂十分</option>
                      <option value="fc_odds_13.php">江苏快3</option> 
        <option value="fc_odds_14.php">吉林快3</option>
        
      </select>
   <!--    &nbsp;&nbsp;<select class="za_select" name="qs" id="qs" onchange="GetData()">
      ：
        <option value="2015014">2015014期</option>
          </select>
    &nbsp;&nbsp;占成：
    <select class="za_select" name="zc" id="zc" onchange="GetData()">
        <option value="">全部</option>
        <option value="1">成數</option>
      </select>
  &nbsp;&nbsp;盤類：
    <select class="za_select" name="ab" id="ab" onchange="GetData()">
        <option value="">全部</option>
        <option value="A">A盤</option>
        <option value="B">B盤</option>
        <option value="C">C盤</option>
        <option value="D">D盤</option>
      </select>
  &nbsp;&nbsp;更新：
    <select class="za_select" name="tim" id="tim" onchange="GetData()">
        <option value="0">停止</option>
        <option value="10000">10秒</option>
        <option value="20000">20秒</option>
                <option value="40000" selected="selected">40秒</option>
        <option value="60000">60秒</option>
      </select> -->
 </div>
  <!--  <div class="con_menu">  </div> -->
</div>
<div class="content">
  <table width="800" align="left" style="margin-right:5px" border="0" cellspacing="0" bordercolordark="#F0F0F0" class="table_line" height="162">
  <tbody><tr class="m_title_over_co">
    <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
    <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
    <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
    <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
    <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
    <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
  </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b01" class="ball_r"><?=$data[0]['class3'] ?></td>
    <td align="center" id="tb01" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p01" style="border:0;"><?=$data[0]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p01',0.1,0,59)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'59','1',0)" id="o01" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p01',0.1,0,59)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b11" class="ball_g"><?=$data[10]['class3'] ?></td>
  <td align="center" id="tb11" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p11" style="border:0;"><?=$data[10]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p11',0.1,0,69)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'69','11',0)" id="o11" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p11',0.1,0,69)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b21" class="ball_g"><?=$data[20]['class3'] ?></td>
  <td align="center" id="tb21" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p21" style="border:0;"><?=$data[20]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p21',0.1,0,79)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'79','21',0)" id="o21" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p21',0.1,0,79)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b31" class="ball_b"><?=$data[30]['class3'] ?></td>
  <td align="center" id="tb31" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p31" style="border:0;"><?=$data[30]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p31',0.1,0,89)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'89','31',0)" id="o31" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p31',0.1,0,89)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b41" class="ball_b"><?=$data[40]['class3'] ?></td>
  <td align="center" id="tb41" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p41"><?=$data[40]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p41',0.1,0,99)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'99','41',0)" id="o41" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p41',0.1,0,99)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b02" class="ball_r"><?=$data[1]['class3'] ?></td>
    <td align="center" id="tb02" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p02" style="border:0;"><?=$data[1]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p02',0.1,0,60)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'60','2',0)" id="o02" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p02',0.1,0,60)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b12" class="ball_r"><?=$data[11]['class3'] ?></td>
  <td align="center" id="tb12" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p12" style="border:0;"><?=$data[11]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p12',0.1,0,70)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'70','12',0)" id="o12" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p12',0.1,0,70)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b22" class="ball_g"><?=$data[21]['class3'] ?></td>
  <td align="center" id="tb22" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p22" style="border:0;"><?=$data[21]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p22',0.1,0,80)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'80','22',0)" id="o22" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p22',0.1,0,80)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b32" class="ball_g"><?=$data[31]['class3'] ?></td>
  <td align="center" id="tb32" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p32" style="border:0;"><?=$data[31]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p32',0.1,0,90)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'90','32',0)" id="o32" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p32',0.1,0,90)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b42" class="ball_b"><?=$data[41]['class3'] ?></td>
  <td align="center" id="tb42" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p42"><?=$data[41]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p42',0.1,0,100)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'100','42',0)" id="o42" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p42',0.1,0,100)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b03" class="ball_b"><?=$data[2]['class3'] ?></td>
    <td align="center" id="tb03" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p03" style="border:0;"><?=$data[2]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p03',0.1,0,61)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'61','3',0)" id="o03" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p03',0.1,0,61)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b13" class="ball_r"><?=$data[12]['class3'] ?></td>
  <td align="center" id="tb13" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p13" style="border:0;"><?=$data[12]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p13',0.1,0,71)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'71','13',0)" id="o13" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p13',0.1,0,71)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b23" class="ball_r"><?=$data[22]['class3'] ?></td>
  <td align="center" id="tb23" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p23" style="border:0;"><?=$data[22]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p23',0.1,0,81)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'81','23',0)" id="o23" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p23',0.1,0,81)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b33" class="ball_g"><?=$data[32]['class3'] ?></td>
  <td align="center" id="tb33" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p33" style="border:0;"><?=$data[32]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p33',0.1,0,91)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'91','33',0)" id="o33" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p33',0.1,0,91)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b43" class="ball_g"><?=$data[42]['class3'] ?></td>
  <td align="center" id="tb43" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p43"><?=$data[42]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p43',0.1,0,101)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'101','43',0)" id="o43" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p43',0.1,0,101)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b04" class="ball_b"><?=$data[3]['class3'] ?></td>
    <td align="center" id="tb04" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p04" style="border:0;"><?=$data[3]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p04',0.1,0,62)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'62','4',0)" id="o04" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p04',0.1,0,62)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b14" class="ball_b"><?=$data[13]['class3'] ?></td>
  <td align="center" id="tb14" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p14" style="border:0;"><?=$data[13]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p14',0.1,0,72)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'72','14',0)" id="o14" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p14',0.1,0,72)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b24" class="ball_r"><?=$data[23]['class3'] ?></td>
  <td align="center" id="tb24" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p24" style="border:0;"><?=$data[23]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p24',0.1,0,82)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'82','24',0)" id="o24" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p24',0.1,0,82)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b34" class="ball_r"><?=$data[33]['class3'] ?></td>
  <td align="center" id="tb34" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p34" style="border:0;"><?=$data[33]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p34',0.1,0,92)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'92','34',0)" id="o34" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p34',0.1,0,92)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b44" class="ball_g"><?=$data[43]['class3'] ?></td>
  <td align="center" id="tb44" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p44"><?=$data[43]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p44',0.1,0,102)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'102','44',0)" id="o44" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p44',0.1,0,102)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b05" class="ball_g"><?=$data[4]['class3'] ?></td>
    <td align="center" id="tb05" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p05" style="border:0;"><?=$data[4]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p05',0.1,0,63)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'63','5',0)" id="o05" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p05',0.1,0,63)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b15" class="ball_b"><?=$data[14]['class3'] ?></td>
  <td align="center" id="tb15" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p15" style="border:0;"><?=$data[14]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p15',0.1,0,73)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'73','15',0)" id="o15" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p15',0.1,0,73)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b25" class="ball_b"><?=$data[24]['class3'] ?></td>
  <td align="center" id="tb25" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p25" style="border:0;"><?=$data[24]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p25',0.1,0,83)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'83','25',0)" id="o25" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p25',0.1,0,83)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b35" class="ball_r"><?=$data[34]['class3'] ?></td>
  <td align="center" id="tb35" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p35" style="border:0;"><?=$data[34]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p35',0.1,0,93)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'93','35',0)" id="o35" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p35',0.1,0,93)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b45" class="ball_r"><?=$data[44]['class3'] ?></td>
  <td align="center" id="tb45" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p45"><?=$data[44]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p45',0.1,0,103)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'103','45',0)" id="o45" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p45',0.1,0,103)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b06" class="ball_g"><?=$data[5]['class3'] ?></td>
    <td align="center" id="tb06" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p06" style="border:0;"><?=$data[5]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p06',0.1,0,64)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'64','6',0)" id="o06" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p06',0.1,0,64)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b16" class="ball_g"><?=$data[15]['class3'] ?></td>
  <td align="center" id="tb16" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p16" style="border:0;"><?=$data[15]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p16',0.1,0,74)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'74','16',0)" id="o16" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p16',0.1,0,74)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b26" class="ball_b"><?=$data[25]['class3'] ?></td>
  <td align="center" id="tb26" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p26" style="border:0;"><?=$data[25]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p26',0.1,0,84)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'84','26',0)" id="o26" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p26',0.1,0,84)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b36" class="ball_b"><?=$data[35]['class3'] ?></td>
  <td align="center" id="tb36" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p36" style="border:0;"><?=$data[35]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p36',0.1,0,94)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'94','36',0)" id="o36" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p36',0.1,0,94)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b46" class="ball_r"><?=$data[45]['class3'] ?></td>
  <td align="center" id="tb46" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p46"><?=$data[45]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p46',0.1,0,104)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'104','46',0)" id="o46" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p46',0.1,0,104)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b07" class="ball_r"><?=$data[6]['class3'] ?></td>
    <td align="center" id="tb07" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p07" style="border:0;"><?=$data[6]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p07',0.1,0,65)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'65','7',0)" id="o07" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p07',0.1,0,65)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b17" class="ball_g"><?=$data[16]['class3'] ?></td>
  <td align="center" id="tb17" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p17" style="border:0;"><?=$data[16]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p17',0.1,0,75)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'75','17',0)" id="o17" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p17',0.1,0,75)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b27" class="ball_g"><?=$data[26]['class3'] ?></td>
  <td align="center" id="tb27" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p27" style="border:0;"><?=$data[26]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p27',0.1,0,85)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'85','27',0)" id="o27" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p27',0.1,0,85)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b37" class="ball_b"><?=$data[36]['class3'] ?></td>
  <td align="center" id="tb37" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p37" style="border:0;"><?=$data[36]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p37',0.1,0,95)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'95','37',0)" id="o37" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p37',0.1,0,95)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b47" class="ball_b"><?=$data[46]['class3'] ?></td>
  <td align="center" id="tb47" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p47"><?=$data[46]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p47',0.1,0,105)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'105','47',0)" id="o47" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p47',0.1,0,105)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b08" class="ball_r"><?=$data[7]['class3'] ?></td>
    <td align="center" id="tb08" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p08" style="border:0;"><?=$data[7]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p08',0.1,0,66)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'66','8',0)" id="o08" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p08',0.1,0,66)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b18" class="ball_r"><?=$data[17]['class3'] ?></td>
  <td align="center" id="tb18" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p18" style="border:0;"><?=$data[17]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p18',0.1,0,76)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'76','18',0)" id="o18" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p18',0.1,0,76)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b28" class="ball_g"><?=$data[27]['class3'] ?></td>
  <td align="center" id="tb28" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p28" style="border:0;"><?=$data[27]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p28',0.1,0,86)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'86','28',0)" id="o28" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p28',0.1,0,86)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b38" class="ball_g"><?=$data[37]['class3'] ?></td>
  <td align="center" id="tb38" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p38" style="border:0;"><?=$data[37]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p38',0.1,0,96)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'96','38',0)" id="o38" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p38',0.1,0,96)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b48" class="ball_b"><?=$data[47]['class3'] ?></td>
  <td align="center" id="tb48" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p48"><?=$data[47]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p48',0.1,0,106)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'106','48',0)" id="o48" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p48',0.1,0,106)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b09" class="ball_b"><?=$data[8]['class3'] ?></td>
    <td align="center" id="tb09" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p09" style="border:0;"><?=$data[8]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p09',0.1,0,67)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'67','9',0)" id="o09" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p09',0.1,0,67)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b19" class="ball_r"><?=$data[18]['class3'] ?></td>
  <td align="center" id="tb19" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p19" style="border:0;"><?=$data[18]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p19',0.1,0,77)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'77','19',0)" id="o19" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p19',0.1,0,77)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b29" class="ball_r"><?=$data[28]['class3'] ?></td>
  <td align="center" id="tb29" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p29" style="border:0;"><?=$data[28]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p29',0.1,0,87)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'87','29',0)" id="o29" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p29',0.1,0,87)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b39" class="ball_g"><?=$data[38]['class3'] ?></td>
  <td align="center" id="tb39" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p39" style="border:0;"><?=$data[38]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p39',0.1,0,97)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'97','39',0)" id="o39" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p39',0.1,0,97)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td align="center" id="b49" class="ball_g"><?=$data[48]['class3'] ?></td>
  <td align="center" id="tb49" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p49"><?=$data[48]['rate'] ?></td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p49',0.1,0,107)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'107','49',0)" id="o49" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p49',0.1,0,107)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
    </tr>
    
                      
    <tr>
    <td height="32" align="center" id="b10" class="ball_b"><?=$data[9]['class3'] ?></td>
    <td align="center" id="tb10" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p10" style="border:0;"><?=$data[9]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p10',0.1,0,68)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'68','10',0)" id="o10" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p10',0.1,0,68)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b20" class="ball_b"><?=$data[19]['class3'] ?></td>
  <td align="center" id="tb20" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p20" style="border:0;"><?=$data[19]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p20',0.1,0,78)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'78','20',0)" id="o20" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p20',0.1,0,78)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b30" class="ball_r"><?=$data[29]['class3'] ?></td>
  <td align="center" id="tb30" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td id="p30" style="border:0;"><?=$data[29]['rate'] ?></td>
    <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p30',0.1,0,88)" class="red1">-</a></td>
  </tr>
  <tr>
    <td onclick="OpenDetail($('#qs').val(),0,'88','30',0)" id="o30" class="">0/0</td>
    <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p30',0.1,0,88)" class="gree1">+</a></td>
  </tr>
  </tbody></table>
  </td>
    <td align="center" id="b40" class="ball_r"><?=$data[39]['class3'] ?></td>
  <td align="center" id="tb40" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
      <tbody><tr>
        <td id="p40" style="border:0;"><?=$data[39]['rate'] ?></td>
        <td style="display:none" width="15px"><a href="javascript:void(0);" onclick="updateRate('jian','p40',0.1,0,98)" class="red1">-</a></td>
      </tr>
      <tr>
        <td onclick="OpenDetail($('#qs').val(),0,'98','40',0)" id="o40" class="">0/0</td>
        <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p40',0.1,0,98)" class="gree1">+</a></td>
      </tr>
    </tbody></table></td>
      <td style="cursor:pointer;font-size:15px" align="center"><b>AB总</b></td>
  <td align="center" id="oall" style="font-weight:bold">0/0</td>
    </tr>
    <tr>
    <td height="37" colspan="5" align="left" nowrap="nowrap" class="table_bg4">&nbsp;&nbsp;
      <select name="tm" id="tm" onchange="GetData()" class="za_select">
        <option value="b">特碼B</option>
      </select></td>
    <form name="xeform" style="margin:0px;padding:0px" method="post" target="fFrame" action="./../admin/main.php?uid=ba9b4025dd39e62da35601b370a2&amp;langx=zh-tw&amp;action=order_tm&amp;act=savexe"></form>
      <td colspan="3" class="table_bg4" style="padding-top:5px"><input style="width:100px" class="input1" type="text" name="xe" id="xe" value="3000" onkeydown="return Yh_Text.CheckNumber2(this)">
        &nbsp;&nbsp;
        <input type="submit" name="saveBtn" id="saveBtn" value="特別號警示" class="button_e"></td>
    
    <td colspan="2" class="table_bg4"><input type="button" name="outBtn" onclick="window.open('../pub/out.php?qs='+$('#qs').val()+'&amp;lx=0')" id="outBtn" value="導出本期注單" class="button_e"></td>
  </tr>
    </tbody></table>
  <table width="15%" align="left" style="margin-right:5px" border="0" cellspacing="0" bordercolordark="#F0F0F0" class="table_line">
    <tbody><tr class="m_title_over_co">
      <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
      <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','单',1.93,'0',13);" align="center"><b>单</b></td>
      <td align="center" id="tb50" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p50" style="border:0;"><?=$data[49]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p50',0.1,0,50)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'50','单',0)" id="o50" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p50',0.1,0,50)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','双',1.93,'0',13);" align="center"><b>双</b></td>
      <td align="center" id="tb51" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p51" style="border:0;"><?=$data[50]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p51',0.1,0,51)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'51','双',0)" id="o51" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p51',0.1,0,51)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','大',1.93,'0',13);" align="center"><b>大</b></td>
      <td align="center" id="tb52" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p52" style="border:0;"><?=$data[51]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p52',0.1,0,52)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'52','大',0)" id="o52" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p52',0.1,0,52)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','小',1.93,'0',13);" align="center"><b>小</b></td>
      <td align="center" id="tb53" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p53" style="border:0;"><?=$data[52]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p53',0.1,0,53)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'53','小',0)" id="o53" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p53',0.1,0,53)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','合单',1.93,'0',13);" align="center"><b>合单</b></td>
      <td align="center" id="tb54" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p54" style="border:0;"><?=$data[53]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p54',0.1,0,54)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'54','合单',0)" id="o54" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p54',0.1,0,54)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','合双',1.93,'0',13);" align="center"><b>合双</b></td>
      <td align="center" id="tb55" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p55" style="border:0;"><?=$data[54]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p55',0.1,0,55)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'55','合双',0)" id="o55" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p55',0.1,0,55)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','红波',2.75,'0',13);" align="center"><b>红波</b></td>
      <td align="center" id="tb56" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p56" style="border:0;"><?=$data[55]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p56',0.1,0,56)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'56','红波',0)" id="o56" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p56',0.1,0,56)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','绿波',2.85,'0',13);" align="center"><b>绿波</b></td>
      <td align="center" id="tb57" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p57" style="border:0;"><?=$data[56]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p57',0.1,0,57)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'57','绿波',0)" id="o57" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p57',0.1,0,57)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','蓝波',2.85,'0',13);" align="center"><b>蓝波</b></td>
      <td align="center" id="tb58" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p58" style="border:0;"><?=$data[57]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p58',0.1,0,58)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'58','蓝波',0)" id="o58" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p58',0.1,0,58)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','家禽',1.93,'0',13);" align="center"><b>家禽</b></td>
      <td align="center" id="tb59" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p59" style="border:0;"><?=$data[58]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p59',0.1,0,748)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'748','家禽',0)" id="o59" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p59',0.1,0,748)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','野兽',1.93,'0',13);" align="center"><b>野兽</b></td>
      <td align="center" id="tb60" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p60" style="border:0;"><?=$data[59]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p60',0.1,0,749)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'749','野兽',0)" id="o60" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p60',0.1,0,749)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','合大',1.93,'0',13);" align="center"><b>合大</b></td>
      <td align="center" id="tb61" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p61" style="border:0;"></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p61',0.1,0,2523)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2523','合大',0)" id="o61" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p61',0.1,0,2523)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','合小',1.93,'0',13);" align="center"><b>合小</b></td>
      <td align="center" id="tb62" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p62" style="border:0;"></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p62',0.1,0,2524)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2524','合小',0)" id="o62" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p62',0.1,0,2524)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','尾大',1.93,'0',13);" align="center"><b>尾大</b></td>
      <td align="center" id="tb63" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p63" style="border:0;"><?=$data[60]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p63',0.1,0,2711)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2711','尾大',0)" id="o63" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p63',0.1,0,2711)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','尾小',1.93,'0',13);" align="center"><b>尾小</b></td>
      <td align="center" id="tb64" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p64" style="border:0;"><?=$data[61]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p64',0.1,0,2712)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2712','尾小',0)" id="o64" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p64',0.1,0,2712)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','大单',3.80,'0',13);" align="center"><b>大单</b></td>
      <td align="center" id="tb65" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p65" style="border:0;"><?=$data[62]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p65',0.1,0,2713)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2713','大单',0)" id="o65" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p65',0.1,0,2713)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','小单',3.80,'0',13);" align="center"><b>小单</b></td>
      <td align="center" id="tb66" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p66" style="border:0;"><?=$data[63]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p66',0.1,0,2714)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2714','小单',0)" id="o66" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p66',0.1,0,2714)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','大双',3.80,'0',13);" align="center"><b>大双</b></td>
      <td align="center" id="tb67" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p67" style="border:0;"><?=$data[64]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p67',0.1,0,2715)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2715','大双',0)" id="o67" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p67',0.1,0,2715)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
            <tr>
      <td height="32" style="cursor:pointer" onclick="return OpenZf('特码','特A','小双',3.80,'0',13);" align="center"><b>小双</b></td>
      <td align="center" id="tb68" class="td0"><table border="0" style="text-align:center" class="clearTable" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            <td id="p68" style="border:0;"><?=$data[65]['rate'] ?></td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jian','p68',0.1,0,2716)" class="red1">-</a></td>
          </tr>
          <tr>
            <td onclick="OpenDetail($('#qs').val(),0,'2716','小双',0)" id="o68" class="">0/0</td>
            <td style="display:none"><a href="javascript:void(0);" onclick="updateRate('jia','p68',0.1,0,2716)" class="gree1">+</a></td>
          </tr>
        </tbody></table></td>
    </tr>
          </tbody></table>
 <!--  <div id="order_hot"><table width="300" align="left" border="0" cellspacing="0" bordercolordark="#F0F0F0" class="table_line"><tbody>
  <tr class="m_title_over_co"> <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
      <td align="center" nowrap="nowrap" class="table_bg1">賠率</td>
      <td align="center" nowrap="nowrap" class="table_bg1">注單</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>01</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>02</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>03</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>04</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>05</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>06</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>07</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>08</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>09</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>10</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>11</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>12</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>13</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>14</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>15</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>16</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>17</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>18</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>19</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>20</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>21</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>22</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>23</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>24</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>25</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>26</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>27</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>28</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>29</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>30</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>31</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>32</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>33</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>34</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>35</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>36</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>37</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>38</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>39</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>40</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>41</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>42</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>43</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>44</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>45</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_r"><b>46</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>47</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_b"><b>48</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr><tr><td height="25" align="center" class="ball_g"><b>49</b></td>
    <td align="center">——————</td>
    <td align="center">0</td>
    </tr></tbody></table></div>
</div> -->
<script type="text/javascript">
var lang_number = "號碼";
var lang_rate = "賠率";
var lang_ozd = "注單";
var lang_fxxs = "風險系數";
var uid = "ba9b4025dd39e62da35601b370a2";

  function GetData()
  {
    $.ajax({
      type:'post',
      data:'qs='+$('#qs').val()+'&zc='+$('#zc').val()+'&ab='+$('#ab').val()+'&tm='+$('#tm').val()+'&t='+(new Date()).getTime(),
      dataType:'json',
      url:'../data/server_tm.php?uid='+uid,
      success:GetDataCallBack
    });
    var t=parseFloat($('#tim').val());
    if(t>=1) window.setTimeout("GetData()",t);
  }
  function GetDataCallBack(r)
  {
    var rs;
    try
    {
      var result=r.data;
      for(i=1;i<=result.length;i++)
      {
        rs=result[i-1];
        num=(i<=9?'0':'')+i;
        $('#tb'+num).attr('class','td'+rs.flag);
        //$('#p'+num).text(rs.rate);  
        var obj=$('#o'+num);
        if(rs.order!="0/0")
            obj.attr('class','red0'); 
        else
            obj.attr('class',''); 
        obj.text(rs.order); 
      }
      //alert(r.len);
      var tmp=$('#oall').text();
      if(tmp!=r.total)
        PaySound();
      $('#oall').text(r.total);
      var html='<table width="300" align="left"  border="0" cellspacing="0" bordercolordark="#F0F0F0" class="table_line">'+
              '<tr class="m_title_over_co">'+
              ' <td height="28" align="center" nowrap="nowrap"  class="table_bg1">'+lang_number+'</td>'+
              ' <td align="center" nowrap="nowrap" class="table_bg1">'+lang_rate+'</td>'+
              ' <td align="center" nowrap="nowrap" class="table_bg1">'+lang_ozd+'</td>'+
              '</tr>';
      $('#order_hot').html(html+r.hot+"</table>");
    }catch(e){
      //alert(e.message);
    }
  }
  $(document).ready(function(e) {
        //GetData();
    });
  
</script>
<iframe name="fFrame" id="fFrame" style="display:none"></iframe>

<div style="display: none; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; cursor: move; opacity: 0; background: rgb(255, 255, 255);"></div><div class="" style="display: none; position: absolute;"><div class="aui_outer"><table class="aui_border"><tbody><tr><td class="aui_nw"></td>
    <td class="aui_n"></td>
    <td class="aui_ne"></td>
    </tr><tr><td class="aui_w"></td>
    <td class="aui_c"><div class="aui_inner"><table class="aui_dialog"><tbody><tr><td colspan="2" class="aui_header"><div class="aui_titleBar"><div class="aui_title" style="cursor: move;"></div><a class="aui_close" href="javascript:/*artDialog*/;">×</a></div></td>
    </tr><tr><td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: none;"></div></td>
    <td class="aui_main" style="width: auto; height: auto;"><div class="aui_content" style="padding: 20px 25px;"></div></td>
    </tr><tr><td colspan="2" class="aui_footer"><div class="aui_buttons" style="display: none;"></div></td>
    </tr></tbody></table></div></td>
    <td class="aui_e"></td>
    </tr><tr><td class="aui_sw"></td>
    <td class="aui_s"></td>
    <td class="aui_se" style="cursor: se-resize;"></td>
    </tr></tbody></table></div></div><!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>