$(document).ready(function() {
    FBRresults(0);
    $('.sportmenu li').click(function() {
        $('.sportmenu li').removeClass('c2')
        $(this).addClass('c2')
    })

});

/*足球-赛果*/
function FBRresults(t) {
    type = 1;
    t=getRresultsDate(t,1);
    var table = '';
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">上半场比分</th>'+
                '<th class="h_1x2">全场比分</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/FBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                
                if(v.TG_Inball<0){
                    s1='<td colspan=2 class="b_cen"  id="">赛事无效</td>'
                    x1=s1;
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball_HR+'</td>' +
                            '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball_HR+'</td>'+
                            '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>';
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr *class*="" id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_MatchTime+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +
                            v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr *class*="" id="">'+
                            x1+
                        '</tr>';

            });
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        
        
        $('#data').html(html);
        $('#tablename').html('足球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        
        
    });

}

/*篮球-赛果*/
function BKRresults(t) {
    type = 2;
    t=getRresultsDate(t,2);
        
    var table = '';
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">1</th>'+
                '<th class="h_1x2">2</th>'+
                '<th  class="h_1x2">3</th>'+
                '<th class="h_1x2">4</th>'+
                '<th  class="h_1x2">上半</th>'+
                '<th class="h_1x2">下半</th>'+
                '<th  class="h_1x2">加时</th>'+
                '<th class="h_1x2">全场</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/BKRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                //将空的数据赋值为长度为0的字符串；
                for (nv in v){
                    if(v[nv]===null){
                        v[nv]='';
                    }
                }
                var s1,x1,mcname;
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="10"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr *class*="" id="">' +
                        '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br />'+v.Match_Time+'</td>' +
                        '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +
                        v.Match_Guest+' </td>' +
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_1st+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_2st+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_3st+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_4st+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_HR+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_ER+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_Add+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball+'</td>'+
                        '</tr>'+
                        '<tr *class*="" id="">'+
                        '<td  class="b_cen"  >'+v.TG_Inball_1st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_2st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_3st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_4st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_HR+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_ER+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_Add+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball+'</td>'+
                        '</tr>';

            });
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=10 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        
        
        $('#data').html(html);
        $('#tablename').html('篮球结果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        
        
    });

}
/*网球-赛果*/
function TNRresults(t) {
    type = 3;
    t=getRresultsDate(t,3);
        
    var table = '';
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">完赛(局)</th>'+
                '<th class="h_1x2">完赛(盘)</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/TNRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            });
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        
        
        $('#data').html(html);
        $('#tablename').html('网球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        
        
    })

}

/*排球-赛果*/
function VBRresults(t) {
    type = 4;
    t=getRresultsDate(t,4);
        
    var table = '';
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">完赛(局)</th>'+
                '<th class="h_1x2">完赛(盘)</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/VBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            });
            
            html += '</table>';

        } else {
            html += '<tr><td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        
        
        $('#data').html(html);
        $('#tablename').html('排球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        
        
    })

}

/*棒球-赛果*/
function BBRresults(t) {
    type = 5;
    t=getRresultsDate(t,5);
        
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">半场</th>'+
                '<th class="h_1x2">全场</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/BBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball_HR+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball_HR+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        
        
        $('#data').html(html)
        $('#tablename').html('棒球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        
        
    })

}
/*获取比赛日期*/
function getRresultsDate(t,type){
    if (t!==null){
        t=parseInt(t)==='NaN'?0:parseInt(t);
        $("#RresultsDate").val(t);
    }
    return t;
}
/*更改比赛日期*/
function setRresultsDate(){
    t=$('#RresultsDate').val();
    if(type==1){
      FBRresults(t);
    }
    if(type==2){
       BKRresults(t);
    }
    if(type==3){
       TNRresults(t);
    }
    if(type==4){
       VBRresults(t); 
    }
    if(type==5){
       BBRresults(t);
    }
    
}
