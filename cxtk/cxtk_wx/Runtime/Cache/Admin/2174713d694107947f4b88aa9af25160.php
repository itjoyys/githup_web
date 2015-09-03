<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/index.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/public.css" />
<link rel="stylesheet" href="__PUBLIC__/css/index.css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<form action="<?php echo U(GROUP_NAME . '/WeChat/wechat_do');?>"  method="post" onsubmit="che">
  <table class="table">
    <tr>
      <td colspan="3" background="__PUBLIC__/images/bg_list.gif">
       <div style="padding-left:10px; font-weight:bold; color:#FFFFFF">接口设置</div> 
      </td>
    </tr>
    <tr>
      <td align="right" width="150">接口地址：</td>
      <td colspan="2">
        <?php echo ($url); ?>
      </td>
    </tr>
    <tr>
      <td align="right">TOKEN：</td>
      <td colspan="2">
        <input class="input" type="text" name="token" value="<?php echo ($data['token']); ?>" />
      </td>
    </tr>
   <tr>
      <td align="right">原始ID：</td>
      <td colspan="2">
        <input class="input" type="text" name="oid" value="<?php echo ($data['oid']); ?>" />
      </td>
    </tr>
     <tr>
      <td align="right">AppID：</td>
      <td colspan="2">
        <input class="input" type="text" name="appid"  value="<?php echo ($data['appid']); ?>"/>
      </td>
    </tr>
     <tr>
      <td align="right">AppSecret：</td>
      <td colspan="2">
        <input class="input" type="text" name="appsecret"  value="<?php echo ($data['appsecret']); ?>"/>
      </td>
    </tr>
       <tr>
      <td align="right">EncodingAESKey：</td>
      <td colspan="2">
        <input class="input" type="text" name="EncodingAESKey"  value="<?php echo ($data['EncodingAESKey']); ?>"/>
      </td>
    </tr>
    <tr>
       <td colspan="3" align="center">
            <input type="hidden" name="id" value="<?php echo ($data['id']); ?>"/>
            <input type="submit" value="提交" class="btn" />
       </td>
    </tr>

  </table>
  </form>

</body>
</html>