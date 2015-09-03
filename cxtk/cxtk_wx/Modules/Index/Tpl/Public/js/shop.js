/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var shop_obj={
	page_init:function(){
		$('#category .close, #cover_layer').click(function(){
			$('html, body').removeAttr('style');
			$('#cover_layer').hide();
			$('#category').animate({left:'-100%'}, 500);
			$('#shop_page_contents').animate({margin:'0'}, 500);
		});
		
		if($('#support').size()){
			$('#support').css('bottom', 0);
			$('#footer').css('bottom', 16);
			$('#footer_points').height(57);
		}
		
		$('footer .category a').click(function(){
			if($('#category').height()>$(window).height()){
				$('html, body, #cover_layer').css({
					height:$('#category').height(),
					width:$(window).width(),
					overflow:'hidden'
				});
			}else{
				$('#category, #cover_layer').css('height', $(window).height());
				$('html, body').css({
					height:$(window).height(),
					overflow:'hidden'
				});
			}
			
			$('#cover_layer').show().html('');
			$('#category').animate({left:'0%'}, 500);
			$('#shop_page_contents').animate({margin:'0 -60% 0 60%'}, 500);
			window.scrollTo(0);
			
			return false;
		});
	},
	
	detail_init:function(){
		if(proimg_count>0){
			(function(window, $, PhotoSwipe){
				$('.touchslider-viewport .list a[rel]').photoSwipe({});
			}(window, window.jQuery, window.Code.PhotoSwipe));
			
			$('.touchslider').touchSlider({
				mouseTouch:true,
				autoplay:true,
				delay:2000
			});
		}
		
		$('#detail .property span').click(function(){
			var PId=$(this).attr('PId');
			$('#detail .property span[PId='+PId+']').removeClass();
			$(this).addClass('cur');
			$('#detail #PId_'+PId).val($(this).attr('LId'));
		});
		
		$('#addtocart_tips .close').click(function(){
			$(this).parent().hide();
		});
		
		$('#addtocart_form').submit(function(){return false;});
		$('#addtocart_form .cart .add, #addtocart_form .cart .buy').click(function(){
			var this_btn=$(this);
			this_btn.attr('disabled', true);
			
			$.post($('#addtocart_form').attr('action')+'ajax/', $('#addtocart_form').serialize(), function(data){
				this_btn.attr('disabled', false);
				if(data.status==1){
					if(this_btn.attr('class')=='buy'){
						window.location=$('#addtocart_form').attr('action');
					}else{
						$('#addtocart_tips .qty').html(data.qty);
						$('#addtocart_tips .total').html('￥'+data.total);
						$('#addtocart_tips').css({
							left:$(window).width()/2-125,
							top:$(window).height()/2-60
						}).show();
					}
				}
			}, 'json');
		});
	},
	
	cart_init:function(){
		var price_detail=function(){
			var total_price=0;
			$('#cart_form .sub_total span span').each(function(){
				var price=parseFloat($(this).parent().parent().siblings('.price').children('span').html().replace('￥', ''));
				var qty=parseInt($(this).parent().siblings('input[name=Qty\\[\\]]').val());
				isNaN(qty) && (qty=1);
				var sub_total=price*qty;
				sub_total=sub_total.toFixed(2);
				$(this).html('￥'+sub_total);
				total_price+=price*qty;
			});
			$('#cart_form .total span').html('￥'+total_price.toFixed(2));
		}
		
		price_detail();
		$('#cart_form input[name=Qty\\[\\]]').keyup(function(){
			//var qty=parseInt($(this).val().replace(/[^\d]/g, ''));
			var obj=$(this);
			var qty=$(this).val();
			qty>=1000 && (qty=999);
			$(this).val(qty);
			
			var _ProId=$(this).parent().attr('ProId');
			var _CId=$(this).parent().children('input[name=CId\\[\\]]').val();
			$.post($('#cart_form').attr('action')+'ajax/', $('#cart_form').serialize()+'&_ProId='+_ProId+'&_CId='+_CId, function(data){
				if(data.status==1){
					obj.parent().siblings('.price').children('span').html('￥'+data.price);
					price_detail();
				}else{
					global_obj.win_alert('出现未知错误！');
				}
			}, 'json');
		});
		
		$('#cart_form .del div').click(function(){
			var obj=$(this);
			$.get($('#cart_form').attr('action')+'ajax/', 'd=list&a=del&CId='+$(this).attr('CId'), function(data){
				if(data.status==1){
					$('#cart_form .total span').html('￥'+data.total);
					obj.parent().parent().remove();
				}
			}, 'json');
		});
		
		$('#cart_form').submit(function(){return false;});
		$('#cart_form .checkout input').click(function(){
			$(this).attr('disabled', true);
			$.post($('#cart_form').attr('action')+'ajax/', $('#cart_form').serialize(), function(data){
				$('#cart_form .checkout input').attr('disabled', false);
				if(data.status==1){
					window.location=$('#cart_form').attr('action')+'?d=checkout';
				}
			}, 'json');
		});
	},
	
	checkout_init:function(){
		var address_display=function(){
			var AId=parseInt($('#checkout_form input[name=AId]:checked').val());
			if(AId==0 || isNaN(AId)){
				$('#checkout .address dl').css('display', 'block');
			}else{
				$('#checkout .address dl').css('display', 'none');
			}
		}
		var total_price_display=function(){
			var shipping_price=parseFloat($('#checkout_form input[name=SId]:checked').attr('Price'));
			isNaN(shipping_price) && (shipping_price=0);
			var total_price=parseFloat($('#checkout_form input[name=total_price]').val())+shipping_price;
			$('#checkout_form .total_price span').html('￥'+total_price.toFixed(2));
		}
		
		$('#checkout_form input[name=AId]').click(address_display);
		$('#checkout_form input[name=SId]').click(total_price_display);
		address_display();
		total_price_display();
		
		$('#checkout_form').submit(function(){return false;});
		$('#checkout_form .checkout input').click(function(){
			var AId=parseInt($('#checkout_form input[name=AId]:checked').val());
			if(AId==0 || isNaN(AId)){
				if(global_obj.check_form($('*[notnull]'))){return false};
			}
			
			$(this).attr('disabled', true);
			$.post($('#checkout_form').attr('action')+'ajax/', $('#checkout_form').serialize(), function(data){
				if(data.status==1){
					window.location=$('#checkout_form').attr('action')+'?d=payment&OrderId='+data.OrderId;
				}
			}, 'json');
		});
	},
	
	payment_init:function(){
		var PaymentMethod=$('#payment_form input[name=PaymentMethod]');
		if(PaymentMethod.size()){
			var change_payment_method=function(){
				if(PaymentMethod.filter(':checked').val()=='线下支付'){
					$('#payment_form .payment_info').show();
				}else{
					$('#payment_form .payment_info').hide();
				}
			}
			PaymentMethod.click(change_payment_method);
			PaymentMethod.filter('[value='+$('#payment_form input[name=DefautlPaymentMethod]').val()+']').click();
			change_payment_method();
		}else{
			$('#payment_form').hide();
		}
		
		$('#payment_form').submit(function(){return false;});
		$('#payment_form .payment input').click(function(){
			$(this).attr('disabled', true);
			$.post($('#payment_form').attr('action')+'ajax/', $('#payment_form').serialize(), function(data){
				$('#payment_form .payment input').attr('disabled', false);
				if(data.status==1){
					window.location=data.url
				}
			}, 'json');
		});
	},
	
	user_address_init:function(){
		$('#address_form .back').click(function(){
			window.location='./?d=address';
		});
		
		$('#address_form').submit(function(){return false;});
		$('#address_form .submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post($('#address_form').attr('action')+'ajax/', $('#address_form').serialize(), function(data){
				if(data.status==1){
					window.location=$('#address_form').attr('action')+'?d=address';
				}
			}, 'json');
		});
	}
}