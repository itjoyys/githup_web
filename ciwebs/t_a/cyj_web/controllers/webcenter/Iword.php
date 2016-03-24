<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iword extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('webcenter/Iword_model');
	}

    //文案信息
	public function index()
	{
        $type = $this->input->get('type');
        //$type = empty($type)?1:$type;

        $map['table'] = 'info_iword_use';
        $map['where']['type'] = $type;
        $map['where']['index_id'] = INDEX_ID;
        $map['where']['site_id'] = SITEID;
        $this->add('iword',$this->Iword_model->rfind($map));
        $this->display('web/iword.html');
	}

        //公告列表
    public function notice_data(){
        $map_m = array();
        $map_m['table'] = 'k_message';
        $map_m['where']['site_id'] = SITEID;
        $map_m['where']['index_id'] = INDEX_ID;
        $map_m['where']['is_delete'] = 0;
        $map_m['where']['show_type'] = 2;
        $map_m['limit'] = 10;
        $map_m['order'] = 'add_time DESC';
        $list = $this->Iword_model->rget($map_m);


        $map_s = array();
        $map_s['table'] = 'site_notice';
        $map_s['select'] = 'notice_cate,notice_date as add_time,notice_content as chn_simplified';
        $map_s['where']['sid'] = 0;
        $map_s['where']['notice_state'] = 1;
        $map_s['where_in']['notice_cate'] = '1,2,3,4,5';
        $notice = $this->Iword_model->rget($map_s);

        if(!empty($list) && !empty($notice)){
            $list = array_merge($list, $notice1);
        }
        if(empty($list)){
            $list = $notice;
        }
        foreach ($list as $key => $row)
        {
            $add_time[$key]  = $row['add_time'];
        }
        array_multisort($add_time,SORT_DESC,$list);
        $this->add("list",$list);
        $this->display("web/notice_data.html");
    }

}
