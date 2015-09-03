<?php
include("../../include/config.php");
include("../common/login_check.php");

$Uagent = M('k_user_agent',$db_config);
$Vagent = M('k_agent_login_view',$db_config);
if (!empty($_GET['suid'])||!empty($_GET['uid'])) {
  $user_data = $Vagent->field('agent_user')->where("uid = '".$_GET['suid']."'")->find();
  switch ($_GET['action']) {
   case 'stop':
    //停用会员
       $data['is_delete'] = 1;
         if($_GET['suid']){
       $Uagent->where("id = '".$_GET['suid']."'")->update($data);
       }
       if( M('sys_admin',$db_config)->where("uid = '".$_GET['uid']."'")->update($data)){
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"停用了代理".$user_data['agent_user']."的账号");
          message('操作成功','agent.php');
       }
    
    break;
      case 'pause':
    //会员暂停
       $data['is_delete'] = 2;
       if(M('sys_admin',$db_config)->where("uid = '".$_GET['suid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"暂停了代理".$user_data['agent_user']."的账号");
          message('操作成功','agent.php');
       }
    
    break;
   case 'using':
    //启用会员
       $data['is_delete'] = 0;
       if($_GET['suid']){
       $Uagent->where("id = '".$_GET['suid']."'")->update($data);
       }
       if(M('sys_admin',$db_config)->where("uid = '".$_GET['uid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"启用了代理".$user_data['agent_user']."的账号");
          message('操作成功','agent.php');
       }
    break;
  }
}

$map['site_id'] = SITEID;
$map['is_demo'] = '0';
$map['agent_type'] = 'a_t';
//检索类别
switch ($_GET['stype']) {
  case '1':
    $str = '%'.$_GET['uname'].'%';
    $map['agent_user'] = array('like',$str);
    break;
  case '2':
    $map['intr'] = $_GET['uname'];
    break;
  case '3':
    $map['agent_name'] = $_GET['uname'];
    break;
  case '4':
    $map['bankno'] = $_GET['uname'];
    break;
}
//总代理选择
if(!empty($_GET['super_agents_id'])){
  $map['pid'] = $_GET['super_agents_id'];
}
//状态选择
if($_GET['enable']=='Y'){
  $map['is_delete'] = 0;
}elseif($_GET['enable']=='N'){
  $map['is_delete'] = 2;
}

$count = $Vagent->where($map)->count(); 

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$agent = $Vagent->where($map)->limit($limit)->order("id desc")->select();  
$page = $Vagent->showPage($totalPage,$page);
//获取上级总代

	   //获取总代信息
$mapU = $agent_up= array();
$mapU['site_id'] = SITEID;
$mapU['is_demo'] = 0;
$mapU['agent_type'] = 'u_a';
$agent_up = M('k_user_agent',$db_config)->where($mapU)->select("id");
?>
<?php $title='代理管理'; require("../common_html/header.php");?>
<body> 
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<div  id="con_wrap">
   <div  class="input_002">代理商帳號管理</div>
   <div  class="con_menu">
    <form  name="myFORM"  id="myFORM"  action="#"  method="get">
      总代理选择：
      <select  id="super_agents_id"  name="super_agents_id" onchange="document.getElementById('myFORM').submit()"  class="za_select">
        <option  value="">全部</option>
        <?php if (!empty($agent_up)) {
           foreach ($agent_up as $key => $val) {
         ?>
           <option  value="<?=$key?>" <?=select_check($key,$_GET['super_agents_id'])?> ><?=$val['agent_user']?></option>
        <?
          }  
        }
        ?>
      </select>
      <select  id="enable"  name="enable" onchange="document.getElementById('myFORM').submit()"  class="za_select">
        <option  value="">全部</option>
        <option  value="Y" <?=select_check('Y',$_GET['enable'])?>>啟用</option>
        <option  value="N" <?=select_check('N',$_GET['enable'])?>>停用</option>
      </select>
      <select  id="orderby"  name="stype"  class="za_select">
        <option  value="1" <?=select_check('1',$_GET['stype'])?> >账号</option>
        <option  value="2" <?=select_check('2',$_GET['stype'])?>>推广ID</option>
        <option  value="3" <?=select_check('3',$_GET['stype'])?>>代理名称</option>
        <option  value="4" <?=select_check('4',$_GET['stype'])?>>银行账号</option>
      </select>
      <input  type="text"  name="uname" id="uname"  value="<?=$_GET['uname']?>"  class="za_text" size="15">
      <input  type="submit"  name="buname"  value="搜索"   class="za_button">
      <input  type="button"  name="append"  value="新增" onclick="document.location='./agent_add.php?atype=u_a'"  class="za_button">

      每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;<?=$page?>
      </form>
  </div>
  </div>
  <div  class="content">
  <table  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">
    <tbody><tr  class="m_title_over_co">
      <td  rowspan="2">代理名稱</td>
      <td  rowspan="2">推广id</td>
      <td  rowspan="2">代理帳號</td>
      <td  rowspan="2">登入帳號</td>
      <td  rowspan="2">所屬總代理</td>
      <td  rowspan="2">會員數</td>
      <td  colspan="3">代理占成</td>
      <td  colspan="3">總代理占成</td>
      <td  rowspan="2">新增日期</td>
      <td  rowspan="2">狀況</td>
      <td  rowspan="2">功能</td>
    </tr>
    <tr  class="m_title_over_co">
      <td>体育</td>
      <td>彩票</td>
      <td>视讯</td>
      <td>体育</td>
      <td>彩票</td>
      <td>视讯</td>
    </tr>
   <?php
   if (!empty($agent)) {
       $User = M('k_user',$db_config);
	   foreach ($agent as $key => $rows) {
        $suid = $rows['uid'];
	     //计算该代理下的会员数
	     $sum=$User->where('agent_id='.$rows["id"].' and site_id="'.SITEID.'" and shiwan = 0')->count();
	     foreach ($agent_up as $ka => $va) {
	     	if ($ka == $rows['pid']) {
	     		$agentUp = $agent_up[$ka];
	     	}
	     }
?>
    <tr  class="m_cen">
      <td><?=$rows["agent_name"]?></td>
      <td><a href="javascript:;" title="?Intr=<?=$rows['intr']?>"><?=$rows["intr"]?></a></td>
      <td><a href="member_index.php?agent_id=<?=$rows['id']?>&mem_enable=0"> <?=$rows["agent_user"]?></a></td>
      <td><?=$rows["agent_login_user"]?></td>
      <td><a href="agent.php?super_agents_id=<?=$rows["pid"]?>"> <?=$agentUp["agent_user"]?></a></td>
      <td><font color="#CC0000">不限制</font>&nbsp;&nbsp;/
          <font  color="#FF0000"><?=$sum?></font>
      </td>
      <td><?=$rows["sports_scale"]*100?>%</td>
      <td><?=$rows["lottery_scale"]*100?>%</td>
      <td><?=$rows["video_scale"]*100?>%</td>
      <td><?=$agentUp["sports_scale"]*100?>%</td>
      <td><?=$agentUp["lottery_scale"]*100?>%</td>
      <td><?=$agentUp["video_scale"]*100?>%</td>
      <td  nowrap="nowrap"><?=$rows["add_date"]?></td>
      <td><?=isstop($rows["is_delete"])?></td>
      <td  align="center" nowrap="nowrap">
      <?php
          $duid = $rows['id'];
         if ($rows['is_delete'] == 1) {
             echo "<a onclick=\"return confirm('确定启用该代理使用')\" href=\"agent.php?action=using&suid=$duid&uid=$suid\">启用</a>";
         }elseif($rows['is_delete'] == 2){
            echo "<a onclick=\"return confirm('确定恢复该代理使用')\" href=\"agent.php?action=using&uid=$suid\">恢复</a>";
         }else{
             echo "<a onclick=\"return confirm('确定停止该代理使用')\" href=\"agent.php?action=stop&suid=$duid&uid=$suid\">停用</a>".' / '."<a onclick=\"return confirm('确定暫停该代理使用')\" href=\"agent.php?action=pause&suid=$suid\">暫停</a>";
         }
       ?> / 
       <a  href="agent_edit.php?atype=u_a&aid=<?=$rows['id']?>">修改</a> /
       <a  href="agent_set.php?aid=<?=$rows['id']?>">設定</a>  /
       <a  href="agents_data.php?uid=<?=$rows["id"]?>&appid=<?=$rows['appid']?>" >资料</a>/
       <a  href="../betRecord/bet_record.php?did=<?=$rows["id"]?>">下注</a></td>
    </tr>
    <?php
  }
}else{?>
<tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="16"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>
<?php }?>

   
  </tbody>
</table>
</div>

<?php
  function isstop($gameType)
  {
    $arr  = array(
      "1"=>"<font style='color:#FF00FF';>停用</font>",
      "2"=>"<font style='color:#FF00FF';>暫停</font>",
      "0"=>"啟用"
      );
    return $arr[$gameType];
  }
?>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>