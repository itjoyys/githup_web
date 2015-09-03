

//Enclose all JS within this namespace
var baoying = baoying || {}; 
(function($) {
  /**
   * Utilities
   */
  baoying.utils = {
		init: function() {
			
		},
		LoginFormFx: function(cls) {
			var valList = [];
			var valListObj = {};
		
			$(cls).each(function(i){
				valList[i] = $(this);
				valListObj[$(this).attr('id')] = $(this).val();
			}); 
			for (var i in valList) {
				var self = valList[i];				
				self.focus( 
					function () { 
						if($(this).attr('id')=="vlcodes")change_zc_yzm('checkNum-img');
						if($(this).val() == valListObj[$(this).attr('id')])$(this).val('');
					} 
				);
				self.blur( 
					function () { 
						if($(this).val() == '')$(this).val(valListObj[$(this).attr('id')]);
					} 
				);
			}
		},
		addImgHover: function(cls) {
			$(cls).css("opacity","0.8").hover(
				function(){
					$(this).stop().animate({'opacity': 1}, 350);
				}, function(){
					$(this).stop().animate({'opacity': 0.8}, 350);
				}
			);

		},
		addImgFloatFx: function(cls) {
			var left_top = 140;
			right_top = 140;
			float_list = [];
				// 廳主自改 - 浮動圖
			$(cls).each(function(i){
					float_list[i] = $(this);
			});       
			for (var i in float_list) {
				var self = float_list[i]; 
				
				var picfloat = (self.attr('picfloat') == 'right') ? 1 : 0;
				self.show().Float({'floatRight' : picfloat, 'topSide' : ((picfloat == 1) ? right_top : left_top)});
				
				// ie6 png bug
				if (navigator.userAgent.toLowerCase().indexOf('msie 6') != -1) {
					$.each(self.find('span'), function(){
						$(this).css({'width':$(this).width(),'height' : $(this).height()});
					});
				}
				
				if (picfloat) {
					right_top = right_top + (self.find('a > span').height() || 300);
				} else {
					left_top = left_top + (self.find('a > span').height() || 300);
				}
				
				self.hover(function() {
					$(this).find('a > span:first').css('display', 'none');
					$(this).find('a > span:last').css('display', 'block');
				}, function() {
					$(this).find('a > span:last').css('display', 'none');
					$(this).find('a > span:first').css('display', 'block');
				}).find('div').click(function() {
					event.cancelBubble = true;
					$(this).parent('div').hide();
				});
			}	
		}
		
	};
  baoying.utils.addImgHover(".img-pos");
  baoying.utils.LoginFormFx(".loginInput");
  baoying.utils.addImgFloatFx(".floatDiv");
}(jQuery));

// 設為首頁
function setFirst(sURL) {
	try {
		document.body.style.behavior = 'url(#default#homepage)';  
		document.body.setHomePage(sURL);  
	} catch(e) {
		if (window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			} catch(e) {
				alert("抱歉，此操作被浏览器拒绝！\n\r请在浏览器地址栏输入“about:config”并回车\n\r然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
			}
		} else {
			alert("抱歉，您的浏览器不支持，请按照下面步骤操作：\n\r1.打开浏览器设置。\n\r2.点击设置网页。\n\r3.输入：" + sURL + "点击确定。");
		}
	}
}
// 加入最愛
function bookMarksite(sURL, sTitle) {
	try {
		window.external.addFavorite(sURL, sTitle);
	} catch(e) {
		try {
			window.sidebar.addPanel(sTitle, sURL, "");
		} catch(e) {
			alert("抱歉，您所使用的浏览器无法完成此操作。\n\r加入收藏失败，请使用Ctrl+D进行添加");
		}
	}
}
function cancelMouse() {
    return false
}

document.oncontextmenu = cancelMouse;

function CSlink(){
	MM_openBrWindow('http://www.providesupport.com?messenger=1eh0fu4poyz2m1bgp2wmy29c28','Rule','resizable=yes,location=no,directories=no,scrollbars=yes,status=no,,resizable=no,top=2,width=600,height=620');
}
function mover(A) {
    A.style.backgroundPosition = "0 bottom"
}
function mout(A) {
    A.style.backgroundPosition = "0 top"
}
function MM_openBrWindow(C, B, A) {
    window.open(C, B, A)
}
function subWin(A) {
    window.open(A, "gameOpen", "width=1024,height=768")
}
function subWinRule(A) {
    window.open(A, "gameOpenRule", "width=1024,height=768,scrollbars=yes")
};
function winOpen(url,width,height,left,top,name)
{
	var temp = "menubar=no,toolbar=no,directories=no,scrollbars=yes,resizable=no,location=no";
	if (parseInt(width)>0) {
	temp += ',width=' + width;
	} else {
	width = window.screen.availWidth;
	}
	if (parseInt(height)>0) {
	temp += ',height=' + height;
	} else {
	height = window.screen.availHeight;
	}
	if (parseInt(left)>0) {
	temp += ',left=' + left;
	} else {
	temp += ',left='
	+ Math.round((window.screen.availWidth - parseInt(width)) / 2);
	}
	if (parseInt(top)>0) {
	temp += ',top=' + top;
	} else {
	temp += ',top='
	+ Math.round((window.screen.availHeight - parseInt(height)) / 2);
	}
	if(typeof(name)=="undefined"){
		name="";
	}
	if(name=="game")
	{
		//alert(temp);
		var obj=window.open (url,name,temp);
		obj.moveTo(0,0);
		obj.resizeTo(window.screen.availWidth,window.screen.availHeight);	
		//window.setTimeout("obj.document.location=url",3000);
	}
	else{
		window.open (url,name,temp);
	}
}
function inputCheck(){
	var userInput = $("input[name=username]");
	var passwdInput = $("input[name=passwd]");
	var vlcodesInput = $("input[name=vlcodes]");
	
	if(userInput.val() == "" || userInput.val() == "帐号"){
        alert('请输入帐号!!');
		userInput.focus();
        return false;
    }else if(passwdInput.val()== "" || passwdInput.val() == "xx@x@x.x"){
        alert('请输入密码!!');
        passwdInput.focus();
        return false;
    }else if(passwdInput.val().length > 0 && passwdInput.val().length < 6){
        alert('密码长度不能少于6个字元');
        passwdInput.focus();
        return false;
    }else if(vlcodesInput.val() == "" || vlcodesInput.val() == "验证码"){
        alert('请输入验证码!!');
        vlcodesInput.focus();
        return false;
    }
    return true;
}
