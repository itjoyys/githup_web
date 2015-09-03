$(document).ready(function(){
  $('.btn-search').click(function(){
     var keyword=$('#newkeyword').val();
     var url=$('#newkeyword').attr('purl');

     if (keyword=="") return false;
     
     $.ajax({
         url:url,
         type:'POST',
         data:'keyword='+keyword,
        // dataType:'json',
         success:function(result){
           
             if (result.status===false) {
               $('.new-srch-lst').css("display","block");
               // location.href ="/weipan/index.php/Index/Bookdinner/orderlist";
         }
     }

     })



  });


})