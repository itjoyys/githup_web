<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");
include("../../class/Level.class.php");

//账号或id查询
if(!empty($_GET['account'])){
  //会员用模糊查询
  if($_GET['search_type']==4){
     $allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."' and is_demo = '0'")->select();
    $str_username = '%'.$_GET['account'].'%';
    $sql ="site_id = '".SITEID."' and username like '".$str_username."' and shiwan = 0 and agent_id = '".$_SESSION['agent_id']."'"; 
    $user=M('k_user',$db_config)->where($sql)->field('uid,username,agent_id')->select();
    if(!$user){
          message('没有此会员！','account_search.php');
        }
  }elseif($_GET['search_type']==3){
     //代理商
    $str_at = '%'.$_GET['account'].'%';
    $sql ="k_user_agent.site_id='".SITEID."' and k_user_agent.agent_user like '".$str_at."' and k_user_agent.agent_type = 'a_t' and k_user_agent.is_demo = '0'";
    $user=M('k_user_agent',$db_config)->join("left join k_user on k_user.agent_id = k_user_agent.id")->field("count(k_user.uid) as username,k_user_agent.id as agent_id")->where($sql)->group("agent_id")->select();
    if(!$user){
      message('没有此代理商！','account_search.php');
    }

  }elseif($_GET['search_type']==5){
     //推广id查询
    $sql ="k_user_agent.site_id='".SITEID."' and k_user_agent.intr = '".$_GET['account']."' and k_user_agent.agent_type = 'a_t' and k_user_agent.is_demo = '0'";
    $user=M('k_user_agent',$db_config)->join("left join k_user on k_user.agent_id = k_user_agent.id")->field("count(k_user.uid) as username,k_user_agent.id as agent_id")->where($sql)->group("agent_id")->select();
    if(!$user){
      message('没有此推广ID!','account_search.php');
    }
  }else if($_GET['search_type']==2){
  //总代理
    $str_at = '%'.$_GET['account'].'%';
    $sql ="k_user_agent.site_id='".SITEID."' and k_user_agent.agent_user like '".$str_at."' and k_user_agent.agent_type = 'u_a' and k_user_agent.is_demo = '0'";
    $user=M('k_user_agent',$db_config)->field("id,pid,agent_user")->where($sql)->select();
    if(!$user){
      message('没有此总代理商！','account_search.php');
    }
    //每个代理商旗下会员总和
    if (!empty($user)) {
          foreach ($user as $k => $v) {
              $agent_c=0;
              $agent_1=M('k_user_agent',$db_config)->where("pid = '".$v['id']."'")->select();
              if (!empty($agent_1)) {
                      foreach ($agent_1 as $key => $val) {
                        $agent_count=M('k_user',$db_config)->field("count(uid) as count")->where("agent_id = '".$val['id']."'")->find();
                        $agent_c+= $agent_count['count'];
                    }
              }
           
              $s_h = M('k_user_agent',$db_config)->where("id = '".$v['pid']."'")->field('agent_user')->find();
              $user[$k]['s_h'] = $s_h['agent_user'];
              $user[$k]['u_a'] = $v['agent_user'];
              $user[$k]['a_t'] = count($agent_1);
              $user[$k]['username'] = $agent_c;
          }
    }
  }
}

?>
<?php $title="會員體系管理"; require("../common_html/header.php");?>
<body>
<div  id="con_wrap">
<div  class="input_002">會員體系管理</div>
<div  class="con_menu">
<form  name="myFORM"  id="myFORM" action="account_search.php"  method="get">
層級查詢：&nbsp;
<select  name="search_type" id="search_type">
  <option value="4" <?php select_check($_GET['search_type'],4)?>>会员</option>
</select>

帳號:
<input  type="text"  name="account"  class="za_text"  value="<?=$_GET['account']?>">&nbsp;&nbsp;
<input  type="submit"  name="subbtn"  class="za_button"  value="查 詢">
</form>

</div>
</div>
<div  class="content">

  <table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="E3D46E"  class="m_tab">
  <tbody><tr  class="m_title_over_co">
    <td>序號</td>
    <td>股东</td>
    <td>总代理</td>
    <td>代理商</td>
    <td>会员</td>
    </tr>
    <?php 
      if (!empty($user)) {
       foreach($user as $k=>$v){
        if ($_GET['search_type'] !=1 && $_GET['search_type'] !=2) {
             $arr=Level::getParents($allagent,$v['agent_id']);
             $data[$k]=array_merge($arr,$v);
        }else{
           $data[$k] =$v;
        }
            
            echo "<tr class='m_cen'>";
            echo "<td>".($k+1)."</td>";
            echo "<td>".$data[$k]['s_h']."</td>";
            echo "<td>".$data[$k]['u_a']."</td>";
            if ($_GET['search_type'] == 3||$_GET['search_type'] == 5) {
              echo "<td>".$data[$k]['a_t']."(代理推广id:".$data[$k]['intr'].")</td>";
            }else{
               echo "<td>".$data[$k]['a_t']."</td>";
            }
            
            echo "<td>".$data[$k]['username']."</td>";
            echo "</tr>";
      }

     }else{
          echo "<tr class='m_cen'>";
          echo "<td height=\"50\" colspan=\"5\">暂无数据</td>";
          echo "</tr>";
     }
    ?>

    </tbody>
  </table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
