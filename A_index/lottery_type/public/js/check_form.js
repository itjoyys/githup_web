function check_submit(){

    if($("#allgold").text()==0){
      alert("请填写下注金额！");
      return false;
    }
}

function isPositiveNum(s){//是否为正整数
    var re = /^[0-9]*[1-9][0-9]*$/ ;
    return re.test(s)
}

$(function(){

 // //下注总金额计算及表单验证
       var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
       var single_note_max = $("#single_note_max").text();//单注金额上限20000
       var single_note_min = $("#single_note_min").text();
       // single_field_max = 200000;
       // single_note_max = 20000;
       // alert(2);
     //下注金额

	  var all_val=0;
    var i;
    var input1=$("input[type='text'][name]");


    var old_val;
    var new_val;
    var val;
      // for(i=0;i<input1.length;i++){

        //验证非法字符
        input1.keyup(function() {
          var newval3 = $(this).val();


          var reg1 = /([a-zA-Z])/ig;
          var reg = /([`~!@#$%^&*()_+<>?:"{},\/;'[\]])/ig;
           var reg2 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;

          if(isNaN(newval3)){
            $(this).val($(this).val().replace(reg, ""));

            $(this).val($(this).val().replace(reg1, ""));

            $(this).val($(this).val().replace(reg2, ""));


            // $(this).val("");
          }

        });

       input1.focus(function(){
          if($(this).val() == ""){
             old_val=0;
             $("#old_val").text(old_val);

          }else{
            old_val=$(this).val();
            $("#old_val").text(old_val);

          }

        })


      input1.blur(function(){
        new_val=$(this).val();
        if(new_val==""){
          new_val=0;
        }
        val=new_val-$("#old_val").text();
        if(new_val == ""){
          new_val=0;
          all_val += val;
        }else if(parseInt(new_val)<0){
           alert("下注金额必须为数字!");
              $(this).val("0");
              all_val-=Math.abs($("#old_val").text());
        }else if(!isPositiveNum(new_val) && new_val != 0){
			alert("下注金额必须为正整数!");
			$(this).val(0);
        }else{
          if(!isNaN($(this).val()) && new_val>=0){
              //判断是否超上限
            if(val>=0 && (all_val+val) >parseInt(single_field_max)){
                alert("下注金额超过上限!");
                $(this).val(0);
                all_val-=Math.abs($("#old_val").text());
            }else{
               if(parseInt(new_val) =<parseInt(single_note_max) && all_val=<parseInt(single_field_max) && parseInt(new_val) >=parseInt(single_note_min)){
                    if(val>=0){
                      all_val+=val;
                    }else{
                      all_val-=Math.abs(val);
                    }
               }else{
                 alert("下注金额超过上限!");
                  $(this).val(0);
                  all_val-=Math.abs($("#old_val").text());
               }
            }
          }else{
                alert("下注金额必须为数字!");
                $(this).val(0);
                all_val-=Math.abs($("#old_val").text());
          }
        }
       document.getElementById("allgold").innerHTML=all_val;
    })
      // }

})


