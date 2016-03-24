<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:29:42
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\note\fc_bet_record.html" */ ?>
<?php /*%%SmartyHeaderCode:25320564180c6557fc7-32417868%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '230f93e45bf959602d9add7912745ff2dde9532e' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\note\\fc_bet_record.html',
      1 => 1447054374,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25320564180c6557fc7-32417868',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'note_types' => 0,
    'cplist' => 0,
    'v' => 0,
    'start_date' => 0,
    'end_date' => 0,
    'i' => 0,
    'totalPage' => 0,
    'itype' => 0,
    'list' => 0,
    'key' => 0,
    'val' => 0,
    'x' => 0,
    'all' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_564180c6655e83_86356829',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564180c6655e83_86356829')) {function content_564180c6655e83_86356829($_smarty_tpl) {?><title>彩票注单</title>
<?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<style>
.m_rig td {
	text-align: center;
}
</style>
<?php echo '<script'; ?>
>
	window.onload = function() {
		document.getElementById("page").onchange = function() {

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
		$("#note_type").get(0).selectedIndex=1;
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

		<div class="input_002">彩票注單</div>
		<div class="con_menu" style="width:1040px;">
		<form id="myFORM" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/fc_bet_record"
				method="get" name="FrmData">
		<?php echo $_smarty_tpl->tpl_vars['note_types']->value;?>
&nbsp;
				 彩票類型： <select id="cp_type" name="cp_type"
					class="za_select ChangeInput"
					onchange="document.getElementById('myFORM').submit()">
					<option value='0'>所有彩种</option> <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cplist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"<?php echo select_check($_smarty_tpl->tpl_vars['v']->value['name'],$_GET['cp_type']);?>

						><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option> <?php } ?>
				</select> 狀態： <select id="status" name="status"
					class="za_select  ChangeInput"
					onchange="document.getElementById('myFORM').submit()">
					<option <?php echo select_check('-1',$_GET['status']);?>

						value="-1" >全部</option>
					<option <?php echo select_check("0",$_GET['status']);?>

						value="0">未結算</option>
					<option <?php echo select_check("5",$_GET['status']);?>

						value="5">已結算</option>
					<option <?php echo select_check("1",$_GET['status']);?>

						value="1">赢</option>
					<option <?php echo select_check("2",$_GET['status']);?>

						value="2">输</option>
				</select> 日期：<input type="text" name="start_date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" id="start_date"
					 class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"> --
				<input type="text" name="end_date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" id="end_date" class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">&nbsp;
				注单号： <input name="order" style="width:90px;min-width: 90px;" type="text" id="order" class="za_text"> 账号：<input
					type="TEXT" name="username" style="width:90px;min-width: 90px;" value="<?php echo $_GET['username'];?>
" class="za_text" >
 <input type="SUBMIT" value="確定" class="za_button">
				 &nbsp;頁數： <select id="page" name="page" class="za_select">
					<?php $_smarty_tpl->tpl_vars[$_smarty_tpl->tpl_vars['i']->value] = new Smarty_variable(0, null, 0);?> <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['total'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['total']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['name'] = 'total';
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['totalPage']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total']);
?>
					<option value="<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);
echo $_smarty_tpl->tpl_vars['i']->value;?>
" <?php echo select_check($_smarty_tpl->tpl_vars['i']->value,$_GET['page']);?>

						><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</option> <?php endfor; endif; ?>
				</select> <?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
 頁&nbsp;
				 <input type="hidden"
					name="gid" value="<?php echo $_GET['gid'];?>
"> <input
					type="hidden" name="did" value="<?php echo $_GET['did'];?>
">
					</div>
				<input type="hidden" name="itype" value="<?php echo $_smarty_tpl->tpl_vars['itype']->value;?>
">
			</form>

	</div>
	<div class="content">

		<table width="100%" border="0" cellspacing="0" cellpadding="0"
			class="m_tab" >
			<tbody>

				<tr class="m_title_over_co">
					<td width="100">時間</td>
					<td>期数</td>
					<td width="">所屬上線</td>
					<td width="70">注單號</td>
					<td width="70">帐号</td>
					<td width="130">類型</td>
					<td width="330">內容</td>
					<td width="90">下注金額</td>
					<td width="60">可赢金额</td>
					<td width="90">結果</td>
				</tr>

				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<tr class="m_cen <?php if ($_smarty_tpl->tpl_vars['key']->value%2==0) {?>even<?php }?>">
					<td><?php echo $_smarty_tpl->tpl_vars['val']->value['addtime'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['val']->value['qishu'];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['val']->value['agent_zh'];?>
</td>
					<td width="70"><?php echo $_smarty_tpl->tpl_vars['val']->value['did'];?>
</td>
					<td width="70"><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>

					</td>
					<td width="130"><?php echo $_smarty_tpl->tpl_vars['val']->value['type'];?>
</td>
					<td width="330">
						<?php echo $_smarty_tpl->tpl_vars['val']->value['mingxi_1'];?>
 <b><font color="#ff0000"><?php echo $_smarty_tpl->tpl_vars['val']->value['mingxi_2'];?>
</font></b>
						@ <b><font color="#ff0000"><?php echo $_smarty_tpl->tpl_vars['val']->value['odds'];?>
</font></b></td>
					<td width="90"><?php echo $_smarty_tpl->tpl_vars['val']->value['money'];?>
</td>
					<td width="60"><?php echo $_smarty_tpl->tpl_vars['val']->value['money']*($_smarty_tpl->tpl_vars['val']->value['odds']-1);?>
</td>
					<td width="90"><?php echo ifstatus($_smarty_tpl->tpl_vars['val']->value['status']);?>


					</td>
				</tr>
				<?php } ?> <?php if (empty($_smarty_tpl->tpl_vars['list']->value)) {?>
				<tr class="m_rig" style="display:;">
					<td height="70" align="center" colspan="10"><font
						color="#3B2D1B">暫無數據。</font></td>
				</tr>
				<?php } else { ?>
				<tr class="m_rig" style="background-Color: #EBF0F1;">
					<td colspan="9">
					<b><font color="#ff0000">&nbsp;小計：<?php echo $_smarty_tpl->tpl_vars['x']->value['count'];?>
笔
					&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['x']->value['money'];?>
元</font></b></td>
				</tr>
				<tr class="m_rig" style="background-Color: #EBF0F1;">
					<td colspan="9"><b><font color="#ff0000">&nbsp;总計：<?php echo $_smarty_tpl->tpl_vars['all']->value['count'];?>
笔
					&nbsp;<?php echo $_smarty_tpl->tpl_vars['all']->value['money'];?>
元</font></b></td>
				</tr>
				<?php }?>


			</tbody>
		</table>
	</div>

	<?php echo '<script'; ?>
 language="javascript">
		var vtimeCashList = 0;
		var timeGoCashList = null;
		function SetTimeCashList(otime) {
			vtimeCashList = otime;
			if (vtimeCashList > 0) {
				window.clearTimeout(timeGoCashList);
				document.getElementById("lblTime").innerHTML = '還有'
						+ vtimeCashList + '秒更新';
				if (otime != 0) {
					timeGoCashList = setInterval("timeCashList(" + otime + ")",
							1000);
				}
			} else {
				document.getElementById("lblTime").innerHTML = "";
				window.clearTimeout(timeGoCashList);
			}
		}
		function timeCashList(otime) {
			if (vtimeCashList <= 0) {
				document.getElementById("lblTime").innerHTML = "";
				window.clearTimeout(timeGoCashList);
			} else {
				vtimeCashList = vtimeCashList - 1;
				if (vtimeCashList <= 0) {
					getdata();
					vtimeCashList = otime;
				}
				document.getElementById("lblTime").innerHTML = '還有'
						+ vtimeCashList + '秒更新';

			}
		}
		function getdata(page) {
			form_obj = document.getElementById("myFORM");
			// form_obj.action = "bet_record.php";
			form_obj.submit();
		}
		var reload = $("#reload").val();
		$(document).ready(function() {
			if (reload > 0) {
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
