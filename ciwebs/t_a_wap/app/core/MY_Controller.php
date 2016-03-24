<?php if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->getkey();
		$this->cismarty->assign('site_url',URL);
		$whuri_1=$this->uri->segment(1);//文件目录
        $whuri_2=$this->uri->segment(2);//控制器名
		$whuri_3=$this->uri->segment(3);//方法名

        $SiteStatus=@file_get_contents(dirname(__FILE__).'/../../../../_Site_Status_Json/site_status.log');
        //$SiteStatus=@file_get_contents('D:\Web\wwwroot_new\web_20156\_Site_Status_Json\site_status.log');
	    $this->SiteStatus=json_decode($SiteStatus,true);
	    
		//echo $wh;
       //p($this->SiteStatus);
		//根据控制器判断对应类别是否维护
		switch ($whuri_1) {
			case 'index':

			    $wgtype=1;
			    $cate_type='webhome'; //首页
				$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;
            case 'sports':
                $wgtype=2;
                $cate_type='webhome'; 
                $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
                $cate_type='sport'; 
                $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
                break;
            case 'api':
            	$wgtype=1;
                $payload = file_get_contents("php://input");
                $payload = json_decode($payload, true);

                if(($whuri_3=='MBet' || $whuri_3=='getHKCLines') && $payload['LotteryId'] == 4 ){//其他彩种维护以此类推

                  $wgtype=4;
                  $cate_type='liuhecai';//六合彩
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' )&& $payload['lotteryId']==2){

                  $wgtype=4;
                  $cate_type='fc_3d';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==3){
                  $wgtype=4;
                  $cate_type='pl_3';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==7){
                  $wgtype=4;
                  $cate_type='cq_ssc';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==9){
                  $wgtype=4;
                  $cate_type='xj_ssc';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==8){
                  $wgtype=4;
                  $cate_type='tj_ssc';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==5){
                  $wgtype=4;
                  $cate_type='bj_8';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==6){
                  $wgtype=4;
                  $cate_type='bj_10';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==11){
                  $wgtype=4;
                  $cate_type='cq_ten';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==12){
                  $wgtype=4;
                  $cate_type='gd_ten';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==13){
                  $wgtype=4;
                  $cate_type='js_k3';
                }elseif(($whuri_3=='MBet' || $whuri_3=='GetHFLines' ) && $payload['lotteryId']==14){
                  $wgtype=4;
                  $cate_type='jl_k3';
                }else{
                  $wgtype=1;
                  $cate_type='lottery';
                }
				$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;

			default:
				# code...
				break;
		}
	}
	/*
 *
 * 跳转方式 $type=0,
 * 监控类别$cate_type,此处匹配来源在数据库manage_cyj/site_cate_module/cate_type字段
 * 前台 $site_type=1 后台$site_type=2
 *
 * */
	public function GetSiteStatus($d,$type=0,$cate_type,$site_type=1){

    if(!isset($cate_type)) return false;
    $echo['status']=0;
    $echo['url']=null;
    if($d['ModuleWap'] || $d['RelationWap']){
        $data=array_merge($d['ModuleWap'],$d['RelationWap']);

        $wh=false;
        $r=array();
        if($cate_type){
            foreach($data as $v){
                $msg=($v['message']);
                    if($v['site_id']==SITEID && $v['cate_type']==$cate_type){
                        $wh=true;
                        $url_[$v['cate_type']]=1;
                        $url_[$v['cate_type'].'_msg']=$msg;
                    }
            }
            foreach($data as $v){
                $msg=($v['message']);

                if(!$v['site_id'] && $v['cate_type']==$cate_type){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
        }else{
            foreach($data as $v){
                $msg=($v['message']);
                if($v['site_id']==SITEID){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
            foreach($data as $v){
                $msg=($v['message']);

                if(!$v['site_id'] ){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
        }

        if($wh==true){
        	header('Content-Type: application/json;charset=utf-8');
            $echo['status']=1;
            $url='http://'.$_SERVER['HTTP_HOST'].'/index.php/wh';
            //以下是定义输出类型
            if($type==4){//彩票单个彩种
                $echo['url']=json_encode(["Success"=>0,"msg"=>"此彩种维护中","errId"=>99]);
            }
            elseif($type==2){ 
                $echo['url']=json_encode(["Success"=>0,"msg"=>$url_[$cate_type.'_msg']]);
            }else{//维护URL地址输出
                $echo['url']=$url;
            }
        }
    }

    if ($echo['status']==1){
        return $echo['url'];
    }


	}
	public function gojson($v,$k='',$msg=''){
		  if($k) $v[$k]=$msg;
		  echo json_encode($v);
		  exit();
	}

	//验证是否登录
	//111 没有登录   112 账号异常已被停止 113 您的账号异常已被暂停使用 114 别处登录
	public  function verify_login($uid){
		if(!isset($uid))
		{
			$json = '{"ErrorCode": 111, "ErrorMsg": "请先登录再进行操作"}';
			header('Content-Type: application/json;charset=utf-8');
			echo $json;
			exit();
		} else {
		    $this->db->from('k_user_login');
		    $this->db->where('uid',$uid);
		    $this->db->where('is_login',1);
		    $ulog = $this->db->get()->row_array();
		    //判断会员是否已经禁用
		    $this->db->from('k_user');
		    $this->db->where('uid',$uid);
		    $this->db->select('is_delete');
		    $isUse = $this->db->get()->row_array();
		    if ($isUse['is_delete'] == '1') {
				$json = '{"ErrorCode": 112, "ErrorMsg": "对不起，您的账号异常已被停止，请与在线客服联系！"}';
				header('Content-Type: application/json;charset=utf-8');
				echo $json;
				exit;
		    }elseif($isUse['is_delete'] == '2'){
				$json = '{"ErrorCode": 113, "ErrorMsg": "对不起，您的账号异常已被暂停使用，请与在线客服联系！"}';
				header('Content-Type: application/json;charset=utf-8');
				echo $json;
				exit;
		    }

		    //屏蔽试玩账号检测
		    if ($_SESSION['shiwan'] == '1') {
		    	$ulog['uid'] = $_SESSION['uid'];
		    }

			if($ulog['uid'] > 0){
				if($ulog['ssid'] != $_SESSION["ssid"])
				{
					session_destroy();
					//别处登陆
					$json = '{"ErrorCode": 114, "ErrorMsg": "对不起，您的账号异常已被暂停使用，请与在线客服联系！"}';
					header('Content-Type: application/json;charset=utf-8');
					echo $json;
					exit;
				}else{
					//更新在线时间
					if (!$_SESSION['shiwan']) {
						$this->load->model('Index_model'); 
				    	$this->Index_model->redis_update_user();
				    }
				}
			}else{
				session_destroy();
				$json = '{"ErrorCode": 115, "ErrorMsg": "已退出"}';
				header('Content-Type: application/json;charset=utf-8');
				echo $json;
				exit;
			}
		}
	}

	/*sports token start*/

	public function get_token_info_is_login(){
		$token=$this->input->post_get('token');
	    $uid=$this->input->post_get('uid');

		$this->load->library('sportsbet');
	    $redis_token_key=$this->sportsbet->make_token_key($uid);
		if($token && $uid){
			$token=$this->redis_get_token($redis_token_key,$token);//通过token UID判断是否登录  可获取USERNAME AGENGIT LEVENTID SHIWAN

			if($token==false) {
				exit(json_encode(array('login'=>2)));
			}else{
				return $token;
			}
		}else exit(json_encode(array('login'=>2)));
	}
	public function redis_get_token($key,$token){//取TOKEN信息
		$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get($key);
	    $value=json_decode($d,true);
		//print_r($value);
	    if($value['token']==$token) {
	    	return $value;
		}
	    else return false;
	}
	/*sports token end*/
	public function add($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function assign($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function display($html) {
		$new_display_url = str_replace('\\','/',CVIEWPATH);
		$this->cismarty->display($new_display_url.'/'.$html);
	}

	public function parse($tpl_var, $resource_name) {

		$this->cismarty->assign($tpl_var, $this->cismarty->fetch($resource_name));
	}

	//获取IP
	public function get_ip(){
	    $realip = '';
	    $unknown = 'unknown';
	    if (isset($_SERVER)){
	        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            foreach($arr as $ip){
	                $ip = trim($ip);
	                if ($ip != 'unknown'){
	                    $realip = $ip;
	                    break;
	                }
	            }
	        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
	            $realip = $_SERVER['HTTP_CLIENT_IP'];
	        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
	            $realip = $_SERVER['REMOTE_ADDR'];
	        }else{
	            $realip = $unknown;
	        }
	    }else{
	        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
	            $realip = getenv("HTTP_CLIENT_IP");
	        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
	            $realip = getenv("REMOTE_ADDR");
	        }else{
	            $realip = $unknown;
	        }
	    }
	    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
	    return $realip;
	}

	//获取地址
	public function get_address($loginIp){
		include_once(dirname(__FILE__)."/ip.php");
        return convertip($loginIp);
	}

	public function getkey(){
		$this->video_db = $this->load->database("video", TRUE, TRUE);
		$this->video_db->from('game_sites');
		$this->video_db->where('site_id',SITEID);
		$this->video_db->select('site_des_key,site_md5_key');
		$key = $this->video_db->get()->row_array();
	    $site_des_key=$key['site_des_key'];
	    $site_md5_key=$key['site_md5_key'];
	    define('SITE_DES_KEY',$site_des_key);
	    define('SITE_MD5_KEY',$site_md5_key);
	}
}
?>