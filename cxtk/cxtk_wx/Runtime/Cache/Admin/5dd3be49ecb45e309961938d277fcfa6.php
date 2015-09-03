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
      <h3>添加栏目</h3>
      <div class="clear"></div>
    </div>
    <div class="content-box-content ">
<form action="add_web_cate_do"  method="post" onsubmit="return check_form()">
  <table class="table">
      <tr>
      <td align="right">栏目名称：</td>
      <td>
        <input class="input" type="text" name="title" style="width:300px;" />
        <font class="input_msg">(中文必填)</font>
      </td>
    </tr>
       <tr>
      <td align="right">栏目简介：</td>
      <td colspan="2">
        <input class="input" type="text" name="introduce" style="width:300px;"/>
      </td>
    </tr>
      <tr>
      <td align="right">排序：</td>
      <td colspan="2">
        <input type="text" class="input"  name="sort" value="1"/>
      </td>
    </tr>
    <tr>
       <td colspan="5" align="center">
            <input type="hidden" name="type" value="<?php echo ($type); ?>" />
            <input type="hidden" name="pid" value="<?php echo ($pid); ?>" />
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