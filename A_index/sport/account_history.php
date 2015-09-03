<html><head>
<title>history_data</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link type="text/css" href="public/css/mem_body_ft.css?=29" rel="stylesheet">
<link type="text/css" href="public/css/mem_body_his.css?=29" rel="stylesheet">
<script src="public/js/jquery-1.7.2.min.js"></script>
    <script>
function onLoad(){
	var select_object = document.getElementById("gtype");
	for(var i=0;i&lt;select_object.length;i++)
	{
		if(select_object.options[i].value == 'ALL')
			select_object.options[i].selected = true;   
	}
	    
	var gdate_object = document.getElementById("gdate");
	for(var i=0;i&lt;gdate_object.length;i++)
	{
		if(gdate_object.options[i].value == '2015-01-16')
			gdate_object.options[i].selected = true;   
	}
	//gdate_object.options[0].selected = true;
	var gdate1_object = document.getElementById("gdate1");
	for(var i=0;i&lt;gdate1_object.length;i++)
	{
		if(gdate1_object.options[i].value == '2015-01-22')
			gdate1_object.options[i].selected = true;   
	}
	
	//gdate1_object.options[0].selected = true;
}

function changeUrl(a) {
    self.location = a;
    //alert(a);
}
function overbars(obj, color) {
	//alert(obj.cells["d_date"].className);  
	var className = obj.cells["d_date"].className;
	if (className == "his_list_none") return;
	obj.cells["d_date"].className = color;
}
function outbars(obj, color) {
	var className = obj.cells["d_date"].className;
	if (className == "his_list_none") return;
	obj.cells["d_date"].className = color;
	//alert("out--"+obj.cells["d_date"].className);
}
</script>
</head>
<body onload="onLoad()" class="bodyset HIS" id="Mall">
<table width="100%" cellspacing="0" cellpadding="0" border="0" id="box">
  <tbody>
  		<tr>
        <td class="top">
			<h1>
				<b>帐户历史摘要</b>
			</h1>
          </td>
      </tr>
  <tr>
    <td class="mem">
    <h2>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="fav_bar">
              <tbody>
			  <tr>
                <td id="page_no">
                </td>
                <td style="line-height:34px;text-align:right">
                按体育查看记录:
                  <select id="gtype" name="gtype">
                    <option value="ALL">所有体育</option>
                    <option value="FT">足球</option>
                    <option value="BK">篮球＆美式足球</option>
                    <option value="TN">网球</option>
                    <option value="VB">排球</option>
                    <option value="BS">棒球</option><!--
                    <option value="CG">综合过关</option>-->
                    <option value="OP">其他</option>
                  </select>
				  <select id="date_s" name="gdate">
                      <?
                      for($i=7;$i>=0;$i--){
                          $date_s=date('Y-m-d',strtotime("- $i day"));
                          echo "<option value=\"$date_s\">$date_s</option>";
                      }
                      ?>

				  </select>
				  <select name="date_e" id="date_e">

                      <?
                      for($i=0;$i<=7;$i++){
                          $date_s=date('Y-m-d',strtotime("- $i day"));
                          echo "<option value=\"$date_s\">$date_s</option>";
                      }
                      ?>
				  </select>
				  <input type="submit" value="查询" onclick="GetHistory()">

                </td>
              </tr>
            </tbody>
			</table>
          </h2>
    <table cellspacing="0" cellpadding="0" border="0" class="game">
      <tbody><tr> 
        <th class="his_time">日期</th>
        <th class="his_wag">投注额</th>
        <th class="his_wag">有效金额</th>
        <th class="his_wag">派彩结果</th>
        <!--th width="25%">有效金额</th-->
      </tr>
        </tbody>
        <tbody id="data">

    </tbody></table> 
	</td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</tbody></table>

</body>
<script>
    GetHistory();
    function GetHistory(){
        var gtype = $('#gtype :selected').val();
        var date_s = $('#date_s :selected').val();
        var date_e = $('#date_e :selected').val();
        $('#data').html('<tr class="color_bg1"><td colspan="4"><center>加载中</center></td></tr>')
        $.post('account_history_data.php',{gtype:gtype,date_s:date_s,date_e:date_e},function(d){
            var html='';
            var AllMoneyTotal=EffecTiveMoneyTotal=WinMoneyTotal=0;
            if(d){
                $.each(d,function(i,v){

                    html+='<tr class="color_bg1">' +
                    '<td id="d_date" class="his_list_none"><span><font color="#CC0000">'+ v.date +' 星期'+ v.week +'</font></span></td>' +
                    '<td class="his_td"><span class="fin_gold"> '+ v.AllMoney +'</span></td>' +
                    '<td class="his_td"> '+ v.EffecTiveMoney +'</td>' +
                    '<td class="his_td"> '+ v.WinMoney +'</td>' +
                    '</tr>';
                    AllMoneyTotal+=parseFloat(v.AllMoney);
                    EffecTiveMoneyTotal+=parseFloat(v.EffecTiveMoney);
                    WinMoneyTotal+=parseFloat(v.WinMoney);

                })
                html+='<tr class="sum_bar right">' +
                    '<td id="d_date" class="center his_total">总计</td>' +
                    '<td class="his_total"><span class="fin_gold"> '+ (AllMoneyTotal).toFixed(2) +'</span></td>' +
                    '<td class="his_total"> '+ EffecTiveMoneyTotal.toFixed(2) +'</td>' +
                    '<td class="his_total"> '+ WinMoneyTotal.toFixed(2) +'</td>' +
                    '</tr>';
                $('#data').html(html)
            }
           else  $('#data').html('<tr class="color_bg1"><td colspan="4"><center>没有数据</center></td></tr>')
        },'json')
    }
</script>

</html>