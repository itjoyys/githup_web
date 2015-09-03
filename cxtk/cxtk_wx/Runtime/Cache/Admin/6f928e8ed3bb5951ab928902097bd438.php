<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/index.js"></script>
<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/index.css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<head>
<style>
.td a{
padding: 4px 8px;
font-family: Verdana, Arial, sans-serif;
border-radius: 5px;
background: #1D3E83;
}
</style>
</head>
<body>
  <table class="table">

  <tr>
    <td background="__PUBLIC__/images/bg_list.gif" colspan="5">
      <div style="padding-left:10px; font-weight:bold; color:#FFFFFF">门店地区
        [<a href="<?php echo U(GROUP_NAME . '/Website/add_store');?>">添加门店</a>]</div> </td>
  </tr>
     <tr>
      <td align="right" width="10%">门店管理地址：</td>
      <td colspan="5">
        <?php echo ($storeurl); ?>
      </td>
    </tr>
    <tr>
      <td>ID</td>
      <td>名称</td>

      <td>排序</td>
      <td>操作</td>

    </tr>

    <?php if(is_array($store_class)): foreach($store_class as $key=>$v): ?><tr>
           <td><?php echo ($v["id"]); ?></td>
           <td><?php echo ($v["name"]); ?></td>
          <td><?php echo ($v["sort"]); ?></td>
         
           <td class="td">
               
               <a href="<?php echo U(GROUP_NAME . '/Website/store_data', array('id' => $v['id']));?>">下级内容</a>
               <a href="<?php echo U(GROUP_NAME . '/Website/revisecolumn', array('id' => $v['id']));?>">修改</a>
               <a href="<?php echo U(GROUP_NAME . '/Website/deletecolumn', array('id' => $v['id']));?>">删除</a>
        
             
           </td>
         </tr><?php endforeach; endif; ?>
  
  </table>



    <form action="<?php echo U(GROUP_NAME . '/Website/run_store_address');?>" method="post">
  <table class="table">
    <tr>
      <td colspan="3" background="__PUBLIC__/images/bg_list.gif"> <div style="padding-left:10px; font-weight:bold; color:#FFFFFF">添加地区</div></td>
    </tr>
    <tr>
      <td align="right">地区名称：</td>
      <td>
        <input type="text" name="name" />
      </td>
    </tr>
        <tr>
      <td align="right" width="10%">顶级地区：</td>
      <td>
        <select name="pid">
          <option value="0">---请选择---</option>
          <?php if(is_array($store_class)): foreach($store_class as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right">排序</td>
      <td>
        <input type="text" name="sort" value="1" />
      </td>
    </tr>

    <tr>
      <td colspan="3" align="center">
        <input type="hidden" name="siteid" value="<?php echo ($siteid); ?>" />
        <input type="submit" class="btn" value="保存添加"/>
      </td>

    </tr>
  
  </table>
  </form>
</body>
</html>