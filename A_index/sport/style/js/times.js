var i = 31;
var aa; 
function check(){
	i = i -1;	
	if(i < 1){
		i=1;
		var varue = document.getElementById('league').value;
		loaded(varue,0);
	}
	$("#location").html("对不起,您点击页面太快,请在"+i+"秒后进行操作");
	aa = setTimeout("check()",1000);
}

function check22(){
	i = 31;
	clearTimeout(aa);
	check()
}