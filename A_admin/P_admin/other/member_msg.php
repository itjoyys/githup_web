<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

//消息类别 判断
$where= "site_id = '".SITEID."' and  is_delete = '0' ";
if(!empty($_GET['msgtype'])){
   $where .=' and type='.$_GET['msgtype'];
}
//账号查询
if (!empty($_GET['account'])) {
    $auid = M('k_user',$db_config)
          ->where("username = '".$_GET['account']."' and site_id = '".SITEID."'")
          ->getField("uid");
    if (!empty($auid)) {
        $where .= " and uid = '".$auid."'";
    }else{
        message("账号不存在");
    }
  
}

$u = M('k_user_msg',$db_config);
//时间判断
if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
    $start_date = $_GET['date_start'];
    $end_date = $_GET['date_end'];
}else{
    $start_date = $end_date=date('Y-m-d');
}
$where.=" and msg_time>='".$start_date." 00:00:00' and msg_time <='".$end_date." 23:59:59'";


$count= $u->where($where)->order("msg_id DESC")->count();
//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$user_msg=$u->where($where)->order("msg_id DESC")->limit($limit)-> select();

$page = $u->showPage($totalPage,$page);

if($_GET['delete']==1){	
	if(M('k_user_msg',$db_config)->where("msg_id = '".$_GET['msg_id']."'")->delete()){
		message('删除成功','member_msg.php');exit;
	}

}
//print_r($user_level);exit;

?>
<?php $title="会员消息"; require("../common_html/header.php");?>
<script type="text/javascript">
  
//下拉框选中
$(document).ready(function(){
   $("#msgtype").val('<?=$_GET['msgtype']?>');
   if('<?=$_GET['date_start']?>'){
	    $("#date_start").val('<?=$_GET['date_start']?>');
   }
  if('<?=$_GET['date_start']?>'){
		$("#date_end").val('<?=$_GET['date_end']?>');
  }
})
</script>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('queryform').submit()
    }
  }
</script>

<body>
<div id="con_wrap">
  <div class="input_002">会员消息管理</div>
  <div class="con_menu">
	<form name="queryform" id="queryform" action="" method="get"> 
  	<a href="./member_msg_add.php">发布新消息</a>&nbsp;&nbsp;
	日期：
	<input type="text" id="date_start" name="date_start"  value="<?=$start_date?>" onClick="WdatePicker()" class="za_text Wdate">
	--
	<input type="text" id="date_end" name="date_end"  value="<?=$end_date?>" onClick="WdatePicker()" style="min-width:80px;width:80px" class="za_text Wdate">
    類型：
	<select name="msgtype" class="za_select" id="msgtype" onchange="document.getElementById('queryform').submit()">
    	<option value="">全部</option>
        <option value="1">普通通知</option>
        <option value="2">優惠通知</option>
        <option value="3">出入款通知</option>
    </select>
    帳號：
  <input type="text" name="account" class="za_text" style="min-width:80px;width:80px" value="<?=$_GET['account'] ?>" size="10">&nbsp;&nbsp;
	<input class="button_d" value="查詢" type="submit">
	       每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('queryform').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;<?=$page?>

	</form> 
	</div>
</div>
<div class="content">
<table width="99%" class="m_tab">
		<tbody><tr class="m_title">
                <td align="center" nowrap="nowrap" class="table_bg" width="50px"> 序號</td>                
                <td align="center" nowrap="nowrap" class="table_bg" width="150px">标题</td>
                <td align="center" nowrap="nowrap" class="table_bg">内容</td>
                <td align="center" nowrap="nowrap" class="table_bg" width="80px">收件者</td>
                <td align="center" nowrap="nowrap" class="table_bg" width="150px">發送日期</td>             
                <td align="center" nowrap="nowrap" class="table_bg" width="120px">操作</td>
              </tr>
			   
			  <?
			  if(is_array($user_msg)){
			  foreach($user_msg as $v){
				if($v['level']==0){
					$shou='全体会员';
				}elseif($v['level']==1){
					$map['site_id']=SITEID;
					$map['id']=$v['level_id'];
					$user_level=M('k_user_level',$db_config)->where($map)->select();
					$shou=$user_level[0]['level_name'];
				
				}elseif($v['level']==2){
					$user=explode(',',$v['uid']);
					$uid='';
					if(is_array($user)){
						foreach($user as $va){
							$mab['uid']=$va;
							$users=M('k_user',$db_config)->where($mab)->select();//print_r($users);exit;
							if($users){
								if($uid==''){
									$uid=$users[0]['username'];
								}else{
									$uid.=','.$users[0]['username'];
								}
							}
						}
					}
					$shou=$uid;
				}
			  
			  ?>
			  
			  
             <tr>
                <td height="25" align="center"><b><?=$v['msg_id']?><b></b></b></td>                
                <td align="center"><?=$v['msg_title']?></td>
                <td align="center"><?=$v['msg_info']?></td>
                <td align="center"><?=$shou?></td>
                <td align="center"><?=$v['msg_time']?></td>                
                <td align="center">
                <!-- <input type="button" value="修改" onClick="document.location='msg_add.php?uid=9ed0209fa5432f4bf77ece52d78a&id=4481399'" class="button_d"> -->
                <input type="button" value="刪除" onclick="if(confirm('确定要删除这条信息吗?')){window.location.href='member_msg.php?delete=1&msg_id=<?=$v['msg_id']?>'}" class="button_d">
                
                </td>               
              </tr>
            <?}} 
            if(empty($user_msg)){
             ?>
               <tr  class="m_cen">
                <td  colspan="16"  height="30">暂无消息</td>
              </tr> 
             <?php
             }?> 

              </tbody></table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>