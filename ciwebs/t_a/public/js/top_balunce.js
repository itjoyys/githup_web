       //下拉余额
$(window).load(function() {
    "use strict";
    var ele_obalance_item_wrap_html = $('<div>').append($('#js-ele-obalance-item-wrap')).html(),
        obalanceCloseTimer, // 自動關閉 timer
        $obalanceWrap,     // 額度包裹物件
        obalanceWrapH,     // 額度包裹高度
        obalanceWrapW,     // 額度包裹寬度
        $miBody = top.mem_index?$('body',top.mem_index.document):$('body');
        $('body').prepend(ele_obalance_item_wrap_html);
        //$obalanceWrap = top.mem_index?$('#js-ele-obalance-item-wrap', top.mem_index.document):$('#js-ele-obalance-item-wrap');
        $obalanceWrap = $('#js-ele-obalance-item-wrap');
    obalanceWrapH = $obalanceWrap.height();
    obalanceWrapW = $obalanceWrap.width();
    $obalanceWrap.css({ display: 'none', height: '0'});
    $('#js-ele-obalance-wrap')
        .on("mouseenter", function() {
            if(typeof obalanceCloseTimer != 'undefined'){
                clearTimeout(obalanceCloseTimer);
            }
            obalanceOpen($('#js-ele-obalance-wrap'));
        })
        .on("mouseleave", function() {
            obalanceAutoClose();
        });
    $obalanceWrap
        .on("mouseenter", function() {
            clearTimeout(obalanceCloseTimer);
            obalanceOpen($('#js-ele-obalance-wrap'));
        })
        .on("mouseleave", function() {
            obalanceAutoClose();
        });

    $(window).scroll(function(){
        obalanceAutoClose();
    });  
         
     if(top.mem_index){
        var Val_AGBalance = $(top.mem_index.document.getElementById('_AGBalance')).find("strong");
        }else{
            var Val_AGBalance = $('#_AGBalance').find("strong");
        }
                
     if(top.mem_index){
        var Val_MGBalance = $(top.mem_index.document.getElementById('_MGBalance')).find("strong");
        }else{
         var Val_MGBalance = $('#_MGBalance').find("strong");
        } 
      if(top.mem_index){
        var Val_OGBalance = $(top.mem_index.document.getElementById('_OGBalance')).find("strong");
        }else{
         var Val_OGBalance = $('#_OGBalance').find("strong");
        } 
        if(top.mem_index){
        var Val_CTBalance = $(top.mem_index.document.getElementById('_CTBalance')).find("strong");
        }else{
         var Val_CTBalance = $('#_CTBalance').find("strong");
        } 
        if(top.mem_index){
        var Val_LEBOBalance = $(top.mem_index.document.getElementById('_LEBOBalance')).find("strong");
        }else{
         var Val_LEBOBalance = $('#_LEBOBalance').find("strong");
        }
        if(top.mem_index){
        var Val_BBINBalance = $(top.mem_index.document.getElementById('_BBINBalance')).find("strong");
        }else{
         var Val_BBINBalance = $('#_BBINBalance').find("strong");
        }  
    function obalanceOpen(o){
        var centerSet = $miBody.width() - $('body').width();
            centerSet = (centerSet > 0) ? centerSet/2 : 0;

        var offSet = o.offset(),
            objLeft = offSet.left + centerSet,
            objTop = offSet.top + o.height(),
            MaxLeft = offSet.left + obalanceWrapW,
            MaxBody = $('body').width() - obalanceWrapW,
                        parentHeight = $(window).height();
            
        if ( MaxLeft > MaxBody) {
            objLeft = ( objLeft - obalanceWrapW ) + $('#js-ele-obalance-wrap').width();
        };
        var time_dd = new Date();
        if (time_dd.getSeconds()%1 == 0) {
             $.ajax({
                 type: 'GET',
                 url: '/video/games/getallbalance.php?action=save',
                 dataType: "json",
                 success: function (rdata) {
                     if(rdata.data.Code ==10017){
                          if(rdata.data.mgstatus){
                            Val_MGBalance.html(parseFloat(rdata.data.mgbalance).toFixed(2));
                          }
                          if(rdata.data.agstatus){
                            Val_AGBalance.html(parseFloat(rdata.data.agbalance).toFixed(2));
                          }
                          if(rdata.data.agstatus){
                            Val_OGBalance.html(parseFloat(rdata.data.ogbalance).toFixed(2));
                          }
                          if(rdata.data.agstatus){
                            Val_CTBalance.html(parseFloat(rdata.data.ctbalance).toFixed(2));
                          }
                          if(rdata.data.agstatus){
                            Val_LEBOBalance.html(parseFloat(rdata.data.lebobalance).toFixed(2));
                          }
                          if(rdata.data.agstatus){
                            Val_BBINBalance.html(parseFloat(rdata.data.bbinbalance).toFixed(2));
                          }
                      }
                 }
           }); 
        };
        $obalanceWrap
            .stop()
            .css({ display: 'block', 'left':objLeft, 'top':objTop, 'bottom':'auto'})
            .animate({ opacity: '1', height: obalanceWrapH}, 300, 'easeOutQuint');
    }

    function obalanceAutoClose(){
        $obalanceWrap.stop().animate({opacity: '0', height: '0'}, 300, 'easeOutQuint', function () {
            $obalanceWrap.css({ display: 'none' });
        });
    }
});