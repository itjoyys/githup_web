<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-11 09:42:38
         compiled from "/home/wwwuser/public_html/A_index/website/templates/livetop.html" */ ?>
<?php /*%%SmartyHeaderCode:4458265865579904e384711-12985946%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40a312d2122703ca0011c7d4f7ce680e01c9911a' => 
    array (
      0 => '/home/wwwuser/public_html/A_index/website/templates/livetop.html',
      1 => 1200347104,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4458265865579904e384711-12985946',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'notice_html' => 0,
    'uid' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5579904e3e7cb7_76385985',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5579904e3e7cb7_76385985')) {function content_5579904e3e7cb7_76385985($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!--banner-->
<div class="PageBnner2"></div>
<!--banner end-->
<?php echo $_smarty_tpl->tpl_vars['notice_html']->value;?>

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/video.css?v=1.0"/>
<div class="CenterMain" style=" margin-top:-60px;margin-bottom: 30px;">
   		<div class="ele-live-am" id="ele-livehall-wrap">
			<div class="ele-live-ag" style="display: none;"></div>
			<div class="ele-live-mg" style="display: none;"></div>
			
			<div class="ele-livehall-aglive">
			<a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style=" display:block; width:158px; height:40px; position:relative; left:155px; top:115px;"></a>
			<a id="ele-game-open" style=" display: block; width:158px; height:40px; position:relative; left:155px; top:130px;" class="game" href="javascript:;"<?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=ag')"<?php }?>></a>
			</div>
			
			<div class="ele-livehall-mglive">
			<a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style="display:block; width:158px; height:40px; position:relative; left:185px; top:115px;"></a>
			<a href="javascript:;" style=" display:block; width:158px; height:40px; position:relative; left:185px; top:130px;" 
			class="game" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=mg')"<?php }?>></a>
			</div>
		</div>

		<div class="ele-live-oc" id="ele-livehall-wrap">
			<div class="ele-live-og" style="display: none;"></div>
			<div class="ele-live-ct" style="display: none;"></div>
			<div class="ele-livehall-oglive">
			<a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style=" display:block; width:158px; height:40px; position:relative; left:155px; top:125px;"></a>
			<a id="ele-game-open"class="game" style=" display:block; width:158px; height:40px; position:relative; left:155px; top:135px;"  href="javascript:;"<?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=og')"<?php }?>></a>
			</div>
			<div class="ele-livehall-ctlive">
			<a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style=" display:block; width:158px; height:40px; position:relative; left:185px; top:125px;"></a>
			 <a href="javascript:;" class="game" style=" display:block; width:158px; height:40px; position:relative; left:185px; top:135px;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=ct')"<?php }?>></a>
			</div>	
		</div>
		<div class="ele-live-hall" id="ele-livehall-wrap">
			<div class="ele-live-bbin" style="display: none;"></div>
			<div class="ele-live-lebo" style="display: none;"></div>
			<div class="ele-livehall-bbinlive">
			<a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style=" display:block; width:158px; height:40px; position:relative; left:155px; top:120px;"></a>
			<a id="ele-game-open" class="game" style=" display:block; width:158px; height:40px; position:relative; left:155px; top:135px;" href="javascript:;" onclick="alert('尚未开通!')"></a>
		    </div>
		    <div class="ele-livehall-lebolive">
		    <a class="rule" href="javascript:;" onclick="opengeme('/video/rule.html')" style=" display:block; width:158px; height:40px; position:relative; left:185px; top:120px;"></a>		
				 <a href="javascript:;" class="game" style=" display:block; width:158px; height:40px; position:relative; left:185px; top:135px;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=lebo')"<?php }?>></a>
			</div>
		</div>
  
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/video.js"><?php echo '</script'; ?>
>

<!--footer-->
<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>

<?php }} ?>
