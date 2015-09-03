	<div id="MNav"> 
		<span class="mbtn">投注记录</span>
		<div class="navSeparate"></div>
		<a  target="k_memr" class="mbtn" href="correspondence.php">往来记录</a> 
	</div>
	<div class="MNavLv2">
		<span class="MGameType" id="sport_today" ><a target="k_memr"  href="record_ds.php">体育</a></span>｜ 
		<span class="MGameType " id="lottery_today" ><a target="k_memr"  href="lottery_today.php">彩票</a></span>｜

		<span class="MGameType hover " id="ag_today" ><a  target="k_memr" href="ag_today.php">AG视讯</a></span>｜	   
		<span class="MGameType " id="mg_today" ><a  target="k_memr" href="mg_today.php">MG视讯</a></span>
		｜	   
		<span class="MGameType " id="mg_today" ><a  target="k_memr" href="mgdz_today.php">MG电子</a></span>｜ 
		<span class="MGameType " id="mgc_today" ><a  target="k_memr" href="lebo_today.php">LEBO视讯</a></span>｜ 
		<span class="MGameType " id="mgc_today" ><a  target="k_memr" href="ct_today.php">CT视讯</a></span>｜ 
		<span class="MGameType " id="bb_today" ><a target="k_memr"  href="bbin_today.php">BBIN视讯</a></span>｜ 
		<span class="MGameType " id="og_today" ><a target="k_memr" href="og_today.php">OG视讯</a></span>
	</div>
<script  type="text/javascript" src="../public/date/WdatePicker.js"></script>
<script  type="text/javascript" src="../public/js/jquery-1.8.3.min.js"></script>
<script>
$(function(){
	var url=parent.document.getElementById("k_memr").contentWindow.location.href
	url_=url.substring(url.lastIndexOf('/')+1)
	url_2  =url_.substring(0,url_.lastIndexOf('.')+4)
	$('.MNavLv2 span a').each(function(){
		if(url_==$(this).attr('href') || url_2 == $(this).attr('href')){
			$(this).attr("style",'color:#ffffff;background:#bc5a83;padding:2px')
		}
	})
})
</script>