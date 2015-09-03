
$(document).ready(function(){
     $('.bottom_a').click(function(){
            $('#form').css("display","block");

      });

     $('#tijiao').click(function(){

      var name=$('.iname').val();
      var content=$('.icontent').val();
      var siteid=$('#formid').val();
      var purl=$('#form').attr('purl');

      if (name!="" && content!="") {
        $.ajax({
         url:purl,
         type:'POST',
         data:'name='+name+'&content='+content+'&siteid='+siteid,
         dataType:'json',
         success:function(result){
           
             if (result.status===true) {
              // $('#order_success').css("display","block");
                $('#form').css("display","none");
              
         }
       }
      })

      };

     })


})

