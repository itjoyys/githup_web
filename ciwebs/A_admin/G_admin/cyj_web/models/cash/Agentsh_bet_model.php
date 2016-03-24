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
        $mapFc['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
	        $mapFc['addtime'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }

        $db_model = array();
        $db_model['tab'] = 'c_bet';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("sum(money) as fc_bet,username")->where($mapFc)->group('uid')->select('u-username');

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
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
	        $mapSp['bet_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("sum(bet_money) as sp_bet,username")->where($mapSp)->group('uid')->select('u-username');
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
	        $mapSp['bet_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet_cg_group';
		$db_model['type'] = 1;

        return $this->M($db_model)->field("sum(bet_money) as sp_cg_bet,username")->where($mapSp)->group('uid')->select('u-username');
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

        //AG视讯
	    if (FALSE !== strpos($type, 'ag')) {
	    	$map['bet_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'ag_bet_record'.$tab_data;
		    $ARR = $this->M($db_model)->field("pkusername,sum(valid_betamount) as ag_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    unset($map['bet_time']);
	    }

	    //BBIN视讯
	    if (FALSE !== strpos($type, 'bbin')) {
	    	$map['wagers_date'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );

	    	$map['gamekind'] = array('in','(1,3,12,15)');
		    $map['payoff'] = array('<>','0.0000');
		    $db_model['tab'] = 'bbin_bet_record'.$tab_data;
		    $bbin_arr= $this->M($db_model)->field("pkusername,sum(betamount) as bbin_bet")->where($map)->group("pkusername")->select('u-pkusername');
            //电子
		    $map['gamekind'] = 5;
		    $bbdz_arr= $this->M($db_model)->field("pkusername,sum(betamount) as bbdz_bet")->where($map)->group("pkusername")->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$bbin_arr);
		    $ARR = $this->video_array($ARR,$bbdz_arr);
		    unset($map['payoff']);
		    unset($bbin_arr);
		    unset($bbdz_arr);
		    unset($map['gamekind']);
		    unset($map['wagers_date']);
	    }

	    //CT视讯
	    if (FALSE !== strpos($type, 'ct')) {
	    	$map['transaction_date_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'ct_bet_record'.$tab_data;
		    $ct_arr = $this->M($db_model)->field("pkusername,sum(availablebet) as ct_bet")->where($map)->group("pkusername")->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$ct_arr);
		    unset($ct_arr);
		    unset($map['transaction_date_time']);
	    }

	    //LEBO视讯
	    if (FALSE !== strpos($type, 'lebo')) {
	    	$map['betstart_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'lebo_bet_record'.$tab_data;
		    $lebo_arr = $this->M($db_model)->field("pkusername,sum(valid_betamount) as lebo_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$lebo_arr);
		    unset($lebo_arr);
		    unset($map['betstart_time']);
	    }

	    //MG
	    if (FALSE !== strpos($type, 'mg')) {
	    	$map['date'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
	    	$map['module_id'] =  array('in','(28,29,30,32)');
		    $db_model['tab'] = 'mg_bet_record'.$tab_data;
		    $mg_arr = $this->M($db_model)->field("pkusername,sum(income) as mg_bet")->where($map)->group('pkusername')->select('u-pkusername');
            $ARR = $this->video_array($ARR,$mg_arr);
            unset($map['module_id']);

		    $map['module_id'] =  array('<','28');
		    $mgdz_arr = $this->M($db_model)->field("pkusername,sum(income) as mgdz_bet")->where($map)->group('pkusername')->select('u-pkusername');

		    $ARR = $this->video_array($ARR,$mgdz_arr);
		    unset($map['module_id']);
		    unset($mg_arr);
		    unset($mgdz_arr);
		    unset($map['date']);
	    }

	    //OG视讯
	    if (FALSE !== strpos($type, 'og')) {
	    	$map['add_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'og_bet_record'.$tab_data;
		    $og_arr =$this->M($db_model)->field("pkusername,sum(valid_amount) as og_bet")->where($map)->group("pkusername")->select('u-pkusername');
		    $ARR = $this->video_array($ARR,$og_arr);
		    unset($og_arr);
		    unset($map['add_time']);
	    }
	    return $ARR;
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