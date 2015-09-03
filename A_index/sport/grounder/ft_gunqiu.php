<?include_once("main_header.php");?>
<input type="hidden" id="touzhutype" value="0">
<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common_gq.js"></script>
<script language="javascript" src="ft_gunqiu.js" ></script>
<script language="javascript" src="../style/js/bet_match.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>

<script  language="JavaScript">



    function chg_league() {
        var legview = document.getElementById('legView');
        var window_lsm=$("#window_lsm").html()
        try {
            if(window_lsm.length > 2000){
                if(window.XMLHttpRequest){ //Mozilla, Safari, IE7
                    if(!window.ActiveXObject){ // Mozilla, Safari,

                        legFrame.location.href = "chuangkous.php?lsm="+window_lsm;
                        //JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
                    }else{ //IE7
                        //JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
                        legFrame.location.href = "chuangkous.php?lsm=zqds";
                    }
                }else{ //IE6
                    //JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
                    legFrame.location.href = "chuangkous.php?lsm=zqds";
                }
            }else{
                //JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
                legFrame.location.href = "chuangkous.php?lsm="+window_lsm;
            }




            //legFrame.location.href = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;
        } catch(e) {

            if(window_lsm.length > 2000){
                if(window.XMLHttpRequest){ //Mozilla, Safari, IE7
                    if(!window.ActiveXObject){ // Mozilla, Safari,

                        legFrame.src = "chuangkous.php?lsm="+window_lsm;
                        //JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
                    }else{ //IE7
                        //JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
                        legFrame.src = "chuangkous.php?lsm=zqds";
                    }
                }else{ //IE6
                    //JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
                    legFrame.src = "chuangkous.php?lsm=zqds";
                }
            }else{
                //JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
                legFrame.src = "chuangkous.php?lsm="+window_lsm;
            }




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
<style>
    .ScoreTime{ border: #C8B187 solid 1px; padding-top: 2px; color: #FFCE39; background: #557A30; width: 80px; margin: 0px auto;
    }
    .scoretime div{color: #9D834C; background: #F9F3DD; margin-top: 2px; padding-top: 2px;}
</style>

<table  id="game_table"  cellspacing="0"  cellpadding="0"  class="game">

    <tbody  id="datashow">

    <tr><td  colspan="9" align="center" style="background:#fff;">正在加载数据...</td></tr>

    </tbody>
</table>


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

