<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Agentsh_bet_model extends MY_Model {
    //股东旗下所有打码
	function __construct() {
		parent::__construct();
	}

	//股东所有打码
   	public  function agentsh_bet_all($pid,$date=array(),$type,$index_id){
   		//股东旗下所有代理
   		$agents = $this->get_agentsid($pid,$index_id);

   		$bet = array();
   	    $fc_bet = $this->agentsh_fc_bet($agents,$date);//彩票
   	 	$sp_bet = $this->agentsh_sp_bet($agents,$date);//体育
   	 	$spcg_bet = $this->agentsh_spcg_bet($agents,$date);//体育串关
   	  	$ARR = $this->agentsh_video_bet($date,$type);//视讯
        if (!empty($fc_bet)) {
            $ARR = $this->video_array($fc_bet,$ARR);
        }

        if (!empty($sp_bet)) {
            $ARR = $this->video_array($sp_bet,$ARR);
        }

        if (!empty($spcg_bet)) {
            $ARR = $this->video_array($spcg_bet,$ARR);
        }
        return $ARR;
    }

    //数据拼接
    public function arr_mer($arr,$s_arr){
	    if (!empty($arr)) {
	        $tmp_arr = array();
	        foreach ($arr as $fk => $fv) {
	            $fk = $_SESSION['site_id'].$fk;
	            $tmp_arr[$fk] = $fv;
	        }
	        if (!empty($s_arr)) {
	            $s_arr = array_merge_recursive($s_arr,$tmp_arr);
	        }else{
	            $s_arr = $tmp_arr;
	        }
	    }
	    return $s_arr;
    }
        //股东彩票所有打码
   	public  function agentsh_fc_bet($agents,$date=array(),$bet_type = 1){
    	$mapFc = array();
    	if (!empty($agents)) {
	    	if (strstr($agents,',')) {
	    	    $mapFc['agent_id'] = array('in','('.$agents.')');
	    	}else{
	    		$mapFc['agent_id'] = $agents;
	    	}
        }
        $mapFc['status'] = array('in','(1,2)');
        $mapFc['mingxi_1'] = array('<>','特码');//六合彩特码时时返水
        $mapFc['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
	        $mapFc['update_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
	        //启用自动分表
	        $beginDate = $date[0].' 00:00:00';
	        $beginDate = date("Y-m-d H:i:s", strtotime("$beginDate -48 hours"));
	        $mapFc['addtime'] = array(
		                        array('>=',$beginDate),
		                        array('<=',$date[1].' 23:59:59')
		                        );
        }

        $db_model = array();
        $db_model['tab'] = 'c_bet';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("/* parallel */ sum(money) as fc_bet,username")->where($mapFc)->group('uid')->select('u-username');

    }
	 //股东体育下注
   	public  function agentsh_sp_bet($agents,$date=array(),$bet_type = 1){
    	$mapSp = array();
        if (!empty($agents)) {
	    	if (strstr($agents,',')) {
	    	    $mapSp['agent_id'] = array('in','('.$agents.')');
	    	}else{
	    		$mapSp['agent_id'] = $agents;
	    	}
        }
        $mapSp['status'] = array('in','(1,2,4,5)');
        //$mapSp['is_jiesuan'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
	        $mapSp['update_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("/* parallel */ sum(bet_money) as sp_bet,username")->where($mapSp)->group('uid')->select('u-username');
    }
    //体育串关
    public  function agentsh_spcg_bet($agents,$date=array(),$bet_type = 1){
    	$mapSp = array();
    	if (!empty($agents)) {
	    	if (strstr($agents,',')) {
	    	    $mapSp['agent_id'] = array('in','('.$agents.')');
	    	}else{
	    		$mapSp['agent_id'] = $agents;
	    	}
	    }

        $mapSp['status'] = array('in','(1,2)');
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
	        $mapSp['update_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet_cg_group';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("/* parallel */ sum(bet_money) as sp_cg_bet,username")->where($mapSp)->group('uid')->select('u-username');
    }

    //视讯打码 type视讯类别 agents代理 bet_type 1打码 2数量
   	public  function agentsh_video_bet($date=array(),$type,$agents,$bet_type = 1){
        $map = array();
        //表后缀
       // $tab_data = '_'.date('Ym',strtotime($date[0]));
        $tab_data = '';
	    $map['site_id'] = $_SESSION['site_id'];
	    if (!empty($agents)) {
		    if (strstr($agents,',')) {
	    	    $map['agent_id'] = array('in','('.$agents.')');
	    	}else{
	    		$map['agent_id'] = $agents;
	    	}
        }
	    $ARR = array();
	    $db_model = array();
	    $db_model['type'] = 3;
        if (empty($type)) {
            $type = $this->get_videos();//获取视讯配置
        }

        //AG视讯
	    if (in_array('ag',$type)) {
	    	$map['bet_time'] = array(
	                            array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
	        $map['flag'] = 1;
		    $db_model['tab'] = 'ag_bet_record'.$tab_data;
		    $ARR = $this->M($db_model)->field("/* parallel */ pkusername,sum(valid_betamount) as ag_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    unset($map['bet_time']);
		    unset($map['flag']);
	    }

	    //BBIN视讯
	    if (in_array('bbin',$type)) {
	    	$map['wagers_date'] = array(
	                            array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
	    	$map['gamekind'] = array('not in','(1,5,12,15)');
	    	// $map['result_type'] = array('<>','-1');
      //       $map['result'] = array('not in',"('D','-1')");
            $map['result'] = array('>',0);
            $map['Commissionable'] = array('>',0);//会员有效投注额

		    $db_model['tab'] = 'bbin_bet_record'.$tab_data;
		    $bbin_arr= $this->M($db_model)->field("/* parallel */ pkusername,sum(betamount) as bbin_bet")->where($map)->group("pkusername")->select('u-pkusername');
            //电子
		    $map['gamekind'] = array('in','(5,15)');
		    $map['result'] = array('in',"(1,200)");
		    $bbdz_arr= $this->M($db_model)->field("/* parallel */ pkusername,sum(betamount) as bbdz_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    //BB体育
		    $map['result'] = array('in',"('L','W','LL','LW')");
		    $map['gamekind'] = 1;
		    $bbsp_arr= $this->M($db_model)->field("/* parallel */ pkusername,sum(betamount) as bbsp_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    //BB彩票
		    $map['result'] = array('in',"('L','W')");
		    $map['gamekind'] = 12;
		    $bbfc_arr= $this->M($db_model)->field("/* parallel */ pkusername,sum(betamount) as bbfc_bet")->where($map)->group("pkusername")->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$bbin_arr);
		    $ARR = $this->video_array($ARR,$bbdz_arr);
		    $ARR = $this->video_array($ARR,$bbsp_arr);
		    $ARR = $this->video_array($ARR,$bbfc_arr);

		    unset($bbin_arr);
		    unset($bbdz_arr);
		    unset($bbfc_arr);
		    unset($bbsp_arr);
		    unset($map['gamekind']);
		    unset($map['result_type']);
		    unset($map['wagers_date']);
		    unset($map['result']);
		    unset($map['Commissionable']);
	    }

	    //CT视讯
	    if (in_array('ct',$type)) {
	    	$map['transaction_date_time'] = array(
	                             array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
		    $db_model['tab'] = 'ct_bet_record'.$tab_data;
		    $ct_arr = $this->M($db_model)->field("/* parallel */ pkusername,sum(availablebet) as ct_bet")->where($map)->group("pkusername")->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$ct_arr);
		    unset($ct_arr);
		    unset($map['transaction_date_time']);
	    }

	    //LEBO视讯
	    if (in_array('lebo',$type)) {
	    	$map['betstart_time'] = array(
	                             array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
		    $db_model['tab'] = 'lebo_bet_record';
		    $lebo_arr = $this->M($db_model)->field("/* parallel */ pkusername,sum(valid_betamount) as lebo_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$lebo_arr);
		    unset($lebo_arr);
		    unset($map['betstart_time']);
	    }

	    //BJ电子
	    if (in_array('eg',$type)) {
	    	$map['betstart_time'] = array(
	                             array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
		    $db_model['tab'] = 'eg_bet_record';
		    $eg_arr = $this->M($db_model)->field("/* parallel */ pkusername,sum(valid_betamount) as eg_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$eg_arr);
		    unset($eg_arr);
		    unset($map['betstart_time']);
	    }

	    //MG
	    if (in_array('mg',$type)) {
	    	$map['date'] = array(
	                            array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
	    	//$map['module_id'] =  array('in','(28,29,30,32)');
	    	$map['module_id'] =  array('in','(25,28,29,30,32)');
		    $db_model['tab'] = 'mg_bet_record'.$tab_data;
		    $mg_arr = $this->M($db_model)->field("/* parallel */ pkusername,sum(income) as mg_bet")->where($map)->group('pkusername')->select('u-pkusername');
            $ARR = $this->video_array($ARR,$mg_arr);
            unset($map['module_id']);

		    $map['module_id'] =  array('not in','(25,28,29,30,32)');
		    $mgdz_arr = $this->M($db_model)->field("/* parallel */ pkusername,sum(income) as mgdz_bet")->where($map)->group('pkusername')->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$mgdz_arr);
		    unset($map['module_id']);
		    unset($mg_arr);
		    unset($mgdz_arr);
		    unset($map['date']);
	    }

	    //OG视讯
	    if (in_array('og',$type)) {
	    	$map['add_time'] = array(
	                            array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
		    $db_model['tab'] = 'og_bet_record'.$tab_data;
		    $og_arr =$this->M($db_model)->field("/* parallel */ pkusername,sum(valid_amount) as og_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$og_arr);
		    unset($og_arr);
		    unset($map['add_time']);
	    }

	     //PT电子
	    if (in_array('pt',$type)) {
	    	$map['GameDate'] = array(
	                            array('>=',$date[0].' 00:00:00'),
	                            array('<=',$date[1].' 23:59:59')
	                          );
	    	$map['Bet + Win'] = array('>',0);
		    $db_model['tab'] = 'pt_bet_record'.$tab_data;
		    $pt_arr =$this->M($db_model)->field("/* parallel */ pkusername,sum(Bet) as pt_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$pt_arr);
		    unset($pt_arr);
		    unset($map['GameDate']);
	    }


	    return $ARR;
   	}

   	   //获取视讯配置
    public function get_videos(){
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $video_module = $this->M(array('tab'=>'web_config','type'=>1))->where($map)->getField("video_module");
        //return $video_module;
        if ($video_module) {
            return explode(',',$video_module);
        }
    }

   	//数组
   	public function video_array($arr = array(),$bet = array()){
   	    if (!empty($arr) && !empty($bet)) {
	        $arr = array_merge_recursive($arr,$bet);
	    }elseif (empty($arr) && !empty($bet)) {
	        $arr = $bet;
	    }
	    return $arr;
   	}

   	//获取股东旗下所有代理
   	public function get_agentsid($pid,$index_id){
   		$map = array();
   		$map['pid'] = $pid;
   		$map['site_id'] = $_SESSION['site_id'];
   		$map['is_demo'] = 0;
   		if (!empty($index_id)) {
   		    $map['index_id'] = $index_id;
   		}

   		$sh_ids = $this->M(array('tab'=>'k_user_agent','type'=>1))->field("id")->where($map)->select("id");
	    if (empty($sh_ids)) {
	        return false;
	    }
	    $sh_ids = implode(',',array_keys($sh_ids));
        //获取代理商
	    $map_sh = array();
	    $map_sh['pid'] = array('in','('.$sh_ids.')');
	    $map_sh['site_id'] = $_SESSION['site_id'];
	    if (!empty($index_id)) {
	        $map_sh['index_id'] = $index_id;
	    }
	    $agents = array();
	    $agents = $this->M(array('tab'=>'k_user_agent','type'=>1))->field("group_concat(id) as agents")->where($map_sh)->find();

	    if (!empty($agents)) {
	        return $agents['agents'];
	    }
   	}

}