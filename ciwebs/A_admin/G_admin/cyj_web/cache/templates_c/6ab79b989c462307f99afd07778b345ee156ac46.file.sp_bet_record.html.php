<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:16:15
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\note\sp_bet_record.html" */ ?>
<?php /*%%SmartyHeaderCode:483056417d9f101ee1-88129636%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ab79b989c462307f99afd07778b345ee156ac46' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\note\\sp_bet_record.html',
      1 => 1447053862,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '483056417d9f101ee1-88129636',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'note_types' => 0,
    'start_date' => 0,
    'end_date' => 0,
    'page' => 0,
    'itype' => 0,
    'data' => 0,
    'key' => 0,
    'val' => 0,
    'rows' => 0,
    'count_x' => 0,
    'money_x' => 0,
    'count_all' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417d9f1a2193_93340730',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417d9f1a2193_93340730')) {function content_56417d9f1a2193_93340730($_smarty_tpl) {?><title>体育注单</title>
<?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php echo '<script'; ?>
>
	window.onload=function(){
	  document.getElementById("page").onchange=function(){
      	document.getElementById('myFORM').submit();
      }
      document.getElementById("note_type").onchange=function(){
          var gurl = $("#note_type").val();
          window.location.href = "<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/bet_record/"+gurl;
      }
	}
	var indexid = "<?php echo $_GET['index_id'];?>
";
	$(document).ready(function(){
        $("#note_type").get(0).selectedIndex=0;
		$("#index_id").val(indexid);
	})

	$(function(){
	    $("#index_id").change(function(event) {
	      $("#myFORM").submit();
	    });
	  })
<?php echo '</script'; ?>
>
<body>

<div id="con_wrap">
<div class="input_002">体育注單 </div>
<div class="con_menu">
<form id="myFORM" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/sp_bet_record" method="get" name="FrmData">
<?php echo $_smarty_tpl->tpl_vars['note_types']->value;?>

&nbsp;类型:<select  id="sp_lx"  name="sp_lx" onchange="document.getElementById('myFORM').submit()"  class="za_select">
                      <option>体育单式</option>
                      <option  value="cg" <?php echo select_check('cg',$_GET['sp_lx']);?>
>体育串关</option>
                    </select>状态:
                    <select  id="ltype"  name="ltype" onchange="document.getElementById('myFORM').submit()"  class="za_select">
                      <option  value="-1" <?php echo select_check('-1',$_GET['ltype']);?>
>全部</option>
                        <option  value="0" <?php echo select_check('0',$_GET['ltype']);?>
>未結算</option>
                        <option  value="1" <?php echo select_check('1',$_GET['ltype']);?>
 >已結算</option>
                    </select> &nbsp;
                </div>
                 会员帐号：<input  type="TEXT"  name="username"  id="username"  value="<?php echo $_GET['username'];?>
"  class="za_text"  style="width:75px;min-width:75px">
                    注单号：<input  type="TEXT"  name="number"  id="number"   value="<?php echo $_GET['number'];?>
" class="za_text"  style="width:110px;">
	&nbsp;日期：<input type="text" name="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" id="start_date" class="za_text Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
	--
	<input type="text" name="end_date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" id="end_date" class="za_text Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
	<input type="SUBMIT" value="確定" class="za_button">
  &nbsp; <?php echo $_smarty_tpl->tpl_vars['page']->value;?>

		<input type="hidden" name="gid" value="<?php echo $_GET['gid'];?>
">
		<input type="hidden" name="did" value="<?php echo $_GET['did'];?>
">
    <input type="hidden" name="itype" value="<?php echo $_smarty_tpl->tpl_vars['itype']->value;?>
">
		</form>
</div>
</div>
<div class="content">
	<table width="99%" border="0" cellspacing="0" cellpadding="0" class="m_tab" >
			<tbody>
			<tr class="m_title_over_co">
                    <td>注单号</td>
                    <td>下注時間</td>
                    <td>所屬上級</td>
                    <td>帳號</td>
                    <td>赛事</td>

                    <td>主客队</td>
                    <td>內容</td>
                    <td>下注金額</td>
                    <td>可贏金額</td>
                    <td>結果</td>
			</tr>
			<?php if (!is_array($_smarty_tpl->tpl_vars['data']->value)) {?>
			<tr class="m_rig" style="display:;">
				<td height="70" align="center" colspan="10"><font color="#3B2D1B">暫無數據。</font></td>
			</tr>
			<?php }?>
			<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<tr class="m_cen <?php if ($_smarty_tpl->tpl_vars['key']->value%2==0) {?>even<?php }?>">
					<td align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['number'];?>
</td>
					<td align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['bet_time'];?>
</td>
					<td align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['agent_zh'];?>
</td>
					<td align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>
</td>
					<td align="center"><font color="#336600"><?php echo $_smarty_tpl->tpl_vars['val']->value['ball_sort'];?>
</font><br><?php echo $_smarty_tpl->tpl_vars['val']->value['match_name'];?>
</td>
					<td align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['master_guest'];?>

                    </td>
					<td align="center">

                                <font style="color:#FF0033">
                                   <?php if ($_smarty_tpl->tpl_vars['val']->value["point_column"]==="match_jr"||$_smarty_tpl->tpl_vars['val']->value["point_column"]==="match_gj") {?>
                                   <?php echo $_smarty_tpl->tpl_vars['rows']->value["bet_info"];?>

                                   <?php } else { ?>
                                   <?php echo str_replace("-","<br>",$_smarty_tpl->tpl_vars['val']->value["bet_info"]);?>

                                   <?php }?>
                                </font>
                                <?php if ($_smarty_tpl->tpl_vars['val']->value["status"]!=0&&$_smarty_tpl->tpl_vars['val']->value["status"]!=3&&$_smarty_tpl->tpl_vars['val']->value["status"]!=6&&$_smarty_tpl->tpl_vars['val']->value["status"]!=7) {?>
                                    <?php if ($_smarty_tpl->tpl_vars['val']->value["MB_Inball"]!='') {?>
                                        [<?php echo $_smarty_tpl->tpl_vars['val']->value["MB_Inball"];?>
:<?php echo $_smarty_tpl->tpl_vars['val']->value["TG_Inball"];?>
]
                                    <?php }?>
                                    <br/><?php echo $_smarty_tpl->tpl_vars['val']->value["login_ip"];?>

                            	<?php }?>

					</td>
					<td><?php echo $_smarty_tpl->tpl_vars['val']->value['bet_money'];?>
</td>
					<td><?php echo round($_smarty_tpl->tpl_vars['val']->value['bet_win'],2);?>
</td>
					<td><?php echo ifstatus($_smarty_tpl->tpl_vars['val']->value['status']);?>
</td>
			</tr>
			<?php } ?>
			<tr class="m_rig" style="background-Color: #EBF0F1;">
					<td colspan="10" align="center">
					<b><font color="#ff0000">&nbsp;小計：<?php echo $_smarty_tpl->tpl_vars['count_x']->value;?>
笔
					&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['money_x']->value;?>
元</font></b></td>
				</tr>
				<tr class="m_rig" style="background-Color: #EBF0F1;">
					<td colspan="10" align="center"><b><font color="#ff0000">&nbsp;总計：<?php echo $_smarty_tpl->tpl_vars['count_all']->value['num'];?>
笔
					&nbsp;<?php echo $_smarty_tpl->tpl_vars['count_all']->value['money']+0;?>
元</font></b></td>
				</tr>

	</tbody></table>
</div>

<?php echo '<script'; ?>
 language="javascript">
var vtimeCashList = 0;
var timeGoCashList = null;
function SetTimeCashList(otime)
{
    vtimeCashList=otime;
    if(vtimeCashList>0)
    {
        window.clearTimeout(timeGoCashList);
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';
        if(otime!=0)
        {
            timeGoCashList=setInterval("timeCashList("+otime+")",1000);
        }
    }
    else
    {
        document.getElementById("lblTime").innerHTML="";
        window.clearTimeout(timeGoCashList);
    }
}
function timeCashList(otime)
{
    if(vtimeCashList<=0)
    {
        document.getElementById("lblTime").innerHTML="";
        window.clearTimeout(timeGoCashList);
    }
    else
    {
        vtimeCashList=vtimeCashList-1;
        if(vtimeCashList<=0)
        {
        	getdata();
            vtimeCashList=otime;
        }
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';

    }
}
function getdata(page){
	form_obj = document.getElementById("myFORM");
	// form_obj.action = "bet_record.php";
	form_obj.submit();
}
var reload = $("#reload").val();
$(document).ready(function(){
	if(reload>0){
		SetTimeCashList(reload);
	}
	$("#reload").val(reload);
	// $("#page_num").val('20');
	// $("#page").val('0');
});
<?php echo '</script'; ?>
>
<!-- 公共尾部 -->

 <?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
