<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Odds_set2 extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->login_check();
        $this->load->model('result/Odds_set2_model');
    }

     //彩票赔率列表
    public function index_fc(){
        $type = $this->input->get('type');//彩票类型
        $pankou = $this->input->get('pankou');  //盘口
        $pankou = empty($pankou)?'A':$pankou;
        $type = empty($type)?'fc_3d':$type;

        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?a:$index_id;
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.$this->Odds_set2_model->select_sites());
        }


        //获取球数与id的关系
        $balls = $this->Odds_set2_model->get_balls($type);

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        $map['lottery_type'] = $type;
        $map['pankou'] = $pankou;
        if($type == 'liuhecai' && $pankou == 'A'){
            $type_id = $this->fc_typeid_arr($type);
        }elseif($type == 'liuhecai' && $pankou == 'B'){
            $type_id = '222';
        }else{
            $type_id = $this->fc_typeid_arr($type);
        }


        $map['type_id'] = array('in','('.$type_id.')');

        $data = $this->Odds_set2_model->get_fc_odds(1,$map);     //1时，查站点表
        $data2 = array();
        foreach ($data as $key => $value) {
            if($value['type_id'] == '224'){
                if($value['type2'] == '正1特'){
                    $data2[$key] = $value;
                }
            }elseif($value['type_id'] == '225'){
                if($value['type2'] == '正码1'){
                    $data2[$key] = $value;
                }
            }elseif($value['type_id'] == '227'){
                $value['input_name'] = $value['type2'].'/'.$value['input_name'];
                $data2[$key] = $value;
            }elseif ($value['type_id'] == '232' || $value['type_id'] == '233') {
                //生肖连
                $value['input_name'] = $value['type2'].'/'.$value['input_name'];
                $data2[$key] = $value;
            }elseif($value['type_id'] == '234'){
                //全不中
                if($value['input_name'] == '1'){
                    $value['input_name'] = $value['type2'];
                    $data2[$key] = $value;
                }
            }elseif($value['type_id'] == '231'){
                //合肖
                if($value['input_name'] == '鼠'){
                    $value['input_name'] = $value['type2'];
                    $data2[$key] = $value;
                }
            }else{
                  $data2[$key] = $value;
                }
            }
        //p($data2);die;
        foreach (explode(',',$type_id) as $key => $val) {
            $i = 0;
            foreach ($data2 as $k => $v) {
                if ($v['type_id'] == $val) {
                    $rdata[$val][$i][] = $v;
                }
                if (count($rdata[$val][$i]) == 10) {
                    $i++;
                }
            }
        }

        foreach ($balls as $key => $value) {
          if($key== 159 || $key == 166){     //排列三 福彩3D
            $balls[$key]['fc_type'] = '第一球/第二球/第三球';
          }elseif($key== 191 || $key == 235 || $key == 246){      //时时彩
            $balls[$key]['fc_type'] = '第一球/第二球/第三球/第四球/第五球';
          }elseif($key== 197 || $key == 241 || $key == 252){
            $balls[$key]['fc_type'] = '前三球/中三球/后三球';
          }elseif($key== 173 || $key == 182){        //快乐十分
          $balls[$key]['fc_type'] = '第一球/第二球/第三球/第四球/第五球/第六球/第七球/第八球';
          }elseif($key== 211){        //北京赛车PK拾
            $balls[$key]['fc_type'] = '冠军/亚军/第三球/第四球/第五球/第六球/第七球/第八球/第九球/第十球';
          }
        }

        $name = $this->return_title();
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('data',$rdata);
        $this->add('type',$type);
        $this->add('name',$name[$type]);
        $this->add('balls',$balls);
        $this->add('index_id',$index_id);
        $this->add('pankou',$pankou);
        $this->display('result/odds/fc_odds2.html');
    }


    //赔率自定义修改
    public function odds_set_do(){
    //   p($_POST);die;
        $fctype = $this->input->post("fctype");
        $pankou = $this->input->post("pankou");
        $odds = $this->input->post("odds");          //要修改的赔率
        $old_odds = $this->input->post("old_odds");  //修改前的赔率
        $type_id = $this->input->post("type_id");    //游戏的某一类型的id  如：第一球，第二球
        $oddid = $this->input->post("id");           //游戏赔率ID
        // $Title = $this->return_title();
        $lottery_type = $Title[$fctype];

        $input_name = $this->input->post('input_name');//下注号码


        $index_id = $this->input->post('index_id');//区分多站点赔率


        //函数名
        $func_name = $fctype.'_odds';

        switch ($fctype) {
           case 'fc_3d':
           case 'pl_3'://福彩3D 排列三
               $func_name = 'fc_3d_odds';
               break;
           case 'js_k3'://江苏 吉林快三
           case 'jl_k3'://江苏 吉林快三
               $func_name = 'jl_k3_odds';
               break;
           case 'cq_ssc'://重庆 天津 新疆 重庆时时彩
           case 'tj_ssc':
           case 'xj_ssc':
               $func_name = 'cq_ssc_odds';
               break;
           case 'cq_ten':     //重庆快乐十分 广东快乐十分
           case 'gd_ten':
               $func_name = 'cq_ten_odds';
               break;
           case 'bj_8':     //北京快乐8
               $func_name = 'bj_8_odds';
               break;
           case 'bj_10':     //北京PK拾
               $func_name = 'bj_10_odds';
               break;
           case 'liuhecai':     //六合彩
               $func_name = 'liuhecai_odds';
               break;
        }
        if($fctype == 'liuhecai'){
          $this->$func_name($fctype,$type_id,$input_name,$odds,$pankou,$index_id);
        }else{
          $this->$func_name($fctype,$type_id,$input_name,$odds,$index_id);
        }
    }

      //六合彩生肖连赔率匹配
    public function liuhecai_sx_odds($type,$odds,$type_id,$input_name,$pankou,$index_id){
        $tmp_input_name = explode('/',$input_name);
        switch ($tmp_input_name[0]) {
          case '二肖连中':
             if ($tmp_input_name[1] == '猴') {
                  $todds = 3.8;
             }else{
                  $todds = 4.35;
             }
            break;
          case '三肖连中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 9.6;
            }else{
                $todds = 11.3;
            }
            break;
          case '四肖连中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 22.5;
            }else{
                $todds = 33.5;
            }
            break;
          case '五肖连中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 75;
            }else{
                $todds = 85;
            }
            break;
          case '二肖连不中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 3.85;
            }else{
                $todds = 4;
            }
            break;
          case '三肖连不中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 7.85;
            }else{
                $todds = 8;
            }
            break;
          case '四肖连不中':
            if ($tmp_input_name[1] == '猴') {
                $todds = 18;
            }else{
                $todds = 20;
            }
            break;
        }
        if ($tmp_input_name[1] == '猴') {
            if ($odds > $todds) {
                showmessage('不能超过极限赔率'.$todds,'back',0);
            }
            $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'猴'",$pankou,$tmp_input_name[0],$index_id);
        }else{
            if ($odds > $todds) {
                showmessage('不能超过极限赔率'.$todds,'back',0);
             }
             $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'鼠','牛','虎','兔','龙','蛇','马','羊','鸡','狗','猪'",$pankou,$tmp_input_name[0],$index_id);
        }
        return $log;
    }

    //六合彩尾数连
    public function liuhecai_ws_odds($type,$odds,$type_id,$input_name,$pankou,$index_id){
        $tmp_input_name = explode('/',$input_name);
        switch ($tmp_input_name[0]) {
          case '二尾连中':
             if ($tmp_input_name[1] == '0') {
                  $todds = 3.5;
             }else{
                  $todds = 3;
             }
            break;
          case '三尾连中':
            if ($tmp_input_name[1] == '0') {
                $todds = 7.5;
            }else{
                $todds = 6.2;
            }
            break;
          case '四尾连中':
            if ($tmp_input_name[1] == '0') {
                $todds = 18;
            }else{
                $todds = 15;
            }
            break;
          case '二尾连不中':
            if ($tmp_input_name[1] == '0') {
                $todds = 3;
            }else{
                $todds = 3.5;
            }
            break;
          case '三尾连不中':
            if ($tmp_input_name[1] == '0') {
                $todds = 6.2;
            }else{
                $todds = 7.5;
            }
            break;
          case '四尾连不中':
            if ($tmp_input_name[1] == '0') {
                $todds = 15;
            }else{
                $todds = 18;
            }
            break;
        }
        if ($tmp_input_name[1] == '0') {
            if ($odds > $todds) {
                showmessage('不能超过极限赔率'.$todds,'back',0);
            }
            $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"0",$pankou,$tmp_input_name[0],$index_id);
        }else{
            if ($odds > $todds) {
                showmessage('不能超过极限赔率'.$todds,'back',0);
             }
             $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1','2','3','4','5','6','7','8','9'",$pankou,$tmp_input_name[0],$index_id);
        }
        return $log;
    }



    //福彩3D 排列三赔率修改
    public function fc_3d_odds($type,$type_id,$input_name,$odds,$index_id){
        switch ($type_id) {
          case '159': case '166'://第一球第二球第三球

              if (is_numeric($input_name) && $input_name >=0 && $input_name <=9) {
                   if ($odds >= 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'0','1','2','3','4','5','6','7','8','9'",'','',$index_id);
               }elseif(in_array($input_name,array('大','小','单','双'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双'",'','',$index_id);
               }
            break;
          case '162':case '172'://跨度
               $tmp_odds = array('0'=>86,'1'=>16.7,'2'=>9.4,'3'=>7.2,'4'=>6.2,
                '5'=>6,'6'=>6.2,'7'=>7.2,'8'=>9.4,'9'=>16.7);
               if ($odds > $tmp_odds[$input_name]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               if ($input_name) {
                   $tmp_input_name = 10-$input_name;
                   $tmp_str = $input_name.','.$tmp_input_name;
               }else{
                   $tmp_str = 0;
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,$tmp_str,'','',$index_id);
            break;
          case '163':case '169'://3连
               $tmp_odds = array('豹子'=>100,'顺子'=>16.6,'对子'=>3.7,'半顺'=>3.17,'杂六'=>2.5);
               if ($odds > $tmp_odds[$input_name]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
            break;
         case '164':case '171'://独胆
               if ($odds > 3.8) {
                   showmessage('不能超过极限赔率3.8','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'0','1','2','3','4','5','6','7','8','9'",'','',$index_id);
            break;
         case '165':case '170'://总和龙虎
              if(in_array($input_name,array('总和大','总和小','总和单','总和双','龙','虎'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总和大','总和小','总和单','总和双','龙','虎'",'','',$index_id);
              }elseif($input_name == '和'){
                   if ($odds > 9) {
                       showmessage('不能超过极限赔率9','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'和'",'','',$index_id);
              }
            break;
        }

        if ($log) {
           $fctitle = $this->return_title();
           $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
           $this->Odds_set2_model->Syslog($logs);
           $redis_status = $this->Odds_set2_model->del_odd_redis($type);
           if($redis_status){
            showmessage('设定赔率成功','back');
           }else{
            showmessage('设定赔率失败','back',0);
           }

       }else{
           showmessage('设定赔率失败','back',0);
       }
    }

    //江苏快三 吉林
    public function jl_k3_odds($type,$type_id,$input_name,$odds,$index_id){
        switch ($type_id) {
           case '268':     //吉林快三和值
           case '273':     //江苏快三和值
               if ($input_name == 3 || $input_name == 18) {
                   if ($odds > 165) {
                       showmessage('不能超过极限赔率165','back',0);
                   }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'3','18'",'','',$index_id);
               }elseif($input_name == 4 || $input_name == 17){
                   if ($odds > 48) {
                       showmessage('不能超过极限赔率48','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'4','17'",'','',$index_id);
               }elseif($input_name == 5 || $input_name == 16){
                   if ($odds > 25) {
                       showmessage('不能超过极限赔率25','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'5','16'",'','',$index_id);
               }elseif($input_name == 6 || $input_name == 15){
                   if ($odds > 15) {
                       showmessage('不能超过极限赔率15','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'6','15'",'','',$index_id);
               }elseif($input_name == 7 || $input_name == 14){
                   if ($odds > 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'7','14'",'','',$index_id);
               }elseif($input_name == 8 || $input_name == 13){
                   if ($odds > 7.2) {
                       showmessage('不能超过极限赔率7.2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'8','13'",'','',$index_id);
               }elseif($input_name == 9 || $input_name == 12){
                   if ($odds > 6.05) {
                       showmessage('不能超过极限赔率6.05','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'9','12'",'','',$index_id);
               }elseif($input_name == 10 || $input_name == 11){
                   if ($odds > 5.6) {
                       showmessage('不能超过极限赔率5.6','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'10','11'",'','',$index_id);
               }elseif(in_array($input_name,array('大','小','单','双'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双'",'','',$index_id);
               }
               break;
           case '269'://独胆
           case '274':
               if (is_numeric($input_name) && $input_name >=1 && $input_name <=6) {
                   if ($odds > 2.05) {
                       showmessage('不能超过极限赔率2.05','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1','2','3','4','5','6'",'','',$index_id);
               }
               break;
           case '270'://豹子
           case '275':
               if (in_array($input_name,array('1,1,1','2,2,2','3,3,3','4,4,4','5,5,5','6,6,6'))) {
                   if ($odds > 165) {
                       showmessage('不能超过极限赔率165','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1,1,1','2,2,2','3,3,3','4,4,4','5,5,5','6,6,6'",'','',$index_id);
               }elseif($input_name == '任意豹子'){
                   if ($odds > 27.5) {
                       showmessage('不能超过极限赔率27.5','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'任意豹子'",'','',$index_id);
               }
               break;
           case '271'://两连
           case '276':
               if (in_array($input_name,array('1,2','1,3','1,4','1,5','1,6','2,3','2,4','2,5','2,6','3,4','3,5','3,6','4,5','4,6','5,6'))) {
                   if ($odds > 4.8) {
                       showmessage('不能超过极限赔率4.8','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1,2','1,3','1,4','1,5','1,6','2,3','2,4','2,5','2,6','3,4','3,5','3,6','4,5','4,6','5,6'",'','',$index_id);
               }
               break;
           case '272':    //对子
           case '277':
               if (in_array($input_name,array('1,1','2,2','3,3','4,4','5,5','6,6'))) {
                   if ($odds > 9.8) {
                       showmessage('不能超过极限赔率9.8','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1,1','2,2','3,3','4,4','5,5','6,6'",'','',$index_id);
               }
               break;
       }

       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
            $this->Odds_set2_model->Syslog($logs);
            $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
        }else{
            showmessage('设定赔率失败','back',0);
        }
    }


    //重庆时时彩 新疆 天津
    public function cq_ssc_odds($type,$type_id,$input_name,$odds,$index_id){
        switch ($type_id) {
           case '191':
           case '235':
           case '246':
           //第一二三四五球
               if (is_numeric($input_name) && $input_name >=0 && $input_name <=9) {
                   if ($odds >= 10) {
                       showmessage('不能超过极限赔率10','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'0','1','2','3','4','5','6','7','8','9'",'','',$index_id);
               }elseif(in_array($input_name,array('大','小','单','双'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双'",'','',$index_id);
               }
               break;
           case '196'://总和龙虎
           case '240':
           case '251':
               if (in_array($input_name,array('总和大','总和小','总和单','总和双','龙','虎'))) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总和大','总和小','总和单','总和双','龙','虎'",'','',$index_id);
               }elseif ($input_name == '和') {
                  if ($odds > 9) {
                       showmessage('不能超过极限赔率9','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'和'",'','',$index_id);
               }
               break;
           case '197'://前三球/中三球/后三球
           case '241':
           case '252':
               $tmp_odds = array('豹子'=>60,'顺子'=>13,'对子'=>2.8,'半顺'=>2,'杂六'=>2.2);
               if ($odds > $tmp_odds[$input_name]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
               break;
           case '200'://斗牛
           case '244':
           case '255':
               if ($input_name == '没牛') {
                   if ($odds > 2.1) {
                       showmessage('不能超过极限赔率2.1','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
               }elseif(in_array($input_name,array('牛1','牛2','牛3','牛4','牛5','牛6','牛7','牛8','牛9','牛牛'))){
                   if ($odds > 9.22) {
                       showmessage('不能超过极限赔率9.22','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'牛1','牛2','牛3','牛4','牛5','牛6','牛7','牛8','牛9','牛牛'",'','',$index_id);
               }elseif(in_array($input_name,array('牛大','牛小','牛单','牛双'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'牛大','牛小','牛单','牛双'",'','',$index_id);
               }
               break;
           case '201'://梭哈
           case '245':
           case '256':
               $tmp_odds = array('五条'=>99,'四条'=>99,'葫芦'=>70,'顺子'=>60,'三条'=>10.9,
                           '两条'=>7.3,'一对'=>1.86,'散号'=>2.86);
               if ($odds > $tmp_odds[$input_name]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               if ($input_name == "五条" || $input_name == '四条') {
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'五条','四条'",'','',$index_id);
               }else{
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
               }
               break;
       }
       if ($log) {
           $fctitle = $this->return_title();
           $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
           $this->Odds_set2_model->Syslog($logs);
           $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
       }else{
           showmessage('设定赔率失败','back',0);
       }
    }


    //重庆快乐十分 广东快乐十分
   public function cq_ten_odds($type,$type_id,$input_name,$odds,$index_id){
      switch ($type_id) {
           case '173':
           case '182':
           //第一二三四五六七八球
               if (is_numeric($input_name) && $input_name >=1 && $input_name <=20) {
                   if ($odds >= 20) {
                       showmessage('不能超过极限赔率20','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'",'','',$index_id);
               }elseif(in_array($input_name,array('大','小','单','双','尾大','尾小','合单','合双'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双','尾大','尾小','合单','合双'",'','',$index_id);
               }elseif(in_array($input_name,array('东','南','西','北'))){
                   if ($odds > 3.76) {
                       showmessage('不能超过极限赔率3.76','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'东','南','西','北'",'','',$index_id);
               }elseif(in_array($input_name,array('中','发'))){
                   if ($odds > 2.7) {
                       showmessage('不能超过极限赔率2.7','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'中','发'",'','',$index_id);
               }elseif($input_name == '白'){
                   if ($odds > 3.15) {
                       showmessage('不能超过极限赔率3.15','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'白'",'','',$index_id);
               }
               break;
           case '181':     //总和龙虎
           case '190':
               if (in_array($input_name,array('总和大','总和小','总和单','总和双','总和尾大','总和尾小','龙','虎'))) {
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总和大','总和小','总和单','总和双','总和尾大','总和尾小','龙','虎'",'','',$index_id);
               }
               break;
          case '278':     //连码
          case '280':
               $tmp_odds = array('任选二'=>6,'任选二组'=>20,'任选三'=>18,'任选四'=>60,'任选五'=>120);
               if ($odds > $tmp_odds[$input_name]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
               break;
       }
       if ($log) {
            $fctitle = $this->return_title();
            $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
            $this->Odds_set2_model->Syslog($logs);
            $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
        }else{
            showmessage('设定赔率失败','back',0);
        }
    }

    public function bj_8_odds($type,$type_id,$input_name,$odds,$index_id){
        switch ($type_id) {
             case '202':   //选一
             case '203':   //选二
                $tmp_odds = array('一中一'=>3,'二中二'=>10);
                if ($odds > $tmp_odds[$input_name]) {
                     showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                 }
                 $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
                 break;
             case '204':   //选三
                $tmp_odds = array('三中三'=>20,'三中二'=>3);
                if ($odds > $tmp_odds[$input_name]) {
                     showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                 }
                 $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
                 break;
             case '205':  //选四
                 $tmp_odds = array('四中四'=>50,'四中三'=>5,'四中二'=>3);
                if ($odds > $tmp_odds[$input_name]) {
                     showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                 }
                 $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
                 break;
             case '206':   //选五
                $tmp_odds = array('五中五'=>250,'五中四'=>20,'五中三'=>5);
                if ($odds > $tmp_odds[$input_name]) {
                     showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                 }
                 $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'','',$index_id);
                 break;
             case '207'://和值
                 if (in_array($input_name,array('总和大','总和小','总和单','总和双'))) {
                     if ($odds >= 2) {
                         showmessage('不能超过极限赔率2','back',0);
                     }
                     $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总和大','总和小','总和单','总和双'",'','',$index_id);
                 }elseif($input_name == '总和810'){
                    if ($odds > 50) {
                         showmessage('不能超过极限赔率50','back',0);
                     }
                     $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总和810'",'','',$index_id);
                 }
                 break;
              case '208'://上中下
              case '209'://奇偶和
                $tmp_odds = array('上盘'=>1.93,'中盘'=>2.8,'下盘'=>1.93,'奇盘'=>1.93,'和盘'=>2.8,'偶盘'=>1.93);
                if ($odds > $tmp_odds[$input_name]) {
                     showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                 }
                if($input_name == '上盘' || $input_name == '奇盘'){
                    $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'上盘','奇盘'",'','',$index_id);
                }elseif ($input_name == '中盘' || $input_name == '和盘') {
                    $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'中盘','和盘'",'','',$index_id);
                }elseif ($input_name == '下盘' || $input_name == '偶盘') {
                    $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'下盘','偶盘'",'','',$index_id);
                }
                /*$log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'".$input_name."'",'');*/
              break;
         }
         if ($log) {
              $fctitle = $this->return_title();
              $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
              $this->Odds_set2_model->Syslog($logs);
              $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
          }else{
              showmessage('设定赔率失败','back',0);
          }
    }

    //北京PK拾
    public function bj_10_odds($type,$type_id,$input_name,$odds,$index_id){
        switch ($type_id) {
            case '211':   //冠军/亚军/第三球/.../第十球
                if (is_numeric($input_name) && $input_name >=1 && $input_name <=10) {
                   if ($odds >= 10) {
                    showmessage('不能超过极限赔率10','back',0);
                    }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1','2','3','4','5','6','7','8','9','10'",'','',$index_id);
               }elseif(in_array($input_name,array('大','小','单','双','龙','虎'))){
                   if ($odds >= 2) {
                       showmessage('不能超过极限赔率2','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双','龙','虎'",'','',$index_id);
               }
            break;
            case '210':     //冠亚军和
              if($input_name == 3 || $input_name == 4 || $input_name == 18 || $input_name == 19){
                  if ($odds >= 45) {
                      showmessage('不能超过极限赔率45','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'3','4','18','19'",'','',$index_id);
              }elseif($input_name == 5 || $input_name == 6 || $input_name == 16 || $input_name == 17){
                  if ($odds >= 22.5) {
                      showmessage('不能超过极限赔率22.5','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'5','6','16','17'",'','',$index_id);
              }elseif($input_name == 7 || $input_name == 8 || $input_name == 14 || $input_name == 15){
                  if ($odds >= 15) {
                      showmessage('不能超过极限赔率15','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'7','8','14','15'",'','',$index_id);
              }elseif($input_name == 9 || $input_name == 10 || $input_name == 12 || $input_name == 13){
                  if ($odds >= 11.25) {
                      showmessage('不能超过极限赔率11.25','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'9','10','12','13'",'','',$index_id);
              }elseif ($input_name == 11) {
                if ($odds > 9) {
                      showmessage('不能超过极限赔率9','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'11'",'','',$index_id);
              }elseif($input_name == '冠亚大' || $input_name == '冠亚双'){
                  if ($odds >= 2.25) {
                      showmessage('不能超过极限赔率2.25','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'冠亚大','冠亚双'",'','',$index_id);
              }elseif($input_name == '冠亚小' || $input_name == '冠亚单'){
                  if ($odds >= 1.8) {
                      showmessage('不能超过极限赔率1.8','back',0);
                  }
                  $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'冠亚小','冠亚单'",'','',$index_id);
              }
            break;
            case '221':   //龙虎
              if($input_name == '龙' || $input_name == '虎'){
                  if ($odds >= 1.97) {
                      showmessage('不能超过极限赔率1.97','back',0);
                  }
              $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'龙','虎'",'','',$index_id);
              }
            break;
        }
         if ($log) {
              $fctitle = $this->return_title();
              $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
              $this->Odds_set2_model->Syslog($logs);
              $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
          }else{
              showmessage('设定赔率失败','back',0);
          }
    }

    //六合彩赔率修改
    public function liuhecai_odds($type,$type_id,$input_name,$odds,$pankou,$index_id){
      switch ($type_id) {
        case '222':
           if (in_array($input_name,array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'))) {
               if ($odds >= 49) {
                   showmessage('不能超过极限赔率49','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'",$pankou,'',$index_id);
           }else{
               $tmp_odds = array('大'=>2,'小'=>2,'单'=>2,'双'=>2,'合大'=>2,
                '合小'=>2,'合单'=>2,'合双'=>2,'尾大'=>2,'尾小'=>2,
                '野兽'=>2,'家禽'=>2,'红波'=>3,'蓝波'=>3,'绿波'=>3,'大单'=>4,'大双'=>4,'小单'=>4,'小双'=>4,'1-10'=>4.5,'11-20'=>4.5,'21-30'=>4.5,'31-40'=>4.5,'41-49'=>4.9);
              if ($odds >= $tmp_odds[$input_name]) {
                  showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
              }
              if(in_array($input_name,array('大','小','单','双','合大','合小','合单','合双','尾大','尾小','家禽','野兽'))){
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双','合大','合小','合单','合双','尾大','尾小','家禽','野兽'",$pankou,'',$index_id);
              }elseif (in_array($input_name,array('大单','大双','小单','小双'))) {
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大单','大双','小单','小双'",$pankou,'',$index_id);
              }elseif (in_array($input_name,array('蓝波','绿波'))) {
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'蓝波','绿波'",$pankou,'',$index_id);
              }elseif ($input_name == '红波') {
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'红波'",$pankou,'',$index_id);
              }elseif (in_array($input_name,array('1-10','11-20','21-30','31-40'))) {
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1-10','11-20','21-30','31-40'",$pankou,'',$index_id);
              }elseif ($input_name == '41-49') {
                $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'41-49'",$pankou,'',$index_id);
              }
           }
         break;
        case '223':
              if (in_array($input_name,array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'))) {
               if ($odds > 7.15) {
                   showmessage('不能超过极限赔率7.15','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'",$pankou,'',$index_id);
              }else{
                    $tmp_odds = array('总单'=>2,'总双'=>2,'总大'=>2,'总小'=>2,'总尾大'=>2,'总尾小'=>2,'龙'=>2,'虎'=>2);
                    if ($odds >= $tmp_odds[$input_name]) {
                        showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
                    }
                    if(in_array($input_name,array('总单','总双','总大','总小'))){
                      $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总单','总双','总大','总小'",$pankou,'',$index_id);
                    }elseif (in_array($input_name,array('总尾大','总尾小'))) {
                      $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'总尾大','总尾小'",$pankou,'',$index_id);
                    }elseif (in_array($input_name,array('龙','虎'))) {
                      $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'龙','虎'",$pankou,'',$index_id);
                    }
             }
         break;
        case '224':
              if (in_array($input_name,array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'))) {
               if ($odds > 49) {
                   showmessage('不能超过极限赔率49','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'",$pankou,'',$index_id);
             }
          break;
        case '225':
              if (in_array($input_name,array('大','小','单','双','合大','合小','合单','合双','尾大','尾小'))) {
               if ($odds > 2) {
                   showmessage('不能超过极限赔率2','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'大','小','单','双','合大','合小','合单','合双','尾大','尾小'",$pankou,'',$index_id);
               }else{
                   if ($odds > 3) {
                       showmessage('不能超过极限赔率3','back',0);
                   }
                   if($input_name == '蓝波' || $input_name == '绿波'){
                       $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'蓝波','绿波'",$pankou,'',$index_id);
                   }else{
                        $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'红波'",$pankou,'',$index_id);
                   }
               }
          break;
        case '227':
              $tmp_odds = array('二全中/二全中'=>61,'二中特/中特'=>30,'二中特/中二'=>50,'特串/特串'=>150,'三全中/三全中'=>630,'三中二/中二'=>20,'三中二/中三'=>85,'四中一/四中一'=>2);
               if ($odds > $tmp_odds[$input_name]) {
                   showmessage('不能超过极限赔率'.$tmp_odds[$input_name],'back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,$input_name,$pankou,'',$index_id);
            break;
        case '229':
            if (is_numeric($input_name)) {
                if (in_array($input_name,array(1,2,3,4,5,6,7,8,9))) {
                    if ($odds > 1.76) {
                         showmessage('不能超过极限赔率1.76','back',0);
                    }
                    $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'1','2','3','4','5','6','7','8','9'",$pankou,'尾数');
                }else{
                   if ($odds > 2.05) {
                       showmessage('不能超过极限赔率2.05','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'0'",$pankou,'尾数',$index_id);
                }
            }else{
                if (in_array($input_name,array('鼠','牛','虎','兔','龙','蛇','马','羊','鸡','狗','猪'))) {
                   if ($odds > 2.05) {
                       showmessage('不能超过极限赔率2.05','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'鼠','牛','虎','兔','龙','蛇','马','羊','鸡','狗','猪'",$pankou,'一肖',$index_id);
               }else{
                   if ($odds > 1.75) {
                       showmessage('不能超过极限赔率1.75','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'猴'",$pankou,'一肖',$index_id);
               }
            }
          break;
        case '230':
              if (in_array($input_name,array('鼠','牛','虎','兔','龙','蛇','马','羊','鸡','狗','猪'))) {
               if ($odds >= 12.25) {
                   showmessage('不能超过极限赔率12.25','back',0);
               }
               $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'鼠','牛','虎','兔','龙','蛇','马','羊','鸡','狗','猪'",$pankou,'',$index_id);
               }else{
                   if ($odds >= 9.8) {
                       showmessage('不能超过极限赔率9.8','back',0);
                   }
                   $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'猴'",$pankou,'',$index_id);
               }
          break;
        case '231':
          $tmp_arr = array('二肖'=>5.6,'三肖'=>3.7,'四肖'=>2.8,'五肖'=>2.2,'六肖'=>1.98,'七肖'=>1.63,'八肖'=>1.43,'九肖'=>1.27,'十肖'=>1.14,'十一肖'=>1.05);
          if($odds > $tmp_arr[$input_name]){
               showmessage('不能超过极限赔率'.$tmp_arr[$input_name],'back',0);
          }
          $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"'鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪'",$pankou,$input_name,$index_id);
          break;
        case '232':
          //生肖连
          $log = $this->liuhecai_sx_odds($type,$odds,$type_id,$input_name,$pankou,$index_id);
          break;
        case '233':
          //尾数连
          $log = $this->liuhecai_ws_odds($type,$odds,$type_id,$input_name,$pankou,$index_id);
          break;
        case '234':
            $tmp_arr = array('五不中'=>2,'六不中'=>2.6,'七不中'=>3.1,'八不中'=>4,'九不中'=>4.6,'十不中'=>5.6,'十一不中'=>6.5,'十二不中'=>8.2);
            if($odds > $tmp_arr[$input_name]){
               showmessage('不能超过极限赔率'.$tmp_arr[$input_name],'back',0);
            }
            $log = $this->Odds_set2_model->odds_set_do($type,$odds,$type_id,"1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49",$pankou,$input_name,$index_id);
           break;
        }
        if ($log) {
              $fctitle = $this->return_title();
              $logs['log_info'] = $fctitle[$type].'--'.$type_id.'--'.$input_name.'设定赔率---'.$odds;
              $this->Odds_set2_model->Syslog($logs);
              $redis_status = $this->Odds_set2_model->del_odd_redis($type);
            if($redis_status){
             showmessage('设定赔率成功','back');
            }else{
             showmessage('设定赔率失败','back',0);
            }
        }else{
            showmessage('设定赔率失败','back',0);
        }
    }


    //彩票玩法调整
    public function fc_typeid_arr($type){
        $type_arr = array('fc_3d'=>'159,162,163,164,165',
                           'pl_3'=>'166,169,170,171,172',
                           'cq_ten'=>'173,181,280',
                           'gd_ten'=>'182,190,278',
                           'cq_ssc'=>'191,196,197,200,201',
                           'tj_ssc'=>'235,240,241,244,245',
                           'xj_ssc'=>'246,251,252,255,256',
                           'bj_8'=>'202,203,204,205,206,207,208,209',
                           'bj_10'=>'210,211,221',
                           'liuhecai'=>'222,223,224,225,227,229,230,231,232,233,234',
                           'jl_k3'=>'268,269,270,271,272',
                           'js_k3'=>'273,274,275,276,277',
                           );
        if ($type && $type_arr[$type]) {
            return $type_arr[$type];
        }else{
            return $type_arr;
        }
    }

   //赔率初始化
   public function odds_set_initializtion(){
       $type = $this->input->get('type');
       $pankou = $this->input->get('pankou');
       $index_id = $this->input->get('index_id');
       if (empty($type) || empty($pankou)) {
           showmessage('参数错误!!!','back',0);
       }

       $map['site_id'] = 1;
       $map['pankou'] = $pankou;
       $map['lottery_type'] = $type;
       if($type == 'liuhecai' && $pankou == 'A'){
          $map['type_id'] = array('in',"('222','223','224','225','227','229','230','231','232','233','234')");
       }elseif ($type == 'liuhecai' && $pankou == 'B') {
          $map['type_id'] = '222';
       }
       $data = $this->Odds_set2_model->get_fc_odds(2,$map);

       $db_model = array();
       $db_model['tab'] = 'c_odds_'.$_SESSION['site_id'];
       $db_model['type'] = 2;
       foreach ($data as $key => $val) {
           $maps = array();
           $maps['site_id'] = $_SESSION['site_id'];
           $maps['index_id'] = $index_id;
           $maps['pankou'] = $pankou;
           $maps['lottery_type'] = $type;
           $maps['input_name'] = $val['input_name'];
           $maps['type_id'] = $val['type_id'];
           $maps['type2'] = $val['type2'];
           unset($val['type2']);
           unset($val['index_id']);
           $this->Odds_set2_model->M($db_model)->where($maps)->update($val);
       }
       $fctitle = $this->return_title();
       $log['log_info'] = '彩票赔率初始化,类型：'.$fctitle[$type];
       $this->Odds_set2_model->Syslog($log);
       showmessage('赔率初始化成功!','back');
   }

     //彩票中文类型
   public function return_title(){
       return array('gd_ten'=>'廣東快樂十分','cq_ssc'=>'重慶時時彩','bj_10'=>'北京赛车PK拾','cq_ten'=>'重慶快樂十分','fc_3d'=>'福彩3D','pl_3'=>'排列三','liuhecai'=>'六合彩','bj_8'=>'北京快乐8','tj_ssc'=>'天津時時彩','xj_ssc'=>'新疆時時彩','js_k3'=>'江苏快3','jl_k3'=>'吉林快3');
   }



}
