/*
**inDelay: 顯示前延遲
**outDelay: 隱藏前延遲
**showTime: 動畫時間
**sub: 子選單區塊
**clearTab: 隱藏所有子選單區塊
**inTab: 顯示
**outTab: 隱藏
**clickTab: 點擊顯示
*/ 
$.fn.subTabs = function(options) {
	 var conf = {
		"inDelay": 400,
        "outDelay": 600,
        "showTime": 300,
        "notOver": 0 //防止超出版面
	 };
	 
	 $.extend(conf, options);
	 
     return this.each(function(){
    	 var _o = $(this);
    	 var tClass = _o.attr("class").split(' ')[0];
    	 var sub = $("div[class=" + tClass+']');
    	 var targetWid = _o.width();
    	 var posX =  _o.position().left;
    	 var moveVal = (posX - (sub.width() - targetWid) / 2) - _o.parent().position().left ;
    	 var tout , tin;
		 //移除title
    	 $(this).find('a').removeAttr('title');
    	 sub.find('a').removeAttr('title');
		     	 
		 if(moveVal < 0 && conf.notOver == 1) {
			 moveVal = 0;
		 }

		if (conf.left != undefined) {
			moveVal = parseInt(conf.left) - parseInt(sub.width() / 2);
		}

		//2012.09.28 新增垂直參數設定
		if(conf.posTop) {
			sub.css("top", conf.posTop);
		}

	     sub.css("left", moveVal);
    	 sub.hide();
    	 
    	 $("." + tClass).hover(function(){
         	 clearTimeout(tout);
             tin = setTimeout(function(){ inTab(); }, "400");
         }, function(){
        	 clearTimeout(tin);
         	 tout = setTimeout(function(){ outTab(); }, "400");
         });
         
         _o.bind("click", function(){
             if(sub.is(":visible")){
				 clickTab();
                 //return false;
             }else{
                 clickTab();
             }
         });
         
    	 function clearTab (){
    		 sub.parent().find("div").hide();
    	 }
    	 
    	 function inTab(){
			sub.stop(true, true).fadeIn(conf.showTime);
    		//position();
    	 }
    	 
    	 function outTab(){
			sub.stop(true, true).fadeOut(conf.showTime);
    	 }
         
    	 function clickTab(){
         	clearTab();
			sub.stop(true, true).fadeIn(conf.showTime);
         }
    	 
         function position(){
		    var m = parseInt(conf.left) - parseInt(sub.width() / 2);
         	sub.css("left", m + "px");
         }
     });
 };

 