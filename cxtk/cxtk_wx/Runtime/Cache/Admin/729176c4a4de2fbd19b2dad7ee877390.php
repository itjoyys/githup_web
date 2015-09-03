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
<script type="text/javascript">
   window.UEDITOR_HOME_URL='__ROOT__/cxtk_wx/Data/ueditor/';
   window.onload=function(){
     window.UEDITOR_CONFIG.initialFrameWidth=800;    
     window.UEDITOR_CONFIG.initialFrameHeight=400;
     window.UEDITOR_CONFIG.savePath= ['details'];
     window.UEDITOR_CONFIG.imageUrl="<?php echo U(GROUP_NAME . '/Website/upload');?>";            
     window.UEDITOR_CONFIG.imagePath="__ROOT__/Uploads/website/";  
     UE.getEditor('details');
    
   }

</script>
<script type="text/javascript" src="__ROOT__/cxtk_wx/Data/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__ROOT__/cxtk_wx/Data/ueditor/ueditor.all.min.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/doubleDate2.0.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="__PUBLIC__/Js/blue/doubleDate.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
  
  $('.doubledate').kuiDate({
    className:'doubledate',
    isDisabled: "0"  // isDisabled为可选参数，“0”表示今日之前不可选，“1”标志今日之前可选
  });

});
</script>
<style type="text/css">
.iptgroup{width:620px;height:60px;margin:20px auto 0 auto;}
.iptgroup li{float:left;height:30px;line-height:30px;padding:5px;}
.iptgroup li .ipticon{background:url(blue/date_icon.gif) 98% 50% no-repeat;border:1px #CFCFCF solid;padding:3px;}
</style>
</head>
<body>
  <div class="content-box role">
    <div class="content-box-header">
      <h3>添加图文</h3>
      <div class="clear"></div>
    </div>
    <div class="content-box-content ">
<form action="<?php echo U(GROUP_NAME . '/web_site/add_web_cate_do');?>"  method="post" onsubmit="return check_form()">
  <table class="table">
      <tr>
      <td align="right">栏目名称：</td>
      <td>
        <input class="input" type="text" name="title" style="width:300px;" value="<?php echo ($data['name']); ?>" />
        <font class="input_msg">(中文必填)</font>
      </td>
    </tr>
       <tr>
      <td align="right">栏目简介：</td>
      <td colspan="2">
        <input class="input" type="text" value="<?php echo ($data['intro']); ?>" name="introduce" style="width:300px;"/>
      </td>
    </tr>
	     <tr>
    	<td align="right">时间：</td>
    	<td colspan="2">
    		<input type="text" name="date" readonly="readonly" class="doubledate ipticon input" value="<?php echo ($data['add_date']); ?>"/>
    	</td>
    </tr>
      <tr>
      <td align="right">排序：</td>
      <td colspan="2">
        <input type="text" class="input"  name="sort" value="<?php echo ($data['sort']); ?>"/>
      </td>
    </tr>
    <tr>
      <td align="right">点击量：</td>      
      <td height="30">
         <input name="read_num" class="input" type="text" value="<?php echo ($data['read_num']); ?>">
       </td>
    </tr>
    <tr>
      <td align="right">
        栏目详情：
      </td>
      <td>
        <textarea name="content" id="details">
          <?php echo ($data['content']); ?>
        </textarea>
      </td>
    </tr>

    <tr>
       <td colspan="5" align="center">
            <input type="hidden" name="type" value="<?php echo ($type); ?>" />
            <input type="hidden" name="pid" value="<?php echo ($pid); ?>" />
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
     if ($("#details").val() == '') {
        $("#details").focus();
        return false;
     };   
 }
</script>
</body>
</html>