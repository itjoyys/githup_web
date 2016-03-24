$(document).ready(function(){
	$('#list_mark span:first').addClass('active');
	$('.tabBox:first').css('display','block');
	autoroll();
	hookThumb();
	hookThumb2();
})
var i=-1; //第i+1个tab开始
var offset = 3000; //轮换时间
var timer = null;
function autoroll(){
	n = $('#list_mark span').length-1;
	i++;
	if(i > n){
	i = 0;
	}
	slide(i);
	timer = window.setTimeout(autoroll, offset);
}

function slide(i){
	$('#list_mark span').eq(i).addClass('active').siblings().removeClass('active');
	$('.tabBox').eq(i).css('display','block').siblings('.tabBox').css('display','none');
	$('.tabBox').eq(i).addClass('tab_on').siblings('.tabBox').removeClass('tab_on');
}

function hookThumb(){    
	$('#list_mark span').hover(
	function(){
		if(timer){
			clearTimeout(timer);
			i = $(this).prevAll().length;
			slide(i); 
		}
	},function(){
		timer = window.setTimeout(autoroll, offset);  
		this.blur();            
		return false;
	});
}

function hookThumb2(){    
	$('.tab_on').hover(
	function(){
		if(timer){
			clearTimeout(timer);
			i = $(this).prevAll().length;
			slide(i); 
		}
	},function(){
		timer = window.setTimeout(autoroll, offset);  
		this.blur();            
		return false;
	}); 
}