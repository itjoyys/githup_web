

$(document).ready(function () {
    //商品加入购物车
    $('#addcart').click(function(){
      var cart_goodsid=$('.shop_detail_box').attr('id');
      var cart_goodsname=$('.title').text();
      var cart_goodsnum=$('.textBox').val();
      var cart_url=$('#addcart').attr('addurl');
      $.ajax({

            type:'POST',
            url:cart_url,
            dataType:'json',
            data:'cart_goodsid='+cart_goodsid+'&cart_goodsnum='+cart_goodsnum+'&cart_goodsname='+cart_goodsname,
            success :function(result){
                if (result.status===true) {
                   $('#order_tisi').css("display","block");
                };

            }



      });

    });

    //立即购买

     $('.buynow').click(function(){
      var cart_goodsid=$('.shop_detail_box').attr('id');
      var cart_goodsname=$('.title').text();
      var cart_goodsnum=$('.textBox').val();
      var cart_url=$('#addcart').attr('addurl');
      var b_url=$('.buynow').attr('buyurl');
      $.ajax({

            type:'POST',
            url:cart_url,
            dataType:'json',
            data:'cart_goodsid='+cart_goodsid+'&cart_goodsnum='+cart_goodsnum+'&cart_goodsname='+cart_goodsname,
            success :function(result){
                if (result.status===true) {
                   window.location.href=b_url;
                };

            }



      });

    });




    //删除购物车里面的商品
    $('.icon_delete').click(function(){
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
     var cartNum = Number(cNum.val())+1;
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







    //    $("#addcart").click(function () {
    //        var pid = $("#hipid").val();
    //        var amount = $("#buyNum").val();
    //        var htmlobj = $.ajax({
    //            type: "get",
    //            url: "/ajax/Data.ashx?t=20&pid=" + pid + "&amount=" + amount + "&time=" + Math.random(),
    //            async: false,
    //            dataType: "html"
    //        });
    //        alert(htmlobj.responseText);
    //    });
    $("#province").change(function () {
        var province = $("#province").val();
        $.ajax({
            type: "get",
            url: "/ajax/Data.ashx?t=60&province=" + province + "&time=" + Math.random(),
            dataType: "html",
            beforeSend: function (XMLHttpRequest) {
                //ShowLoading();
            },
            success: function (data, textStatus) {
                //debugger;
                $("#city").html(data);
                $("#regionId").empty();
            },
            complete: function (XMLHttpRequest, textStatus) {
                //HideLoading();
            },
            error: function () {
                //请求出错处理
                alert("出现错误，请重试  !!");
                return false;
            }
        });
    });

    $("#city").change(function () {
        var city = $("#city").val();
        $.ajax({
            type: "get",
            url: "/ajax/Data.ashx?t=61&city=" + city + "&time=" + Math.random(),
            dataType: "html",
            beforeSend: function (XMLHttpRequest) {
                //ShowLoading();
            },
            success: function (data, textStatus) {
                //debugger;
                $("#regionId").html(data);
            },
            complete: function (XMLHttpRequest, textStatus) {
                //HideLoading();
            },
            error: function () {
                //请求出错处理
                alert("出现错误，请重试  !!");
                return false;
            }
        });
    });


    $("#cartClear").click(function () {
        var siteid = $("#siteid").val();
        var openid = $("#openid").val();
        $.ajax({
            type: "get",
            url: "/ajax/Data.ashx?t=55&time=" + Math.random(),
            dataType: "html",
            beforeSend: function (XMLHttpRequest) {
                //ShowLoading();
            },
            success: function (data, textStatus) {
                //debugger;
                if (parseInt(data) > 0) { // 1:成功
                    window.location = '/show/Shop/?siteid=' + siteid + '&openid=' + openid
                    //alert("成功!");
                    return true;
                }
            },
            complete: function (XMLHttpRequest, textStatus) {
                //HideLoading();
            },
            error: function () {
                //请求出错处理
                alert("出现错误，请重试  !!");
                return false;
            }
        });
    });



    //立即购买
    //    $("#buy-now").click(function () {

    //    });

    //再逛逛 
    $("#notice-cancel").click(function () {
        $('#message-notice').addClass("qb_none");
    });

});

