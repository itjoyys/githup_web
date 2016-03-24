<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//我要推广
class Spread extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/Spread_model');
		$this->Spread_model->login_check($_SESSION['uid']);
		if($_SESSION['shiwan'])
		{
		   message("试玩账号不能使用此功能,请用正式账号！");
		}
	}
    //推广主页
    public function spread(){
        //推广链接
        $surl = $_SERVER['HTTP_HOST'].'/?uuno='.$_SESSION['uid'];
        //获取推广数据
        $sdata = $this->Spread_model->get_spread_data();

        //读取会员返利比例
        $title = array('fc_dis'=>'彩票','sp_dis'=>'体育','ag_dis'=>'AG视讯',
        	           'og_dis'=>'OG视讯','mg_dis'=>'MG视讯',
        	           'mgdz_dis'=>'MG电子','ct_dis'=>'CT视讯',
        	           'pt_dis'=>'PT电子','lebo_dis'=>'LEBO视讯',
        	           'bbin_dis'=>'BB视讯','bbdz_dis'=>'BB电子',
        	           'bbsp_dis'=>'BB体育','bbfc_dis'=>'BB彩票',
        	           'eg_dis'=>'EG电子');

        $vconfig = $this->Spread_model->video_config();
        if ($vconfig) {
        	$video_types = array('fc_dis','sp_dis');
            foreach ($vconfig as $k => $v) {
	            $video_types[] = $v.'_dis';
	            if ($v == 'mg') {
	                $video_types[] = 'mgdz_dis';
	            }elseif($v == 'bbin'){
	                $video_types[] = 'bbdz_dis';
	                $video_types[] = 'bbsp_dis';
	                $video_types[] = 'bbfc_dis';
	            }
	        }
        }

        //获取返利比例
        $disdata = $this->Spread_model->get_spread_dis();


        $this->add('surl',$surl);
        $this->add('sdata',$sdata);
        $this->add('title',$title);
        $this->add('disdata',$disdata);
        $this->add('video_types',$video_types);
        $this->display('member/spread_index.html');
    }

    //推广
    public function spread_top(){
       //获取人数排行榜
       $spread_num = $this->Spread_model->get_spread_top('1');
       //获取获利排行榜
       $spread_money = $this->Spread_model->get_spread_top('2');

       foreach ($spread_num as $key => $val) {
       	   $spread_num[$key]['order'] = $key + 1;
           $spread_num[$key]['username'] = substr_replace($val['username'],'******',-3,3);
           $spread_num[$key]['usernamem'] = substr_replace($spread_money[$key]['username'],'******',-3,3);
           $spread_num[$key]['spread_moneym'] = $spread_money[$key]['spread_money'];
       }
       $this->add('spread_num',$spread_num);
      // $this->add('spread_money',$spread_money);
       $this->display('member/spread_top.html');
    }
}
