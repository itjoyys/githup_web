// JavaScript Document
function check_user_show(obj1){
	if(obj1.ask.value == ""){
		obj1.ask.focus();
		return false;
	}
	
	if(obj1.answer.value == ""){
		obj1.answer.select();
		return false;
	}
	
	return true;
}



