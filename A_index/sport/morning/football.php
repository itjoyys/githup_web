<?include_once("main_header.php");?>
<input type="hidden" id="touzhutype" value="0">
<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common.js"></script>
<script language="javascript" src="ft_danshi.js" ></script>
<script language="javascript" src="../style/js/bet_match.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>
<script  language="JavaScript">
    function chg_league() {
        var legview = document.getElementById('legView');
        var window_lsm=$("#window_lsm").html()

        $('#legFrame').attr('src','chuangkous.php?lsm=ftz_danshi');
        legview.style.display = '';
        legview.style.top = document.body.scrollTop + 82;
        legview.style.left = document.getElementById('myTable').scrollLeft + 10;
    }
function setleghi(leghight) {
	var legview = document.getElementById('legFrame');
	if ((leghight * 1) > 95) {
		legview.height = leghight;
	} else {
		legview.height = 95;
	}
}
function LegBack() {
	var legview = document.getElementById('legView');
	legview.style.display = 'none';
	reload_var("");
}
</script>
          <table  id="game_table"  cellspacing="0"  cellpadding="0"  class="game">
            <tr>
              <th  class="time">时间</th>
			  <th  class="time">赛事</th>
			  <th  class="h_1x2">独赢</th>
			  <th class="h_r">全场 - 让球</th>
              <th  class="h_ou">全场 - 大小</th>
              <th  class="h_oe">单双</th>
			  <th  class="h_1x2">独赢</th>
			  <th class="h_r">半场 - 让球</th>
              <th  class="h_ou">半场 - 大小</th>
            </tr>
         <tbody  id="datashow">		 

		  <tr><td  colspan="9" align="center" style="background:#fff;">正在加载数据...</td></tr>
	</tbody></table>
	</td>
      </tr>
<Tr><td id="foot"><b>&nbsp;</b></td></Tr>
    </tbody></table>
				<div  id="refresh_down"  class="refresh_M_btn" ><span onclick="javascript:shuaxin(document.getElementById('league').value);" >刷新</span></div>
			</td>
		</tr>
	</tbody>
</table>
<div  id="legView"  style="display:none;"  class="legView">
    <div  class="leg_head" ></div>
	<div><iframe  id="legFrame"  scrolling="no"  frameborder="no"  border="0"  allowtransparency="true"></iframe></div>
    <div  class="leg_foot"></div>
</div>
</body></html>

