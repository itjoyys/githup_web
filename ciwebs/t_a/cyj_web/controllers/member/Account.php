<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/Account_model');
		$this->Account_model->login_check($_SESSION['uid']);
		$this->load->model('Common_model');
	}
    //我的账户
	public function userinfo()
	{
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$copyright = $this->Common_model->get_copyright();
		$video_config = explode(',',$copyright['video_module']);

		//余额总计计算
		$allmoney = $userinfo['money'];
		foreach ($video_config as $key => $val) {
			if ($val != 'eg') {
			    $allmoney += $userinfo[$val.'_money'];
			}else{
                unset($video_config[$key]);
			}
		}

		//判断是否开启自助返水
		$is_self = $this->Account_model->is_self_user();
		$this->add('is_self',$is_self['is_self_fd']);

		$cash_data = $this->Account_model->get_cash_limit();
		$this->add('cash_data',$cash_data);//最近十笔记录
		$this->add('allmoney',$allmoney);//余额总计
		$this->add('userinfo',$userinfo);//用户信息
		$this->add('video_config',$video_config);//站点视讯模块
		$this->display('member/userinfo.html');
	}

	//体育投注咨询
	public function advisory()
	{
		//判断是否开启自助返水
		$is_self = $this->Account_model->is_self_user();
		$this->add('is_self',$is_self['is_self_fd']);

		$spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球','bs'=>'棒球','tn'=>'网球');
		$spArr = array('ft','bk','vb','bs','tn');
		$spType = $this->Account_model->get_sp_advisory($spArr);
		$this->add('spTitle',$spTitle);
		$this->add('spType',$spType);
		$this->display('member/advisory.html');
	}

	//彩票投注咨询

	public function lottery_advisory()
	{
		//判断是否开启自助返水
		$is_self = $this->Account_model->is_self_user();
		$this->add('is_self',$is_self['is_self_fd']);

	   //标题
		$fcTitle = array(
         'fc_3d'=>'福彩3D','pl_3'=>'排列三',
         'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
         'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
         'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
         'xj_ssc'=>'新疆时时彩',
         //'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩',
         'jl_k3'=>'吉林快三','js_k3'=>'江苏快三',
         'liuhecai'=>'六合彩'
        );

       //站点彩票退水限额
		$fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8',
           'bj_10','tj_ssc','xj_ssc','jl_k3','js_k3',
           'liuhecai'
        );
        $fcType = $this->Account_model->get_lottery_advisory($fcArr);
		$this->add('fcTitle',$fcTitle);
		$this->add('fcType',$fcType);
		$this->display('member/lottery_advisory.html');
	}

	//加载修改密码页面
	public function password(){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		if($userinfo['change_pwd'] == 0){
			message('系统管理员已禁止您自行修改登录密码。\n 请联系系统管理员！');
		}

		$pwdtype = $this->input->get('pwdtype');
		$pwdtype = !empty($pwdtype) ? $pwdtype : 'login';

		//读取视讯配置
		$conf['site_id'] = SITEID;
		$conf['index_id'] = INDEX_ID;
		$video_config = $this->db->from('web_config')->where($conf)->select('video_module')->get()->row_array();
	    $video_config = explode(',',$video_config['video_module']);
	    $video = array('mg','pt','bbin');
	    $edvideo = array_intersect($video,$video_config);
	    if(in_array($pwdtype, $edvideo)){
	    	$video_user = $this->Account_model->get_video_username($pwdtype);
	    	$this->add('video_user',$video_user);
	    }

		$this->add('edvideo', $edvideo);
		$this->add('pwdtype', $pwdtype);
		$this->display('member/edit_pwd.html');
	}

	//修改网站登录密码
	public function userpwd(){
		$oldpass = $this->input->post('oldpass');
		$newpass2 = $this->input->post('newpass2');
		if($oldpass && $newpass2 && $oldpass!=$newpass2)
		{
			//表单验证
		    $this->load->library('form_validation');
		    $this->form_validation->set_rules('oldpass', 'oldpass', 'required|min_length[6]|max_length[12]');
		    $this->form_validation->set_rules('newpass2', 'newpass2', 'required|min_length[6]|max_length[12]');

		    if ($this->form_validation->run() == FALSE) {
		    	message('登录密码格式错误!');
		    }

			$set['password'] = md5(md5($newpass2));
			$result = $this->Account_model->edit_password($set);
			if($result)
			{
				echo "<script>alert('登陆密码修改成功');</script>";
				session_destroy();
				echo "<script>top.location.href='/';</script>";
			}else
			{
				echo "<script>alert('登陆密码修改失败');</script>";
			}
		}else{
				message("原取款密码不能与修改后的取款密码一致!");
		}
	}

	//修改取款密码
	public function moneypwd(){
		$oldmoneypass = $this->input->post('oldmoneypass');
		$newmoneypass2 = $this->input->post('newmoneypass2');
		if($oldmoneypass && $newmoneypass2 && $oldmoneypass!=$newmoneypass2)
		{

			//表单验证
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('oldmoneypass', 'oldmoneypass', 'required|min_length[4]|max_length[4]');
	    $this->form_validation->set_rules('newmoneypass2', 'newmoneypass2', 'required|min_length[4]|max_length[4]');

	     if ($this->form_validation->run() == FALSE) {
	    	message('取款密码格式错误!');
	    }

			if($_SESSION['shiwan']==1)
			{
				exit("<script language=javascript>alert('试玩账号没有取款密码，请使用正式账号！');history.go(-1);</script>");
			}

			$data['qk_pwd'] = $newmoneypass2;
			$result = $this->Common_model->get_user_info($_SESSION['uid']);
			if($result['qk_pwd'] == $oldmoneypass)
			{
				if($oldmoneypass != newmoneypass2){
						$set['qk_pwd'] = $newmoneypass2;
						$row = $this->Account_model->edit_password($set);
						if($row)
						{
							message("取款密码修改成功!");
						}
						else
						{
							message("取款密码修改失败!");
						}
				}else{
					  message("原取款密码不能与修改后的取款密码一致!");
				}
			}
			else
			{
				message("原取款密码错误!");
			}
		}

	}

	//修改mg登录密码
	public function mgpwd(){
		if($_SESSION['shiwan']==1)
		{
			message("试玩账号没有MG密码，请使用正式账号！");
		}
		$omgpass = $this->input->post('omgpass');
		$newmgpass2 = $this->input->post('newmgpass2');
		if($omgpass && $newmgpass2)
		{
				//表单验证
		    $this->load->library('form_validation');
		    $this->form_validation->set_rules('omgpass', 'omgpass', 'required|min_length[6]|max_length[12]');
		    $this->form_validation->set_rules('newmgpass2', 'newmgpass2', 'required|min_length[6]|max_length[12]');
		    if ($this->form_validation->run() == FALSE) {
		    	message('登录密码格式错误!');
		    }

			$this->load->library('Games');
			$games = new Games();
			$password = md5(md5($omgpass));
			$result = $this->Common_model->get_user_info($_SESSION['uid']);
			if($result['password'] == $password)
			{
				$row = $games->MgEditAccountPwd($result['username'],$newmgpass2);
				$data = json_decode($row);
				if($data->result){
					message("密码修改成功!");
				}else{
					message("修改密码失败!");
				}
			}else{
				message("原登录密码错误!");
			}
		}
	}

	//修改PT密码
	public function ptpwd(){
		if($_SESSION['shiwan']==1){
			message("试玩账号没有PT密码，请使用正式账号！");
		}
		$optpass = $this->input->post('optpass');
		$newptpass2 = $this->input->post('newptpass2');
		if($optpass && $newptpass2){
			//表单验证
		    $this->load->library('form_validation');
		    $this->form_validation->set_rules('optpass', 'optpass', 'required|min_length[6]|max_length[12]');
		    $this->form_validation->set_rules('newptpass2', 'newptpass2', 'required|min_length[6]|max_length[12]');
		    if ($this->form_validation->run() == FALSE) {
		    	message('登录密码格式错误!');
		    }

			$this->load->library('Games');
			$games = new Games();
			$password = md5(md5($_POST[optpass]));
			$result = $this->Common_model->get_user_info($_SESSION['uid']);

			if($result['password'] == $password){
				$row = $games->PtEditAccountPwd($result['username'],$newptpass2);
				$data = json_decode($row);
				if($data->result){
					message("密码修改成功!");
				}else{
					message("密码修改失败!");
				}
			}else{
				message("原登录密码错误!");
			}
		}
	}

	//修改BBIN密码
	public function bbinpwd(){
		if($_SESSION['shiwan']==1){
			message("试玩账号没有PBBIN密码，请使用正式账号！");
		}
		$obbinpass = $this->input->post('obbinpass');
		$newbbinpass2 = $this->input->post('newbbinpass2');
		if($obbinpass && $newbbinpass2){
			//表单验证
		    $this->load->library('form_validation');
		    $this->form_validation->set_rules('obbinpass', 'obbinpass', 'required|min_length[6]|max_length[12]');
		    $this->form_validation->set_rules('newbbinpass2', 'newbbinpass2', 'required|min_length[6]|max_length[12]');
		    if ($this->form_validation->run() == FALSE) {
		    	message('登录密码格式错误!');
		    }

			$this->load->library('Games');
			$games = new Games();
			$password = md5(md5($_POST[obbinpass]));
			$result = $this->Common_model->get_user_info($_SESSION['uid']);

			if($result['password'] == $password){
				$row = $games->BbinEditAccountPwd($result['username'],$newbbinpass2);
				$data = json_decode($row);
				if($data->result){
					message("密码修改成功!");
				}else{
					message("密码修改失败!");
				}
			}else{
				message("原登录密码错误!");
			}
		}
	}

	 //会员自助返水
    public function user_self_fd(){
        $this->load->model('member/User_self_fd_model');
        //获取视讯配置
        $vconfig = $this->User_self_fd_model->video_config();

        $title = array('fc'=>'彩票','sp'=>'体育','ag'=>'AG视讯','og'=>'OG视讯','mg'=>'MG视讯','mgdz'=>'MG电子',
        	'ct'=>'CT视讯','pt'=>'PT电子','eg'=>'EG电子','lebo'=>'LEBO视讯','bbin'=>'BB视讯','bbdz'=>'BB电子','bbsp'=>'BB体育','bbfc'=>'BB彩票');
        array_unshift($vconfig, "fc", "sp");
        if (in_array('bbin',$vconfig)) {
            array_push($vconfig, 'bbdz');
            array_push($vconfig, 'bbsp');
            array_push($vconfig, 'bbfc');
        }

        if (in_array('mg',$vconfig)) {
            array_push($vconfig, 'mgdz');
        }
        $odata = $old_data = array();
        //今日累计自助返水
        $old_data = $this->User_self_fd_model->user_self_fd_olddata(date('Ymd'));

        foreach ($old_data as $key => $val) {
            if (false !== strpos($key,'fd')) {
                $ki = str_replace('_fd','',$key);
                $odata[$ki] = $val;
            }
        }
        $this->add('odata',$odata);
        $this->add('vconfig',$vconfig);
        $this->add('title',$title);
        $this->display('member/user_self_fd.html');
    }

    //请求返水数据
    public function user_self_fd_data(){
    	$this->load->model('member/User_self_fd_model');
    	//当前即时数据
        $data = $this->User_self_fd_model->user_self_fd_data();
        //前期累计返水数据
        $old_data = $this->User_self_fd_model->user_self_fd_olddata($data['orderIds']);

        $title = array('fc_bet'=>'彩票','sp_bet'=>'体育','ag_bet'=>'AG视讯','og_bet'=>'OG视讯','mg_bet'=>'MG视讯','mgdz_bet'=>'MG电子',
        	'ct_bet'=>'CT视讯','pt_bet'=>'PT电子','eg_bet'=>'EG电子','lebo_bet'=>'LEBO视讯','bbin_bet'=>'BB视讯','bbdz_bet'=>'BB电子','bbsp_bet'=>'BB体育','bbfc_bet'=>'BB彩票');


        if ($data['total_e_fd'] && ($data['all_bet'] - $old_data['all_bet']) > 0) {

            if ($_SESSION['self_fd_data']) { unset($_SESSION['self_fd_data']);}

        	$_SESSION['self_fd_data'] = $data;
            foreach ($data as $key => $val) {
            	if ($key != 'orderIds') {
            	    $data[$key] = $val - $old_data[$key];
            	}
            }


            $th_html = $td_html = $fd_html = '';
	        foreach ($data as $k => $v) {
	        	if (FALSE !== strpos($k,'bet')) {
	        		if ($title[$k]) {
	        		    $th_html .= '<th>'.$title[$k].'</th>';
	                    $td_html .= '<td>'.$v.'</td>';
	        		}

	        	}elseif(FALSE !== strpos($k,'fd') && $k != 'total_e_fd'){
	                $fd_html .= '<td>'.$v.'</td>';
	                $old_fd_html .= '<td>'.$old_data[$k].'</td>';
	        	}
	        }

	        $dhtml = '<tr><th colspan="20" style="background-color: #C5D9F1;">有效打码</th></tr><tr>'.$th_html.'</tr><tr>'.$td_html.'</tr><tr><th colspan="20" style="background-color: #C5D9F1;">返水额度</th></tr><tr>'.$th_html.'</tr><tr>'.$fd_html.'</tr><tr><th colspan="20" style="background-color: #C5D9F1;">今日累计返水额度【不含当次】</th></tr><tr>'.$th_html.'</tr><tr>'.$old_fd_html.'</tr><tr><td colspan="20" align="center"><input type="button" value="自助返水写入" id="fdbtndo" onclick="self_fd_data();"></tr>';


            $udata['state'] = 1;
            $udata['msg'] = '可获返水金额：'.$data['total_e_fd'];
            $udata['data'] = $dhtml;

            exit(json_encode($udata));
        }else{
        	$udata['state'] = 0;
            $udata['msg'] = '暂无可获返水';
            $udata['data'] = 0;
            exit(json_encode($udata));
        }
    }

    //会员自助返水处理
    public function user_self_fd_data_do(){
        $this->load->model('member/User_self_fd_model');
        if (empty($_SESSION['self_fd_data']) || empty($_SESSION['self_fd_data']['total_e_fd'])) {
            exit('error');
        }

    	//当前即时数据
        $log = $this->User_self_fd_model->user_self_fd_data_do();
        if ($log) {
        	$udata['state'] = 1;
            $udata['msg'] = '自助返水操作成功!';
        }else{
        	$udata['state'] = 0;
            $udata['msg'] = '自助返水操作失败!';
        }
        exit(json_encode($udata));
    }


}
