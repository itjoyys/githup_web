/**
 * ajax修改赔率
 

$(function() {
	//获取class为caname的元素
	$(".addinput").click(function() {
	var td = $(this);
	var txt = jQuery.trim(td.text());
	var input = $("<input size='5' type='text'value='"+txt+"'/>");
	td.html(input);
	input.click(function() { return false; });
	//获取焦点
	input.trigger("focus");
	//文本框失去焦点后提交内容，重新变为文本
	input.blur(function() {
	var newtxt = $(this).val();
	//判断文本有没有修改
	if (newtxt != txt) {
	var caid = td.attr('id');
    var type = td.parent().parent().parent().attr('id');
    var name =  $(".content").attr('id');
	//ajax异步更改数据库,加参数date是解决缓存问题
	var url = "../results/js_ajax.php?value=" + newtxt + "&caid=" + caid + "&name=" + name+"&type=" + type;
	//使用get()方法打开一个一般处理程序，data接受返回的参数（在一般处理程序中返回参数的方法 context.Response.Write("要返回的参数");）
	//数据库的修改就在一般处理程序中完成
	$.get(url, function(data) {
		if(data=='yes'){
			td.html(newtxt);
		}else{
			td.html(txt);
			}
	});
	}else{
	td.html(newtxt);}
	});
	});
	}); 
	*/
