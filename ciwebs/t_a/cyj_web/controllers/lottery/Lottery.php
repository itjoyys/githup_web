<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lottery extends MY_Controller
{

  public function __construct()
  {
      parent::__construct();
      // session_destroy();
      //p($_SESSION);
      //测试用
      // $_SESSION['uid'] = 1060;
      // $_SESSION['agent_id'] = 112;
      // $_SESSION['username'] = 'lijiajun01';
      // $_SESSION['level_id'] = 6;
      // $_SESSION['shiwan'] = 0;
      // $_SESSION['ssid'] = kotjct00u33mtmdve511j7d7u7;




      $this->load->model('lottery/Lottery_model','lottery');
      $type = $this->input->get('type');
      $type_arr_n = array('liuhecai','fc_3d','pl_3','cq_ssc','jx_ssc','xj_ssc','tj_ssc','jl_k3','js_k3','cq_ten','gd_ten','bj_8','bj_10','xy_28','gd_11');
      if(!in_array($type, $type_arr_n)){
        $type = 'cq_ssc';
      }
      // $pankou = $this->lottery->_get_pankou($type);
      // $_SESSION['pankou_set'] = $pankou;
      // if($pankou == 'ALL'){
      //   if(empty($_SESSION['pankou'])){
      //     $_SESSION['pankou'] = 'A'; //默认显示的盘口
      //   }
      // }elseif ($pankou == 'B') {
      //   $_SESSION['pankou'] = 'B';
      // }elseif ($pankou == 'A') {
      //   $_SESSION['pankou'] = 'A';
      // }


// echo $_SESSION['pankou'];

      $guonian_2016 = '2016-02-08 00:00:00';
      if(func_nowtime('Y-m-d H:i:s','now') > $guonian_2016){
        $nianfen=2016;
      }else{
        $nianfen=2015;
      }
      $this->add('nianfen',$nianfen);

      $config_css = $this->config->item('css');
      $this->add('type',$type);
      $this->add('config_css',$config_css);

  }


  public function pankou_ajax(){
    if($this->input->post('action') == 'pankou_ajax'){
      $pankou_get = $this->input->post("pankou");
      $type = $this->input->post("type");
      // $pankou = $this->lottery->_get_pankou($type);
      // $_SESSION['pankou_set'] = $pankou;
      // if($pankou == 'ALL'){
        if(!empty($pankou_get) && $pankou_get != $_SESSION['pankou']){
          $_SESSION['pankou'] = $pankou_get;//盘口切换
        }
      // }elseif ($pankou == 'B') {
      //   $_SESSION['pankou'] = 'B';
      // }elseif ($pankou == 'A') {
      //   $_SESSION['pankou'] = 'A';
      // }
      echo $pankou_get;
    }
  }


  public function index(){

     if(empty($_SESSION['pankou'])){
        $_SESSION['pankou']= 'A';
      }

      if($this->input->get("gameid") != 222){
        // echo $this->input->get("gameid");
        // exit;
        $_SESSION['pankou'] = 'A'; //临时用
      }


    // echo date("Y-m-d H:i:s");
    $type = $this->input->get('type');
    $type_name_c = eng2chn($type);
    $this->add('type_name_c', $type_name_c);
    $type_arr_n = array('liuhecai','fc_3d','pl_3','cq_ssc','jx_ssc','xj_ssc','tj_ssc','jl_k3','js_k3','cq_ten','gd_ten','bj_8','bj_10','xy_28','gd_11');
      if(!in_array($type, $type_arr_n)){
        $type = 'cq_ssc';
      }
    /* $ifopen = $this->lottery->ifopen($type,INDEX_ID,SITEID);
    if($ifopen == 0){
    	message("彩种已关闭");
    } */
    $head = $this->lottery->_get_head();
    $this->add('uid',$_SESSION['uid']);
    $mapf['table'] = 'fc_games_type';
    $mapf['select'] = 'name,fc_type,id as gameid ,tep_type';
    $mapf['where']['fc_type'] = $type;
    $mapf['order'] = 'id asc';
    $this->lottery->_get_fengpan_time($type);
    $list = $this->lottery->get_table($mapf);
    //p($list);
    $this->add('list', $list);
    $odds_arr = $this->lottery->get_odds_all($type);
     // p($odds_arr);

    //时时彩转数组格式
    if($type == 'cq_ssc' || $type == 'tj_ssc' || $type == 'xj_ssc' || $type == 'jx_ssc' ){
      $odds_html_5 =  pl_odds_ssc5($odds_arr);
      // p($odds_arr);exit;
      // p($odds_html_5);exit;
      $odds_html_4 =  pailie_odds_ssc_4($odds_arr);
      $this->add('list_5',$odds_html_5);
      $this->add('list_4',$odds_html_4);
    }elseif($type == 'xy_28'){

      $odds_xy_28 =  pailie_odds_xy_28($odds_arr);
// p($odds_xy_28);
      $this->add('odds_xy_28',$odds_xy_28);

    }elseif($type == 'cq_ten' ||$type == 'gd_ten' ){
      $odds_html_2mian =  pailie_odds_ten($odds_arr);
      $odds_html_dijiqiu =  pailie_odds_ten_dijiqiu($odds_arr);
      $odds_html_4 =  pailie_odds_ssc_4($odds_arr);
      $odds_html_zonghe =  pailie_odds_ten_zonghe($odds_arr);
     // p($odds_html_2mian);
      $this->add('list_4',$odds_html_4);
      // p($odds_html_2mian[9]);exit;
      $this->add('odds_html_zonghe',$odds_html_zonghe);
      $this->add('odds_html_2mian',$odds_html_2mian);
      $this->add('odds_html_dijiqiu',$odds_html_dijiqiu);
    }elseif ($type=='pl_3'||$type == 'fc_3d'){
        $arr1 = array('第一球','第二球','第三球');
        $odds_html_5 =  pailie_odds_5($odds_arr,$arr1);
        $arr2 = array('独胆','跨度','3连','總和,龍虎');
        $odds_html =  pailie_odds_5($odds_arr,$arr2);
        $odds_html =  pailie_odds_5_j($odds_html);
        // p($odds_html);
        $this->add('odds_html',$odds_html);
        $this->add('list_5',$odds_html_5);
    }elseif ($type == 'js_k3' ||$type =='jl_k3'){
        //$arr1 = array('和值','两连','独胆','豹子','对子');
        $odds_html_5 =  pl_odds_k3($odds_arr,$arr1);
        //p($odds_arr);exit;
        // p($odds_html_5);exit;
        $this->add('list_5',$odds_html_5);
    }elseif ($type=='bj_10'){
        $odds_html_dijiqiu =  pl_odds_ten($odds_arr);
        //p($odds_html_dijiqiu[0][1]);exit;
        $this->add('odds_html_dijiqiu',$odds_html_dijiqiu);
    }elseif($type == 'bj_8' ){
        $oddsall = array('202','203','204','205','206');
        $beijin=array('840','841','842','844','847');
        $lianma = array();
        foreach ($odds_arr as $k=>$v){
            if(in_array($v['id'], $beijin)){
              $selected[$k] =selected_one($v);
            }
            if(in_array($v['type_id'], $oddsall)){
              // $lianma[$v['fc_type']]['odds'] .= $v['odds_value'].',';
              $lianma[$v['fc_type']]['odds'] = 0;
              $lianma[$v['fc_type']]['id'] = $v['id'];
              $lianma[$v['fc_type']]['count_arr'] = $v['count_arr'];
            }
        }
// p($lianma);
        foreach ($lianma as $k => $v) {
          $aa = rtrim($v['odds'],',');
          $lianma[$k]['odds'] = $aa;
        }
        $j = array();
        $h = array();
        for($i=1;$i<=4;$i++){
          $j[] = $i;
        }
        for($i=1;$i<=20;$i++){
          $h[] = $i;
        }

     // p($odds_arr);
     // p($selected);
      $arr1 = array('选一');
      $odds_html_5 =  pailie_odds_k3_5($selected[0],$arr1,8);
      $this->add('list_5',$odds_html_5);
      $this->add('lianma',$lianma);
      $this->add('j',$j);
      $this->add('h',$h);
      $arr2 = array('和值','上中下','奇和偶');
      $odds_html =  pailie_odds_5($odds_arr,$arr2);
      $this->add('odds_html',$odds_html);
        //$list = $list;

       // $selected = putong_odds($selected);
        //p($odds_html_dijiqiu);
       // p($selected);




        $this->add('selected', $selected);
        $this->add('oddsall', $oddsall);
    }


 //p($odds_html_5);
    $this->add('head',$head);
    $this->add('url',URL);
    if(!empty($type)&&$type!='liuhecai'){

      $this->add('type',$type);
       $result = $this->lottery->_get_luzhu($type);

      $this->add('is_login',$_SESSION['uid']);


      $this->display("lottery/".$type.'.html');
    }else{
        $this->liuhecai($type);
    }
  }
public function liuhecai($type){
    $gameid = $this->input->get('gameid');
    $ganemid_arr_n = array('222','223','224','225','226','227','228','229','230','231','232','233','234');
    if(!in_array($gameid, $ganemid_arr_n)){
        $gameid = '222';
      }

    //  p($odds_arr);
    $config = $this->config->item('games')[$gameid];
    $gamename2 = $this->input->get('gamename2');
    $gamename2 = $gamename2?$gamename2:$config[0]['name'];
    $odds_arr = $this->lottery->get_odds_liuhecai($type,$gameid,$gamename2);
    $this->add('config', $config);
    $this->add('gamename2', $gamename2);
    if($gameid==225){
    $odds_arr = zhengma($odds_arr);
    }elseif($gameid==226){
        $odds_arr = guoguan($odds_arr);
    }elseif($gameid==227 ){
      $odds_arr = lianma($odds_arr);
    }elseif($gameid==228 ){

    }elseif( $gameid==229 ||$gameid==230 ||$gameid==231||$gameid==232 ||$gameid==233){
        if (strstr($gamename2,'尾') == true){
          $qiu =  func_get_weishu();
        }else{
            $qiu = func_get_shenxiao($gameid);
        }
    }elseif($gameid==234){

    }else{
        $odds_arr = tamaodds($odds_arr);
    }

    // p($odds_arr);
    $now_qishu = $this->lottery->_dq_qishu($type);//当前期数
    $this->add('now_qishu', $now_qishu);
    $this->add('is_login',$_SESSION['uid']);
    $this->add('gamename2', $gamename2);
     $this->add('odds_arr', $odds_arr);
     $this->add('qiu', $qiu);
    $this->display("lottery/".$type.'/'.$gameid.'.html');
}
  public function rule()
  {
      $lotteryId = $this->input->get('lotteryId');
      $this->add('type', $lotteryId);
      $this->display("lottery/rule.html");
  }
  public function notCount(){
      $uid = $_SESSION['uid'];
      $lotteryId = $this->input->get('lotteryId');
      $starttime = date('Y-m-d')." 00:00:00";
      $endtime  = date('Y-m-d')." 23:59:59";
      if(empty($lotteryId)){
          /*$map['table'] ='c_bet';
          $map['select'] = "COUNT(*) as count,type,sum(money) as money";
          $map['where'] = "date_format(addtime,'%Y-%m-%d') ='".date('Y-m-d')."' and uid=".$uid;
          $map['group'] = 'type';
          $data = $this->lottery->get_table($map);*/
      	  $sql="SELECT COUNT(a.id) as count,b.`name`, sum(a.money) as money from fc_games as b LEFT JOIN (SELECT id,type,money FROM `c_bet` WHERE addtime BETWEEN '".$starttime."' and '".$endtime."' and `uid` = $uid and `js` = 0) as a
      	  		on a.type=b.name where b.state=1 GROUP BY b.name ORDER BY b.id";
      	  $query = $this->db->query($sql);
          $data = $query->result_array();
          $this->add('data', $data);
          $this->display("lottery/notCount.html");
      }else{
          $map['table'] ='c_bet';
          $map['select'] = "*";
          $map['where'] = "addtime BETWEEN '".$starttime."' and '".$endtime."' and uid=".$uid."  and `js`= 0 and type='".$lotteryId."'";
          $map['order'] = 'id desc';
          // $map['limit'] = '20';
          $data = $this->lottery->get_table($map);
          foreach ($data as $k=>$v){
              $mun['count'] =+($k+1);
              $mun['money'] +=$v['money'];
              $mun['win'] +=$v['win'];
          }
          $this->add('data', $data);
          $this->add('mun', $mun);
          $this->display("lottery/notCount2.html");
      }
  }


  public function get_json(){

      $postarr =$this->input->file_get();
      header('Content-Type: application/json;charset=utf-8');
      $type = $postarr['lotteryId'];

      $json = $this->lottery->_get_json($type);
      for($i=0;$i<=20;$i++){
        $json = preg_replace('/,"p":"'.$i.'"}/',',"p":'.$i.'}',$json);
      }
      //$this->input->set_cookie("_LW",time(),60);
      echo $json;

  }


/*
 * 下注
 *  */
  public function bet(){
      $postarr =$this->input->file_get();
      header('Content-Type: application/json;charset=utf-8');
      $data = $this->lottery->_addlottery_bet($postarr);
      echo $data;
  }



	public function liuhecaijson(){
	    $postarr =$this->input->file_get();
	    header('Content-Type: application/json;charset=utf-8');
	    $list = $this->lottery->get_odds_one($postarr['lotteryPan']);
	    $type = 'liuhecai';
	    if($_SESSION['username']){
	        $IsLogin = true;
	    }else{
	        $IsLogin = false;
	    }
	    $fengpan_time = $this->lottery->_get_fengpan_time($type);
        $json = array(
            'Success' => 1,
            'Msg' =>"",
            'Obj' => array(
                'IsLogin'=>$IsLogin,
                'Lines' => $list,
                "CloseCountdown" => $fengpan_time['f_t_stro']

            ),
            'ExtendObj'=>array('IsLogin'=>$IsLogin),
            'OK'=>false,
            'PageCount'=>0
            // 'SxResult' =>$SxResult

        );
        echo json_encode($json);
	}
public function setiframe(){
    $this->display("lottery/setiframe.html");
}
public function GetBalance(){
	    $postarr =$this->input->file_get();
	    header('Content-Type: application/json;charset=utf-8');
	    $type = 'liuhecai';
	    $fengpan_time = $this->lottery->_get_fengpan_time($type);
	    $kaijiang = $this->lottery->_get_result($type,1);//最近开奖结果
	   // p($kaijiang);
	    if($kaijiang[0]){
	        for ($i=0;$i<7;$i++){
	            $balls[$i]['number'] = $kaijiang[0]['ball_'.($i+1)];
	            $balls[$i]['sx'] = func_shenxiao($kaijiang[0]['ball_'.($i+1)]);
	            $balls[$i]['color'] =func_set_style($kaijiang[0]['ball_'.($i+1)]);
	        }
	    }
	    $money = $this->lottery->_get_userinfo($_SESSION['uid']);
	    $json= array('Success'=>1,
	                 'Msg'=>'',
	                 'Obj'=>array(
	                     "CurrentPeriod"=>$this->lottery->_dq_qishu($type),
	                     "OpenCount"=>$fengpan_time['k_t_stro'],
	                     "Balance"=>$money['money']?$money['money']:'0.00',
	                     "LotterNo"=>$kaijiang[0]['qishu'],
	                     'WinLoss'=>0,
	                     'PreResult'=>$balls,
                       'NotCountSum'=>$this->lottery->_beted_limit_1($type)?$this->lottery->_beted_limit_1($type):'0.00'
	                 ),
	                   "ExtendObj"=>null,"OK"=>false,"PageCount"=>0
	    );
	    echo json_encode($json);
}
	public function GetNoticeList(){
	    header('Content-Type: application/json;charset=utf-8');
	    $type = $this->input->get('type');
      $type_arr_n = array('liuhecai','fc_3d','pl_3','cq_ssc','jx_ssc','xj_ssc','tj_ssc','jl_k3','js_k3','cq_ten','gd_ten','bj_8','bj_10','xy_28');
      if(!in_array($type, $type_arr_n)){
        $type = 'cq_ssc';
      }
	    echo '{"Success":1,"Msg":"","Obj":[],"ExtendObj":null,"OK":false,"PageCount":0}';
	}
	public function syspars(){
	    header('Content-Type: application/json;charset=utf-8');
	    $type = $this->input->get('type');
      $type_arr_n = array('liuhecai','fc_3d','pl_3','cq_ssc','jx_ssc','xj_ssc','tj_ssc','jl_k3','js_k3','cq_ten','gd_ten','bj_8','bj_10','xy_28');
      if(!in_array($type, $type_arr_n)){
        $type = 'cq_ssc';
      }
	    echo '{"Success":1,"Msg":"","Obj":{"notice":[{"ID":49,"VenderName":"RYGameOpen","SiteNo":"RY@00-88","SiteNoList":",RY@00-88,","UserTypeList":"1,0,","TypeList":"1,","State":true,"IsTop":true,"CreateDateTime":"\/Date(1435243965000)\/","StartDateTime":"\/Date(1447599480000)\/","EndDateTime":"\/Date(1475316000000)\/","CreatorType":0,"CreatorUserName":"maya88","CreatorNickName":"maya88","EditorType":0,"EditorUserName":"maya88","EditorNickName":"maya88","EditDateTime":"\/Date(1451137306000)\/","Content_zh_cn":"尊敬的会员：您好，2015年香港六合彩第151期开奖时间为2015年12月29日星期二21：35分。公布所有赔率为浮动赔率，下注时请确认当前赔率及金额，下注确认后一概不能更改。开奖后的投注，将被视为【无效】。如遇到疑问，请及时与在线客服联系。谢谢！","Content_zh_tw":"尊敬的會員：您好，2015年香港六合彩第151期開獎時間為2015年12月29日星期二21：35分。公佈所有賠率為浮動賠率，下注時請確認當前賠率及金額，下注確認後一概不能更改。開獎後的投注，將被視為【無效】。如遇到疑問，請及時與在線客服聯繫。謝謝！","Content_en":null,"Content":"尊敬的会员：您好，2015年香港六合彩第151期开奖时间为2015年12月29日星期二21：35分。公布所有赔率为浮动赔率，下注时请确认当前赔率及金额，下注确认后一概不能更改。开奖后的投注，将被视为【无效】。如遇到疑问，请及时与在线客服联系。谢谢！","DBNullParameterList":null}],"lotteryIds":[4,6,3,7,2,5,9,8,10,1]},"ExtendObj":null,"OK":false,"PageCount":0}';
	}
}
