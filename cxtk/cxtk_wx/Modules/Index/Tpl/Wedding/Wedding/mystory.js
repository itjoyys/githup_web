
var myScroll, pullUpEl, pullUpOffset, generatedCount = 0;
var myScroll;
var pageNo = 1;
//喜帖id
var xtid = g_xtid;
window.onload = function() {
	loaded();
	loadData();//加载数据
}

function pullUpAction() {
	//加载更多故事数据
	if(!loadingDataing){
		loadData();
		loadingDataing = true;
	}else{
		myScroll.refresh();
	}
}
//是否正在加载数据
var loadingDataing = false;
function loaded() {
	
	pullUpEl = document.getElementById('pullUp');
	pullUpOffset = pullUpEl.offsetHeight;

	myScroll = new iScroll(
			'wrapper',
			{
				useTransition : true,
				onRefresh : function() {
					if (pullUpEl.className.match('loading')) {
						pullUpEl.className = '';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = '向上拉获取更多...';
					}
				},
				onScrollMove : function() {
					if (this.y < (this.maxScrollY - 5)
							&& !pullUpEl.className.match('flip')) {
						pullUpEl.className = 'flip';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = '松开手开始加载...';
						this.maxScrollY = this.maxScrollY;
					} else if (this.y > (this.maxScrollY + 5)
							&& pullUpEl.className.match('flip')) {
						pullUpEl.className = '';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = '向上拉获取更多...';
						this.maxScrollY = pullUpOffset;
					}
				},
				onScrollEnd : function() {
					if (pullUpEl.className.match('flip')) {
						pullUpEl.className = 'loading';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
						pullUpAction(); // Execute custom function (ajax call?)
						
					}
				}
			});
}

document.addEventListener('touchmove', function(e) {
	e.preventDefault();
}, false);

// document.addEventListener('DOMContentLoaded', loaded, false);
//加载数据并填充到我们的故事容器
function loadData(){
	   $.ajax({
		  type: "GET",
		  url: g_basePath+"mystoryAction.do?method=getMyStroy&pageNo="+pageNo+"&xtid="+xtid,
		  success: function(response,status,xhr){
			  eval("var data="+response+"");
			  //如果有错误 提示错误
			  if(data.errormsg){
				  alert(data.errormsg);
	          	  return ;
	          }else{
	        	  //我们的故事数据
	        	  var storys  = data.storys;
	        	  if(storys && storys.length && storys.length>0){
	        		  appendToContainer(storys);
	        	  }else{
	        		  $('.pullUpLabel').html("没有数据了");
	        		  loadingDataing = false;
	        	  }
	          }
			  
		  },
		  timeout:5000 //超时5秒
		  ,error:function(jqXHR,  textStatus,  errorThrown){
			  loadingDataing = false;
			  alert("服务器异常或者超时！请稍后重试！" +errorThrown);
		  }
	  });
}

function appendToContainer(storys){
	
	for(var i=0,len=storys.length;i<len;i++){
		var story = storys[i];
		
		var html='<p class="imgcontainer"><img src="'+g_basePath+story.fjlj+'"></p>'
		+'<p class="text">'+story.gsms+'</p>'
		+'<p class="line"></p>';
		$('.container').append(html);
	}
	var imgcount  = storys.length;
 	var loaded = 0;
	$(".container img[alt!='1']").on("load",function(){
 		//设置属性 打标记
 		$(this).attr("alt","1");
 		if(imgcount%loaded==0){
 			setTimeout (function(){myScroll.refresh();loadingDataing = false;pageNo ++;},500);
 		}
 		loaded++;
 	})
 	
 	$(".container img[alt!='1']").on("error",function(){
 		//设置属性 打标记
 		$(this).attr("alt","1");
 		if(imgcount%loaded==0){
 			setTimeout (function(){myScroll.refresh();loadingDataing = false;pageNo ++;},500);
 		}
 		loaded++;
 	})
	
}
