<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/index.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/public.css" />
<link rel="stylesheet" href="__PUBLIC__/css/index.css" />
<link rel="stylesheet" href="__PUBLIC__/css/easydialog.css">
<script src="__PUBLIC__/js/easydialog.min.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<form action="<?php echo U(GROUP_NAME . '/web_site/web_config_do');?>" enctype="multipart/form-data" method="post">
  <table class="table">
    <tr>
      <td colspan="3" background="__PUBLIC__/images/bg_list.gif">
       <div style="padding-left:10px; font-weight:bold; color:#FFFFFF">基本设置</div> 
      </td>
    </tr>
    <tr>
    	<td align="right" width="10%">地址：</td>
    	<td colspan="2">
    		<?php echo ($url); ?>
    	</td>
    </tr>
    <tr>
    	<td align="right">标题：</td>
    	<td colspan="2">
    		<input type="text" class="input" name="title" value="<?php echo ($data['title']); ?>" />
    	</td>
    </tr>
	 <tr>
      <td align="right">电话：</td>
      <td colspan="2">
        <input type="text" class="input" name="tel" value="<?php echo ($data['tel']); ?>" />
      </td>
    </tr>
       <tr>
      <td align="right">QQ：</td>
      <td colspan="2">
        <input type="text" class="input" name="qq" value="<?php echo ($data['qq']); ?>" />
      </td>
    </tr>
          <tr>
      <td align="right">地址：</td>
      <td colspan="2">
        <input type="text" class="input" name="address" value="<?php echo ($data['address']); ?>" />
      </td>
    </tr>
     <tr>
    	<td align="right">Keywords：</td>
    	<td colspan="2">
    		<input type="text" class="input" name="Keywords"  value="<?php echo ($data['Keywords']); ?>"/>
    	</td>
    </tr>
     <tr>
    	<td align="right">Description：</td>
    	<td colspan="2">
    		<input type="text" class="input" name="Description"  value="<?php echo ($data['Description']); ?>"/>
    	</td>
    </tr>
    <tr>
       <td colspan="3" align="center">
            <input type="hidden" name="id" value="<?php echo ($data['id']); ?>" />
            <input type="submit" value="提交" class="btn" />
       </td>
    </tr>

  </table>
  </form>
  
<!--     <form action="<?php echo U(GROUP_NAME . '/Website/Websitebg');?>" enctype="multipart/form-data" method="post">
  <table class="table">
    <tr>
      <td colspan="3" background="__PUBLIC__/images/bg_list.gif">
       <div style="padding-left:10px; font-weight:bold; color:#FFFFFF">微网站图片</div>
      
      </td>

    </tr>
    <tr>
      <td align="right">微网站图片：</td>
      <td class="td" height="30" width="100"><img src="<?php echo ($image_url); echo ($bg); ?>" height="110" ></td>
      
      <td height="30">
         <input name="bg" type="file" size="2000000">
       </td>
    </tr>
    <tr>
       <td colspan="3" align="center">
            <input type="hidden" name="siteid" value="<?php echo ($siteid); ?>" />
            <input type="submit" value="提交" class="btn" />
       </td>
    </tr>

  </table>
  </form> -->
</body>
</html>