<?include_once("main_header.php");?>
<input type="hidden" id="touzhutype" value="0">
<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common.js"></script>
<script language="javascript" src="ft_shangbanbodan.js" ></script>
<script language="javascript" src="../style/js/bet_match.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>

<script  language="JavaScript">


function chg_league() {
	var legview = document.getElementById('legView');
	try {
		
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.location.href = "chuangkous.php?lsm=ft_shangbanbodan";
		
	
	
	
	
		//legFrame.location.href = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;
	} catch(e) {
	
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.src = "chuangkous.php?lsm=ft_shangbanbodan";
		
	
	
	
	
		//legFrame.src = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;

	}
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
			  <th  class="time" style="width:120px;">主客队伍</th>
			  <th  class="h_1x2">1:0</th>
			  <th class="h_1x2">2:0</th>
              <th class="h_1x2">2:1</th>
              <th class="h_1x2">3:0</th>
			  <th class="h_1x2">3:1</th>
			  <th class="h_1x2">3:2</th>
              <th class="h_1x2">4:0</th>
              <th class="h_1x2">4:1</th>
			 <th class="h_1x2">4:2</th>
              <th class="h_1x2">4:3</th>
			  <th class="h_1x2">0:0</th>
			  <th class="h_1x2">1:1</th>
              <th class="h_1x2">2:2</th>
              <th class="h_1x2">3:3</th>
			  <th class="h_1x2">4:4</th>
              <th class="h_1x2">UP5</th>
		
            </tr>
         <tbody  id="datashow">	
		 <!--
		 <tr>
              <td class="b_cen" rowspan=2>时间</td>
			  <td  class="b_cen" rowspan=2 width="13%">主客队伍</td>
			  <td class="b_cen" width="5%">1:0</td>
			  <td class="b_cen" width="5%">2:0</td>
              <td class="b_cen" width="5%">2:1</td>
              <td class="b_cen" width="5%">3:0</td>
			  <td class="b_cen" width="5%">3:1</td>
			  <td class="b_cen" width="5%">3:2</td>
              <td class="b_cen" width="5%">4:0</td>
              <td class="b_cen" width="5%">4:1</td>
			 <td class="b_cen" width="5%">4:2</td>
              <td class="b_cen" width="5%">4:3</td>
			  <td class="b_cen" width="5%" rowspan=2>0:0</td>
			  <td class="b_cen" width="5%" rowspan=2>1:1</td>
              <td class="b_cen" width="5%" rowspan=2>2:2</td>
              <td class="b_cen" width="5%" rowspan=2>3:3</td>
			  <td class="b_cen" width="5%" rowspan=2>4:4</td>
              <td class="b_cen" width="5%" rowspan=2>UP5</td>
		
            </tr>
			<tr>
			  <td class="b_cen">1:0</td>
			  <td class="b_cen">2:0</td>
              <td class="b_cen">2:1</td>
              <td class="b_cen">3:0</td>
			  <td class="b_cen">3:1</td>
			  <td class="b_cen">3:2</td>
              <td class="b_cen">4:0</td>
              <td class="b_cen">4:1</td>
			 <td class="b_cen">4:2</td>
              <td class="b_cen">4:3</td>
		
            </tr>-->

		  <tr><td  colspan="18" align="center" style="background:#fff;">正在加载数据...</td></tr>
	
	</tbody></table>
  
	
	</td>
      </tr>
<Tr><td id="foot"><b>&nbsp;</b></td></Tr>
    </tbody></table>
				
				
				<div  id="refresh_down"  class="refresh_M_btn" ><span>刷新</span></div>
				

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


