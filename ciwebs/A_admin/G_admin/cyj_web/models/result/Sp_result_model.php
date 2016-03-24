<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Sp_result_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//足球
	public function get_football($map){
		$db_model['tab'] = 'bet_match';
		$db_model['type'] = 2;
        return $this->M($db_model)->field('ID,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Name,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,match_sbjs,Match_Time,Match_CoverDate')->where($map)->order("Match_CoverDate,Match_Name,Match_Master,iPage,iSn desc")->select();
	}

	public function get_basketball($map){
		$db_model['tab'] = 'lq_match';
		$db_model['type'] = 2;
        return $this->M($db_model)->field('ID,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_MasterID,Match_GuestID,Match_Name,MB_Inball_1st,TG_Inball_1st,MB_Inball_2st,TG_Inball_2st,MB_Inball_3st,TG_Inball_3st,MB_Inball_4st,TG_Inball_4st,MB_Inball_HR,	TG_Inball_HR,MB_Inball_ER,TG_Inball_ER,MB_Inball,TG_Inball,MB_Inball_Add,TG_Inball_Add ,MB_Inball_OK,TG_Inball_OK,match_js')->where($map)->order("Match_CoverDate,match_name,Match_Master,iPage,iSn desc")->select();
	}

	public function get_tennis($map){
		$db_model['tab'] = 'tennis_match';
		$db_model['type'] = 2;
        return $this->M($db_model)->field('ID,Match_ID,Match_Time,Match_Master,Match_Guest,Match_Name,MB_Inball,TG_Inball')->where($map)->order("Match_CoverDate,match_name,Match_Master desc")->select();
	}

		//排球
	public function get_volleyball($map){
		$db_model['tab'] = 'volleyball_match';
		$db_model['type'] = 2;
        return $this->M($db_model)->field('ID,Match_ID,Match_Date,Match_Time,Match_Master,Match_Guest,Match_Name,MB_Inball,TG_Inball')->where($map)->order("Match_CoverDate,match_name,Match_Master desc")->select();
	}

	//棒球
	public function get_baseball($map){
		$db_model['tab'] = 'baseball_match';
		$db_model['type'] = 2;
        return $this->M($db_model)->field('ID,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Name,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR')->where($map)->order("Match_CoverDate,match_name,Match_Master desc")->select();
	}

	//冠军
	public function get_champion($map){
		$db_model['tab'] = 't_guanjun';
		$db_model['type'] = 2;
        return $this->M($db_model)->field("t_guanjun.*,t_guanjun_team.xid")->join("left join t_guanjun_team on t_guanjun.x_id=t_guanjun_team.xid")->where($map)->order("t_guanjun.match_coverdate desc,t_guanjun.x_id desc")->select();

	}

}