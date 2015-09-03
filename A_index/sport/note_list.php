
<html><head>
<title>today_wagers</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link type="text/css" href="public/css/mem_body_ft.css?=29" rel="stylesheet">
<link type="text/css" href="public/css/mem_body_his.css?=29" rel="stylesheet">
<script type="text/javascript" src="style/js/jquery.js"></script>
<style>
    body{ font-size: 12px;background: #fff}
    h1,.game td,.game th{ font-size: 12px;}
    .game{width: 100%;}
    .game th{ padding: 3px;}
    .game td{ text-align: center; border-bottom: #b8b8b2 solid 1px; padding:3px;}
</style>
</head>
<body id="MFT">
<form method="POST" action="" name="LAYOUTFORM">
<table cellspacing="0" cellpadding="0" border="0" style="width:100%" id="box">
  <tbody>
  	<tr>
        <td class="top">
			<h1>
				<b>交易状况</b>
			</h1>
        </td>
    </tr>
  <tr>
    <td colspan="2" class="mem">
    <h2>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="fav_bar">
              <tbody><tr>
				<td align="right" style="line-height:34px;">
                    <select id="type" onchange="$('#page').val(1);get_data()" disabled>
                        <option value="0" selected>未结算</option>
                        <option value="1">已结算</option>
                    </select>
                    <select id="page" onchange="get_data()">
                        <option>1</option>
                    </select>
			    </td>
              </tr>
            </tbody>
            </table>
        </h2>
	<table cellspacing="0" cellpadding="0" border="0" class="game">

        <tbody>
        <tr>
            <th width="5%">注单编号</th>
           <!-- <th width="10%">投注日期</th>-->
            <th width="15%">投注类型</th>
            <th width="45%">选项</th>
            <th width="10%">投注额</th>
            <th width="10%">可赢金额</th>
            <th width="10%">注单状态</th>
        </tr>
        </tbody>
        <tbody id="htmlstatus">

        </tbody>
        <tbody id="html_pages">
            <tr class="sum_bar center">

                <td colspan="5">  </td>
                <td>

                </td>
            </tr>
        </tbody>
    </table>

	</td>
  </tr>
  	<tr>
	  <td id="foot"><b>&nbsp;</b></td>
	</tr>
</tbody>
</table>

</form>
<script>
    get_data()
    function BallResult(status){
        if(status==0)  status='未结算';
        else if(status==1)  status='<span style="color:#FF0000;">赢</span>';
        else if(status==2)  status='<span style="color:#00CC00;">输</span>';
        else if(status==8)  status='和局';
        else if(status==3)  status='注单无效';
        else if(status==4)  status='<span style="color:#FF0000;">赢一半</span>';
        else if(status==5)  status='<span style="color:#00CC00;">输一半</span>';
        else if(status==6)  status='进球无效';
        else if(status==7)  status='红卡取消';
        return status;
    }
    function get_data(){
        var type=$('#type').val()
        var p=$('#page').val()
        var html="";
        $.post('note_list_data_.php',{p:p,action:type},function(d){
            var rows=0;
            if(d.d){
                page='';
                for(i=1;i<= d.page;i++){
                    page+="<option value='"+i+"' "+(i==p?"selected":"")+">"+i+"</option>";
                }
                $("#page").html(page)
                $.each(d.d,function(i,v){
                    rows++;
                    var MT='';
                    var status= v.status

                        if(status==0)  status='未结算';
                        else if(status==1)  status='<span style="color:#FF0000;">赢</span>';
                        else if(status==2)  status='<span style="color:#00CC00;">输</span>';
                        else if(status==8)  status='和局';
                        else if(status==3)  status='注单无效';
                        else if(status==4)  status='<span style="color:#FF0000;">赢一半</span>';
                        else if(status==5)  status='<span style="color:#00CC00;">输一半</span>';
                        else if(status==6)  status='进球无效';
                        else if(status==7)  status='红卡取消';

                    var gqqr='';
                    if(v.lose_ok==0 && v.ball_sort=='足球滚球') gqqr='<span style="color:#FF0000;">[确认中]</span>';
                    else if(v.lose_ok==1 && v.ball_sort=='足球滚球') gqqr='<span style="color:#00CC00;">[已确认]</span>';
                    if(v.MB_Inball && v.TG_Inball){
                        MT=' [ '+v.MB_Inball+':'+v.TG_Inball+' ]'
                    }

                    if(v.ball_sort=='串关') info= v.bet_info;
                    else info= v.match_name+"<br>"+ v.master_guest+"<br>"+ v.bet_info+" "+ MT+" "+gqqr ;
                    html+="<tr class='his_even center'>" +
                        "<td>"+ v.number+"</td>" +
                        /* "<td>"+ v.bet_time+"</td>" +*/
                        "<td>"+ v.ball_sort+"<br>"+ v.bet_time+"</td>" +
                        "<td>"+ info+"</td>" +
                        "<td>"+ v.bet_money+"</td>" +
                        "<td>"+ v.bet_win+"</td>" +
                        "<td>"+ status+"</td>" +
                        "</tr>";


                })
            }else{
                html='<tr><td colspan="7" align=center>无数据</td></tr>';
            }
            if(rows==0) html='<tr><td colspan="7" align=center>无数据</td></tr>';

            $("#htmlstatus").html(html)
        },'json')
    }

</script>
</body></html>