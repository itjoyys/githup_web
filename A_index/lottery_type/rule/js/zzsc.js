$(function(){	
	var $body = $(document.body);;
	var $bottomTools = $('#footer');
		$(window).scroll(function () {
			var scrollHeight = $(document).height();
			var scrollTop = $(window).scrollTop();
			var $windowHeight = $(window).innerHeight();
			scrollTop > 50 ? $("#but-top").fadeIn(200).css("display","block") : $("#but-top").fadeOut(200);			
			$bottomTools.css("bottom", scrollHeight - scrollTop - $footerHeight > $windowHeight ? 40 : $windowHeight + scrollTop + $footerHeight + 40 - scrollHeight);
		});
		$('#but-top').click(function (e) {
	
			e.preventDefault();
			$('html,body').animate({ scrollTop:0});
		});
		$qrTools.hover(function () {
			qrImg.fadeIn();
		}, function(){
			 qrImg.fadeOut();
		});
});

