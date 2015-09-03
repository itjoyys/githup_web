<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/public_config.php");
//北京快乐8

$data=M("c_odds_8",$db_config)->select();

 ?>
<?php require("../common_html/header.php");?>
<script src="../public/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="../public/js/oddsajax.js" type="text/javascript"></script>
</head>
<body>
<div  id="con_wrap">
<div class="input_002">北京快乐8即時赔率</div>
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
        <option value="fc_odds_4.php" selected="selected">北京快乐8</option>
        <option value="fc_odds_5.php">北京PK拾</option>
        <option value="fc_odds_6.php">廣東快樂十分</option>
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
<div class="content" id="8">
			
      <!-- ------------------------------------------------------------------------ -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>选一，选二，选三，选四，选五 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
                </tr>
              </tbody></table>
			<table width="600" border="0" cellspacing="0" class="table_line" height="0" id="array">
			
			
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
             <td height="32" align="center" class="zdnum">选一</td>
              <td height="32" align="center" class="addinput" id="ball_1,h1" ><?=$data['0']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">选二</td>
              <td height="32" align="center" class="addinput" id="ball_2,h1" ><?=$data['1']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">选三</td>
              <td height="32" align="center" class="addinput" id="ball_3,h1" ><?=$data['2']['h1'] ?>
             </td>
							<td height="32" align="center" class="zdnum">选四</td>
              <td height="32" align="center" class="addinput" id="ball_4,h1" ><?=$data['3']['h1'] ?>
            </td>
							<td height="32" align="center" class="zdnum">选五</td>
              <td  height="32" align="center" class="addinput" id="ball_5,h1" ><?=$data['4']['h1'] ?>
            </td>
					</tr>		
		  		   <tr>              
             <td height="32" align="center" class="zdnum">选五中四</td>
              <td height="32" align="center" class="addinput" id="ball_5,h2" ><?=$data['4']['h2'] ?>
             </td>
             <td height="32" align="center" class="zdnum">选五中三</td>
              <td height="32" align="center" class="addinput" id="ball_4,h3" ><?=$data['4']['h3'] ?>
             </td>
             							<td height="32" align="center" class="zdnum">选四中三</td>
              <td height="32" align="center" class="addinput" id="ball_4,h2" ><?=$data['3']['h2'] ?>
             </td>
							<td height="32" align="center" class="zdnum">选四中二</td>
              <td height="32" align="center" class="addinput" id="ball_3,h3" ><?=$data['3']['h3'] ?>
             </td>
							<td height="32" align="center" class="zdnum">选三中二</td>
              <td height="32" align="center" class="addinput" id="ball_3,h2" ><?=$data['2']['h2'] ?>
             </td>
					</tr>

      
          </tbody></table>

            


            <!-- ------------------------------------------------------------------------ -->
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>和值 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
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
             <td height="32" align="center" class="zdnum">总和大</td>
              <td height="32" align="center" class="addinput" id="h1" ><?=$data['5']['h1'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和小</td>
              <td height="32" align="center" class="addinput" id="h2" ><?=$data['5']['h2'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和单</td>
              <td height="32" align="center" class="addinput" id="h3" ><?=$data['5']['h3'] ?>
             </td>
              <td height="32" align="center" class="zdnum">总和双</td>
              <td height="32" align="center" class="addinput" id="h4" ><?=$data['5']['h4'] ?>
            </td>
               <td height="32" align="center" class="zdnum">总和810</td>
              <td height="32" align="center" class="addinput" id="h4" ><?=$data['5']['h4'] ?>
            </td>
             
          </tr>   


              
          </tbody></table>


            <!-- ------------------------------------------------------------------------ -->
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>上中下 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
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
             <td height="32" align="center" class="zdnum">上盘</td>
              <td height="32" align="center" class="addinput" id="h1" ><?=$data['6']['h1'] ?>
             </td>
              <td height="32" align="center" class="zdnum">中盘</td>
              <td height="32" align="center" class="addinput" id="h2" ><?=$data['6']['h2'] ?>
             </td>
              <td height="32" align="center" class="zdnum">下盘</td>
              <td height="32" align="center" class="addinput" id="h3" ><?=$data['6']['h3'] ?>
             </td>
              <td colspan="4"></td>
          </tr>   

                                        
          </tbody></table>


            <!-- ------------------------------------------------------------------------ -->
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr class="m_title_over_co">
                  <td> <strong>奇偶和 (<span class="font_s2" id="yzzh_total">0/0</span>)</strong></td>
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
             <td height="32" align="center" class="zdnum">奇盘</td>
              <td height="32" align="center" class="addinput" id="h1" ><?=$data['7']['h1'] ?>
             </td>
              <td height="32" align="center" class="zdnum">和盘</td>
              <td height="32" align="center" class="addinput" id="h2" ><?=$data['7']['h2'] ?>
             </td>
              <td height="32" align="center" class="zdnum">偶盘</td>
              <td height="32" align="center" class="addinput" id="h3" ><?=$data['7']['h3'] ?>
             </td>
            <td colspan="4"></td>
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
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>