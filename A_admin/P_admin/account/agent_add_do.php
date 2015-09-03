<?php  
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/setCenter.class.php");

$u = M('k_user_agent',$db_config);

if (!empty($_POST['atype'])) {
    $Sys = M('sys_admin',$db_config);
   if ($_POST['atype'] == 'u_a') {
      $url = 'agent.php';
      $atype = 'a_t';
   }elseif($_POST['atype'] == 's_h'){
      $url = 'up_agent.php';
      $atype = 'u_a';
   }else{
      message("非法参数错误",'agent.php');
   }

  if(empty($_POST['agent_name'])){
       message('请完善表单！','agent_add.php');
   }
   $dataA = array();
   //判断密码是否更改
   if(empty($_POST['appid'])){
     if ($_POST['agent_pwd'] != $_POST['agent_pwd2']) {
           message('两次密码不对',$url);
      }else{
         if($_POST['agent_pwd'] != '******'){
            $dataA['agent_pwd'] = md5(md5($_POST['agent_pwd']));
            $data_sys['login_pwd'] = md5(md5($_POST['agent_pwd']));
         }
      }
   }else{
       $dataA['agent_pwd'] = $_POST['app_pwd'];
       $data_sys['login_pwd'] = $_POST['app_pwd'];
   }
  $data_sys['about'] = $_POST['agent_name'];
  $dataA['agent_name'] = $_POST['agent_name'];//名字
  if (empty($_POST['id'])) {
    //添加
          if (empty($_POST['agent_user'])) {
              message('请输入账号！','agent_add.php');
          }
         
          $is_name = $Sys->where("login_name_1 = '".$_POST['agent_user']."'")->find();
          if ($is_name) {
             message('账号已存在','agent_add.php');
          }
          $intr = $u->order('intr desc')->find();
          $a=M('web_config',$db_config);
          $config=$a->where("site_id='".SITEID."'")->find();
          $dataA['video_scale'] = $_POST['video_scale'];//视讯占成
          $dataA['sports_scale'] = $_POST['sports_scale'];//体育占成
          $dataA['lottery_scale'] = $_POST['lottery_scale'];//彩票占成

          $dataA['intr'] = $intr['intr'] + 1;//代理商推广id
          $dataA['site_id'] = SITEID;
          $dataA['pid'] = $_POST['shareholder'];
          $dataA['agent_user'] = SITEID.$_POST['agent_user'];
          $dataA['agent_login_user'] = $_POST['agent_user'];
          $dataA['add_date'] =date('Y-m-d H:i:s');//添加时间
          $dataA['agent_company'] = COMPANY_NAME;//所属公司
          $dataA['agent_type'] = $atype;//表示代理
          //如果为前台申请,密码写入
          if (empty($_POST['appid'])) {
              $log_1 = $u ->add($dataA);
          }else{
              $dataA['is_delete'] = 0;
              $log_1 = $u ->where('id='.$_POST['appid'])->update($dataA);
              $log_1 =$_POST['appid'];
          }
          
          //写入设定参数
          if ($log_1) {
              $state = setCenter::agent_setAdd($log_1,$dataA['pid']);
              if (empty($state)) {
                    $do_log = '设定参数错误:'.$dataA['agent_user'];
                    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
                    message("添加失败,设定参数错误请联系管理员",$url);
              }

             $dataS['login_name'] = SITEID.$_POST['agent_user'];
             $dataS['login_name_1'] = $_POST['agent_user'];
             $dataS['quanxian']='agadmin';//添加代理时 默认给权限
             $dataS['admin_url'] = $config['agent_url'];
             $dataS['agent_id'] = $log_1;
             $dataS['type'] = 1;
             $dataS['login_pwd'] = $dataA['agent_pwd'];
             $dataS['about'] = $_POST['agent_name'];

             
             $dataS['site_id'] = SITEID;
             $dataS['add_date'] =date('Y-m-d H:i:s');//添加时间

             if ($Sys->add($dataS)) {
                $do_log = '添加代理:'.$dataA['agent_user'];
                admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
                 message("添加成功!",$url);
             }else{
                $do_log = '添加代理失败:'.$dataA['agent_user'];
                admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
                 message("添加失败",$url);
             }
      }else{
          $do_log = '添加代理失败:'.$dataA['agent_user'];
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
          message("添加失败",$url);
      }
   }else{
      //更新
       $log_1 = $u->where("id = '".$_POST['id']."'")->update($dataA);
       if ($log_1) {
          $log_2 = $Sys->where("agent_id = '".$_POST['id']."' and type = '1'")->update($data_sys);
          if ($log_2) {
              $do_log = '编辑代理:'.$_POST['agent_user'].'资料';
               admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
               message('更新代理成功',$url);
          }else{
               $do_log = '编辑代理失败:'.$_POST['agent_user'];
               admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
               message('更新代理登陆失败',$url);
          }
       }else{
               //操作记录
          $do_log = '编辑代理失败:'.$_POST['agent_user'];
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
          message('更新代理失败！',$url);
       } 
    }
}
?>