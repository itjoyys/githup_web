<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:16:16
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\result\football.html" */ ?>
<?php /*%%SmartyHeaderCode:2473856417da06f5ae5-56148787%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a89b6fd1d2e0a299375bc88f923523de4d3ee00' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\result\\football.html',
      1 => 1446180321,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2473856417da06f5ae5-56148787',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'date' => 0,
    'data' => 0,
    'i' => 0,
    'rows' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417da075f279_30534099',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417da075f279_30534099')) {function content_56417da075f279_30534099($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/mem_body_result.css">
   <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/mem_body_ft.css">
  <div id="con_wrap">
    <div class="input_002">足球赛程</div>
    <div class="con_menu">
      <form name="game_result" action="" method="get" id="game_result">
      <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/sp_result/football" style="color:red;" target="_self">足球</a>
      <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/sp_result/basketball" target="_self">籃球</a>
      <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/sp_result/tennis" target="_self">網球</a>
     <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/Sp_result/volleyball" target="_self">排球</a>
      <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/Sp_result/basebal" target="_self">棒球</a>
      <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/result/Sp_result/champion" target="_self">冠军</a>

	  选择日期 :
	  <input type="text" name="date" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  onClick="WdatePicker()" class="Wdate" />
	  <input class="za_button" type="submit" value="搜索" style="line-height:10px;">
    </form>
    </div>
  </div>
  <div class="content" id="MRSU">
    <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tbody>
        <tr>
          <td>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>
                <tr>
                  <th class="time">时间</th>
                  <th class="rsu">赛果</th>
                </tr>
              </tbody>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>
<?php  $_smarty_tpl->tpl_vars['rows'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rows']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rows']->key => $_smarty_tpl->tpl_vars['rows']->value) {
$_smarty_tpl->tpl_vars['rows']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['rows']->key;
?>
                <tr>
                  <td colspan="6" class="b_hline">
                  <span id="leg_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" onclick="$('.leg_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
').toggle()" class="showleg">
                    <span id="LegOpen"></span>
                  </span>
                  <span class="leg_bar"><?php echo $_smarty_tpl->tpl_vars['rows']->value["Match_ID"];
echo $_smarty_tpl->tpl_vars['rows']->value["Match_Name"];?>
</span></td>
                </tr>
                <tbody class="leg_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
">
                  <tr class="b_cen" id="TR_108_1817926" style="display:">
                  <td rowspan="3" class="time"><?php echo $_smarty_tpl->tpl_vars['rows']->value["Match_CoverDate"];?>
</td>
                  <td class="team">比賽隊伍</td>
                  <td colspan="2" class="team_out_ft">
                    <table border="0" cellpadding="0" cellspacing="0" class="team_main">
                      <tbody>
                        <tr class="b_cen">
                          <td width="12"></td>
                          <td class="team_c_ft"><?php echo $_smarty_tpl->tpl_vars['rows']->value['Match_Master'];?>
</td>
                          <td class="vs">vs.</td>
                          <td class="team_h_ft"><?php echo $_smarty_tpl->tpl_vars['rows']->value['Match_Guest'];?>
</td>
                          <td width="12"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="more_td"></td>
                </tr>
                <tr id="TR_1_108_1817926" style="display:" class="hr">
                  <td class="hr_title">半場</td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;">
                    <?php if ($_smarty_tpl->tpl_vars['rows']->value['MB_Inball_HR']==-1) {?>
                      赛事无效
                    <?php } else { ?>
                      <?php echo $_smarty_tpl->tpl_vars['rows']->value['MB_Inball_HR'];?>

                    <?php }?>
                    </span>
                  </td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;">
                    <?php if ($_smarty_tpl->tpl_vars['rows']->value["TG_Inball_HR"]==-1) {?>
                      赛事无效
                    <?php } else { ?>
                      <?php echo $_smarty_tpl->tpl_vars['rows']->value["TG_Inball_HR"];?>

                    <?php }?>
                    </span>
                  </td>
                  <td rowspan="2" class="more_td"></td>
                </tr>
                <tr id="TR_2_108_1817926" style="display:" class="full">
                  <td class="full_title">全場</td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;">
                    <?php if ($_smarty_tpl->tpl_vars['rows']->value["MB_Inball"]==-1) {?>
                      赛事无效
                    <?php } else { ?>
                      <?php echo $_smarty_tpl->tpl_vars['rows']->value["MB_Inball"];?>

                    <?php }?>
                    </span>
                  </td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;">
                    <?php if ($_smarty_tpl->tpl_vars['rows']->value["TG_Inball"]==-1) {?>
                      赛事无效
                    <?php } else { ?>
                      <?php echo $_smarty_tpl->tpl_vars['rows']->value["TG_Inball"];?>

                    <?php }?>
                    </span>
                  </td>
                </tr>
                </tbody>

<?php } ?>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
