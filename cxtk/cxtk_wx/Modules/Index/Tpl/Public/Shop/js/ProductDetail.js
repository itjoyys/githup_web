$(document).ready(function () {
    //商品加入购物车
    $('#addcart').click(function(){
      var cart_gid=$('.shop_detail_box').attr('id');
      var cart_goodsname=$('.title').text();
      var cart_goodsnum=$('.textBox').val();
      var cart_url=$('#addcart').attr('addurl');
      $.ajax({
            type:'POST',
            url:cart_url,
            dataType:'json',
            data:'gid='+cart_gid+'&goodsnum='+cart_goodsnum+'&goodsname='+cart_goodsname,
            success :function(result){
                if (result===true) {
                   $('#order_tisi').css("display","block");
                }else{
                    alert('数量超过库存数量');
                };
            }
      });

    });

    //立即购买
     $('.buynow').click(function(){
      var cart_gid=$('.shop_detail_box').attr('id');
      var cart_goodsname=$('.title').text();
      var cart_goodsnum=$('.textBox').val();
      var cart_url=$('#addcart').attr('addurl');
      var b_url=$('.buynow').attr('buyurl');
      $.ajax({
            type:'POST',
            url:cart_url,
            dataType:'json',
            data:'gid='+cart_gid+'&goodsnum='+cart_goodsnum+'&goodsname='+cart_goodsname,
            success :function(result){
                if (result===true) {
                   window.location.href=b_url;
                }else{
                    alert('数量超过库存数量');
                };;
            }
      });
    });
    //删除购物车里面的商品
    $('.icon_delete').click(function(){
        $('#index_tisi').css('display',block);
        var cart_goodsid=$(this).attr('id');
        var cart_url=$(this).attr('url');
        var div_id='#item_'+cart_goodsid;
        $.ajax({
            type:'POST',
            url:cart_url,
            dataType:'json',
            data:'cart_goodsid='+cart_goodsid,
            success :function(result){
                if (result.status===true) {
                   // alert(result.goodsid);
                    $(div_id).remove();  
                };

            }


        });
      

    });


  $('.cartaddQuantity').click(function(){
    //商品详细页商品数量增加
     var cNum = $(this).parent().find('.textBox');
     var num = $('#kucun').attr('num');
     var cartNum = Number(cNum.val())+1;
     if (cartNum > num) { return false};
     cNum.val(cartNum);

  });

    $('.cartdelQuantity').click(function(){
    //商品详细页商品数量减少
     var cNum = $(this).parent().find('.textBox');
     var cartNum = Number(cNum.val())-1;
     if (cartNum<1) {
        return false;
     };
     cNum.val(cartNum);

  });
});

