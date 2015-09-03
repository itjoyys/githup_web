(function(){
        var onBridgeReady = function () {
        	WeixinJSBridge.call("hideToolbar");
        	
			var appId  = "",	//$('#txt-wx').data('appid'),
				link   = window.location.href,	//$('#txt-wx').data('link'),
				title  = htmlDecode($('title').text()),	// htmlDecode($('#txt-wx').data('title')),
				desc   = htmlDecode($('title').text() + "，敬请访问！"),	//<br/>官网地址：" + window.location.href),	// htmlDecode($('#txt-wx').data('desc')),
				desc = desc || link,
				imgUrl = "";
			
			var url=window.location.href;
			var site_id = url.substring(url.indexOf('realty/index')+13,url.indexOf('?'));
			if(site_id == '445'){
				desc = '林隐朝阳 八十一座';
			}

			var link = link.replace(/([&\?])code=[\d\w]+&?/,"$1");
			link = link.replace('&weixin.qq.com=', '');
			link = link.replace('#wechat_webview_type=1', '');
			
			var image = $.trim($('link[data-logo]').attr('href'));	//$('#txt-wx').data('img')
			if (image=='' || image==undefined) {
				image = $.trim($('img[data-share-logo]').attr('src'));
			}
			if (image=='' || image==undefined) {
				image = $('#wx-share-img').val();
			}
			if (image=='' || image==undefined) {
				image = $.trim($('img:first').attr('src'));
			}
			if (image!='' && image!=undefined) {
				if (image.indexOf('http://') == -1) {
					imgUrl = "http://" + window.location.host + image;
				} else {
					imgUrl = image;
				}
			}
			
			// 发送给好友;
			WeixinJSBridge.on('menu:share:appmessage', function(argv){
				WeixinJSBridge.invoke('sendAppMessage',{
					//"appid"      : appId,
					"img_url"    : imgUrl,
					//"img_width"  : "640",
					//"img_height" : "640",
					"link"       : link,
					"desc"       : desc,
					"title"      : title
				},
				function(res){});
			});

			// 分享到朋友圈;
			WeixinJSBridge.on('menu:share:timeline', function(argv){
				WeixinJSBridge.invoke('shareTimeline',{
					"img_url"    : imgUrl,
					//"img_width"  : "640",
					//"img_height" : "640",
					"link"       : link,
					"desc"       : desc,
					"title"      : title
				}, function(res){});
			});
			
			// 分享到微博;
			var weiboContent = '';
			WeixinJSBridge.on('menu:share:weibo', function(argv){
				WeixinJSBridge.invoke('shareWeibo',{
					"content" : title + link,
					"url"     : link,
				},
				function(res){});
			});
		};

        if(document.addEventListener){
			document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
		} else if(document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady'   , onBridgeReady);
			document.attachEvent('onWeixinJSBridgeReady' , onBridgeReady);
		}
})();