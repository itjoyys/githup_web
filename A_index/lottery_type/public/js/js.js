// var TimeObj=null;
// var kspanelobj=null;


$(document).ready(function(e) {
		var ksstatus=1;
		var val1;var val2;var val3;var val4;var val5;
		$.ajax({  
		type: "post",  
		url: "./js_ajax.php",  
		async:false,
		dataType: "json",  		
		data: {action:"ask"},  
		success: function(msg){  

			if(msg==0){
				ksstatus=0;//不使用快捷功能
			}else{				
				val1 = msg[0];
				val2 = msg[1];
				val3 = msg[2];
				val4 = msg[3];
				val5 = msg[4];				
			}

		}
			
              
	});  



	
	var html = 	'<div id="kspanel"><div class="divheader">下注金额</div><div><ul><li>'+val1+'元</li><li>'+val2+'元</li><li>'+val3+'元</li><li>'+val4+'元</li><li>'+val5+'元</li></ul></div></div>'

	// TimeObj=setTimeout("makeRequest()", ftime);	
	if(ksstatus==1){
		$("body").append(html);		
		
		$("input[type=text][js=js]").live("click",function(e){
				//alert(e.pageY)
			kspanelobj=$(this);
            if(kspanelobj.attr("readonly")!="readonly"){

				topLimit = $(this).offset().top-$("#kspanel").height();
				leftLimit = $(this).offset().left+$("#kspanel").width();
				
				if($('body').scrollTop() < topLimit){
					panelTop = $(this).offset().top - $("#kspanel").height() - $(this).height() + 10;
				}else{
					panelTop = $(this).offset().top + $(this).height();
				}
					
					//alert("leftLimit:"+leftLimit);
				if($('body').width() < leftLimit){

					//alert("$('body').width():"+$('body').width()+ "  ---  " + leftLimit);

					panelLeft = $(this).offset().left - $(this).width() - 50;
				}else{
					//alert("$('body').width():"+$('body').width()+ "  ===   " + leftLimit);

					panelLeft = $(this).offset().left + $(this).width() - 25
				}
				// alert(panelTop);alert(panelLeft);
				$("#kspanel").css("top",panelTop).css("left",panelLeft).css('display','block');  
            }
		});
		$("#kspanel li").bind("click",function(){
			kspanelobj.val($(this).text().replace("元",""));
			kspanelobj.trigger("blur");
			$("#kspanel").hide();
		});

		$("#kspanel").bind("mouseleave",function(){
			//alert("dd");
			$(this).hide();
		});
	}
    // $("span[rate=true]").live("click",function(e){
    //     url='main.php?action=order_mode_2&lx='+lx+'&uid='+uid+'&langx='+langx+'&p='+escape(params)+'&r='+($(this).attr("id").split("_")[1]);
    //     parent.k_meml.betOrder(url);
    // });
});


