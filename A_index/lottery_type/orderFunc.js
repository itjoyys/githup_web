
  window.onload=function(){
    //下注总金额计算及表单验证
      // var single_field_max=document.getElementById("single_field_max").innerHTML; //单注总金额上限
      // var single_note_max=document.getElementById("single_note_max").innerHTML;//单注金额上限
 
     //下注金额
     var i;
    var input1=document.getElementsByTagName("input"); 
    var all_val=0;
  var old_val;
  var new_val;
  var val;
      for(i=0;i<input1.length;i++){

        input1[i].onfocus=function(){
          if(this.value == ""){
             old_val=0;
          }
          old_val=this.value;
        }


         input1[i].onblur=function(){
        new_val=this.value;
        val=new_val-old_val;
        if(new_val == ""){
          new_val=0;
        }else if(eval(new_val)<0){
           alert("下注金额必须为数字!");
              this.value="";
              all_val-=Math.abs(old_val);
        }
          if(!isNaN(this.value) && new_val>=0){
            //判断是否超上限
                // if(eval(new_val) <eval(single_note_max) && all_val<single_field_max){
                        if(val>=0){
                          all_val+=val;
                        }else{
                          all_val-=Math.abs(val);
                        }  
                // }else{
                //   alert("下注金额超过上限!");
                //    this.v