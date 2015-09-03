<script type="text/javascript">
var timer;  
$(document).ready(function(){
	setFastType();
	$("#FastPanel").animate({"top":25},500);
	$(".tab_004 ~ dd").hide();

	

    $("#txtrmb").blur(function(event) {
    	var single_note_max = $("#single_note_max").text();
    	
    	if(parseInt(single_note_max) <= parseInt($(this).val())){
    		alert("超过单注限额！");
    		$(this).val("");
    	}
    });
});
function setFastType(){
	$(".tab_004").click(function () {
		$("."+$(this).attr("id")+"_sub").slideToggle("fast");
		$(this).toggleClass("tab_004_on");
	})
}


</script>

<div id="FastPanel" style="top: 25px;left:800px;">
	<dl id="dlCon">
		<dt class="tbtitle4 hset">快速选择</dt>
		<dt class="dt_s">
			<b>金额：</b><input id="txtrmb" style="text-align:right" class="input1" onkeydown="return Yh_Text.CheckNumber2()"  maxlength="6" size="6" type="text" js='js'>
		</dt>
		<dt class="tab_004" id="sx">生肖</dt>
		<dd class="sx_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
						<td onclick="quick_select(0,this)">鼠</td>
						<td onclick="quick_select(1,this)">牛</td>
						<td onclick="quick_select(2,this)">虎</td>
					</tr>
					<tr class="t15">
						<td onclick="quick_select(3,this)">兔</td>
						<td onclick="quick_select(4,this)">龙</td>
						<td onclick="quick_select(5,this)">蛇</td>
					</tr>
					<tr class="t15">
						<td onclick="quick_select(6,this)">马</td>
						<td onclick="quick_select(7,this)">羊</td>
						<td onclick="quick_select(8,this)">猴</td>

					</tr>	
					<tr class="t15">
						<td onclick="quick_select(9,this)">鸡</td>
						<td onclick="quick_select(10,this)">狗</td>
						<td onclick="quick_select(11,this)">猪</td>
					</tr>
				</tbody>
			</table>
		</dd>
		<dt class="tab_004" id="sb">色波</dt>
		<dd class="sb_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
					  <td onclick="quick_select(12,this)">红</td>
					  <td onclick="quick_select(13,this)">蓝</td>
					  <td onclick="quick_select(14,this)">绿</td>
					</tr>
				</tbody>
			</table>
		</dd>
		<dt class="tab_004" id="ds">单双</dt>
		<dd class="ds_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
						<td onclick="quick_select(19,this)">单</td>
						<td onclick="quick_select(15,this)" colspan="2">合单</td>
					</tr>
					<tr class="t15">
						
						<td onclick="quick_select(20,this)">双</td>
						<td onclick="quick_select(16,this)" colspan="2">合双</td>
					</tr>
				</tbody>
			</table>
		</dd>
		<dt class="tab_004" id="dx">大小</dt>
		<dd class="dx_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
					  <td onclick="quick_select(17,this)">大</td>
					  <td onclick="quick_select(21,this)" colspan="2">合大</td>
					</tr>
					<tr class="t15">
						<td onclick="quick_select(18,this)">小</td>
						<td onclick="quick_select(22,this)" colspan="2">合小</td>
					</tr>
				</tbody>
			</table>
		</dd>
		<dt class="tab_004" id="bb">半波</dt>
		<dd class="t15 bb_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
					  <td onclick="quick_select(23,this)">红单</td>
					  <td onclick="quick_select(24,this)">红双</td>
					  <td onclick="quick_select(25,this)">红大</td>

					</tr>    
					<tr class="t15">
					  <td onclick="quick_select(26,this)">红小</td>
					  <td onclick="quick_select(27,this)">蓝单</td>
					  <td onclick="quick_select(28,this)">蓝双</td>
					</tr>
					<tr class="t15">
					  <td onclick="quick_select(29,this)">蓝大</td>
					  <td onclick="quick_select(30,this)">蓝小</td>
					  <td onclick="quick_select(31,this)">绿单</td>
					</tr>
					<tr class="t15">
					  <td onclick="quick_select(32,this)">绿双</td>
					  <td onclick="quick_select(33,this)">绿大</td>
					  <td onclick="quick_select(34,this)">绿小</td>
					</tr>
				</tbody>
			</table>
		</dd>
		<dt class="tab_004" id="ws">尾数</dt>
		<dd class="ws_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
					  <td onclick="quick_select(40,this)">0尾</td>
					  <td onclick="quick_select(41,this)">1尾</td>
					  <td onclick="quick_select(42,this)">2尾</td>
					</tr>
					<tr class="t15">
					  <td onclick="quick_select(43,this)">3尾</td>
					  <td onclick="quick_select(44,this)">4尾</td>
					  <td onclick="quick_select(45,this)">5尾</td>
					</tr>
					<tr class="t15">
					  <td onclick="quick_select(46,this)">6尾</td>
					  <td onclick="quick_select(47,this)">7尾</td>
					  <td onclick="quick_select(48,this)">8尾</td>
					</tr>
					<tr class="t15">
					  <td onclick="quick_select(49,this)">9尾</td>
					  <td></td>
					  <td></td>
					</tr>
				</tbody>
			</table>
		</dd>

		<dt class="tab_004" id="qt">其他</dt>
		<dd class="qt_sub" style="display: none;">
			<table style="text-align: center;" class="game_table" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="260">
				<tbody>
					<tr class="t15">
						  <td onclick="quick_select(50,this)">大单</td>
						  <td onclick="quick_select(51,this)">大双</td>
						  <td onclick="quick_select(52,this)">小单</td>
						</tr>    
						<tr class="t15">
						  <td onclick="quick_select(53,this)">小双</td>
						  <td onclick="quick_select(54,this)">家禽</td>
						  <td onclick="quick_select(55,this)">野兽</td>
						</tr>
						<tr class="t15">
						  <td onclick="quick_select(56,this)">红单大</td>
						  <td onclick="quick_select(57,this)">红单小</td>
						  <td onclick="quick_select(58,this)">红双大</td>
						</tr>    
						<tr class="t15">
						  <td onclick="quick_select(59,this)">红双小</td>
						  <td onclick="quick_select(60,this)">蓝单大</td>
						  <td onclick="quick_select(61,this)">蓝单小</td>
						</tr>
						<tr class="t15">
						  <td onclick="quick_select(62,this)">蓝双大</td>
						  <td onclick="quick_select(63,this)">蓝双小</td>
						  <td onclick="quick_select(64,this)">绿单大</td>
						</tr>    
						<tr class="t15">
						  <td onclick="quick_select(65,this)">绿单小</td>
						  <td onclick="quick_select(66,this)">绿双大</td>
						  <td onclick="quick_select(67,this)">绿双小</td>
						</tr>
				</tbody>
			</table>
		</dd>
		<dt>
				<input id="send" class="button_a" onclick="ChkSubmit();" name="send" value="发送" type="button">
				<input class="button_a" onclick="clear_select();" name="Submit23" value="重置" type="reset">
				<input type="hidden" name="check" id="check">
		</dt>
	<dl>

</dl></dl></div>

<script src="./public/js/fast.js" type="text/javascript"></script>




