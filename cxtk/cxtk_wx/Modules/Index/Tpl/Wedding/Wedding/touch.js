$(document).ready(function(){

var startX;//横向开始坐标
var startY;//纵向开始坐标
var mstartX,mstartY;//滑动的时候横向和纵向坐标
var startT;//开始时间
var endT;//结束时间
document.addEventListener("touchstart", touchStart, false);
document.addEventListener("touchmove", touchMove, false);
document.addEventListener("touchend", touchEnd, false);
	function touchStart(event){
	  var touch = event.touches[0];
	  startT = new Date().getTime();
	  startX =touch.pageX;
	  startY = touch.pageY;
	  //记录触摸的起点位置
	 // $('.info').html("startX:"+startX + ",startY:"+startY);
	}
	
	function touchMove(event){
	 var touch = event.touches[0];
	 mstartX =touch.pageX;
	 mstartY = touch.pageY;
	 //$('.info').html("move:"+touch.pageX);
	} 
	
	
	//滑动事件是在用户一秒内水平拖拽大于30PX，或者纵向拖曳小于20px的事件发生时触发的事件：
	//向右滑动事件在用户向右拖动元素大于30px时触发：
	function touchEnd(event){ 
		endT  = new Date().getTime();
		//alert((endT-startT)/1000)
		if((endT-startT)/1000<1){
			var bx = (mstartX - startX)>30; //向右侧滑动
			var by = Math.abs(mstartY - startY)<30;
			if(bx && by){
				//alert('swiper');
				parent.backToMain()
			}
		}
		//清空数据
		startX = mstartY = mstartX =mstartY =0;
	}
});
  