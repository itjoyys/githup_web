/**
 * @version 1.0.1
 * 
 * @requires jquery.js (tested with 1.3.2)
 * @param shortPass:    "shortPass",    //optional
 * @param badPass:        "badPass",        //optional
 * @param goodPass:        "goodPass",        //optional
 * @param strongPass:    "strongPass",    //optional
 * @param baseStyle:    "testresult",    //optional
 * @param userid:        "",                //required override
 * @param messageloc:    1                //before == 0 or after == 1
 * @link http://mypocket-technologies.com/jquery/password_strength/
 * 
 */
$.fn.passStrength = function(options) {
	var defaults = {
		shortPass_txt: '密码强度：太短',
		badPass_txt: '密码强度：弱',
		goodPass_txt: '密码强度：很好',
		strongPass_txt: '密码强度：强',
		samePassword_txt: '帐号与密码不能相同',
		resultStyle_txt: "",
		shortPass:         "top_shortPass",    //optional
		badPass:        "top_badPass",        //optional
		goodPass:        "top_goodPass",        //optional
		strongPass:        "top_strongPass",    //optional
		baseStyle:        "top_testresult",    //optional
		userid:            "",                //required override
		messageloc:        1                //before == 0 or after == 1
	}; 
 	var opts = $.extend(defaults, options);  
  
 	return this.each(function() { 
 		var obj = $(this);
        
	    obj.unbind().keyup(function(){
	    	this.checkValue();
		}).focus(function(){
            $(this).blur(function(){ 
            	this.checkValue();
            });
        });
	    
	    this.checkValue = function () {
	    	var password = $(this).val(), username = $(opts.userid).val(), results = '', resultStyle = '';

            if (password.length < 6 ) { // password < 6
            	resultStyle = opts.shortPass;
            	results = opts.shortPass_txt;
            	
            } else if (password.toLowerCase() == username.toLowerCase()) { // password == user name
            	resultStyle = opts.badPass;
            	results = opts.samePassword_txt;
            	
            } else { // checkstrength
            	var score = $.fn.checkstrength(password);
            	
    			if (score < 34 ){ 
    				resultStyle = opts.badPass; 
    				results = opts.badPass_txt;
    			} else if (score < 68 ){ 
    				resultStyle = opts.goodPass;
    				results = opts.goodPass_txt;
    			} else {
    				resultStyle = opts.strongPass;
    				results = opts.strongPass_txt;  
    			}
            }
            
		    $('form').find('.'+opts.baseStyle).remove();
		    
		    if (opts.messageloc === 1) {
		        $(this).next("." + opts.baseStyle).remove();
		        $(this).after("<span class=\""+opts.baseStyle+"\"><span></span></span>");
		        $(this).next("." + opts.baseStyle).addClass(resultStyle).find("span").text(results);
		    } else {
		        $(this).prev("." + opts.baseStyle).remove();
		        $(this).before("<span class=\""+opts.baseStyle+"\"><span></span></span>");
		        $(this).prev("." + opts.baseStyle).addClass(resultStyle).find("span").text(results);
		    }
	    }
	});  
};  

$.fn.checkstrength = function (password) {
	var score = 0;
    //password length
    score += password.length * 4;
    score += ( $.fn.checkRepetition(1,password).length - password.length ) * 1;
    score += ( $.fn.checkRepetition(2,password).length - password.length ) * 1;
    score += ( $.fn.checkRepetition(3,password).length - password.length ) * 1;
    score += ( $.fn.checkRepetition(4,password).length - password.length ) * 1;

    //password has 3 numbers
    if (password.match(/(.*[0-9].*[0-9].*[0-9])/)){ score += 5;} 
    
    //password has 2 symbols
    if (password.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)){ score += 5 ;}
    
    //password has Upper and Lower chars
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)){  score += 10;} 
    
    //password has number and chars
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)){  score += 15;} 
    //
    //password has number and symbol
    if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([0-9])/)){  score += 15;} 
    
    //password has char and symbol
    if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([a-zA-Z])/)){score += 15;}
    
    //password is just a numbers or chars
    if (password.match(/^\w+$/) || password.match(/^\d+$/) ){ score -= 10;}
    
    //verifying 0 < score < 100
    if ( score < 0 ){score = 0;} 
    if ( score > 100 ){  score = 100;}
    
    return score;
}

$.fn.checkRepetition = function(pLen,str) {
     var res = "";
     for (var i=0; i<str.length ; i++ ) 
     {
         var repeated=true;
         
         for (var j=0;j < pLen && (j+i+pLen) < str.length;j++){
             repeated=repeated && (str.charAt(j+i)==str.charAt(j+i+pLen));
             }
         if (j<pLen){repeated=false;}
         if (repeated) {
             i+=pLen-1;
             repeated=false;
         }
         else {
             res+=str.charAt(i);
         }
     }
     return res;
    };