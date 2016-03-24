<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sp_result extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('result/Sp_result_model');
	}
    //足球
	public function football(){
	    $date = $this->input->get('date');
	    $date = empty($date)?date('Y-m-d'):$date;
	    $date = substr($date, 5);
        $map['match_js'] = 1;
	    $map['match_date'] = array('like','%'.$date.'%');
	    $data = $this->Sp_result_model->get_football($map);
	    $this->add('date',date('Y').'-'.$date);
	    $this->add('data',$data);
        $this->display('result/football.html');
	}

	//篮球
	public function basketball(){
		$date = $this->input->get('date');
		$date = empty($date)?date('Y-m-d'):$date;
		$date = substr($date, 5);
		$map['match_js'] = 1;
		$map['match_date'] = array('like','%'.$date.'%');
		$data = $this->Sp_result_model->get_basketball($map);

        $this->add('date',date('Y').'-'.$date);
        $this->add('data',$data);
        $this->display('result/basketball.html');
	}
    //网球
	public function tennis(){
        $date = $this->input->get('date');
		$date = empty($date)?date('Y-m-d'):$date;
		$date = substr($date, 5);
    $map['match_js'] = 1;
		$map['match_date'] = array('like','%'.$date.'%');
		$data = $this->Sp_result_model->get_tennis($map);

		$this->add('date',date('Y').'-'.$date);
		$this->add('data',$data);
        $this->display('result/tennis.html');
	}
      //排球
	public function volleyball(){
        $date = $this->input->get('date');
	    $date = empty($date)?date('Y-m-d'):$date;
	    $date = substr($date, 5);
        $map['match_js'] = 1;
	    $map['Match_Date'] = array('like','%'.$date.'%');
	    $data = $this->Sp_result_model->get_volleyball($map);
	    $this->add("result",$data);
	    $this->add("date",date('Y').'-'.$date);
        $this->display('result/volleyball.html');
	}

    //棒球
	public function basebal(){
      $date = $this->input->get('date');
	    $date = empty($date)?date('Y-m-d'):$date;
	    $date = substr($date, 5);
      $map['match_js'] = 1;
	    $map['match_date'] = array('like','%'.$date.'%');

	    $data = $this->Sp_result_model->get_baseball($map);
	    $this->add("date",date('Y').'-'.$date);
	    $this->add("result",$data);
        $this->display('result/basebal.html');
	}

	//冠军
	public function champion(){
      $date = $this->input->get('date');
	    $date = empty($date)?date('Y-m-d'):$date;
	    $date = substr($date, 5);
      $map['t_guanjun.match_type'] = 1;
	    $map['match_date'] = array('like','%'.$date.'%');

	    $data = $this->Sp_result_model->get_champion($map);
	    $this->add("date",date('Y').'-'.$date);
	    $this->add("result",$data);
      $this->display('result/champion.html');
	}
}
