(function(){
	var a=$("#idSlider").find(".item");
	var u=a.length;
	$("#idSlider").css("width",u*100+"%");
	var t=function(x,w){for(var v=0,n=x.length;v<n;v++){w.call(this,x[v],v)}};
	t(a,function(n,i){$(n).css({"float":"left",width:Math.floor($("#idSlider").width()/u)+"px"})});
	var p=createPicMove("idContainer","idSlider",u);
	var q=[];document.getElementById("idNum").innerHTML="";
	for(var r=0,o=p._count-1;r<=o;r++){var m=document.createElement("li");
		m.className="trigger";
	    q[r]=document.getElementById("idNum").appendChild(m)}
	    p.onStart=function(){
	    	 t(q,function(v,n){
	    	 	 v.className=p.Index==n?"trigger active":"trigger"})};
	    	 $("#idSlider").css("position","relative");
	    	 var k=0;
	    	 var j=0;
	    	 var g=0;
	    	 var d=0;
	    	 function b(i){
	    	 	g=i.touches[0].clientX;
	    	 	d=i.touches[0].clientY;
	    	 	k=g}function s(w){
	    	 		var y=w.touches;
	    	 		var n=w.touches[0].clientX;
	    	 		var i=w.touches[0].clientY;
	    	 		if(Math.abs(i-d)>Math.abs(n-g)){return}w.preventDefault();
	    	 		j=n;var v=Math.abs(n-g);
	    	 		var x=$("#idSlider").css("left").replace("px","");
	    	 		if(g>n){
	    	 			p.Stop();
	    	 		    $("#idSlider").css("left",(parseInt(x)-v)+"px")}
	    	 		else{
	    	 			p.Stop();
	    	 			$("#idSlider").css("left",(parseInt(x)+v)+"px")}
	    	 		g=n}function h(i){if(j==0){return}if(k>j){e(k,j)}
	    	 		else{if(k<j){e(k,j)}}k=0;j=0}var c=document.getElementById("idContainer");
	    	 		c.addEventListener("touchstart",b,false);
	    	 		c.addEventListener("touchmove",s,false);
	    	 		c.addEventListener("touchend",h,false);
	    	 		function e(n,i){
	    	 			if(n>=i){p.Next()}
	    	 				else{p.Previous()}}p.Run();
	    	 			window.screenSize=null;
	    	 			var l=function(){
	    	 				if(window.screenSize==$("#idContainer").width()){return}
	    	 					window.screenSize=$("#idContainer").width();p.Stop();
	    	 				var n=$("#idContainer").width();
	    	 				var i=(p.Index-1)%p._count;$("#idSlider li").css("width",n+"px");
	    	 				$("#idSlider").css("left",(-1*n*i)+"px");p.Change=n;p.Run()};
	    	 				window.addEventListener("resize",l);
	    	 				window.addEventListener("orientationchange",l);l();
	    	 				var f=false;
	    	 				$(document).scroll(function(){var x=$(window).scrollTop();
	    	 					var v=$(document).height();
	    	 					var n=$(window).height();
	    	 					if(x>=v-n-300){var w=parseInt($("#page").val())+1;
	    	 					var i=$("#totalPage").val();
	    	 					if(w>i||f){if(w>i){
	    	 						$("#loadingTip").html("没有更多了")
	    	 					}
	    	 						return}
	    	 						f=true;$("#loadingTip").html("加载中...");
	    	 					$.ajax({
	    	 						type:"post",
	    	 						url:"/sg4jdapp/actList.html",
	    	 						data:{page:w,vs:$("#vs").val(),sid:$("#sid").val()},
	    	 						dataType:"html",
	    	 						success:function(y){
	    	 							$(".brand-list").append(y);
	    	 							$("#page").val(w);
	    	 							f=false;
	    	 							panda(function(z){
	    	 								panda.widget.manager.get(z("widget.ResponseLazyLoad"),
	    	 									{target:panda(".brand-list img[srcset]")
	    	 								})
	    	 							})
	    	 						}
	    	 					})

	    	 			}})})();
	    $(function(){
	    	loadJdHeadAndFooter()});