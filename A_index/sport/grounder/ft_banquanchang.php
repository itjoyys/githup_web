<?include_once("main_header.php");?>
<input type="hidden" id="touzhutype" value="0">
<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common.js"></script>
<script language="javascript" src="ft_banquanchang.js" ></script>
<script language="javascript" src="../style/js/bet_match.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>

<script  language="JavaScript">


function chg_league() {
	var legview = document.getElementById('legView');
	try {
		
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.location.href = "chuangkous.php?lsm=ft_banquanchang";
		
	
	
	
	
		//legFrame.location.href = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;
	} catch(e) {
	
		
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.src = "chuangkous.php?lsm=ft_banquanchang";
		
	
	
	
	
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
			  <th  class="h_1x2">主/主</th>
			  <th class="h_1x2">主/和</th>
              <th class="h_1x2">主/客</th>
              <th class="h_1x2">和/主</th>
			  <th class="h_1x2">和/和</th>
			  <th class="h_1x2">和/客</th>
              <th class="h_1x2">客/主</th>
              <th class="h_1x2">客/和</th>
			 <th class="h_1x2">客/客</th>
		
            </tr>
         <tbody  id="datashow">
		 <!--
		 <tr>
			   <td  class="b_cen" style="width:80px;">时间</td>
			  <td  class="b_cen" style="width:140px;">主客队伍</td>
			  <td  class="b_cen">主/主</td>
			  <td class="b_cen">主/和</td>
              <td class="b_cen">主/客</td>
              <td class="b_cen">和/主</td>
			  <td class="b_cen">和/和</td>
			  <td class="b_cen">和/客</td>
              <td class="b_cen">客/主</td>
              <td class="b_cen">客/和</td>
			 <td class="b_cen">客/客</td>		 
			</tr>-->
		  <tr><td  colspan="11" align="center" style="background:#fff;">正在加载数据...</td></tr>
	
	</tbody></table>
  
	
	</td>
      </tr>
   
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


