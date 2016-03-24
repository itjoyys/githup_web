<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Odds_set extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->login_check();
        $this->load->model('result/Odds_set_model');
    }

    //福彩3D type
    //ball_1 第一球 ball_2 第二球 ball_3 第三球 ball_4总和，龙虎
    //ball_5 三连 ball_6 跨度 ball_7独胆

     //彩票赔率
    public function index_fc(){
        // if ($_SESSION['site_id'] == 't' || $_SESSION['site_id'] == 'aai') {

        // }else{
        //     die('赔率系统升级中');
        // }

        $type = $this->input->get('type');//彩票类型
        $type = empty($type)?5:$type;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        //$map['site_id'] = $siteid;

        $data = $this->Odds_set_model->get_fc_odds($type,$map);

        if (empty($data)) {
           //为空添加数据
           $map['site_id'] = 1;
           $data = $this->Odds_set_model->get_fc_odds($type,$map);

           $db_model = array();
           $db_model['tab'] = 'c_odds_'.$type;
           $db_model['type'] = 2;
           foreach ($data as $key => $val) {
               $data[$key]['site_id'] = $_SESSION['site_id'];
               //$data[$key]['site_id'] = $siteid;
               $data[$key]['id'] = '';
           }

           $this->Odds_set_model->M($db_model)->add($data,1);
        }

        if($type == 7){
            //标题
            $title7 = array('k_tm'=>'特码','k_zm'=>'正码','k_zt'=>'正码特',
              'k_zm6'=>'正码1-6','k_gg'=>'过关','k_lm'=>'连码','k_bb'=>'半波','k_sxp'=>'一肖/尾数','k_sx'=>'特码生肖','k_sx6'=>'合肖','k_sxt2'=>'生肖连','k_wsl'=>'尾数连','k_wbz'=>'全不中');
            //数据处理
            foreach ($data as $key => $val) {
                switch ($val['class1']) {
                  case '特码':
                    if ($val['class2'] == '特A') {
                        $k_tm_data[] = $val;
                    }
                    break;
                  case '正码':
                    if ($val['class2'] == '正A') {
                        $k_zm_data[] = $val;
                    }
                    break;
                  case '正特':
                    if ($val['class2'] == '正1特' && is_numeric($val['class3'])) {
                        $k_zt_data[] = $val;
                    }
                    break;
                  case '正1-6':
                    if ($val['class2'] == '正码1') {
                        if ($val['class3'] == '大' || $val['class3'] == '小' || $val['class3'] == '单' || $val['class3'] == '双' || $val['class3'] == '红波' || $val['class3'] == '蓝波' || $val['class3'] == '绿波') {
                            $k_zm6_data[] = $val;
                        }
                    }
                    break;
                  case '过关':
                    //if ($val['class2'] == '正码1') {
                       // if ($val['class3'] == '大' || $val['class3'] == '小' || $val['class3'] == '单' || $val['class3'] == '双' || $val['class3'] == '红波' || $val['class3'] == '蓝波' || $val['class3'] == '绿波') {
                           // $k_gg_data[] = $val;
                       // }
                    //}
                    break;
                  case '连码':
                    //$k_lm_data[] = $val;
                    break;
                  case '半波':
                    //$k_bb_data[] = $val;
                    break;
                  // case '一肖/尾数':
                  //   $k_sxp_data[] = $val;
                  //   break;
                  // case '特码生肖':
                  //   $k_sx_data[] = $val;
                  //   break;
                  case '生肖'://合肖
                    //$k_sx6_data[] = $val;
                     // $val['class3'] = $val['class2'];
                     // $k_sx6_data[$val['class2']] = $val;
                    $val['class3'] = $val['class2'];
                    $k_sx6_data[$val['class2']] = $val;
                    break;
                  case '生肖连':
                   // $k_sxt2_data[] = $val;
                    //$val['class3'] = $val['class2'];
                   // $k_sxt2_data[$val['class2']] = $val;
                    break;
                  // case '尾数连':
                  //   $k_wsl_data[] = $val;
                  //   break;
                  case '全不中':
                    //$k_wbz_data[] = $val;
                    $val['class3'] = $val['class2'];
                    $k_wbz_data[$val['class2']] = $val;
                    break;
                }
            }
            $data7['k_tm'] = array_chunk($k_tm_data,15);
            $data7['k_zm'] = array_chunk($k_zm_data,15);
            $data7['k_zt'] = array_chunk($k_zt_data,15);
            $data7['k_zm6'] = array_chunk($k_zm6_data,15);
            //$data7['k_gg'] = array_chunk($k_gg_data,15);
            //$data7['k_lm'] = array_chunk($k_lm_data,15);
           // $data7['k_bb'] = array_chunk($k_bb_data,15);
           // $data7['k_sxp'] = array_chunk($k_sxp_data,15);
            // $data7['k_sx'] = array_chunk($k_sx_data,15);
           // $data7['k_sx6'] = array_chunk($k_sx6_data,15);
           // $data7['k_sxt2'] = array_chunk($k_sxt2_data,15);
            //$data7['k_wsl'] = array_chunk($k_wsl_data,15);
           // $data7['k_wbz'] = array_chunk($k_wbz_data,15);

            // p($k_sx6_data);die();
            $this->add('title7',$title7);
            $this->add('data7',$data7);
            // $this->add('k_zm_data',$k_zm_data);
            // $this->add('k_memr_data',$k_memr_data);
        }

        // p($data7);die();

        $this->add('data',$data);
        $this->add('type',$type);
        $this->display('result/odds/fc_odds_'.$type.'.html');
    }

    //赔率自定义修改
    public function odds_set_do(){
        $fctype = $this->input->post("fctype");
        $odds = $this->input->post("odds");
        $ball_type = $this->input->post("ball_type");
        $h_type = $this->input->post("h_type");

        // $btype  = str_replace('ball_','',$ball_type);
        $htype  = str_replace('h','',$h_type);

        //函数名
        $func_name = 'odds_'.$fctype;

        switch ($fctype) {
           case '6':
           case '5'://福彩3D 排列三
               $func_name = 'odds_5';
               break;
           case '13'://江苏 吉林快三
           case '14'://江苏 吉林快三
               $func_name = 'odds_13';
               break;
           case '2'://重庆 天津 新疆 重庆时时彩
           case '10':
           case '11':
           case '12':
               $func_name = 'odds_2';
               break;
           case '1':
           case '4':
               $func_name = 'odds_4';
               break;
        }


        // p($_POST);die();

        $this->$func_name($fctype,$ball_type,$htype,$odds);

    }

  //六合彩修改赔率
  function odds_7($type,$btype,$htype,$odds){
      $btype = explode('|',$btype);
     // p($btype);die();
      switch ($btype[0]) {
        case '特码':
           if ($btype[1] == '特A') {
               if (is_numeric($btype[2]) && $btype[2] >=1 && $btype[2] <= 49) {
                   if ($odds >= 49) {
                       showmessage('不能超过极限赔率49','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set7_do($btype,$odds,1,49);
               }else{
                   $tmp_odds = array('大'=>2,'小'=>2,'单'=>2,'双'=>2,'合大'=>2,
                    '合小'=>2,'合单'=>2,'合双'=>2,'尾大'=>2,'尾小'=>2,
                    '野兽'=>2,'家禽'=>2,'红波'=>3,'蓝波'=>3,'绿波'=>3,'大单'=>4,'大双'=>4,'小单'=>4,'小双'=>4);
                  if ($odds >= $tmp_odds[$btype[2]]) {
                      showmessage('不能超过极限赔率'.$tmp_odds[$btype[2]],'back',0);
                   }
                   switch ($btype[2]) {
                     case '大':case '小':
                     case '单':case '双':
                     case '合大':case '合小':
                     case '合单':case '合双':
                     case '尾大':case '尾小':
                     case '家禽':case '野兽':
                       $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'大-小-单-双-合大-合小-合单-合双-尾大-尾小-家禽-野兽','');
                       break;
                    case '大单':case '大双':
                    case '小单':case '小双':
                       $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'大单-大双-小单-小双','');
                       break;
                    case '红波':case '蓝波':
                    case '绿波':
                       $log = $this->Odds_set_model->odds_set7_do($btype,$odds,$btype[2],'');
                    break;
                   }
               }
           }
          break;
        case '正码':
          if ($btype[1] == '正A') {
              if (is_numeric($btype[2]) && $btype[2] >=1 && $btype[2] <= 49) {
                  if ($odds > 7.15) {
                       showmessage('不能超过极限赔率7.15','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set7_do($btype,$odds,1,49);
               }else{
                   $tmp_odds = array('总单'=>2,'总双'=>2,'总大'=>2,'总小'=>2);
                   if ($odds >= 2) {
                      showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'总单-总双-总大-总小','');
               }
          }
          break;
        case '正特':
              if (is_numeric($btype[2]) && $btype[2] >=1 && $btype[2] <= 49) {
                  if ($odds >= 49) {
                       showmessage('不能超过极限赔率49','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set7_do($btype,$odds,1,49);
               }
          break;
        case '正1-6':
            if ($btype[2] == '红波') {
                if ($odds > 2.7) {
                  showmessage('不能超过极限赔率2.7','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'红波','');
            }elseif($btype[2] == '蓝波'){
                if ($odds > 2.85) {
                  showmessage('不能超过极限赔率2.85','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'蓝波','');
            }elseif($btype[2] == '绿波'){
                if ($odds > 2.85) {
                  showmessage('不能超过极限赔率2.85','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'绿波','');
            }else{
                $tmp_odds = array('大'=>2,'小'=>2,'单'=>2,'双'=>2);
               if ($odds > 2) {
                  showmessage('不能超过极限赔率2','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'大-小-单-双','');
            }
          break;
        case '过关':
            if ($btype[2] == '红波') {
                if ($odds > 2.7) {
                  showmessage('不能超过极限赔率2.7','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'红波','');
            }elseif($btype[2] == '蓝波'){
                if ($odds > 2.85) {
                  showmessage('不能超过极限赔率2.85','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'蓝波','');
            }elseif($btype[2] == '绿波'){
                if ($odds > 2.85) {
                  showmessage('不能超过极限赔率2.85','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'绿波','');
            }else{
                $tmp_odds = array('大'=>2,'小'=>2,'单'=>2,'双'=>2);
               if ($odds >= 2) {
                  showmessage('不能超过极限赔率2','back',0);
                }
                $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'大-小-单-双','');
            }
          break;
        case '生肖':
            $tmp_odds = array('特肖'=>10.5,'一肖'=>2.05,'二肖'=>5.6,'三肖'=>3.7
            ,'四肖'=>22.8,'五肖'=>2.2,'六肖'=>1.98,'七肖'=>1.63,'八肖'=>1.43,'九肖'=>1.27,'十肖'=>1.14,'十一肖'=>1.05);
            if ($odds > $tmp_odds[$btype[2]]) {
                showmessage('不能超过极限赔率'.$tmp_odds[$btype[2]],'back',0);
            }
            $log = $this->Odds_set_model->odds_set7_do($btype,$odds,'鼠-虎-龙-马-猴-狗-牛-兔-蛇-羊-鸡-猪','');
          break;
       case '全不中':
            $tmp_odds = array('五不中'=>2,'六不中'=>2.6,'七不中'=>3.1,'八不中'=>4,'九不中'=>4.6,'十不中'=>5.6,'十一不中'=>6.5,'十二不中'=>8.2);
            if ($odds > $tmp_odds[$btype[2]]) {
                showmessage('不能超过极限赔率'.$tmp_odds[$btype[2]],'back',0);
            }
            $log = $this->Odds_set_model->odds_set7_do($btype,$odds,1,49);
          break;
      }

      if ($log) {
          $fctitle = $this->return_title();
          $logs['log_info'] = $fctitle[$type].'--'.$btype[0].'--'.$btype[2].'设定赔率---'.$odds;
          $this->Odds_set_model->Syslog($logs);
          showmessage('设定赔率成功','back');
      }else{
          showmessage('设定赔率失败','back',0);
      }

  }

    //重庆时时彩 江西 新疆 天津
   function odds_2($type,$btype,$htype,$odds){
        switch ($btype) {
           case '1':
           case '2':
           case '3':
           case '4':
           case '5'://第一二三四五球
               if ($htype >=1 && $htype <= 10) {
                   if ($odds >= 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,10,1,5);
               }elseif($htype >=11 && $htype <= 14){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,11,14,1,5);
               }
               break;
           case '6'://总和龙虎
               if ($htype >=1 && $htype <= 6) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,6,6,6);
               }elseif ($htype == 7) {
                  if ($odds > 9) {
                       showmessage('不能超过极限赔率9','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,7,7,6,6);
               }
               break;
           case '7'://前三球/中三球/后三球
           case '8':
           case '9':
               $tmp_odds = array('1'=>60,'2'=>13,'3'=>2.8,'4'=>2,'5'=>2.2);
               if ($odds > $tmp_odds[$htype]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,7,9);
               break;
           case '10'://斗牛
               if ($htype == 1) {
                   if ($odds > 2.1) {
                       showmessage('不能超过极限赔率2.1','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,1,10,10);
               }elseif($htype >= 2 && $htype <= 11){
                   if ($odds > 9.22) {
                       showmessage('不能超过极限赔率9.22','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,2,11,10,10);
               }elseif($htype >= 12 && $htype <= 15){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,12,15,10,10);
               }
               break;
           case '11'://梭哈
               $tmp_odds = array('1'=>99,'2'=>99,'3'=>70,'4'=>60,'5'=>10.9,
                           '6'=>7.3,'7'=>1.86,'8'=>2.86);
               if ($odds > $tmp_odds[$htype]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               if ($htype ==1 || $htype == 2) {
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,2,11,11);
               }else{
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,11,11);
               }
               break;
       }

       if ($log) {
           $fctitle = $this->return_title();
           $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
           $this->Odds_set_model->Syslog($logs);
           showmessage('设定赔率成功','back');
       }else{
           showmessage('设定赔率失败','back',0);
       }
   }

    //彩票赔率 北京PK10
   function odds_3($type,$btype,$htype,$odds){
        //p($htype);die();
        //1到10球玩法
        if ($btype >=1 && $btype <= 10) {
            if ($htype >=1 && $htype <=10) {
                if ($odds >= 10) {
                    showmessage('不能超过极限赔率10','back',0);
                }
                $log = $this->Odds_set_model->odds_set_do($type,$odds,1,10,1,10);
            }elseif($htype == 15 || $htype == 16){
                if ($odds >= 2) {
                    showmessage('不能超过极限赔率2','back',0);
                }

                $log = $this->Odds_set_model->odds_set_do($type,$odds,15,16,1,5);
            }elseif($htype >= 11 && $htype <= 14){
                if ($odds >= 2) {
                    showmessage('不能超过极限赔率2','back',0);
                }
                $log = $this->Odds_set_model->odds_set_do($type,$odds,11,14,1,10);
            }
        }elseif ($btype == 11) {
            switch ($htype) {
                case '1':case '2':
                case '16':case '17':
                    if ($odds >= 45) {
                        showmessage('不能超过极限赔率45','back',0);
                    }

                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'1-2-16-17','',11,11);
                    break;
                case '3': case '4':
                case '14':case '15':
                    if ($odds >= 22.5) {
                        showmessage('不能超过极限赔率22.5','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'3-4-14-15','',11,11);
                    break;
                case '5':case '6':
                case '12':case '13':
                    if ($odds >= 15) {
                        showmessage('不能超过极限赔率15','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'5-6-12-13','',11,11);
                    break;
                case '7': case '8':
                case '10': case '11':
                    if ($odds >= 11.25) {
                        showmessage('不能超过极限赔率11.25','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'7-8-10-11','',11,11);
                    break;
                case '9':
                    if ($odds > 9) {
                        showmessage('不能超过极限赔率9','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,9,9,11,11);
                    break;
                case '18':
                case '21':
                    if ($odds > 2.25) {
                        showmessage('不能超过极限赔率2.25','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'18-21','',11,11);
                    break;
                case '19':
                case '20':
                    if ($odds > 1.8) {
                        showmessage('不能超过极限赔率1.8','back',0);
                    }
                    $log = $this->Odds_set_model->odds_set_do($type,$odds,'19-20','',11,11);
                    break;
            }
        }

        if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
            $this->Odds_set_model->Syslog($logs);
            showmessage('设定赔率成功','back');
        }else{
            showmessage('设定赔率失败','back',0);
        }
   }

    //重庆快乐十分 广东快乐十分
   function odds_4($type,$btype,$htype,$odds){
       switch ($btype) {
           case '1':case '2':
           case '3':case '4':
           case '5':case '6':
           case '7':case '8'://第一二三四五六七八球
               if ($htype >=1 && $htype <=20) {
                   if ($odds >= 20) {
                       showmessage('不能超过极限赔率20','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,20,1,8);
               }elseif($htype >=21 && $htype <= 28){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,21,28,1,8);
               }elseif($htype >= 29 && $htype <= 32){
                   if ($odds > 3.76) {
                       showmessage('不能超过极限赔率3.76','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,29,32,1,8);
               }elseif($htype == 33 || $htype == 34){
                   if ($odds > 2.7) {
                       showmessage('不能超过极限赔率2.7','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,33,34,1,8);
               }elseif($htype == 35){
                   if ($odds > 3.15) {
                       showmessage('不能超过极限赔率3.15','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,35,35,1,8);
               }
               break;
           case '9':
               if ($htype >=1 && $htype <=8) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,8,9,9);
               }
               break;
       }

       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
            $this->Odds_set_model->Syslog($logs);
            showmessage('设定赔率成功','back');
        }else{
            showmessage('设定赔率失败','back',0);
        }
   }

   //福彩3D  排列三
   function odds_5($type,$btype,$htype,$odds){
       switch ($btype) {
           case '1':
           case '2':
           case '3'://第一球/第二球/第三球
               if ($htype >=1 && $htype <=10) {
                   if ($odds >= 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,10,1,3);
               }elseif($htype >=11 && $htype <= 14){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,11,14,1,3);
               }
               break;
           case '4'://总和龙虎
               if ($htype >=1 && $htype <=6) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,6,4,4);
               }elseif($htype == 7){
                   if ($odds > 9) {
                       showmessage('不能超过极限赔率9','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,7,7,4,4);
               }
               break;
           case '5'://三连
               $tmp_odds = array('1'=>100,'2'=>16.6,'3'=>3.7,'4'=>3.17,'5'=>2.5);
               if ($odds > $tmp_odds[$htype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,5,5);
               break;
           case '6'://跨度
               if ($htype ==1) {
                   if ($odds > 86) {
                       showmessage('不能超过极限赔率16.7','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,1,6,6);
               }elseif($htype == 2 || $htype == 10){
                   if ($odds > 16.7) {
                       showmessage('不能超过极限赔率16.7','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'2-10','',6,6);
               }elseif($htype == 3 || $htype == 9){
                   if ($odds > 9.4) {
                       showmessage('不能超过极限赔率9.4','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'3-9','',6,6);
               }elseif($htype == 4 || $htype == 8){
                   if ($odds > 7.2) {
                       showmessage('不能超过极限赔率7.2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'4-8','',6,6);
               }elseif($htype == 5 || $htype == 7){
                   if ($odds > 6.2) {
                       showmessage('不能超过极限赔率6.2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'5-7','',6,6);
               }elseif($htype == 6){
                   if ($odds > 6) {
                       showmessage('不能超过极限赔率6','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,6,6,6,6);
               }
               break;
            case '7'://独胆
               if ($htype >=1 && $htype <= 10) {
                   if ($odds > 3.8) {
                       showmessage('不能超过极限赔率3.8','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,10,7,7);
               }
               break;
       }

       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
            $this->Odds_set_model->Syslog($logs);
            showmessage('设定赔率成功','back');
        }else{
            showmessage('设定赔率失败','back',0);
        }
   }

   //北京快乐8
   function odds_8($type,$btype,$htype,$odds){
       switch ($btype) {
           case '1':
           case '2':
              $tmp_odds = array('1'=>3,'2'=>10);
              if ($odds > $tmp_odds[$btype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$btype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,1,1,$btype,$btype);
               break;
           case '3':
              $tmp_odds = array('1'=>20,'2'=>3);
              if ($odds > $tmp_odds[$htype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,3,3);
               break;
           case '4':
               $tmp_odds = array('1'=>50,'2'=>5,'3'=>3);
              if ($odds > $tmp_odds[$htype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,4,4);
               break;
           case '5':
              $tmp_odds = array('1'=>250,'2'=>20,'3'=>5);
              if ($odds > $tmp_odds[$htype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,5,5);
               break;
           case '6'://和值
               if ($htype >=1 && $htype <= 4) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,4,6,6);
               }elseif($htype == 5){
                  if ($odds > 50) {
                       showmessage('不能超过极限赔率50','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,5,5,6,6);
               }
               break;
            case '7'://上中下
            case '8'://奇偶和
              $tmp_odds = array('1'=>1.93,'2'=>2.8,'3'=>1.93);
              if ($odds > $tmp_odds[$htype]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$htype],'back',0);
               }
               $log = $this->Odds_set_model->odds_set_do($type,$odds,$htype,$htype,7,8);
               break;
       }

       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
            $this->Odds_set_model->Syslog($logs);
            showmessage('设定赔率成功','back');
        }else{
            showmessage('设定赔率失败','back',0);
        }
   }

   //吉林快三 江苏快三
   function odds_13($type,$btype,$htype,$odds){
        //p($htype);die();
        switch ($btype) {
           case '1'://和值
               if ($htype ==1 || $htype ==16) {
                   if ($odds > 165) {
                       showmessage('不能超过极限赔率165','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'1-16','',1,1);
               }elseif($htype ==2 || $htype ==15){
                   if ($odds > 48) {
                       showmessage('不能超过极限赔率48','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'2-15','',1,1);
               }elseif($htype ==3 || $htype ==14){
                   if ($odds > 25) {
                       showmessage('不能超过极限赔率25','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'3-14','',1,1);
               }elseif($htype ==4 || $htype ==13){
                   if ($odds > 15) {
                       showmessage('不能超过极限赔率15','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'4-13','',1,1);
               }elseif($htype ==5 || $htype ==12){
                   if ($odds > 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'5-12','',1,1);
               }elseif($htype ==6 || $htype ==11){
                   if ($odds > 7.2) {
                       showmessage('不能超过极限赔率7.2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'6-11','',1,1);
               }elseif($htype ==7 || $htype ==10){
                   if ($odds > 6.05) {
                       showmessage('不能超过极限赔率6.05','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'7-10','',1,1);
               }elseif($htype ==8 || $htype ==9){
                   if ($odds > 5.6) {
                       showmessage('不能超过极限赔率5.6','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,'8-9','',1,1);
               }elseif($htype >=17 && $htype <= 20){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,17,20,1,1);
               }
               break;
           case '2'://独胆
               if ($htype >=1 && $htype <= 6) {
                   if ($odds > 2.05) {
                       showmessage('不能超过极限赔率2.05','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,6,2,2);
               }
               break;
           case '3'://豹子
               if ($htype >=1 && $htype <=6) {
                   if ($odds > 165) {
                       showmessage('不能超过极限赔率165','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,6,3,3);
               }elseif($htype == 7){
                   if ($odds > 27.5) {
                       showmessage('不能超过极限赔率27.5','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,7,7,3,3);
               }
               break;
           case '4'://两连
               if ($htype >=1 && $htype <=15) {
                   if ($odds > 4.8) {
                       showmessage('不能超过极限赔率4.8','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,15,4,4);
               }
               break;
           case '5'://对子
               if ($htype >=1 && $htype <= 6) {
                   if ($odds > 9.8) {
                       showmessage('不能超过极限赔率9.8','back',0);
                   }
                   $log = $this->Odds_set_model->odds_set_do($type,$odds,1,6,5,5);
               }
               break;
       }

       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$btype.'--'.$htype.'设定赔率---'.$odds;
            $this->Odds_set_model->Syslog($logs);
            showmessage('设定赔率成功','back');
        }else{
            showmessage('设定赔率失败','back',0);
        }
   }

   //赔率初始化
   public function odds_set_initializtion(){
       $type = $this->input->get('type');
       if (empty($type)) {
           showmessage('参数错误!!!','back',0);
       }

       $map['site_id'] = 1;
       if($type == 7){
           $map['class1'] = array('in',"('特码','正码')");
           $map['class2'] = array('<>','特B');
       }
       $data = $this->Odds_set_model->get_fc_odds($type,$map);

       $db_model = array();
       $db_model['tab'] = 'c_odds_'.$type;
       $db_model['type'] = 2;
       //p($data);die();
       foreach ($data as $key => $val) {
           $maps = array();
           $maps['site_id'] = $_SESSION['site_id'];

           if($type == 7){
               //六合彩特殊处理
               $maps['class1'] = $val['class1'];
               $maps['class2'] = $val['class2'];
               $maps['class3'] = $val['class3'];
               unset($data[$key]['class1']);
               unset($data[$key]['class2']);
               unset($data[$key]['class3']);
               unset($data[$key]['blrate']);
               unset($data[$key]['gold']);
               unset($data[$key]['xr']);
               unset($data[$key]['locked']);
               unset($data[$key]['adddate']);
               unset($data[$key]['mrate']);
           }else{
               $maps['type'] = $val['type'];
               unset($data[$key]['type']);
           }
           unset($val['id']);
           unset($val['site_id']);

           $this->Odds_set_model->M($db_model)->where($maps)->update($val);
       }
       $fctitle = $this->return_title();
       $log['log_info'] = '彩票赔率初始化,类型：'.$fctitle[$type];
       $this->Odds_set_model->Syslog($log);

       $this->Odds_set_model->del_redis_odds($type);
       showmessage('赔率初始化成功!','back');
   }

   //彩票中文类型
   public function return_title(){
       return array('1'=>'廣東快樂十分','2'=>'重慶時時彩','3'=>'北京赛车PK拾','4'=>'重慶快樂十分','5'=>'福彩3D','6'=>'排列三','7'=>'六合彩','8'=>'北京快乐8','10'=>'天津時時彩','11'=>'江西時時彩','12'=>'新疆時時彩','13'=>'江苏快3','14'=>'吉林快3');
   }
}
