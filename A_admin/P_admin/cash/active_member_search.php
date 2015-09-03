<?php
 include_once("../../include/config.php");
 include_once("../common/login_check.php");
 include("../../class/userCash.class.php");
 include("../../class/Level.class.php");

//时间检索
if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
   $end_date = $_GET['end_date'];
   $start_date = $_GET['start_date'];
}else{
   $end_date = $start_date = date('Y-m-d');
}
$tmpSdate = $start_date.' 00:00:00';
$tmpEdate = $end_date.' 23:59:59';
//站点 股东代理
$agent = M('k_user_agent',$db_config)
       ->where("site_id = '".SITEID."' and is_demo = '0'")
       ->select();
       
if (!empty($_GET['agent_id'])) {
    $mapU['agent_id'] = $_GET['agent_id'];
}
$mapU['site_id'] = SITEID;
$mapU['shiwan'] = 0;
$mapU['reg_date'] = array(
                      array('>=',$tmpSdate),
                      array('<=',$tmpEdate)
                    );
//账号检索
if(!empty($_GET['username'])){
  $mapU['username'] = trim($_GET['username']);
}

$User = M('k_user',$db_config);
$count = $User->where($mapU)->count();
$perNumber = 50;
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$userYxAll = $User->field("uid,username,pay_name,agent_id,reg_date,money,reg_address,qq,mobile,email")->where($mapU)->limit($limit)->select();  
$page = $User->showPage($totalPage,$page);

//代理商查询
$map_agent['site_id'] = SITEID;
$map_agent['agent_type'] = 'a_t';
$map_agent['is_demo'] = 0;
$agent_data = M('k_user_agent',$db_config)->where($map_agent)->order("id desc")->select();
?>

<?php $title="会员查询"; require("../common_html/header.php");?>
<body>
 <script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
    document.getElementById("fs").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<style type="text/css">
  .m_tab tr, .m_tab_2 tr {
    line-height: 14px;
}
</style>
<div id="con_wrap">
  <div class="input_002">會員查詢</div>
  <div class="con_menu" style="width:1100px;">
    <form method="get" name="action_form" action="" id="myFORM">
    <input type="hidden" name="action" value="1">
    	<input onclick="document.location='active_member.php'" class="button_d" value="有效會員列表" type="button"> &nbsp;
      日期：
     <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" size="10" name="start_date">
      ~
    <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$end_date?>" size="10" name="end_date">
       代理商：
      <select name="agent_id" id="agent_id">
        <option value="">全部</option>
        <?php if (!empty($agent_data)) {
           foreach ($agent_data as $key => $val) {?>
        <option  value="<?=$val['id']?>"<?=select_check($val['id'],$_GET['agent_id'])?>><?=$val['agent_user']?>(<?=$val['agent_name']?>)</option>
        <?
          }  
        }
        ?>
      </select> 
 	 方式：
      <select name="fs" id="fs">
      	<option value="">全部</option>
        <option value="1" <?=select_check(1,$_GET['fs'])?>>有存款</option>
        <option value="2" <?=select_check(2,$_GET['fs'])?>>有出款</option>
      </select> 
      <input type="hidden" name="new" value="<?=$_GET['new']  ?>">
      <input type="hidden" name="daili" value="<?=$_GET['daili']  ?>">
	會員帳號：
    <input class="za_text inpuut1" style="min-width:80px;width:80px" name="username" value="">
      <input class="button_d" value="查詢" type="submit">
  &nbsp; <?=$page ;?>&nbsp;

    </form>
    
  </div>
</div>
<div>
<table class="m_tab" style="width:100%;">
  <tbody>
  <tr class="m_title_over_co">
    <td class="table_bg" width="130px" rowspan="2">新增日期</td>
    
    <td class="table_bg" rowspan="2">股東</td>
    <td class="table_bg" rowspan="2">總代理</td>
    <td class="table_bg" rowspan="2">代理</td>
    <td class="table_bg" rowspan="2">帳號</td>
    <td class="table_bg" rowspan="2">真實姓名</td>
    <td class="table_bg" width="100px" rowspan="2">余額</td>
    <td class="table_bg" colspan="2">存款次數</td>
    <td class="table_bg" colspan="2">提款次數</td>
    <td class="table_bg" colspan="2">存款總數</td>
    <td class="table_bg" colspan="2">提款總數</td>
    <td class="table_bg" rowspan="2">國家</td>
    <td class="table_bg" rowspan="2">電話號碼</td>
    <td class="table_bg" rowspan="2">電子郵箱</td>
    <td class="table_bg" rowspan="2">QQ</td>
    <td class="table_bg" rowspan="2">狀態</td>
  </tr>
  <tr class="m_title">
    <td>線上(公司)</td>
    <td>人工</td>
    <td>線上</td>
    <td>人工</td>
    <td>線上</td>
    <td>人工</td>
    <td>線上</td>
    <td>人工</td> 
  </tr>
  <?php  
    $zong_bishu=0;//总笔数
    $zong_in_money=0;//总存款总数
    $zong_in=0;//总存款次数
    $zong_out_money=0;//总提款总数
    $zong_out=0;//总提款次数
  if(!empty($userYxAll)){
    foreach($userYxAll as $k => $v){
       //会员基本信息
      $daili = Level::getParents($agent,$v['agent_id']);
      //人工存款
      $userCash_RG = userCash::userCash_RG($v['uid'],$arr_date);
      //公司入款 线上入款
      $userCash_GS = userCash::userCash_GS($v['uid'],$arr_date);
      //人工取款
      $userOUT_RG = userCash::userOUT_RG($v['uid'],$arr_date);
      //线上取款
      $userOUT_XS = userCash::userOUT_XS($v['uid'],$arr_date);

      $in_e_count += $userCash_GS['num'] + $userCash_RG['num'];// 存款次数
      $in_e_money += $userCash_GS['money'] + $userCash_RG['money'];
      $out_e_count += $userOUT_RG ['num']+$userOUT_XS['num'];//取款次数
      $out_e_money += $$userOUT_RG ['money']+$userOUT_XS['money'];
      if ($_GET['fs'] == '1') {
         if(($userCash_GS['num'] + $userCash_RG['num'])==0){
            continue;
         }else{

         }
      }

      if ($_GET['fs'] == '2') {
         if(($userOUT_RG['num']+$userOUT_XS['num'])==0){
            continue;
         }
      }
  ?>
	
    <tr class="m_cen">
    <td><?=$v['reg_date']?></td>
    <td><?=$daili['s_h']?></td>
    <td><?=$daili['u_a']?></td>
    <td><?=$daili['a_t']?></td>
    <td><?=$v['username']?></td>
    <td><?=$v['pay_name']?></td>
    <td><?=$v['money']?></td>
    <td><?=$userCash_GS['num']+0?></td><!-- 存款次数 线上-->
    <td><?=$userCash_RG['num']+0 ?></td><!-- 存款次数 人工 -->
    <td><?=$userOUT_XS['num']+0?></td><!-- 提款次数 线上-->
    <td><?=$userOUT_RG['num']+0?></td><!-- 提款次数 人工 -->
    <td><?=$userCash_GS['money']+0?></td><!-- 存款总数 线上-->
    <td><?=$userCash_RG['money']+0 ?></td><!-- 存款总数 人工 -->
    <td><?=$userOUT_XS['money']+0?></td><!-- 提款总数 线上-->
    <td><?=$userOUT_RG['money']+0?></td><!-- 提款总数 人工 -->
    <td><?=$v['reg_address']?></td>
    <td><?=$v['mobile']?></td>
    <td><?=$v['email']?></td>
    <td><?=$v['qq']?></td>
    <td>正常</td>
  </tr>
   <?}}?>
    		<tr align="center">
			<td colspan="21">小计:
			存入次数：<font class="fontsty1"><?=$in_e_count+0?></font>
			提款次數：<font class="fontsty3"><?=$out_e_count+0  ?></font>
			存款總數：<font class="fontsty4"><?=$in_e_money+0?></font>
			提款總數：<font class="fontsty4"><?=$out_e_money+0?></font>
			
			</td>
		</tr>
    <tr align="center">
    
</tbody></table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>