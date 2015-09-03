<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$typeArr = array('fc','sp','ag','mg','mgdz','bbin','bbdz','og','ct','lebo');
if ($_POST['distype'] == 'dis_cx' && !empty($_POST['id'])) {
   $no_people = count($_POST['id']);
   $ex = 0;
   $money_str;
   foreach ($_POST['id'] as $key => $val) {
     try{
       $val = str_replace('-', '"', $val);
       $val = json_decode($val, true);
       /**
         * 事务开始
         */
        $kUser = M("k_user", $db_config);
        $kUser->begin();
        
        //更新用户金额
        $data_u['money'] = array('-',$val['total_e_fd']);
        $log_1 = $kUser->where("uid = '".$val['uid']."'")->update($data_u);
        //写入现金记录
        $u_money = admin::getUserMoney($val['uid']);//获取用户余额

        $dataR = array();
        $dataR['uid'] = $val['uid'];
        $dataR['username'] = $val['username'];
        $dataR['site_id'] = SITEID;
        $dataR['cash_balance'] = $u_money;// 用户当前余额;
        $dataR['cash_date'] = date('Y-m-d H:i:s');
        $dataR['cash_type'] = 13;//表示优惠冲销类别
        $dataR['cash_do_type'] = 2;//表示取出类别
        $dataR['cash_num'] = $val['total_e_fd']; // 冲销金额
        $dataR['remark'] = $_POST['rtitle'].'(美东) 優惠冲销';
        $log_2 = $kUser->setTable("k_user_cash_record")->add($dataR);  
        //发送用户消息
        $dataM = array();
        $dataM['type'] = 2;
        $dataM['site_id'] = SITEID;
        $dataM['uid'] = $val['uid'];
        $dataM['level'] = 2;
        $dataM['msg_title'] = $_POST['rtitle']."(美东) 優惠冲销";
        $dataM['msg_info'] = $val['username'] . "優惠冲销" .$val['total_e_fd']. "元 账户余额" . $u_money . "元 祝您游戏愉快！";
        $log_3 = $kUser->setTable("k_user_msg")->add($dataM);

        //更新返水状态为冲销
        $data_d = array();
        $data_d['state'] = 2;
        $log_4 = $kUser->setTable("k_user_discount_count")
               ->where("id = '".$val['id']."'")
               ->update($data_d);

        //更新总数和余额
        $data_s = array();
        $data_s['no_people_num'] = array('+','1');//冲销人数
        $data_s['money'] = array('-',$val['total_e_fd']);
        $data_s['totalbet'] = array('-',$val['betall']);

        foreach ($typeArr as $kt => $vt) {
          $sField = 'to'.$vt.'bet';
          $s_Field = 'to'.$vt.'fd';
          $eField = $vt.'_bet';
          $e_Field = $vt.'_fd';
          if ($val[$eField] > 0) {
              $data_s[$sField] = array('-',$val[$eField]);
          }
          if ($val[$e_Field] > 0) {
              $data_s[$s_Field] = array('-',$val[$e_Field]);
          }
          
        }

        $data_s['total_fd'] = array('-',$val['total_e_fd']);
        $log_5 = $kUser->setTable("k_user_discount_search")
               ->where("id = '".$_POST['sid']."'")
               ->update($data_s);
        //判断是否有综合稽核
        if (!empty($_POST['zh'])) {
            //有综合稽核的去掉综合稽核
            $data_z = array();
            $data_z['is_zh'] = 0;//更改综合稽核状态为0
            $data_z['type_code_all'] = 0;
            $data_z['catm_give'] = 0;
            $log_6 = $kUser->setTable("k_user_audit")
               ->where("source_id = '".$val['id']."' and source_type = 4")
               ->update($data_z);
        }else{
            $log_6 = 1;
        }

                //事务提交处理
        if ($log_1 && $log_2 && $log_3 && $log_4 && $log_5 && $log_6) {
            $ex++;// 本次事务正常 加一
        }else{
            $kUser->rollback();
            message('优惠冲销失败代码DCX001，请联系管理员');
        }
      }catch (Exception $e) {
        p($e);
        die();
        $kUser->rollback();
        $do_log = '优惠冲销失败:'.$_POST['rtitle'].'(美东)';
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
        message('优惠冲销失败，请联系管理员');
      }
     
   }
   //所有事务提交
   if ($ex == count($_POST['id'])) {
      //所有事务通过

       $kUser->commit();
    //写入用户操作
       $do_log = '优惠冲销成功:'.$_POST['rtitle'].'(美东)'.$val['username'];
       admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
       message('优惠冲销成功');
   }else{
       $kUser->rollback();
       $do_log = '优惠冲销失败:'.$_POST['rtitle'].'(美东)';
       admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
       message('优惠冲销失败DCX002，请联系管理员');
   }
   
}
?>
