$(function(){
	$("#gg").click(function(){
		window.open("info.php?"+Math.random(),"mainFrame");
	})

	$("#out").click(function(){
		if(confirm('您真的要退出后台管理吗？')){
			window.parent.location.href = 'out.php';
	 	}else{
			return false;
		}
	})
})

function show_lm(dlm,xlm){ //大栏目，小栏目
	$("#"+dlm).removeClass("bg_10");
	$("#"+dlm).addClass("bg_13");
	$("#"+xlm).css("display","block");
}

function hide_lm(dlm,xlm){ //大栏目，小栏目
	$("#"+dlm).removeClass("bg_13");
	$("#"+dlm).addClass("bg_10");
	$("#"+xlm).css("display","none");
}

function s_h(dlm,xlm){ //大栏目，小栏目
	if(document.getElementById(dlm).className == "bg_10"){
		show_lm(dlm,xlm);
	}else{
		hide_lm(dlm,xlm);
	}
}