function disableRightClick(e){
	if(!document.rightClickDisabled){ // initialize
		if(document.layers){
			document.captureEvents(Event.MOUSEDOWN);
			document.onmousedown = disableRightClick;
		}else{
			document.oncontextmenu = disableRightClick;
		}
		return document.rightClickDisabled = true;
	}
	
	if(document.layers || (document.getElementById && !document.all)){
		if (e.which==2||e.which==3) return false;
	}else{
		return false;
	}
}
disableRightClick();

//解决IE6不缓存背景图片问题
try{
	document.execCommand("BackgroundImageCache", false, true);
}catch(e){}