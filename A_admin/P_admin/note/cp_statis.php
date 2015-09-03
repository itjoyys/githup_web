<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../Lottery/GetOdds.php");
$CpType= new CaiPaioType();
$CpIdName= $CpType->Cp;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>彩票即時注單</title>
   <script  src="../public/js/jquery-1.7.min.js"></script>
    <link  rel="stylesheet"  href="../public/t/css/main.css"  type="text/css">
    <link  rel="stylesheet"  href="../public/t/css/styleCss.css"  type="text/css">
    <link rel="stylesheet" href="../public/css/easydialog.css" />
    <link  rel="stylesheet"  href="../public/css/cp_statis.css"  type="text/css">
    <link  rel="stylesheet"  href="../public/<?=SITEID?>/css/main.css"  type="text/css">

</head>
<body>
<div class="warp">
    <div class="top" id="alert_pos">
        <ul>
            <li class="input_002 top_menu_1">
                彩票即时注單
            </li>
            <li>
                彩票类型：
                    <select id="cp_type"  onchange="get_cp_type('',1)"  CLASS="za_text">
                        <?
                        foreach($CpIdName as $k =>$r){
                            echo "<option value='$k'>$r</option>";
                        }
                        ?>

                    </select>
            </li>
            <li class="input_sub">
                期数：
                    <select id="cp_qs_select" CLASS="za_text"  onchange="$('#cp_qs').val(this.value);get_cp_type('',0);">
                    <option>请选择彩票类型</option>
                    </select>
            </li>
            <li>
                手动输入期数查询: <input id="cp_qs" CLASS="za_text"  onchange="get_cp_type('',0)">
            </li>
            <li>
                <button onclick="get_cp_type(0,0)" class="za_button">查看</button>
            </li>
            <li style="display: none;">
                刷新：
                    <select>
                    <option>001</option>
                    <option>002</option>
                    </select>
            </li>
        </ul>

    </div>
    <div class="clear"></div>
    <div class="main">
        <div id="show">

        </div>
        <div id="data" style="display: none"></div>
    </div>

</div>

<script src="../public/js/easydialog.min.js"></script>
<script>

function get_cp_qs(type){
    $('#show').html('期数加载中')
    $.post('cp_stats_data.php',{action:'get_cp_qs',cp_type:type},function(d){
        if(d!=1){
            $('#cp_qs_select').html(d);
            $('#cp_qs').val($('#cp_qs_select :selected').val())
            get_cp_type(0,0);
        }else{
            get_cp_type(0,0);
        }

    })
}
function get_cp_type(type,qs_action){
    $('#show').html('loading')

    if(!type){
        type=$('#cp_type :selected').val()

    }else{

        $('#cp_type option:eq('+(type-1)+')').attr('selected','selected')

    }
    if(qs_action==1) get_cp_qs(type)
    var qs=$('#cp_qs').val()
    var cp_name=$('#cp_type :selected').html()
    if(qs==''){ qs=1}

        $.post('cp_stats_data.php',{action:'cp_type_show',cp_type:type,cp_name:cp_name,qs:qs},function(d){
            $('#show').html(d)
            span_data=$('.cp_zd')
            $.each(span_data,function(){
                if($(this).html()!='0/0') {
                    $(this).addClass('ball_red');
                    $(this).parent().addClass('xs')
                }
                else  {
                    $(this).removeClass('ball_red');
                    $(this).parent().removeClass('getlist')
                }
            })
          //  alert(span_data.index())

        })


}


    var j = function(){
        return document.getElementById(arguments[0]);
    };

    var btnFn = function( e ){
        alert( e.target );
        return false;
    };
function gotopage(p)
{
    var w=$(window).width()-150;
    var h=$(window).height()+150;
    $('.easyDialog_text').html('数据加载中');
    var id=$('#list_id').html();
    var a=id.split('_');
    var qs=$('#cp_qs').val();
    var cp_name=$('#cp_type :selected').html();
    if(cp_name=='北京赛车PK拾' || cp_name=='六合彩') m3=a[3];
    else m3='';
    m1=a[1];
    m2=a[2];
    $.post('cp_stats_data.php',{action:'getlist',qs:qs,m1:m1,m2:m2,m3:m3,cp_name:cp_name,p:p},function(d){
        num=d['num'];
        var select=d['page'];

        var html='<table cellspacing="1" cellpadding="1" class="table_cp table_alert">' +
            '<tr>' +
            '<th colspan="7" id="list_id">'+id+'</th>' +
            '<th></th>' +
            '<th><select onchange="gotopage(this.value)">'+select+'</select></th></tr>' +
            '<tr><th>序号</th><th>注单号</th><th>下单时间</th><th>期数</th><th>账号</th><th>下注金额</th><th>内容</th><th>状态</th></tr>';
        //var title=$('#cp_type :selected').html()+' '+$('#cp_qs ').val()+' - <span id="list_id">'+id+'</span>';

        $.each(d['data'],function(i,v){
            html+='<Tr>' +
                '<Td>'+ v.id+'</td>' +
                '<Td>'+ v.did+'</td>' +
                '<Td>'+ v.addtime+'</td>' +
                '<Td>'+ v.type+'</td>' +
                '<Td>'+ v.qishu+'</td>' +
                '<Td>'+ v.username+'</td>' +
                '<Td>'+ v.money+'</td>' +
                '<Td>'+ v.mingxi_1+' | '+ v.mingxi_2+'  '+ (v.mingxi_3?'|'+v.mingxi_3:'')+'@ '+ v.odds+'</td>' +
                '<Td>'+ (v.js==1?"已结算":"未结算")+'</td>' +
                '</tr>';
        })
        html+='<tr><td colspan="9"  style="text-align: right"><button onclick="$(\'#data\').hide()" id="close">关闭(ESC)</button></td></tr>';
        html+='</table>';
        html+='<div class="clear"></div> ';
        $("#data").html(html);
        $('#data').css({"width":w});
      //  $('.easyDialog_text').html(html)
      //  $('.easyDialog_text').css({w:w,h:h});
    },'json')
}

$('.getlist').live('click',function(){
    var w=$(window).width()-150;
    var h=$(window).height()+150;
   // alert(w+'-'+h)
    var x= this;

    var id=$(this).attr('id')
    var a=id.split('_');
    var qs=$('#cp_qs').val()
    var cp_name=$('#cp_type :selected').html()
    if(cp_name=='北京赛车PK拾' || cp_name=='六合彩') m3=a[3];
    else m3='';
    m1=a[1];
    m2=a[2];

    $.post('cp_stats_data.php',{action:'getlist',qs:qs,m1:m1,m2:m2,m3:m3,cp_name:cp_name},function(d){
        num=d['num'];
        var select=d['page'];
        var html='<table cellspacing="1" cellpadding="1" class="table_cp table_alert">' +
            '<tr><th colspan="7" id="list_id">'+id+'</th><th></th><th><select onchange="gotopage(this.value)">'+select+'</select></th></tr>' +
            '<tr><th>序号</th><th>注单号</th><th>下单时间</th><th>彩种</th><th>期数</th><th>账号</th><th>下注金额</th><th>内容</th><th>状态</th></tr>';
        var title=$('#cp_type :selected').html()+' '+$('#cp_qs ').val()+' - <span id="list_id">'+id+'</span>';

        $.each(d['data'],function(i,v){
            html+='<Tr>' +
                '<Td>'+ v.id+'</td>' +
                '<Td>'+ v.did+'</td>' +
                '<Td>'+ v.addtime+'</td>' +
                '<Td>'+ v.type+'</td>' +
                '<Td>'+ v.qishu+'</td>' +
                '<Td>'+ v.username+'</td>' +
                '<Td>'+ v.money+'</td>' +
                '<Td>'+ v.mingxi_1+' | '+ v.mingxi_2+'  '+ (v.mingxi_3?'|'+v.mingxi_3:'')+'@ '+ v.odds+'</td>' +
                '<Td>'+ (v.js==1?"已结算":"未结算")+'</td>' +
                '</tr>';
        })
        html+='<tr><td colspan="9"  style="text-align: right"><button onclick="$(\'#data\').hide()" id="close">关闭(ESC)</button></td></tr>';
        html+='</table>';
        html+='<div class="clear"></div> ';
        $("#data").html(html);
        //$("#data").css("opacity","0.3");

        x_=$(window).scrollTop();
       // alert(x_)
        easyDialog.open({
           /* container : {
                header : title,
                content : html,
                fixed : false

            },*/
            container  : 'data',
            fixed : true
            ,
            follow : "alert_pos",
            followX : +75,
            followY : +x_

        });
       // $('.easyDialog_text').css({"width":w,"height":h});
        $('#data').css({"width":w});
    },'json')

})
get_cp_type(0,1)

</script>

</body>
</html>
