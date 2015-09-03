// 将商品加入购物车
$(document).ready(function(){

  //订单提交
  $('.sub_orders').click(function(){
     var order_width=($(window).width()-350)/2+'px';
     var order_name=$('#contactName').val();
     var order_tel=$('#telnum').val();
     var order_tailprice=$('#p_span').text();
     var order_address=$('#address').val();
     var order_note=$('#note').val();
     var order_url=$('.sub_orders').attr('url');

     if (order_name=="" || order_tel=="" || order_address=="") {

        $('#order_tisi').css("left",order_width);
        $('#order_tisi').css("display","block");
        

     }else{

       $.ajax({
         url:order_url,
         type:'POST',
         data:'order_name='+order_name+'&order_tel='+order_tel+'&order_address='+order_address+'&order_tailprice='+order_tailprice+'&order_note='+order_note,
         dataType:'json',
         success:function(result){
           
             if (result.status===true) {
               $('#order_success').css("display","block");
               // location.href ="/weipan/index.php/Index/Bookdinner/orderlist";
         }
       }
      })

     }

  });


  $('#cancel').click(function(){

      $('#order_tisi').css("display","none");
  })


  //购物车数量更新

  $('.cartaddQuantity').click(function(){
    //购物车数量增加
     var cNum = $(this).parent().find('.textBox');
     var cartNum = Number(cNum.val())+1;
     ajaxUpdateCartNum(cartNum,cNum);

  });

    $('.cartdelQuantity').click(function(){
    //购物车数量减少
     var cNum = $(this).parent().find('.textBox');
     var cartNum = Number(cNum.val())-1;
     ajaxUpdateCartNum(cartNum,cNum);

  });



  // 将商品加入购物车
   $("label").click(function(){
      var labelclass=$(this).attr("class");
   
      if (labelclass=="current") {
        //购物车减一
        $(this).removeClass("current");
        var goodsNum = parseInt($('#goodsNum').text())-1;
       // $('#goodsNum').html(goodsNum);
        var goodsNum_state=1;
        var goodsid = $(this).attr("goodsid");
        ajaxUpdateGoodsNum(goodsNum,goodsid,goodsNum_state);

      }else{
        //购物车加一
       $(this).addClass("current"); //给当前元素添加样式；
       var goodsNum = parseInt($('#goodsNum').text())+1;
       var goodsNum_state=2;

       var goodsid = $(this).attr("goodsid");
      // alert(goodsNum);
       ajaxUpdateGoodsNum(goodsNum,goodsid,goodsNum_state);
    
      }
 
});

   /**
     异步更新购物车商品数量

   */
    function ajaxUpdateGoodsNum(goodsNum,goodsid,goodsNum_state){
      var url= $('#goodsNum').attr('url');
      $.ajax({
         url:url,
         type:'POST',
         data:'gid='+goodsid+'&num='+goodsNum+'&state='+goodsNum_state,
         dataType:'json',
         success:function(result){
           
             if (result.status===true) {
                $('#goodsNum').html(result.num);
             }
           
         }
      })
    }

      /**
     更新购物车商品数量

   */
    function ajaxUpdateCartNum(goodsNum,cNum){
      if (goodsNum<1) {
         return false;
      };
      var cart_goodsid = cNum.attr("id");
      var url= $('.cartaddQuantity').attr('url');
      $.ajax({
         url:url,
         type:'POST',
         data:'gid='+cart_goodsid+'&num='+goodsNum,
         dataType:'json',
         success:function(result){
             
             if (result.status===true) {
               cNum.val(result.num);
                //alert(result.tailprice);
               $('#p_span').html(result.tailprice);
               $('#p_b').html(result.tailprice);
               $('#p_s').html(result.tailprice);
             };
             
         }
      })
    }

 //提交表单验证



});





