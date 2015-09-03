<?php
@session_start();
include('../../include/filter.php');
include_once("../../include/public_config.php");


$date	=	date('Y-m-d');
if(@$_GET['ymd']) $date	=	@$_GET['ymd'];
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <script language="javascript" src="../style/js/jquery.js"></script>
    <script language="javascript" src="../style/js/common.js"></script>
    <script language="javascript">
        var i = 31;
        function check(){
            clearTimeout(aas);
            i = i -1;
            $("#location").html("对不起,您点击页面太快,请在"+i+"秒后进行操作");
            if(i == 1){
                window.location.href ='bet_match.php'
            }
            var aas = setTimeout("check()",1000);
        }
    </script>


    <title></title>

    <link  rel="stylesheet"  href="../public/css/mem_body_olympics.css"  type="text/css">


</head>

<body  id="Mr"  class="bodyset" onload="loaded(document.getElementById('league').value,0)" >

<table  border="0"  cellpadding="0"  cellspacing="0"  id="myTable">
    <tbody>
    <tr>
        <td>
            <table  border="0"  cellpadding="0"  cellspacing="0"  id="box">
                <tbody>
                <tr>
                    <td  class="top"><h1> 棒球结果
                        </h1></td>
                </tr>
                <tr>
                    <td  class="mem">
                        <h2>
                            <table  width="100%"  border="0"  cellpadding="0"  cellspacing="0"  id="fav_bar">
                                <tbody>
                                <tr>
                                    <td  id="page_no">
                                        <div  id="euro_btn"  class="euro_btn"     style=""></div><div  id="euro_up"  class="euro_up"     style="display: none;"></div>
                                        <div id="top" style="vertical-align:top; cursor:pointer">
                                        </div>

                                    </td>
                                    <td  id="tool_td">

                                        <table  border="0"  cellspacing="0"  cellpadding="0"  class="tool_box">
                                            <tbody><form class="bk3" style="margin:0 0 0 0" action="" method="get">
                                                <tr id="shuaxin">
                                                    <td  class="refresh_btn"  id="refresh_btn" >
                                                    </td>
                                                    <td  class="leg_btn xzls">
                                                    </td>
                                                    <td  id="SortGame"  class="SortGame"  name="SortGame">
                                                        <select  id="ymd" name="ymd">
                                                            <?php
                                                            for($i=0;$i<7;$i++){
                                                                $d	=	date('Y-m-d',strtotime("-12 hour")-$i*86400);
                                                                ?>
                                                                <option value="<?=$d?>" <?= $d==$date ? 'selected="selected"' : ''?>><?=$d?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="submit" value="查询">
                                                    </td>
                                                    <td  class="OrderType"  id="Ordertype"></td>
                                                </tr></form>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </h2>




                        <table  id="game_table"  cellspacing="0"  cellpadding="0"  class="game">
                            <tr>
                                <th  class="time">时间</th>
                                <th  class="time">主客队伍</th>
                                <th  class="h_1x2">半场</th>
                                <th class="h_r">全场</th>

                            </tr>
                            <tbody  id="datashow">

                            <?php
                            $sql	=	"select Match_Date,Match_Time,match_name,match_master,match_guest,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from baseball_match where MB_Inball is not null and  match_Date='".date('m-d',strtotime($date))."' and match_js=1";//
                            $query	=	$mysqli->query($sql);
                            $rows	=	$query->fetch_array();
                            if(!$rows){
                                echo '<tr><td  colspan="4" align="center" style="background:#fff;">暂无赛果</td></tr>';
                            }else{
                                do{
                                    if(@$temp_match_name!=$rows["match_name"]){
                                        $temp_match_name=$rows["match_name"];
                                        ?>

                                        <tr>
                                            <td  colspan="4"  class="b_hline">
                                                <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>
                                                    <tr>
                                                        <td  class="legicon" >
                                                            <span  id="<?=$rows["match_name"]?>"  class="showleg"><span  id="LegOpen"></span> </span>
                                                        </td>
                                                        <td  class="leg_bar" ><?=$rows["match_name"]?> </td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr  id="TR_01-1870256"    *class*="">
				<td  rowspan="2"  class="b_cen"><?=$rows["Match_Date"]?><br /><?=$rows["Match_Time"]?></td>
				<td  rowspan="2"  class="b_cen"><?=$rows["match_master"]?> <br>
                    <?=$rows["match_guest"]?> </td>
				    <?php if($rows["MB_Inball"]<0) {?>
                                        <td  class="b_cen"  id="118389861_MH">赛事无效</td>
                                        <td  class="b_cen"  id="118389861_PRH">赛事无效</td>
                                    <? }else{?>
                                        <td  class="b_cen"  id="118389861_MH"><?=$rows["MB_Inball_HR"] ?></td>
                                        <td  class="b_cen"  id="118389861_PRH"><?=$rows["MB_Inball"]?></td>
                                    <? }?>
		  </tr> 
		  <tr  id="TR1_01-1870256"    *class*="">
		  
		  <?php if($rows["TG_Inball"]<0) {?>
                                        <td  class="b_cen"  id="118389861_MC">赛事无效</td>
                                        <td  class="b_cen"  id="118389861_PRC">赛事无效</td>
                                    <? }else{?>
                                        <td  class="b_cen"  id="118389861_MC"><?=$rows["TG_Inball_HR"]?></td>
                                        <td  class="b_cen"  id="118389861_PRC"><?=$rows["TG_Inball"]?></td>
                                    <? }?>
			
			
		  </tr>
	  <?php
	}while($rows = $query->fetch_array());
                            }
                            ?>
                            </tbody></table>


                    </td>
                </tr>

                </tbody></table>


            <div  id="refresh_down"  class="refresh_M_btn" ><span onclick="location.reload() ;" >刷新</span></div>


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


