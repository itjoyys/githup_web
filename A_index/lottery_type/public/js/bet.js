$(function(){
	
	$("span[rate='true']").click(function(){
	var name=$(this).parents("td").next("td").children('input')[0].name;
	var type=$("#title_3d").text();
	var bet=$(this).text();
	var fc_type=$("#fc_type").text();
	var haoma=$(this).parents("td").prev("td").text();
	var style=$("#style").text();
	var title_3d=$("#title_3d").text();
	var qishu=$("input[name='qishu']").val();

	//time_bet.php
	// parent.k_meml.bet_order_frame.location.href="time_bet.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type;
	parent.k_meml.location.href="main_left.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type+"&action=dan"+"&haoma="+haoma+"&style="+style+"&leixing="+title_3d+"&qishu="+qishu;
	
	});

	//六合彩点击赔率跳转（除正1-6）
	$("span[rate='true1']").click(function(){
	var name=$(this).parents("td").next("td").children('input')[0].name;
	var type=$("#title_3d").val();
	var bet=$(this).text();
	var fc_type=$("#fc_type").val();
	var ids=$("#ids").val();
	var haoma=$(this).parents("td").prev("td").text();
	var style=$("#style").text();
	var title_3d=$("#title_3d").val();
	// var qishu=$("input[name='qishu']").val();
	//time_bet.php
	// parent.k_meml.bet_order_frame.location.href="time_bet.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type;
	parent.k_meml.location.href="main_left.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type+"&action=dan_1"+"&class2="+ids+"&haoma="+haoma+"&style="+style+"&leixing="+title_3d;
	
	});

	//六合彩点击赔率跳转(正1-6)
	$("span[rate='true2']").click(function(){
	var name=$(this).parents("td").next("td").children('input')[0].name;
	var type=$("#title_3d").val();
	var bet=$(this).text();
	var fc_type=$("#fc_type").val();
	var ids=$("#ids").val();
	var haoma=$(this).parents("tr[class='Ball_tr_H']").siblings("tr[class='hset']").text();
	var style=$("#style").text();
	var title_3d=$("#title_3d").val();
	// var qishu=$("input[name='qishu']").val();
	//time_bet.php
	// parent.k_meml.bet_order_frame.location.href="time_bet.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type;
	parent.k_meml.location.href="main_left.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type+"&action=dan_1"+"&class2="+ids+"&haoma="+haoma+"&style="+style+"&leixing="+haoma;
	
	});

		//六合彩点击赔率跳转  (一肖/尾数)(特码生肖)
	$("span[rate='true3']").click(function(){
	var name=$(this).parents("td").next("td").children('input')[0].name;
	var type=$("#title_3d").val();
	var bet=$(this).text();
	var fc_type=$("#fc_type").val();
	var ids=$("#ids").val();
	var haoma=$(this).parents("td").prev("td").text();
	var style=$("#style").text();
	var title_3d=$("#leixing").val();
	// var qishu=$("input[name='qishu']").val();
	//time_bet.php
	// parent.k_meml.bet_order_frame.location.href="time_bet.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type;
	parent.k_meml.location.href="main_left.php?name="+name+"&type="+type+"&bet="+bet+"&fc_type="+fc_type+"&action=dan_1"+"&class2="+ids+"&haoma="+haoma+"&style="+style+"&leixing="+title_3d;
	
	});


	










})

function lottery(type,num,qishu){
	$.ajax({  
		type: "get",  
		url: "lottery_results_ajax.php",  
		data: "type="+type+"&num="+num+"&qishu="+qishu, 
		success: function(data_arr){ 
			if(data_arr!=1){
			$("#lottery").replaceWith(data_arr); 
			}			 				  

		}
              
	}); 
}

//function lottery(type,num,qishu){
//	setInterval(show(type,num,qishu),500); 
//}


