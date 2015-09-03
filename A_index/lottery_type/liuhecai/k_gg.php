<? 
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }
$_GET['leixing']='过关';
$_GET['leixing_bet']='过关';
$XF=19;
$ids="过关";


$result=$mysqli->query("Select class3,rate,locked from c_odds_7 where class1='过关' order by ID");
$drop_table = array();
$y=0;
while($image =$result->fetch_array()){
//$y++;
//echo $image['class3'];
//echo $image['rate']."<br>";
$image['rate']=round($image['rate'],2);
//echo $image['rate']."<br>";
array_push($drop_table,$image);
if($image['locked']==1){
$drop_table[$y][1]="停";
$drop_table[$y][2]="disabled";
}else{
$drop_table[$y][1]=$image['rate'];
$drop_table[$y][2]="";
}

$y++;
}
?>
<?
// if ($Current_KitheTable[29]==0 ) {   
?>
<?
// exit;								
// }else{
// if ($Current_KitheTable[19]==0 ) {  
?>
<script language="javascript">
// Make_FlashPlay('imgs/T2.swf','T','780','500');
</script>
<?
// exit;
// }


// }

 
?>

<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';} 
if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>


 <style type="text/css">
<!--
.STYLE2 {color: #333}
body {
/*	margin-left: 10px;
	margin-top: 10px;*/
}
-->
 </style>
 <body oncontextmenu="return false"   onselect="document.selection.empty()" oncopy="document.selection.empty()" 
>

<TABLE  border="0" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC"width=780 >
  <TBODY>
 <!--  <TR class="tbtitle">
    <TD > -->
        <?php include("common_qishu.php"); ?>
<!-- <FORM name="lt_form_138" onSubmit="return SubChk(this);"         action="liuhecai.php?action=k_ggsave" method="post" target="k_meml_h" style="height:580px;">  -->

<form  name="lt_form_138" id="lt_form" method="get" action="./main_left.php" style="height:640px;" target="k_meml" onSubmit="return SubChk(this);">

 <input type="hidden" name="title_3d" value="过关" id="title_3d">
 <input type="hidden" name="leixing" value="过关" id="leixing">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="ggsave" id="action"> 
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">


<table style="width:780px;" border="0" cellpadding="0" cellspacing="1" class="game_table all_body" style="display:none;">
   <tr class="tbtitle2">
    <th colspan="13"><div  style="float:left;line-height:30px;text-align:center;width:778px"><?=$_GET['leixing'] ?></div>
	</th>
  </tr> 
  <tr class="tbtitle2">
    <td  class="td_caption_1" >正码过关</td>
    <td  class="td_caption_1">单</td>
    <td  class="td_caption_1">双</td>
    <td  class="td_caption_1">大</td>
    <td  class="td_caption_1">小</td>
    <td  class="td_caption_1 Font_R">红波</td>
    <td  class="td_caption_1 Font_G">绿波</td>
    <td  class="td_caption_1 Font_B">蓝波</td>
  
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码一</td>
    <td ID="jeu_p_243" class="ball_ff"><INPUT type="radio" <?=$drop_table[0][2]?> value="1" name="game1">
                  <FONT 
                        color="#db0000"><B><?=$drop_table[0][1]?>
						
						</B></FONT></td>
            
    <td ID="jeu_p_249" class="ball_ff"><INPUT type=radio <?=$drop_table[1][2]?> value=2 name="game1">
                  <FONT 
                        color=#db0000><B><?=$drop_table[1][1]?></B></FONT></td>
    <td ID="jeu_p_231" class="ball_ff"><INPUT type=radio <?=$drop_table[2][2]?> value=3 name="game1">
                 <FONT 
                        color=#db0000><B><?=$drop_table[2][1]?></B></FONT></td>
    <td ID="jeu_p_237" class="ball_ff"><INPUT type=radio <?=$drop_table[3][2]?> value=4 name="game1">
                  <FONT 
                        color=#db0000><B><?=$drop_table[3][1]?></B></FONT></td>
    <td ID="jeu_p_255" class="ball_ff"><INPUT type=radio <?=$drop_table[4][2]?> value=5 name="game1">
                <FONT 
                        color=#db0000><B><?=$drop_table[4][1]?></B></FONT></td>
    <td ID="jeu_p_267" class="ball_ff"><INPUT type=radio <?=$drop_table[5][2]?> value=6 name="game1">
                <FONT 
                        color=#db0000><B><?=$drop_table[5][1]?></B></FONT></td>
    <td ID="jeu_p_261" class="ball_ff"><INPUT type=radio <?=$drop_table[6][2]?> value=7 name="game1">
                <FONT 
                        color=#db0000><B><?=$drop_table[6][1]?></B></FONT></td>
    
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码二</td>
    <td ID="jeu_p_244" class="ball_ff"><INPUT type=radio <?=$drop_table[7][2]?> value=8 name=game2>
                  <FONT 
                        color=#db0000><B><?=$drop_table[7][1]?></B></FONT></td>
    <td ID="jeu_p_250" class="ball_ff"><INPUT type=radio <?=$drop_table[8][2]?> value=9 name=game2>
                  <FONT 
                        color=#db0000><B><?=$drop_table[8][1]?></B></FONT></td>
    <td ID="jeu_p_232" class="ball_ff"><INPUT type=radio <?=$drop_table[9][2]?> value=10 name=game2>
                 <FONT 
                        color=#db0000><B><?=$drop_table[9][1]?></B></FONT></td>
    <td ID="jeu_p_238" class="ball_ff"><INPUT type=radio <?=$drop_table[10][2]?> value=11 name=game2>
                 <FONT 
                        color=#db0000><B><?=$drop_table[10][1]?></B></FONT></td>
    <td ID="jeu_p_256" class="ball_ff"><INPUT type=radio <?=$drop_table[11][2]?> value=12 name=game2>
                <FONT 
                        color=#db0000><B><?=$drop_table[11][1]?></B></FONT></td>
                        <td ID="jeu_p_268" class="ball_ff"><INPUT type=radio <?=$drop_table[12][2]?> value=13 name=game2>
                <FONT 
                        color=#db0000><B><?=$drop_table[12][1]?></B></FONT></td>
    <td ID="jeu_p_262" class="ball_ff"><INPUT type=radio <?=$drop_table[13][2]?> value=14 name=game2>
               <FONT 
                        color=#db0000><B><?=$drop_table[13][1]?></B></FONT></td>
    
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码三</td>
    <td ID="jeu_p_245" class="ball_ff"><INPUT type=radio <?=$drop_table[14][2]?> value=15 name=game3>
                  <FONT 
                        color=#db0000><B><?=$drop_table[14][1]?></B></FONT></td>
    <td ID="jeu_p_251" class="ball_ff"><INPUT type=radio <?=$drop_table[15][2]?> value=16 name=game3>
                 <FONT 
                        color=#db0000><B><?=$drop_table[15][1]?></B></FONT></td>
    <td ID="jeu_p_233" class="ball_ff"><INPUT type=radio <?=$drop_table[16][2]?> value=17 name=game3>
                  <FONT 
                        color=#db0000><B><?=$drop_table[16][1]?></B></FONT></td>
    <td ID="jeu_p_239" class="ball_ff"><INPUT type=radio <?=$drop_table[17][2]?> value=18 name=game3>
                 <FONT 
                        color=#db0000><B><?=$drop_table[17][1]?></B></FONT></td>
    <td ID="jeu_p_257" class="ball_ff"><INPUT type=radio <?=$drop_table[18][2]?> value=19 name=game3>
               <FONT 
                        color=#db0000><B><?=$drop_table[18][1]?></B></FONT></td>
                        <td ID="jeu_p_269" class="ball_ff"><INPUT type=radio <?=$drop_table[19][2]?> value=20 name=game3>
                <FONT 
                        color=#db0000><B><?=$drop_table[19][1]?></B></FONT></td>
    <td ID="jeu_p_263" class="ball_ff"><INPUT type=radio <?=$drop_table[20][2]?> value=21 name=game3>
                <FONT 
                        color=#db0000><B><?=$drop_table[20][1]?></B></FONT></td>
    
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码四</td>
    <td ID="jeu_p_246" class="ball_ff"><INPUT type=radio <?=$drop_table[21][2]?> value=22 name=game4>
                  <FONT 

                        color=#db0000><B><?=$drop_table[21][1]?></B></FONT></td>
    <td ID="jeu_p_252" class="ball_ff"><INPUT type=radio <?=$drop_table[22][2]?> value=23 name=game4>
                  <FONT 
                        color=#db0000><B><?=$drop_table[22][1]?></B></FONT></td>
    <td ID="jeu_p_234" class="ball_ff"><INPUT type=radio <?=$drop_table[23][2]?> value=24 name=game4>
                 <FONT 
                        color=#db0000><B><?=$drop_table[23][1]?></B></FONT></td>
    <td ID="jeu_p_240" class="ball_ff"><INPUT type=radio <?=$drop_table[24][2]?> value=25 name=game4>
                 <FONT color=#db0000><B><?=$drop_table[24][1]?></B></FONT></td>
    <td ID="jeu_p_258" class="ball_ff"><INPUT type=radio <?=$drop_table[25][2]?> value=26 name=game4>
               <FONT 
                        color=#db0000><B><?=$drop_table[25][1]?></B></FONT></td>
                        <td ID="jeu_p_270" class="ball_ff"><INPUT type=radio <?=$drop_table[26][2]?> value=27 name=game4>
                <FONT 
                        color=#db0000><B><?=$drop_table[26][1]?></B></FONT></td>
    <td ID="jeu_p_264" class="ball_ff"><INPUT type=radio <?=$drop_table[27][2]?> value=28 name=game4>
                <FONT 
                        color=#db0000><B><?=$drop_table[27][1]?></B></FONT></td>
    
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码五</td>
    <td ID="jeu_p_247" class="ball_ff"><INPUT type=radio <?=$drop_table[28][2]?> value=29 name=game5>
                   <FONT 
                        color=#db0000><B><?=$drop_table[28][1]?></B></FONT></td>
    <td ID="jeu_p_253" class="ball_ff"><INPUT type=radio <?=$drop_table[29][2]?> value=30 name=game5>
                  <FONT 
                        color=#db0000><B><?=$drop_table[29][1]?></B></FONT></td>
    <td ID="jeu_p_235" class="ball_ff"><INPUT type=radio <?=$drop_table[30][2]?> value=31 name=game5>
                  <FONT 
                        color=#db0000><B><?=$drop_table[30][1]?></B></FONT></td>
    <td ID="jeu_p_241" class="ball_ff"><INPUT type=radio <?=$drop_table[31][2]?> value=32 name=game5>
                 <FONT color=#db0000><B><?=$drop_table[31][1]?></B></FONT></td>
    <td ID="jeu_p_259" class="ball_ff"><INPUT type=radio <?=$drop_table[32][2]?> value=33 name=game5>
                 <FONT 
                        color=#db0000><B><?=$drop_table[32][1]?></B></FONT></td>
                        <td ID="jeu_p_271" class="ball_ff"><INPUT type=radio <?=$drop_table[33][2]?> value=34 name=game5>
                <FONT 
                        color=#db0000><B><?=$drop_table[33][1]?></B></FONT></td>
    <td ID="jeu_p_265" class="ball_ff"><INPUT type=radio <?=$drop_table[34][2]?> value=35 name=game5>
                <FONT 
                        color=#db0000><B><?=$drop_table[34][1]?></B></FONT></td>
    
  </tr>
  <tr class="Ball_tr_H">
    <td class="ball_bg">正码六</td>
    <td ID="jeu_p_248" class="ball_ff"><INPUT type=radio <?=$drop_table[35][2]?> value=36 name=game6>
                 <FONT 
                        color=#db0000><B><?=$drop_table[35][1]?></B></FONT></td>
    <td ID="jeu_p_254" class="ball_ff"><INPUT type=radio <?=$drop_table[36][2]?> value=37 name=game6>
                 <FONT 
                        color=#db0000><B><?=$drop_table[36][1]?></B></FONT></td>
    <td ID="jeu_p_236" class="ball_ff"><INPUT type=radio <?=$drop_table[37][2]?> value=38 name=game6>
                 <FONT 
                        color=#db0000><B><?=$drop_table[37][1]?></B></FONT></td>
    <td ID="jeu_p_242" class="ball_ff"><INPUT type=radio <?=$drop_table[38][2]?> value=39 name=game6>
                 <FONT color=#db0000><B><?=$drop_table[38][1]?></B></FONT></td>
    <td ID="jeu_p_260" class="ball_ff"><INPUT type=radio <?=$drop_table[39][2]?> value=40 name=game6>
               <FONT 
                        color=#db0000><B><?=$drop_table[39][1]?></B></FONT></td>
                        <td ID="jeu_p_272" class="ball_ff"><INPUT type=radio <?=$drop_table[40][2]?> value=41 name=game6>
                <FONT 
                        color=#db0000><B><?=$drop_table[40][1]?></B></FONT></td>
    <td ID="jeu_p_266" class="ball_ff"><INPUT type=radio <?=$drop_table[41][2]?> value=42 name=game6>
                <FONT 
                        color=#db0000><B><?=$drop_table[41][1]?></B></FONT></td>
    
  </tr>

</table>

<table class="game_table all_body" style="display:none;" style="width:780px;border:1px solid #bbafaf;border-top:none" border="0" cellpadding="0" cellspacing="0">
 <tbody>
     <tr  class="hset2" align="middle">
   <td width="100" class="tbtitle" bgcolor="ffffff"></td>
   <td width="100" class="tbtitle" bgcolor="ffffff"></td>
<td width="100" class="tbtitle"></td> 
<td class="tbtitle" colspan="2"><!-- 下注金额 --></td>
  <!--   <td class="tbtitle">
    <input type="text" class="inp1" value=""  name="xiazhu_money" style="width:80px;" id="xiazhu_money"></td> -->

        <td  id="M_ConfirmClew" style="text-align:left" class="tbtitle">
        <input name="btnSubmit"  type="submit"  class="button_a" id="btnSubmit" value="投注" />
&nbsp;&nbsp;
       <input class='button_a' type="reset"  name="Submit3" value="重 设"  /></td>
	   <td  bgcolor="ffffff" class="tbtitle" width="350"></td>
    </tr>
	 </tbody>
</table>

<input type=hidden value="过关" name=Current_items>

</FORM>
<SCRIPT language=javascript>
if(self == top) location = '/';


var mess1 =  '请先下注!';
var mess2 =  '请选择二组以上玩法，若只要单一下注请前往正特投注!' ;
var mess3 =  '超出下注范围!';
function SubChk(obj) {
	var checkCount = 0;
	var checknum = obj.elements.length;
	
	for( i=0; i < checknum; i++ ) {
		if (obj.elements[i].checked) {
			checkCount ++;
		}
	}
	
	if (checkCount == 0)
	{
		alert(mess1);
		return false;
	}
	if (checkCount == 1)
	{
		alert(mess2);
		return false;
	}
	if (checkCount > 9)
	{
		alert(mess3);
		return false;
		//alert(checkCount);
		//document.lt_form.submit();

	}
	if (checkCount >= 2)
	{
		// return true;
		//alert(checkCount);
		document.lt_form_138.submit();
      self.location.reload();
	}

}
</SCRIPT>
