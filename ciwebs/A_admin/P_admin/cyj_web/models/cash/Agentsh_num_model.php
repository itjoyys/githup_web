<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Agentsh_num_model extends MY_Model {
    //股东旗下有效会员
	function __construct() {
		parent::__construct();
	}

	//股东所有打码
   	public  function agentsh_num_all($pid,$date=array(),$type,$index_id){
   		//股东旗下所有代理
   		$agents = $this->get_agentsid($pid,$index_id);

   		$bet = array();
   	    $fc_num = $this->agentsh_fc_num($agents,$date);//彩票
   	 	$sp_num = $this->agentsh_sp_num($agents,$date);//体育
   	 	$spcg_num = $this->agentsh_spcg_num($agents,$date);//体育串关
   	  	$ARR = $this->agentsh_video_num($date,$type);//视讯
        if (!empty($fc_num)) {
            $ARR = $this->arr_mer($fc_num,$ARR);
        }

        if (!empty($sp_num)) {
            $ARR = $this->arr_mer($sp_num,$ARR);
        }

        if (!empty($spcg_num)) {
            $ARR = $this->arr_mer($spcg_num,$ARR);
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
   	public  function agentsh_fc_num($agents,$date=array(),$bet_type = 1){
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
	        $mapFc['update_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }

        $db_model = array();
        $db_model['tab'] = 'c_bet';
		$db_model['type'] = 1;

        $fc_arr = $this->M($db_model)->field("username,agent_id")->where($mapFc)->group('agent_id')->select('u-agent_id');
        return $this->arr_num($fc_arr,'username');

    }

	 //股东体育下注
   	public  function agentsh_sp_num($agents,$date=array(),$bet_type = 1){
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
	        $mapSp['update_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet';
		$db_model['type'] = 1;

        $sp_arr = $this->M($db_model)->field("agent_id,username")->where($mapSp)->group('agent_id')->select('u-agent_id');
        return $this->arr_num($sp_arr,'username');
    }
    //体育串关
    public  function agentsh_spcg_num($agents,$date=array(),$bet_type = 1){
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

        $sp_cg_arr = $this->M($db_model)->field("agent_id,username")->where($mapSp)->group('agent_id')->select('agent_id');
        return $this->arr_num($sp_cg_arr,'username');
    }

    //视讯打码 type视讯类别 agents代理 bet_type 1打码 2数量
   	public  function agentsh_video_num($date=array(),$type,$agents,$bet_type = 1){
        $map = array();
	    $map['update_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
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
		    $db_model['tab'] = 'ag_bet_record';
		    $ARR = $this->M($db_model)->field("agent_id,player_name")->where($map)->group("agent_id")->select('u-agent_id');

            $ARR = $this->arr_num($ARR,'player_name');
	    }

	    //BBIN视讯
	    if (FALSE !== strpos($type, 'bbin')) {
		    $map['payoff'] = array('<>','0.0000');
		    $db_model['tab'] = 'bbin_bet_record';
		    $bbin_arr= $this->M($db_model)->field("agent_id,username")->where($map)->group("agent_id")->select('u-agent_id');
            $bbin_arr = $this->arr_num($bbin_arr,'username');
		    $ARR = $this->video_array($ARR,$bbin_arr);
		    unset($map['payoff']);
		    unset($bbin_arr);
	    }


	    //CT视讯
	    if (FALSE !== strpos($type, 'ct')) {
		    $db_model['tab'] = 'ct_bet_record';
		    $ct_arr = $this->M($db_model)->field("agent_id,member_id")->where($map)->group("agent_id")->select('u-agent_id');
		    $ct_arr = $this->arr_num($ct_arr,'member_id');

		    // if (!empty($ARR) && !empty($ct_arr)) {
		    //     foreach($ct_arr as $tk => $v){
		    //       $kname[] = str_replace('@','',$tk);
		    //     }
		    //     $ct_arr = array_combine($kname,array_slice($ct_arr,0));
		    //     $ARR = array_merge_recursive($ARR,$ct_arr);
		    // }elseif (empty($ARR) && !empty($ct_arr)) {
		    //     foreach($ct_arr as $tk => $v){
		    //       $kname[] = str_replace('@','',$tk);
		    //     }
		    //     $ct_arr = array_combine($kname,array_slice($ct_arr,0));
		    //     $ARR = $ct_arr;
		    // }
		    unset($ct_arr);
	    }


	    //LEBO视讯
	    if (FALSE !== strpos($type, 'lebo')) {
		    $db_model['tab'] = 'lebo_bet_record';
		    $lebo_arr = $this->M($db_model)->field("agent_id,member")->where($map)->group("agent_id")->select('u-agent_id');
		    $lebo_arr = $this->arr_num($lebo_arr,'member');
		    $ARR = $this->video_array($ARR,$lebo_arr);
		    unset($lebo_arr);
	    }


	    //MG
	    if (FALSE !== strpos($type, 'mg')) {
		    $db_model['tab'] = 'mg_bet_record';
		    $mg_arr = $this->M($db_model)->field("agent_id,account_number")->where($map)->group('agent_id')->select('u-agent_id');
		    $mg_arr = $this->arr_num($mg_arr,'account_number');
		    $ARR = $this->video_array($ARR,$mg_arr);
		    unset($mg_arr);
	    }

	    //OG视讯
	    if (FALSE !== strpos($type, 'og')) {
		    $db_model['tab'] = 'og_bet_record';
		    $og_arr =$this->M($db_model)->field("agent_id,user_name")->where($map)->group("agent_id")->select('u-agent_id');
		    $og_arr = $this->arr_num($og_arr,'user_name');
		    $ARR = $this->video_array($ARR,$og_arr);
		    unset($og_arr);
	    }

	    //pt电子
	    if (FALSE !== strpos($type, 'pt')) {
	    	$db_model['tab'] = 'pt_bet_record';
	    	$map['Bet + Win'] = array('>',0);
		    $pt_arr =$this->M($db_model)->field("agent_id,PlayerName")->where($map)->group("agent_id")->select('u-agent_id');
		    $pt_arr = $this->arr_num($pt_arr,'PlayerName');
		    $ARR = $this->video_array($ARR,$pt_arr);
		    unset($pt_arr);

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
    //数据处理
   	public function arr_num($sarr,$field){
	    foreach ($sarr as $key => &$val) {
            unset($val['agent_id']);
            $val[$val[$field]] = 1;
            unset($val[$field]);
        }
        unset($val);
        return $sarr;
   	}

}