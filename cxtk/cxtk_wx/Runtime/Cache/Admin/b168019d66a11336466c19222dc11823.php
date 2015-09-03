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
<style type="text/css">

</style>
<script type="text/javascript">
  function img_up(id){
    $("#img_id").val(id);
    easyDialog.open({
          container : 'logo_up',
          fixed : false
    });
}

</script>
  <div class="content-box role">
    <div class="content-box-header">
      <h3>微站栏目 <a class="add_cost" href="add_web_cate?pid=<?php echo ($pid); ?>&type=1">添加栏目</a>
      <a class="add_cost" href="add_web_cate?pid=<?php echo ($pid); ?>&type=0">添加图文</a></h3>
      <div class="clear"></div>
    </div>
    <div class="content-box-content ">
    <form  action="<?php echo U(GROUP_NAME . '/Website/sortWebsite');?>"  method="post">
      <table class="table">
       <thead>
        <tr>
          <th class="sort"><div class="header">排序</div></th>
          <th><div class="header">栏目名称</div></th>
          <th style="width:120px;"><div class="header">栏目图片</div></th>
          <th><div class="header">模板</div></th>
          <th style="width:130px;"><div class="header">添加时间</div></th>
          <th style="width:260px;"><div class="header" >操作</div></th>

        </tr>
       </thead>
        <?php if(is_array($column)): foreach($column as $key=>$v): ?><tr <?php if(($key%2)==0): ?>class="td_one"<?php endif; ?>>
               <td class="sort"><span class="severity3"><?php echo ($v["sort"]); ?></span></td> 
               <td><?php echo ($v["name"]); ?></td>
               <td style="text-align: center;"><?php if(empty($v['img'])): ?><font style="color:red;" class="view" onclick="img_up('<?php echo ($v['id']); ?>')">【上传】
                     </font>
                <?php else: ?>
                     <font style="color:#1A6102;" class="view" onclick="view('<?php echo ($v['img']); ?>','<?php echo ($v['width']); ?>','<?php echo ($v['height']); ?>')">【预览】
                     </font>
 <font style="color:#000;" class="view" onclick="if(confirm('确定删除')){ window.location.href='web_cate_img_del?id=<?php echo ($v['id']); ?>';}">【删除】
                     </font><?php endif; ?>

              </td>
              <td style="width:85px;text-align: center;">
                  <?php echo ($v["template_id"]); ?> 
               </td>
               <td style="text-align: center;">
                <?php echo ($v["add_date"]); ?> 
               </td>
               <td class="td" style="text-align: center;">
                   <a href="<?php echo U(GROUP_NAME . '/Website/column_d_tpl', array('id' => $v['id']));?>">模板</a>
                   <a href="web_cate_edit?id=<?php echo ($v['id']); ?>&type=<?php echo ($v['type']); ?>">修改</a>
                   
                 <?php if($v["type"] == 0): else: ?>
                   <a href="column_index?pid=<?php echo ($v['id']); ?>">下级内容</a><?php endif; ?>
                    <a style="background-color: #e86829;color:#fff;"  onclick="return confirm('确定删除');"
                    href="web_cate_del?id=<?php echo ($v['id']); ?>&type=<?php echo ($v['type']); ?>">删除</a>
                 
               </td>
             </tr><?php endforeach; endif; ?>
         <tr>
           <td colspan="8" align="center">
               <?php echo ($page); ?>
          </td>
        </tr>
      </table>
      </form>
  </div>
</div>

  <!-- 图片上传-->
<div id="logo_up" style="display:none;background-color:white;" class="con_menu">
  <form action="up_web_cate_img" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return checkForm_img()" >
    <input name="id" id="img_id" value="" type="hidden">
    <table class="m_tab" style="width:300px;margin:0;">
        <tbody><tr class="de_title">
            <td colspan="2" height="27" class="table_bg" align="center">
            <span id="de_title">上传图片</span>
            </td>
        </tr>
        <tr class="m_title">
            <td>选择文件</td>
            <td class="de_td">
                <div class="uploader blue">
                <input type="text" class="filename" readonly="readonly" name="filename">
                <input type="button" name="file" class="button B_button" value="Browse...">
                <input type="file" size="30" name="img">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="提交" class="botton_a">
                <input type="reset" value="关闭" onclick="easyDialog.close();" class="botton_a">
            </td>
        </tr>
    </tbody></table>
</form>
    </div>
<script type="text/javascript">
$(function(){
  $("input[type=file]").change(function(){$(this).parents(".uploader").find(".filename").val($(this).val());});
  $("input[type=file]").each(function(){
  if($(this).val()==""){$(this).parents(".uploader").find(".filename").val("");}
  })
})

function checkForm_img (){
 if($("input[class=filename]").val() == ''){
    alert('请先选择图片');
    return false;
 }
}
</script>
</body>
</html>