<?php
include_once("../include/config.php");
include ("../include/public_config.php");

$type = $_GET['type'];
if($type==5){
	$type_name = '福彩3D';
}elseif($type==6){
	$type_name = '排列三';
}
elseif($type==2){
	$type_name = '重庆时时彩';
}
elseif($type==10){
    $type_name = '天津时时彩';
}
elseif($type==11){
    $type_name = '江西时时彩';
}
elseif($type==12){
    $type_name = '新疆时时彩';
}


 ?>


<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>


<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">

<script src="./public/js/jquery-1.8.3.min.js" type="text/javascript"></script>

<script>
var tim_t="天";
var tim_x="小时";
var tim_f="分";
var tim_m="秒";
var uid='16c18abad2f6645aaf220';
var langx='zh-cn';
var lx='5';
top.cgTypebtn="r_class";
/*$("#main").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});

$("#topFrame").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});
$("#right").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});
*/
</script>

</head>
<body marginwidth="1" marginheight="1" id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">
<div id="type_lei" style="display:none;"><?=$type ?></div>
<div id="data" style="display:none;"></div>



<script type="text/javascript">
var type_lei = $("#type_lei").text();


	$.ajax({
		type: "post",
		url: "./show_zoushi_ajax.php",
		async: false,
		dataType: "json",
		data: {type_lei:type_lei},
		success: function(data_arr){
			 data_arr= JSON.stringify(data_arr);

			  data_arr = data_arr.replace(/\",\"/g, '\],\[');
			  data_arr = data_arr.replace(/\"\]/g, '\]\]');
			  data_arr = data_arr.replace(/\[\"/g, '\[\[');

			$("#data").html(data_arr);


		}

	});

var data_str = $("#data").html();
// alert(data_str);

// var data = JSON.parse(data_str);
var data = eval("(" + data_str + ")");  // 转换为json对象
// var data = data_str.parseJSON();
var game = 1;
var type = 'big_small';

$(document).ready(function(){

	var data_str = $("#data").html();

	show_map();
	$(".game_result th").click(function(){
		$(this).siblings().attr('id','');
		$(this).attr('id','choose');
		game = $(this).attr('abbr');
		if(game!=7){
		$("th[name='dragon_tiger']").hide();
		}else{
			$("th[name='dragon_tiger']").show();
			}
		show_map();
	})
	$(".game_result2 th").click(function(){
		$(this).siblings().attr('id','');
		$(this).attr('id','choose');
		type = $(this).attr('abbr');
		show_map();
	})
});
function show_map(){
	for (i=0;i<4;i++){
		for (j=0;j<44;j++){
			//alert("item_"+j+'_'+i);
			$('#item_'+j+'_'+i).html("&nbsp");
			//$('#item_'+j+'_'+i).attr("color","#58adff");
		}
	}
	if(type=='dragon_tiger'){
		if(data[game]['dragon_tiger']){
			$.each(data[game]['dragon_tiger'], function(j,rs){
				$.each(rs, function(i,item){
					//alert(item);
					var val = '';
					var color = '';
					if(item==1){
						val = '龙';
						color = '#58adff';
					}else if(item==2){
						val = '虎';
						color = '#e70f0f';
					}else{
						val = '和';
						color = '#FFD400';
					}
					$('#item_'+j+'_'+i).html(val);
					$('#item_'+j+'_'+i).attr("color",color);
				});
			});
		}
	}else{
		if(data[game][type]){
			$.each(data[game][type], function(j,rs){
				$.each(rs, function(i,item){
					//alert(item);
					var val = '';
					var color = '';
					if(type == 'big_small'){
						if(item==1){
							val = '大';
							color = '#58adff';
						}else{
							val = '小';
							color = '#e70f0f';
						}
					}else{
						if(item==1){
							val = '单';
							color = '#58adff';
						}else{
							val = '双';
							color = '#e70f0f';
						}
					}
					$('#item_'+j+'_'+i).html(val);
					$('#item_'+j+'_'+i).attr("color",color);
				});
			});
		}
	}
}

</script>

<div align="center">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="game_table">
  <tbody><tr class="hset">
    <td class="tbtitle4" align="left"><span class="STYLE1"><b><?=$type_name ?>开奖路图</b></span></td>
  </tr>
  <!--tr class="hset">
	<td class="tbtitle">
		<table>
			<form  method="post" name="regstep1" id="regstep1">
				<tr>
				<td colspan="2" align="center" nowrap="nowrap"></td>
				<td align="center" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td></td>
                <td width="80" align="center"></td>
                <td>　</td>
              </tr>
          </table></td>
        </tr>
      </form>
	  </table>
	</td>
  </tr-->
</tbody></table>


 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="game_tab">
	<tbody><tr class="game_result">
		<th abbr="1" id="choose">第一球</th>
		<th abbr="2">第二球</th>
		<th abbr="3">第三球</th>
	<?php if($type==2||$type==10||$type==11||$type==12){
		 ?>
		 <th abbr="4">第四球</th>
		 <th abbr="5">第五球</th>
	<?php } ?>
		<th abbr="7">總和,龍虎</th>

	</tr>
	</tbody></table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="game_tab">
	<tbody><tr class="game_result2">
		<th abbr="big_small" id="choose">大小</th>
		<th abbr="odd_even">单双</th>
		<th abbr="dragon_tiger" name="dragon_tiger" style="display:none">龙虎和</th>
	</tr>
</tbody></table>
<table id="map" width="100%" border="0" cellspacing="0" cellpadding="0" class="resultLoad">
	<tbody>



	<tr class="resultLoad">
						<td>
			<font id="item_0_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_1_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_2_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_3_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_4_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_5_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_6_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_7_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_8_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_9_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_10_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_11_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_12_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_13_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_14_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_15_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_16_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_17_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_18_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_19_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_20_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_21_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_22_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_23_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_24_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_25_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_26_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_27_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_28_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_29_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_30_0" color="#58adff"></font>
		</td>
						<td>
			<font id="item_31_0" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_32_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_33_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_34_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_35_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_36_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_37_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_38_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_39_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_40_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_41_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_42_0">&nbsp;</font>
		</td>
						<td>
			<font id="item_43_0">&nbsp;</font>
		</td>
	</tr>




	<tr class="resultLoad">
						<td>
			<font id="item_0_1" color="#58adff"></font>
		</td>
						<td>
			<font id="item_1_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_2_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_3_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_4_1" color="#58adff"></font>
		</td>
						<td>
			<font id="item_5_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_6_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_7_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_8_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_9_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_10_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_11_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_12_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_13_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_14_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_15_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_16_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_17_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_18_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_19_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_20_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_21_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_22_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_23_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_24_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_25_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_26_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_27_1" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_28_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_29_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_30_1" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_31_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_32_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_33_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_34_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_35_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_36_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_37_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_38_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_39_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_40_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_41_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_42_1">&nbsp;</font>
		</td>
						<td>
			<font id="item_43_1">&nbsp;</font>
		</td>
	</tr>




	<tr class="resultLoad">
						<td>
			<font id="item_0_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_1_2" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_2_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_3_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_4_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_5_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_6_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_7_2" color="#e70f0f"></font>
		</td>
						<td>
			<font id="item_8_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_9_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_10_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_11_2" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_12_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_13_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_14_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_15_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_16_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_17_2" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_18_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_19_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_20_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_21_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_22_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_23_2" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_24_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_25_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_26_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_27_2" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_28_2" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_29_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_30_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_31_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_32_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_33_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_34_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_35_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_36_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_37_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_38_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_39_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_40_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_41_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_42_2">&nbsp;</font>
		</td>
						<td>
			<font id="item_43_2">&nbsp;</font>
		</td>
	</tr>



	<tr class="resultLoad">
						<td>
			<font id="item_0_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_1_3" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_2_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_3_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_4_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_5_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_6_3" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_7_3" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_8_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_9_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_10_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_11_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_12_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_13_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_14_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_15_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_16_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_17_3" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_18_3" color="#58adff">大</font>
		</td>
						<td>
			<font id="item_19_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_20_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_21_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_22_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_23_3" color="#e70f0f">小</font>
		</td>
						<td>
			<font id="item_24_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_25_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_26_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_27_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_28_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_29_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_30_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_31_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_32_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_33_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_34_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_35_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_36_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_37_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_38_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_39_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_40_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_41_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_42_3">&nbsp;</font>
		</td>
						<td>
			<font id="item_43_3">&nbsp;</font>
		</td>
			</tr>
	</tbody></table>

</div>
</body></html>