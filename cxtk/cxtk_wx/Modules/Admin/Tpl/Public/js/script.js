$(document).ready(function() {

	$("#sign-in-tab").click( // the sign in tab click event
		function(event) {
			$('.register-form').fadeOut(300);
			$('.sign-in-form').delay(300).fadeIn(300);
			$('#sign-in-tab').addClass("active");
			$('#register-tab').removeClass("active");
		}
	);

	$("#register-tab").click( // the register tab click event
		function(event) {
			$('.sign-in-form').fadeOut(300);
			$('.register-form').delay(300).fadeIn(300);
			$('#register-tab').addClass("active");
			$('#sign-in-tab').removeClass("active");
		}
	);
	
	$("#checkbox .unchecked-state").click( // checkbox select event
		function(event) {
			$(this).parent().addClass("selected");
			$(this).parent().find("checkbox").attr("checked","checked");
		}
	);
	
	$("#checkbox .checked-state").click( // checkbox deselect event
		function(event) {
			$(this).parent().removeClass("selected");
			$(this).parent().find("checkbox").removeAttr("checked");
		}
	);	
});