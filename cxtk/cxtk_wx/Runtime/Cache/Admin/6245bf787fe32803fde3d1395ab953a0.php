<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CXTK</title>
</head>
<frameset rows="60,*" cols="*" framespacing="0" scrolling="No" frameborder="no" border="0">
  <frame src="<?php echo U('Index/top');?>" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame">
  <frameset rows="*" cols="163,*" framespacing="0" frameborder="no" border="0">
    <frame src="<?php echo U('Index/left');?>" name="leftmenu" scrolling="yes" noresize="noresize" id="mainFrame" title="mainFrame">
    <frameset rows="*" cols="*" framespacing="0" frameborder="no" border="0">   
    <frame src="<?php echo U('Index/main');?>" name="main" scrolling="yes" noresize="NORESIZE" id="rightFrame" title="rightFrame">
  </frameset>
  </frameset>
</frameset><noframes></noframes>
<noframes>
 <div class="arc_secmenu">
            <ul>
                <li>
<!--    <volsit name="date_cate" id="v">
    <a <?php if($detail_id == 1): ?>class="curr"<?php endif; ?> 
    href="<?php echo U('Index/News/index');?>"><i class="vn-ico"></i><?php echo ($v["title"]); ?></a>
   </volist> -->
                 <!--    <a <?php if($serid == 2): ?>class="curr"<?php endif; ?> href="<?php echo U('Index/News/service_item',array('id'=>24));?>"><i class="vn-ico"></i>服务项目</a>
                    <a <?php if($serid == 3): ?>class="curr"<?php endif; ?> href="<?php echo U('Index/News/creation');?>"><i class="vn-ico"></i>基层建设</a>
                    <a <?php if($serid == 4): ?>class="curr"<?php endif; ?> href="<?php echo U('Index/News/science');?>"><i class="vn-ico"></i>学术专栏</a>
                    <a <?php if($serid == 5): ?>class="curr"<?php endif; ?> href="<?php echo U('Index/News/quality',array('id'=>40));?>"><i class="vn-ico"></i>相关标准</a> -->
                </li>
            </ul>
</div>
</noframes>
</html>