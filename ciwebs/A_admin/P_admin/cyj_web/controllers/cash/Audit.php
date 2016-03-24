<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Audit_model');
	}

	public function index()
	{
		$username = $this->input->get('username');
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d H:i:s'):$end_date;
		

		if (!empty($username)) {
		    $map = array();
		    $map['table'] = 'k_user';
		    $map['select'] = 'uid,username,level_id';
		    $map['where']['username'] = $username;
		    $map['where']['site_id'] = $_SESSION['site_id'];

		    $user = $this->Audit_model->rfind($map);
		    if (empty($user)) {
		         showmessage('会员账号不存在','../audit/index',0);
		    }else{

		        $type = 'ag,og,mg,ct.bbin,lebo';
		        $date = array('2015-10-01 00:00:00','2015-12-23 23:59:59');

		        $adata = $this->Audit_model->get_user_audit($user['uid'],$user['level_id'],$user['username'],$type,$end_date);
		        // p($adata);die();
		        $audit_data = $this->admin_show($adata);

		        $total = $audit_data['count_dis'] + $audit_data['count_xz'] + $audit_data['out_fee'];
                $total = sprintf("%.2f", $total);
                $this->add('total',$total);
                $this->add('audit_data',$audit_data);
                $this->add('now_date',date('Y-m-d H:i:s'));
		    }
		    $this->add('username',$username);
		}

		$this->display('cash/audit_index.html');
	}

	//稽核样式输出
    public function admin_show($allData){
        $arr = array();
        if (!empty($allData)) {
          //数据非空
            $arr['betAll'] = $allData['bet_all'];
            $arr['count_dis'] = $allData['count_dis'];
            $arr['count_xz'] = $allData['count_xz'];
            $arr['out_fee'] = $allData['out_fee'];
            unset($allData['bet_all']);
            unset($allData['count_dis']);
            unset($allData['count_xz']);
            unset($allData['out_fee']);
            $arr['content'] = $this->header_html().$this->main_html($allData);
        }
        return $arr;
    }

        //样式头部
    public function header_html(){
    	return '<table width="99%" class="m_tab"><tbody><tr class="m_title"><td class="TdB" rowspan="2" width="150">存款日期區間</td><td class="TdB" rowspan="2" width="55">存款金额</td><td class="TdB" rowspan="2" width="55">存款优惠</td><td class="TdB hide1" width="230" colspan="3">實際有效投注額</td><td class="TdR hide2" width="470" colspan="8">優惠稽核</td><td class="TdG" width="340" colspan="5">常態稽核</td></tr><tr class="m_title"><td class="TdB hide1" width="45">體育</td><td class="TdB hide1" width="45">彩票</td><td class="TdB hide1" width="45">視訊</td><td class="TdR hide2" width="70">體育打碼</td><td class="TdR hide2" width="35">通過</td><td class="TdR hide2" width="70">彩票打碼</td><td class="TdR hide2" width="35">通過</td><td class="TdR hide2" width="70">視訊打碼</td><td class="TdR hide2" width="35">通過</td><td class="TdR hide2" width="80">綜合打碼</td><td class="TdR hide2" width="70">是否達到</td><td class="TdG" width="70">常態打碼</td><td class="TdG" width="60">放寬額度</td><td class="TdG" width="45">通过</td><td class="TdG" width="90">需扣除行政費用</td><td class="TdG" width="70">需扣除金額</td></tr>';
    }

    //内容样式
    public function main_html($arr){
       if (!empty($arr)) {
       	  $main_html = '';
       	  foreach ($arr as $k => $v) {
       	     $main_html .= '<tr class="m_cen"><td style="width:160px;">起始:'.$v['begin_date'].'</td>
	    <td rowspan="2">'.$v['deposit_money'].'</td>
	    <td rowspan="2">'.($v['atm_give']+$v['catm_give']).'</td>
	    <td class="hide1" rowspan="2">'.$v['cathectic_sport'].'</td>
	    <td class="hide2" rowspan="2">'.($v['cathectic_fc']+0).'</td>
	    <td class="hide2" rowspan="2">'.($v['cathectic_video']+0).'</td>
	    <td class="hide2" rowspan="2">'.$v['cathectic_sport'].'</td>
	    <td class="hide2" rowspan="2">-</td>
	    <td class="hide2" rowspan="2">'.($v['cathectic_fc']+0).'</td>
	    <td class="hide2" rowspan="2">-</td>
	    <td class="hide2" rowspan="2">'.($v['cathectic_video']+0).'</td>
	    <td class="hide2" rowspan="2">-</td>
	    <td class="hide2" rowspan="2">'.$v['type_code_all'].'</td>
	    <td class="hide2" rowspan="2">'.$this->zh_state($v['is_pass_zh']).'</td>
	    <td class="hide2" rowspan="2">'.$v['normalcy_code'].'</td>
	    <td class="hide2" rowspan="2">'.$v['relax_limit'].'</td>
	    <td class="hide2" rowspan="2">'.$this->ct_state($v['is_pass_ct']).'</td>
	    <td class="hide2" rowspan="2">'.$this->xz_state($v['is_expenese_num']).'</td>
	    <td class="hide2" rowspan="2">'.sprintf("%.2f",$v['deduction_e']).'<br>'.sprintf("%.2f", $v['de_wind']).'</td></tr><tr class="m_cen"><td>結束:'.$v['end_date'].'</td></tr>';
       	  }
       	  return $main_html.'</tbody></table>';
       }
    }

     //综合稽核状态返回
    public function zh_state($st){
    	switch ($st) {
    		case '0':
    			return "<font color=\"#ff0000\">否</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">是</font>";
    			break;
    		case '2':
    			return "不需要稽核";
    			break;
    	}
    }

    //扣除行政费用状态
    public function xz_state($xz){
    	switch ($xz) {
    		case '0':
    			return "<font color=\"#ff0000\">否</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">是</font>";
    			break;
    		case '2':
    			return "不需要稽核";
    			break;
    	}
    }

    //常态稽核状态返回
    public function ct_state($ct){
    	switch ($ct) {
    		case '0':
    			return "<font color=\"#ff0000\">未通過</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">通過</font>";
    			break;
    		case '2':
    			return "-";
    			break;
    	}
    }



	//稽核日志
	public function audit_log(){

		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$index_id = $this->input->get('index_id');//站点切换
		$username = $this->input->get('username');
		$page = $this->input->get('page');

		$map['site_id'] = $_SESSION['site_id'];
		$map['type'] = 0;
		if (!empty($username)) {
		    $str_user = '%'.$username.'%';
		    $map['username'] = array('like',$str_user);
		}
		if (!empty($index_id)) {
		    $map['index_id'] = $index_id;
		}
		//时间检索
		if(empty($start_date) || empty($end_date)){
		    $start_date = $end_date = date('Y-m-d');
		}

		$map['update_date'] = array(
		                          array('>=',$start_date.' 00:00:00'),
		                          array('<=',$end_date.' 23:59:59')
		                      );

        $db_model = array();
		$db_model['tab'] = 'k_user_audit_log';
        $db_model['type'] = 1;
        $count = $this->Audit_model->mcount($map,$db_model);
		$perNumber=50;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
		//查询表信息
		$audit_data=$this->Audit_model->get_audit_log($map,$limit);

        //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Audit_model->select_sites());
	    }
	    $this->add('audit_data',$audit_data);
	    $this->add('index_id',$index_id);
	    $this->add('username',$username);
	    $this->add('start_date',$start_date);
	    $this->add('end_date',$end_date);
	    $this->add('page',$this->Audit_model->get_page('k_user_audit_log',$totalPage,$page));
		$this->display('cash/audit_log.html');
	}

}
