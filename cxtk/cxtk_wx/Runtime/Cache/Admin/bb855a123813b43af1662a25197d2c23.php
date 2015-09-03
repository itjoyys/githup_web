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
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
  <div class="content-box role">
    <div class="content-box-header">
      <h3>添加轮播图</h3>
      <div class="clear"></div>
    </div>
    <div class="content-box-content ">
<form action="add_web_flash_do"  method="post" onsubmit="return check_form()">
  <table class="table">
      <tr>
      <td align="right">标题：</td>
      <td>
        <input class="input" type="text" name="name" style="width:300px;" value="<?php echo ($data['name']); ?>" />
        <font class="input_msg">(中文必填)</font>
      </td>
    </tr>
<!--        <tr>
      <td align="right">链接地址：</td>
      <td colspan="2">
        <input class="input" type="text" name="url" style="width:300px;" value="<?php echo ($data['url']); ?>"/>
      </td>
    </tr> -->
         <tr>
      <td align="right">链接URL：</td>
      <td colspan="2">
        <input class="input" type="text" name="url" style="width:300px;" value="<?php echo ($data['url']); ?>"/><font class="input_msg">(如果为外面链接,需填写,否则为空即可)</font>
      </td>
    </tr>
      <tr>
      <td align="right">排序：</td>
      <td colspan="2">
        <input type="text" class="input"  name="sort" value="<?php echo ($data['sort']); ?>"/>
      </td>
    </tr>
    <tr>
       <td colspan="5" align="center">
            <input type="hidden" name="id" value="<?php echo ($data['id']); ?>" />
            <input type="submit" value="提交" class="btn" />
       </td>
    </tr>

  </table>
  </form>
  </div>
  </div>
<script type="text/javascript">
 function check_form(){
     if ($("input[name='title']").val() == '') {
        $("input[name='title']").focus();
        return false;
     };
     if ($("input[name='sort']").val() == '') {
        $("input[name='sort']").focus();
        return false;
     };   
 }
</script>
</body>
</html>