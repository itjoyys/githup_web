function numToCny(num){  
    var capUnit = ['万','亿','万','元',''];     
    var capDigit = { 2:['角','分',''], 4:['仟','佰','拾','']};     
    var capNum=['零','壹','贰','叁','肆','伍','陆','柒','捌','玖'];     
    if (((num.toString()).indexOf('.') > 16)||(isNaN(num)))      
        return '';  
	num = num*1000;
    num = (Math.round(num*100)/100).toString();     
    num =((Math.pow(10,19-num.length)).toString()).substring(1)+num;     
    var i,ret,j,nodeNum,k,subret,len,subChr,CurChr=[];     
    for (i=0,ret='';i<5;i++,j=i*4+Math.floor(i/4)){     
        nodeNum=num.substring(j,j+4);     
        for(k=0,subret='',len=nodeNum.length;((k<len) && (parseInt(nodeNum.substring(k))!=0));k++){     
            CurChr[k%2] = capNum[nodeNum.charAt(k)]+((nodeNum.charAt(k)==0)?'':capDigit[len][k]);     
            if (!((CurChr[0]==CurChr[1]) && (CurChr[0]==capNum[0])))     
                if(!((CurChr[k%2] == capNum[0]) && (subret=='') && (ret=='')))     
                    subret += CurChr[k%2];     
        }     
        subChr = subret + ((subret=='')?'':capUnit[i]);     
        if(!((subChr == capNum[0]) && (ret=='')))     
            ret += subChr;     
    }     
    ret=(ret=='')? capNum[0]+capUnit[3]: ret;       
    return ret;     
}
$(document).ready(function(e) {
    $("input[money=true]").bind("keyup",function(){
			var obj=$(this);
			if(!isNaN(obj.val()))
				obj.parent().find("span[moneycap='true']").text(numToCny(obj.val()));
	});
	$("input[name=username]").bind('focus keyup',function(){
		var pre_name = $("input[name='username0']").val();
			var username = pre_name + $(this).val();
			$(this).attr('ajaxurl','../members/mem_chk.php?uid={UID}&langx={LANGX}&user='+username);
		
	});
});
function CheckKey(){
	// 僅接受數字
	if(event.keyCode == 13) return false;
	if( (event.keyCode == 8 || event.keyCode == 46) || (event.keyCode > 47 && event.keyCode < 58)) {
		return true;
	}else{
		return false;
	}
}