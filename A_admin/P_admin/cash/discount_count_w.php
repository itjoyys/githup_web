<?php
set_time_limit(0);
ini_set('memory_limit','512M');
include("../../include/config.php");
include("../common/login_check.php");

// 事件写入
if ($_POST['save'] == 'true' && !empty($_POST['uid'])) {
    $typeArr = array('fc','sp','ag','mg','bbin','og','ct','lebo');
    $data = array();
    $data['site_id'] = SITEID;
     //是否勾选层级
    if (!empty($_POST['level_str'])) {
        $data['level_str'] = $_POST['level_str'];
    }
    $data['agent_id'] = $_POST['agent_id'];
    $data['admin_user'] = $_SESSION['login_name']; // 操作者
    $data['back_time_start'] = $_POST['date_start'];
    $data['back_time_end'] = $_POST['date_end'];
    foreach ($_POST['uid'] as $k => $v) {
        $v = str_replace('-', '"', $v);
        $v = json_decode($v, true);
        $data['total_fd'] += $v['total_e_fd'];
        $data['money'] += $v['total_e_fd'];
        $data['totalbet'] += $v['betall'];

        foreach ($typeArr as $kt => $vt) {
             //打码
           $bField = 'to'.$vt.'bet';
           $b_Field = $vt.'_bet';
           if ($v[$b_Field] > 0) {
              $data[$bField] += $v[$b_Field];
           }

           $fField = 'to'.$vt.'fd';
           $f_Field = $vt.'_fd';
           //返点
           if ($v[$f_Field] > 0) {
              $data[$fField] += $v[$f_Field];
           }
         
        }
    }
    if (!empty($_POST['zhbet'])) {
       $data['bet'] = $_POST['zhbet']; // 综合打码量
    }
    
    $data['event'] = $_POST['remark']; // 事件名称
    $data['people_num'] = count($_POST['uid']);//选择的退水人数

    //统一时间
    $nowDate = date('Y-m-d H:i:s');
    $data['addtime'] = $nowDate;

    $userTmpdate = md5($_POST['date_start'].$_POST['date_end']);//返水时间

    // 事务开始
    $kUser = M("k_user_discount_search", $db_config);
    $kUser->begin();
    $kds_id = $kUser->add($data);
    if (empty($kds_id)) {
        $kUser->rollback();
        message("退水写入错误代码DIS001");
        die();
    }
    // 会员明细写入
    $ex = 0;
    $data_m = array();
    $data_m['kds_id'] = $kds_id;
    $data_m['site_id'] = SITEID;
    foreach ($_POST['uid'] as $key => $val) {
       try {
          $val = str_replace('-', '"', $val);
          $val = json_decode($val, true);
          $data_all = array();
          $data_all = array_merge($data_m, $val); // 合并2个数组
          
          //获取当前会员余额
          $user_old_money = M('k_user',$db_config)->where("uid = '".$val['uid']."'")->getField("money");
          //获取稽核放宽额度
          $pay_id = M('k_user_level',$db_config)->where("id='".$data_all['level_id']."'")->getField('RMB_pay_set');
          $line_ct_fk_audit = M('k_cash_config_view',$db_config)->where("id = '".$pay_id."'")->getField("line_ct_fk_audit");
           //稽核状态查询
          $map_audit = array();
          $map_audit['site_id'] = SITEID;
          $map_audit['type'] = 1;
          $map_audit['uid'] = $val['uid'];
          $is_audit = M('k_user_audit',$db_config)->where($map_audit)->order("id desc")->find();

              //判断稽核是否锁定
          $map_lock = array();
          $map_lock['site_id'] = SITEID;
          $map_lock['uid'] = $val['uid'];
          $map_lock['out_status'] = array('in','(0,4)');
          $is_audit_lock = M('k_user_bank_out_record',$db_config)->where($map_lock)->find();

            //启动清除稽核
          if (($user_old_money < $line_ct_fk_audit) && $is_audit && !$is_audit_lock) {
              $data_au = array();
              $data_au['type'] = 2;
              $log_c1 = $kUser->setTable('k_user_audit')->update($data_au);  
              //更新稽核最后一笔结束时间
              $data_al = array();
              $data_al['end_date'] = $now_date;
              $log_c2 = $kUser->setTable('k_user_audit')
                      ->where("id = '".$is_audit['id']."'")
                      ->update($data_al);
              //写入稽核日志
              $data_auto = array();
              $data_auto['update_date'] = $now_date;
              $data_auto['uid'] = $val['uid'];
              $data_auto['site_id'] = SITEID;
              $data_auto['username'] = $val['username'];
              $data_auto['content'] = '會員：'.$val['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
              $log_c3 = $kUser->setTable('k_user_audit_log')->add($data_auto);
          }else{
              $log_c1 = 1;
              $log_c2 = 1;
              $log_c3 = 1;
         }

          unset($data_all['level_id']); // 删除不要的数据
          unset($data_all['money']); // 删除不要的数据
          $data_all['date'] = $userTmpdate;
          $data_all['do_time'] = $nowDate;
          $log_1 = $kUser->setTable('k_user_discount_count')
                 ->add($data_all);
          $now_date = date('Y-m-d H:i:s');

           //更新会员余额
          $udataM = array();
          $udataM['money'] = array('+',$val['total_e_fd']);
          $u_map['uid'] = $val['uid'];
          $log_2 = $kUser->setTable('k_user')->where($u_map)
                  ->update($udataM);

          //如果综合打码不为0
          if (!empty($_POST['zhbet'])) {
              //写入稽核记录
             //更新上一个稽核的终止时间
             $l_audit = M('k_user_audit',$db_config)
                      ->field("max(id) as maxid")
                      ->where("uid = '".$val['uid']."' and type = '1' and site_id = '".SITEID."'")
                      ->find();
             if ($l_audit['maxid']) {
                //存在更新终止时间
                $data_l = array();
                $data_l['end_date'] = $now_date;
                $log_6 = $kUser->setTable("k_user_audit")
                       ->where("id = '".$l_audit['maxid']."'")
                       ->update($data_l);
             }else{
               $log_6 = 1;
             }
             $datae = array();
             $datae['username']=$val['username'];
             $datae['site_id'] = SITEID;
             $datae['uid'] = $val['uid'];
             $datae['begin_date'] = $now_date;
             $datae['type'] = 1;
             $datae['is_zh'] = 1;//有综合稽核
             $datae['is_ct'] = 0;//无常态稽核
             $datae['relax_limit'] = 10;
             $datae['source_id'] = $log_1;//稽核数据来源id
             $datae['source_type'] = 4;//优惠退水稽核
             $datae['catm_give'] = $val['total_e_fd'];
             $datae['type_code_all'] =$_POST['zhbet']*$val['total_e_fd'];
             $log_3 = $kUser->setTable('k_user_audit')->add($datae);
          }else{
             $log_3 = 1;
             $log_6 = 1;
          }

          // 现金系统写入记录
          $useMoney = admin::getUserMoney($val['uid']);
          $dataR = array();
          $dataR['uid'] = $val['uid'];
          $dataR['username']=$val['username'];
          $dataR['agent_id'] = $val['agent_id'];
          $dataR['site_id'] = SITEID;
          $dataR['cash_balance'] = $useMoney;// 用户当前余额;
          $dataR['cash_date'] = $now_date;
          $dataR['cash_type'] = 9;
          $dataR['cash_do_type'] = 1;
          $dataR['discount_num'] = $val['total_e_fd']; // 返点金额
          $dataR['remark'] = $data['back_time_start'].'-'.$data['back_time_end'].'(美东),優惠退水';
          $log_4 = $kUser->setTable("k_user_cash_record")
                 ->add($dataR);

          // 发送用户信息
          $dataM = array();
          $dataM['type'] = 2;
          $dataM['site_id'] = SITEID;
          $dataM['uid'] = $val['uid'];
          $dataM['level'] = 2;
          $dataM['msg_title'] = $_POST['date_start'].'-'.$_POST['date_end']."(美东)優惠退水" . $val['total_e_fd'] . "元";
          $dataM['msg_info'] = $val['username'] . "優惠退水" . $val['total_e_fd'] . "元 账户余额" .$useMoney. "元 祝您游戏愉快！";
          $log_5 = $kUser->setTable("k_user_msg")->add($dataM);
          
          if ($log_1 && $log_2 && $log_3 && $log_4 && $log_5 && $log_6 && $log_c1 && $log_c2 && $log_c3) {
              $ex++;
          }else{
               $kUser->rollback();
               message('数据添加失败，错误代码DIS003','./discount_index.php');
          }

        }catch (Exception $e) {
            $kUser->rollback();
            message('数据添加失败，错误代码DIS002','./discount_index.php');
        }  
      } 

      //提交全部事务
      if($ex == count($_POST['uid'])){
           $kUser->commit();
           $log = $_POST['date_start'].'-'.$_POST['date_end']."(美东)優惠退水完成";
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);   
           message('優惠退水成功！！！','discount_index.php');
      }else{
           $kUser->rollback();
           $log = $_POST['date_start'].'-'.$_POST['date_end']."(美东)優惠退水失败";
           admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);   
           message('優惠退水失败！,错误代码DIS004','discount_index.php');
      }    
}else{
        message('会员选择不能为空！');
}

?>