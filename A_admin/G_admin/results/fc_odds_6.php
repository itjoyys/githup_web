<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/public_config.php");
//廣東快樂十分

$data=M("c_odds_1",$db_config)->limit("9")->select();
// p($data);

 ?>
<?php require("../common_html/header.php");?>
<script src="../public/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="../public/js/oddsajax.js" type="text/javascript"></script>

</head>
<body>
<div  id="con_wrap">
<div class="input_002">廣東快樂十分即時赔率</div>
<div class="con_menu">

    	&nbsp;&nbsp;<select class="za_select" name="temppid" id="temppid" onchange="var jmpURL=this.options[this.selectedIndex].value;if(jmpURL!=''){window.location=jmpURL;}else{this.selectedIndex=0;}">
        <option value="">请选择彩票类型</option>

        <option value="fc_odds_8.php">六合彩</option> 
		    <option value="fc_odds_1.php">福彩3D</option>
	     	<option value="fc_odds_2.php">排列三</option>
        <option value="fc_odds_3.php">重慶時時彩</option>
                <option value="fc_odds_10.php">天津時時彩</option> 
        <option value="fc_odds_11.php">江西時時彩</option> 
        <option value="fc_odds_12.php">新疆時時彩</option>  
        <option value="fc_odds_4.php">北京快乐8</option>
        <option value="fc_odds_5.php">北京PK拾</option>
        <option value="fc_odds_6.php" selected="selected">廣東快樂十分</option>
        <option value="fc_odds_7.php">重慶快樂十分</option>
              <option value="fc_odds_13.php">江苏快3</option> 
        <option value="fc_odds_14.php">吉林快3</option> 
			</select>
 
</div>
<!--
<div class="con_menu">
</div>
-->
</div>
<div class="content" id='1'>
			
      <!-- ------------------------------------------------------------------------ -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第一球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_1">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['0']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['0']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['0']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['0']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['0']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['0']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['0']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['0']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['0']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['0']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['0']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['0']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['0']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['0']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['0']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['0']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['0']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['0']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['0']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['0']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['0']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['0']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['0']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['0']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['0']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['0']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['0']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['0']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['0']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['0']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['0']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['0']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['0']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['0']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['0']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
                <!-- ------------------------------------------------------------------------ -->
           						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第二球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_2">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['1']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['1']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['1']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['1']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['1']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['1']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['1']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['1']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['1']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['1']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['1']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['1']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['1']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['1']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['1']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['1']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['1']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['1']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['1']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['1']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['1']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['1']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['1']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['1']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['1']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['1']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['1']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['1']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['1']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['1']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['1']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['1']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['1']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['1']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['1']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
                          <!-- ------------------------------------------------------------------------ -->
           						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第三球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_3">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['2']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['2']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['2']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['2']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['2']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['2']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['2']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['2']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['2']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['2']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['2']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['2']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['2']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['2']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['2']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['2']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['2']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['2']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['2']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['2']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['2']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['2']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['2']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['2']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['2']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['2']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['2']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['2']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['2']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['2']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['2']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['2']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['2']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['2']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['2']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
                          <!-- ------------------------------------------------------------------------ -->
            						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第四球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_4">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['3']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['3']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['3']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['3']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['3']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['3']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['3']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['3']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['3']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['3']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['3']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['3']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['3']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['3']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['3']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['3']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['3']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['3']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['3']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['3']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['3']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['3']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['3']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['3']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['3']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['3']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['3']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['3']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['3']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['3']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['3']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['3']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['3']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['3']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['3']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
          
                          <!-- ------------------------------------------------------------------------ -->
            						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第五球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_5">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['4']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['4']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['4']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['4']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['4']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['4']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['4']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['4']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['4']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['4']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['4']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['4']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['4']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['4']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['4']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['4']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['4']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['4']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['4']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['4']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['4']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['4']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['4']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['4']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['4']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['4']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['4']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['4']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['4']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['4']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['4']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['4']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['4']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['4']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['4']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
          
                          <!-- ------------------------------------------------------------------------ -->
           						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第六球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_6">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['5']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['5']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['5']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['5']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['5']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['5']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['5']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['5']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['5']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['5']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['5']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['5']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['5']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['5']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['5']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['5']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['5']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['5']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['5']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['5']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['5']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['5']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['5']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['5']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['5']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['5']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['5']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['5']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['5']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['5']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['5']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['5']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['5']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['5']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['5']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
          
                <!-- ------------------------------------------------------------------------ -->
           						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第七球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_7">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['6']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['6']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['6']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['6']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['6']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['6']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['6']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['6']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['6']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['6']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['6']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['6']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['6']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['6']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['6']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['6']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['6']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['6']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['6']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['6']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['6']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['6']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['6']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['6']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['6']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['6']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['6']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['6']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['6']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['6']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['6']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['6']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['6']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['6']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['6']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
                          <!-- ------------------------------------------------------------------------ -->
          						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>第八球 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_8">
			
			
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
		  		   <tr>              
             <td height="32" align="center" class="zdnum">1</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['7']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">2</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['7']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">3</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['7']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">4</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['7']['h4'] ?>
            </td>
							<td height="32" align="center" class="zdnum">5</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['7']['h5'] ?>
            </td>
					</tr>		


          <tr><td height="32" align="center" class="zdnum">6</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['7']['h6'] ?>
             </td>
						<td height="32" align="center" class="zdnum">7</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['7']['h7'] ?>
             </td>
						<td height="32" align="center" class="zdnum">8</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['7']['h8'] ?>
             </td>
						<td height="32" align="center" class="zdnum">9</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['7']['h9'] ?>
             </td>
						<td height="32" align="center" class="zdnum">10</td>
              <td  height="32" align="center" class="zdnum addinput" id="h10" style="color:#f00;"><?=$data['7']['h10'] ?>
           </td>
					</tr>		
          <tr>              
             <td height="32" align="center" class="zdnum">11</td>
              <td height="32" align="center" class="zdnum addinput" id="h11" style="color:#f00;"><?=$data['7']['h11'] ?>
             </td>
              <td height="32" align="center" class="zdnum">12</td>
              <td height="32" align="center" class="zdnum addinput" id="h12" style="color:#f00;"><?=$data['7']['h12'] ?>
             </td>
              <td height="32" align="center" class="zdnum">13</td>
              <td height="32" align="center" class="zdnum addinput" id="h13" style="color:#f00;"><?=$data['7']['h13'] ?>
             </td>
              <td height="32" align="center" class="zdnum">14</td>
              <td height="32" align="center" class="zdnum addinput" id="h14" style="color:#f00;"><?=$data['7']['h14'] ?>
            </td>
              <td height="32" align="center" class="zdnum">15</td>
              <td  height="32" align="center" class="zdnum addinput" id="h15" style="color:#f00;"><?=$data['7']['h15'] ?>
            </td>
          </tr>   
          <tr>              
             <td height="32" align="center" class="zdnum">16</td>
              <td height="32" align="center" class="zdnum addinput" id="h16" style="color:#f00;"><?=$data['7']['h16'] ?>
             </td>
              <td height="32" align="center" class="zdnum">17</td>
              <td height="32" align="center" class="zdnum addinput" id="h17" style="color:#f00;"><?=$data['7']['h17'] ?>
             </td>
              <td height="32" align="center" class="zdnum">18</td>
              <td height="32" align="center" class="zdnum addinput" id="h18" style="color:#f00;"><?=$data['7']['h18'] ?>
             </td>
              <td height="32" align="center" class="zdnum">19</td>
              <td height="32" align="center" class="zdnum addinput" id="h19" style="color:#f00;"><?=$data['7']['h19'] ?>
            </td>
              <td height="32" align="center" class="zdnum">20</td>
              <td  height="32" align="center" class="zdnum addinput" id="h20" style="color:#f00;"><?=$data['7']['h20'] ?>
            </td>
          </tr>   	
          <tr>              
             <td height="32" align="center" class="zdnum">大</td>
              <td height="32" align="center" class="zdnum addinput" id="h21" style="color:#f00;"><?=$data['7']['h21'] ?>
             </td>
              <td height="32" align="center" class="zdnum">小</td>
              <td height="32" align="center" class="zdnum addinput" id="h22" style="color:#f00;"><?=$data['7']['h22'] ?>
             </td>
              <td height="32" align="center" class="zdnum">单</td>
              <td height="32" align="center" class="zdnum addinput" id="h23" style="color:#f00;"><?=$data['7']['h23'] ?>
             </td>
               <td height="32" align="center" class="zdnum">双</td>
              <td height="32" align="center" class="zdnum addinput" id="h24" style="color:#f00;"><?=$data['7']['h24'] ?>
             </td>
               <td height="32" align="center" class="zdnum">尾大</td>
              <td height="32" align="center" class="zdnum addinput" id="h25" style="color:#f00;"><?=$data['7']['h25'] ?>
             </td>
          </tr>   		
           <tr>              
             <td height="32" align="center" class="zdnum">尾小</td>
              <td height="32" align="center" class="zdnum addinput" id="h26" style="color:#f00;"><?=$data['7']['h26'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h27" style="color:#f00;"><?=$data['7']['h27'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h28" style="color:#f00;"><?=$data['7']['h28'] ?>
             </td>
               <td height="32" align="center" class="zdnum">东</td>
              <td height="32" align="center" class="zdnum addinput" id="h29" style="color:#f00;"><?=$data['7']['h29'] ?>
             </td>
               <td height="32" align="center" class="zdnum">南</td>
              <td height="32" align="center" class="zdnum addinput" id="h30" style="color:#f00;"><?=$data['7']['h30'] ?>
             </td>
          </tr>
           <tr>              
             <td height="32" align="center" class="zdnum">西</td>
              <td height="32" align="center" class="zdnum addinput" id="h31" style="color:#f00;"><?=$data['7']['h31'] ?>
             </td>
              <td height="32" align="center" class="zdnum">北</td>
              <td height="32" align="center" class="zdnum addinput" id="h32" style="color:#f00;"><?=$data['7']['h32'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中</td>
              <td height="32" align="center" class="zdnum addinput" id="h33" style="color:#f00;"><?=$data['7']['h33'] ?>
             </td>
               <td height="32" align="center" class="zdnum">发</td>
              <td height="32" align="center" class="zdnum addinput" id="h34" style="color:#f00;"><?=$data['7']['h34'] ?>
             </td>
               <td height="32" align="center" class="zdnum">白</td>
              <td height="32" align="center" class="zdnum addinput" id="h35" style="color:#f00;"><?=$data['7']['h35'] ?>
             </td>
          </tr>				           								
          </tbody></table>
          
        
            <!-- ------------------------------------------------------------------------ -->
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>总和龙虎 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
      <table width="600" border="0" cellspacing="0" class="table_line" height="0" id="ball_9">
      
      
            <tbody><tr class="m_title_over_co">
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
              <td height="28" align="center" nowrap="nowrap" class="table_bg1">號碼</td>
              <td align="center" nowrap="nowrap" class="table_bg1">赔率</td>
    </tr>
             <tr>              
             <td height="32" align="center" class="zdnum">总和大</td>
              <td height="32" align="center" class="zdnum addinput" id="h1" style="color:#f00;"><?=$data['8']['h1'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和小</td>
              <td height="32" align="center" class="zdnum addinput" id="h2" style="color:#f00;"><?=$data['8']['h2'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和单</td>
              <td height="32" align="center" class="zdnum addinput" id="h3" style="color:#f00;"><?=$data['8']['h3'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和双</td>
              <td height="32" align="center" class="zdnum addinput" id="h4" style="color:#f00;"><?=$data['8']['h4'] ?>
            </td>
              <td height="32" align="center" class="zdnum">总和尾大</td>
              <td  height="32" align="center" class="zdnum addinput" id="h5" style="color:#f00;"><?=$data['8']['h5'] ?>
            </td>
          </tr>   


          <tr><td height="32" align="center" class="zdnum">总和尾小</td>
              <td  height="32" align="center" class="zdnum addinput" id="h6" style="color:#f00;"><?=$data['8']['h6'] ?>
             </td>
            <td height="32" align="center" class="zdnum">龙</td>
              <td  height="32" align="center" class="zdnum addinput" id="h7" style="color:#f00;"><?=$data['8']['h7'] ?>
             </td>
            <td height="32" align="center" class="zdnum">虎</td>
              <td  height="32" align="center" class="zdnum addinput" id="h8" style="color:#f00;"><?=$data['8']['h8'] ?>
             </td>
            <td height="32" align="center" class="zdnum">和</td>
              <td height="32" align="center" class="zdnum addinput" id="h9" style="color:#f00;"><?=$data['8']['h9'] ?>
             </td>
           <td colspan="2"></td>
          </tr>                                           
          </tbody></table>



       
        
</div>
		
<script type="text/javascript">
var lx = 1;

function GetData()
{
	$.ajax({
		type:'post',
		data:'qs='+$('#qs').val()+'&zc='+$('#zc').val()+'&ab='+$('#ab').val()+'&lx=' + lx + '&type=0&t='+(new Date()).getTime(),
		dataType:'json',
		url:'../data/server_3d.php?uid='+uid,
		success:GetDataCallBack
	});
	var t=parseFloat($('#tim').val());
	if(t>=1) window.setTimeout("GetData()",t);
}
function GetDataCallBack(r)
{
	try
	{
		var arr=new Array('yzzh','bdw','sdw','gdw');
		for(k=0;k<arr.length;k++){
			var data=r[arr[k]].data;
			var len=data.length;
			for(i=0;i<len;i++)
			{
				var obj=$("#"+arr[k]+"o"+i);
				if(data[i].order!="0/0")
						obj.attr('class','red0');	
				else
						obj.attr('class','');	
				obj.text(data[i].order);	
			}
			$("#"+arr[k]+"_total").text(r[arr[k]].total);	
		}
	}catch(e){
	//	alert(e.message);
	}
}
$(document).ready(function(e) {
	GetData();
});
</script>


<div style="display: none; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; cursor: move; opacity: 0; background: rgb(255, 255, 255);"></div><div class="" style="display: none; position: absolute;"><div class="aui_outer"><table class="aui_border"><tbody><tr><td class="aui_nw"></td><td class="aui_n"></td><td class="aui_ne"></td></tr><tr><td class="aui_w"></td><td class="aui_c"><div class="aui_inner"><table class="aui_dialog"><tbody><tr><td colspan="2" class="aui_header"><div class="aui_titleBar"><div class="aui_title" style="cursor: move;"></div><a class="aui_close" href="javascript:/*artDialog*/;">×</a></div></td></tr><tr><td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: none;"></div></td><td class="aui_main" style="width: auto; height: auto;"><div class="aui_content" style="padding: 20px 25px;"></div></td></tr><tr><td colspan="2" class="aui_footer"><div class="aui_buttons" style="display: none;"></div></td></tr></tbody></table></div></td><td class="aui_e"></td></tr><tr><td class="aui_sw"></td><td class="aui_s"></td><td class="aui_se" style="cursor: se-resize;"></td></tr></tbody></table></div></div><!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>