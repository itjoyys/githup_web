<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lottery extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('lottery/lottery_model');
		$_SESSION['pankou'] = "A";

        $guonian_2016 = '2016-02-08 00:00:00';
        if(func_nowtime('Y-m-d H:i:s','now') > $guonian_2016){
            $nianfen=2016;
        }else{
            $nianfen=2015;
        }
        $this->add('nianfen',$nianfen);

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

	public function index() {
	    $LotteryId = $this->input->get('LotteryId');
	    $mapf['table'] = 'fc_games_type';
        $mapf['select'] = 'wanfa,fc_type,id as gameid';
        $mapf['where']['wanfa'] = $LotteryId;
        $mapf['where']['state'] = 1;
        $mapf['order'] = 'id asc';
        $list = $this->lottery_model->get_table($mapf,3);

        foreach ($list as $k=>$v){
        	$arr[$k]['fc_type']= $v['wanfa'];
        	$arr[$k]['name']= $v['fc_type'];
        	$arr[$k]['gameid']= $v['gameid'];
        }
        $list = $arr;
        // p($list);exit;
        if($LotteryId == 'pl_3'){
        	foreach ($list as $k=>$v){
        		switch ($k){
        			case 3 : $array[4] =$v;break;
        			case 4 : $array[6] =$v;break;
        			case 6 : $array[3] =$v;break;
        			default:$array[$k]=$v;break;
        		}
        	}
        	ksort($array);
        	$list = $array;
        }
        // p($list);exit;
        $this->add('list', $list);
		$this->display('/lottery/'.$LotteryId.'/index.html');
	}

    //?gameid=278&LotteryId=gd_ten&gamename=%E8%BF%9E%E7%A0%81
    public function fc_games_type(){
        $gameid = $this->input->get('gameid');
        $LotteryId = $this->input->get('LotteryId');
        $gamename2 = $this->input->get('gamename2');
        $this->add('gameid',$gameid);
       if($LotteryId=='liuhecai'&&$gamename2==''){
	        $config = $this->config->item('games')[$gameid];
	        $this->add('config', $config);
	        switch ($gameid) {
	            case 225:
	             $return ='one';
	            break;
	            case 226:
	             $return ='one';
	            break;
	            default:
	               $return ='all';
	            break;
	        }
	        if($return=='all'){
	        	if($config[0]['name'] == '特码'){
	        		$this->display('/lottery/'.$LotteryId.'/list_tm.html');
	        	}else{
	        		$this->display('/lottery/'.$LotteryId.'/list.html');
	        	}
	        }elseif($return=='one'){
	            foreach ($config as $k1=>$v1){
	                $config[$k1]['data']= $this->lottery_model->get_odds($gameid,$v1['name']);
	            }
	            $this->add('config', $config);
	            $mapf['table'] = 'fc_games_type';
	            $mapf['select'] = 'fc_type';
	            $mapf['where']['id'] = $gameid;
	            $data = $this->lottery_model->get_table_one($mapf,3);
	            $gamename = $data['fc_type'];
	            $this->add('gamename', $gamename);
	            //p($gamename);exit;
	            $this->display('/lottery/'.$LotteryId.'/'.$gameid.'.html');
	        }
       }else if(($LotteryId=='gd_ten' && $gameid == 278) || ($LotteryId=='cq_ten' && $gameid == 280)){
            $config = $this->config->item('games')[$gameid];
            $this->add('config', $config);
            $this->display('/lottery/'.$LotteryId.'/list.html');
       }else{
	        $list = $this->lottery_model->get_odds($gameid,$gamename2);
	        $oddsall = array('202','203','204','205','206');
	        if (in_array($list[0]['type_id'], $oddsall)) {
	            $data = $list;
	            $list = $this->lottery_model->selected_one($list);
	            $this->add('data', $data);
	            $this->add('list', $list);
	            $this->display('/lottery/'.$LotteryId.'/'.$gameid.'.html');
	        }else{

	            if($list[0]['type_id'] == '210'){
	                $this->add('notball', 1);
	                foreach ($list as $a=>$b){
	                	if(is_numeric($b['input_name'])){
	                		$array[$a+4]=$b;
	                	}else{
	                		$array[$a-17]=$b;
	                	}
	                }
	                ksort($array);
	                $list = $array;
	            }
	            $this->add('list', $list);
	            $this->display('/lottery/'.$LotteryId.'/odds.html');
	       }
       }
    }


    public function lianma(){
        $gameid = $this->input->get('gameid');
        $LotteryId = $this->input->get('LotteryId');
        $gamename2 = $this->input->get('gamename2');

        $data  = $this->lottery_model->get_odds_lianma($gameid,$gamename2);
        //echo($gamename2);
        //print_r($data );
        $lists = array();
        for($i=1;$i<= 20 ;$i++){
            $lists[$i-1]['id'] = $data[0]['id'];
            $lists[$i-1]['input_name'] = $i;
            $lists[$i-1]['odds_value'] = $data[0]['odds_value'];
            $lists[$i-1]['name'] = $data[0]['type2'];
        }
        $this->add('data', $data);
        $this->add('list', $lists);
        $this->display('/lottery/'.$LotteryId.'/'.$gameid.'.html');

    }

    public function liuhecai(){
        $gameid = $this->input->get('gameid');
        $LotteryId = $this->input->get('LotteryId');
        $gamename2 = $this->input->get('gamename2');
        $pk = $this->input->get('pk');
        $this->add("pk", $pk);

        $list = $this->lottery_model->get_odds($gameid,$gamename2,$pk);

        $mapf['table'] = 'fc_games_type';
        $mapf['select'] = 'fc_type';
        $mapf['where']['id'] = $gameid;
        $data = $this->lottery_model->get_table_one($mapf,3);
        $gamename = $data['fc_type'];

        $this->add('gamename', $gamename);
         $oddsall = array('202','203','204','205','206','227');
            if (in_array($list[0]['type_id'], $oddsall)) {
	            if($list[0]['type_id'] == 227)$num = 49;
	            $data = $list;
	            $list = $this->lottery_model->selected_one($list,$num);
	            $this->add('data', $data);
	        }

	    if($list[0]['type_id'] == 222){
	    	$list['A'] = $this->lottery_model->oddspx($list,'A');
	    	$list['B'] = $this->lottery_model->oddspx($list,'B');
	    }
	    // p($list);exit;
        $this->add('list', $list);
        $this->add('gamename', $gamename);
        $this->display('/lottery/'.$LotteryId.'/'.$gameid.'.html');
    }
}
?>