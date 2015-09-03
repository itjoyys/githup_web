function ready(){
	
	$(".menu a").each(function(){
		$(this).html("<b class='b1'>&nbsp;</b><b class='b2'>"+$(this).html()+"</b><b class='b3'>&nbsp;</b>");
	});
	
	
	$(".checkBox").toggle(
		function(){
			$(this).css("background-position","bottom");
			$(this).attr("rel","checked");
		},
		function(){
			$(this).css("background-position","top");
			$(this).attr("rel","");
		}
	);
	
	$("[name=skypeName]").focus(function(){
		if($(this).val() == "Skype Name") $(this).val("");
	});
	
	$("[name=skypeName]").blur(function(){
		if($(this).val() == "") $(this).val("Skype Name");
	});
	
	
	
	var conInnerConWidth = $(".innerCon").width();
	var conSize = $(".innerCon").size();
	var tabHeight = $(".tab a").height();
	if(!window.cur) window.cur=0;
	
	$(".tab a").click(function(){
		var index = $(".tab a").index(this);
	   slide(conInnerConWidth, tabHeight, index);
		return false;
	});
	
	$(".prev").click(function(){
	   if(window.cur>0) slide(conInnerConWidth, tabHeight, window.cur-1);
		return false;
	});
	
	$(".next").click(function(){
	   if(window.cur<conSize-1) slide(conInnerConWidth, tabHeight, window.cur+1);
		return false;
	});
	
	$(".faq ul a").click(function(){
		var index = $(".faq ul a").index($(this));
		$('html, body').animate({scrollTop: $(".details h1").eq(index).offset().top-15}, 1500);
		return false;
	});
	
	
	$("a[href=#]").live('click', function(event){
		return false;
	});
	
	$(".top a").click(function(){
		$('html, body').animate({scrollTop:0}, 1500);
	});
	
	$("[name=contactForm] .submit").click(function(){$("[name=contactForm]").submit();});
	
	$("[name=contactForm]").submit(function(){
		window.error=0;
		check("contactName","str",3,100);
		check("contactEmail","email",3,55);
		check("contactMsg","str",5,500);
		if(window.error==0){
			$.ajax({type:"POST",url:"ajax.php",data:"act=contact&f1="+$("[name=contactName]").val()+"&f2="+$("[name=contactEmail]").val()+"&f3="+$("[name=contactMsg]").val(),success: function(msg){$("input,textarea").val("");$("[name=contactForm]").html("Your comment is successfully sent");}});
		}
		return false;
	});
	
	$(".user_guide .w40").each(function(i){
		$(this).css("background-position","0 -"+(i-1)*230+"px");
	});
	
	$("[name=getZapleeForm] .submit").click(function(){
		$(this).parent().submit();
	});
	
	$("[name=getZapleeForm]").submit(function(){
		if(!$("[name=skypeName]").val() || $("[name=skypeName]").val() == "Skype Name"){alert("Please enter your Skype Name");$("[name=skypeName]").focus();return false;}
		if(!$("a.checkBox").attr("rel")){alert("Please read Zaplee Privacy/Terms & Conditions and check the box below first");$("a.checkBox").focus();return false;}
		$.ajax({type:"POST",url:"/ajax.php",data:"act=downloadStandart&f1="+$("[name=skypeName]").val(),success: function(msg){window.location = "/app/zaplee.msi";}});
		return false;
	});
	
	$(".downloadHostedForm").submit(function(){
		window.error=0;
		check("downloadFirstName","str",3,100);
		check("downloadLastName","str",3,100);
		check("downloadSkypeId","str",3,55);
		check("downloadEmail","email",3,55);
		if(window.error==0){
			$.ajax({type:"POST",url:"/ajax.php",data:"act=downloadHosted&f1="+$("[name=downloadFirstName]").val()+"&f2="+$("[name=downloadLastName]").val()+"&f3="+$("[name=downloadSkypeId]").val()+"&f4="+$("[name=downloadEmail]").val(),success: function(msg){$(".downloadHostedForm>*").val("");$(".downloadHostedForm").html("Thanks "+$("[name=downloadFirstName]").val()+". We will contact you soon.");}});
		}
		return false;
	});
	
	$(".signUp_hosted").click(function(){
		$("#overlay").show();
	});
	
	$("#closeBox").click(function(){
		$("#overlay").hide();
	});
	
	$(".feature_tour .tab, .feature_tour .nav").addClass("vv");
}

//////////////////////////////////////
function slide(conInnerConWidth, tabHeight, index){
	$(".tab a").removeClass("current");
	$(".tab a").eq(index).addClass("current");
	$(".tab").css("background-position","0 -"+index*tabHeight+"px");
	$(".maskCon").animate({marginLeft:-index*conInnerConWidth+"px"});
	window.cur = index;
}

function scrl(id){
	$('html, body').animate({scrollTop: $("#"+id).offset().top-15}, 1500);
}

function check(fieldName,type,minChar,maxChar,fieldName2,customMsg){
	if(window.error==0){
		var field = $("[name="+fieldName+"]");
		var field2 = $("[name="+fieldName2+"]");
		if(field.val().length<minChar || field.val().length>maxChar){
			window.error=1;
		}
		else if(type=="num" && isNaN(field.val().split(" ").join("").split("-").join("").split("+").join(""))){
			window.error=1;
		}
		else if(type=="email" && (field.val().length<7 || field.val().indexOf("@")<1 || (field.val().indexOf("@")+2)>field.val().lastIndexOf(".") || field.val().lastIndexOf(".")>(field.val().length-2))){
			window.error=1;
		}
		else if(type=="ifSame" && (field.val() != field2.val())){
			window.error=1;
		}
		else if(type=="checkbox"){
			if(!$("['name="+field+"'][checked]").val()) window.error=1;
		}
		if(window.error==1){
			var label =field.prev().html().split("*").join("").split(" :").join("");
			if(customMsg) var msg=customMsg; else var msg="Please check '"+label+"' field!";
			alert(msg);
			field.focus();
		}
	}
}

function focusToSkypename(){
	$("[name=skypeName]").focus();
}

