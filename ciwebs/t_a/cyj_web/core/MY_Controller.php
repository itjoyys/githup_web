<?php if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Controller extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->getkey();
		$this->cismarty->assign('site_url',URL);

		//判断手机
		$this->mobile_location(SITEID,INDEX_ID);

		$whuri_1=$this->uri->segment(1);//文件目录
        $whuri_2=$this->uri->segment(2);//控制器名
		$whuri_3=$this->uri->segment(3);//方法名

        $SiteStatus=@file_get_contents(dirname(__FILE__).'/../../../../_Site_Status_Json/site_status.log');


		// $SiteStatus=@file_get_contents('D:\php\trunk\web_20156\_Site_Status_Json\site_status.log');
// p($SiteStatus);
		// echo $SiteStatus;
    // exit;
		$this->SiteStatus=json_decode($SiteStatus,true);
		//echo $wh;
		//根据控制器判断对应类别是否维护
		switch ($whuri_1) {
			case 'index':
			    if($whuri_2=='sports'){
			    	$wgtype=1;
					$cate_type='sport';
					$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
					break;
			    }else{
			    	$wgtype=1;
			    	$cate_type='webhome';
					$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
					break;
			    }
			case 'video':
                $wgtype=1;
                $cate_type=$this->input->get('g_type');
                if($cate_type){
                    $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
                }

                break;
            case 'games':
                $wgtype=1;
                $cate_type=$this->input->get('g_type');
                if($cate_type=='mg'){
                    $cate_type='mg_game';
                }else if($cate_type=='ag'){
                	$cate_type='ag_game';
                }else if($cate_type=='pt'){
                	$cate_type='pt_game';
                }else if($cate_type=='pk'){
                	$cate_type='pk_game';
                }else if($cate_type=='eg'){
                	$cate_type='eg_game';
                }else{

                }
                $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
                break;
            case 'lottery':
				        $wgtype=1;
                $payload = file_get_contents("php://input");
                $payload = json_decode($payload, true);

                if(($whuri_3=='bet' || $whuri_3=='index' || $whuri_3=='liuhecaijson') && ($payload['lotteryPan']>=222 && $payload['lotteryPan']<=234)){
                  $wgtype=4;
                  $cate_type='liuhecai';//六合彩
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' )&& $payload['lotteryId']=='fc_3d'){
                  $wgtype=4;
                  $cate_type='fc_3d';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='pl_3'){
                  $wgtype=4;
                  $cate_type='pl_3';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='cq_ssc'){
                  $wgtype=4;
                  $cate_type='cq_ssc';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='xj_ssc'){
                  $wgtype=4;
                  $cate_type='xj_ssc';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='tj_ssc'){
                  $wgtype=4;
                  $cate_type='tj_ssc';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='bj_8'){
                  $wgtype=4;
                  $cate_type='bj_8';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='bj_10'){
                  $wgtype=4;
                  $cate_type='bj_10';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='cq_ten'){
                  $wgtype=4;
                  $cate_type='cq_ten';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='gd_ten'){
                  $wgtype=4;
                  $cate_type='gd_ten';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='js_k3'){
                  $wgtype=4;
                  $cate_type='js_k3';
                }elseif(($whuri_3=='bet' || $whuri_3=='get_json' ) && $payload['lotteryId']=='jl_k3'){
                  $wgtype=4;
                  $cate_type='jl_k3';
                }else{
                  $wgtype=1;
                  $cate_type='lottery';
                }
				$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;
			case 'sports':
				$wgtype=3;
				$cate_type='sport';
				$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;
			case 'member':
				$wgtype=1;
				$cate_type='webhome';
				$this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;

			default:
				# code...
				break;
		}
	}

	//判断手机
	public function mobile_location($siteid,$indexid){
		$this->load->library('user_agent');
		if (trim($_GET['type']) == 'PC') {
			//添加cookie
			//setcookie("",value,expire,path,domain,secure)
			/*
			$server = $_SERVER["HTTP_HOST"];
			$domains = explode(".", $_SERVER["HTTP_HOST"]);
			$max = count($domains);
			if($max  >= 2){
				$domain = $domains[$max-2]."." $domains[$max-1];
			}*/
			setcookie("type_client","PC",0); 
			return;
		}
		if($this->input->cookie("type_client") == 'PC'){
				return;
		}
		if ($this->agent->is_mobile())
		{
			$this->private_db = $this->load->database("private", TRUE, TRUE);
			$this->private_db->from('web_config');
			$this->private_db->where('site_id',$siteid);
			$this->private_db->where('index_id',$indexid);
			$this->private_db->select('wap_url');
			$key = $this->private_db->get()->row_array();
			$server = $_SERVER["HTTP_HOST"];
			$tmp_host = explode('.', $server);
			$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://':"http://";
			if(count($tmp_host)>2){
				if(strpos($tmp_host[0],'pay') !== false){
					
				}else{
					if(!empty($key['wap_url'])){
						$intr = $this->input->get('intr');
						if(!empty($intr)){
							$this->load->model('Index_model');
							$Astate = $this->Index_model->is_intr($intr);
							if ($Astate) {
								$pg = $http_type.$key['wap_url'].'/?intr='.$Astate['intr'];
								echo '<script>location.href="'.$pg.'"</script>';exit;
							}
						}
						$pg = $http_type.$key['wap_url'];
						echo '<script>location.href="'.$pg.'"</script>';exit;
					}
				}
			}else{
				if(!empty($key['wap_url'])){
					$intr = $this->input->get('intr');
					if(!empty($intr)){
						$this->load->model('Index_model');
						$Astate = $this->Index_model->is_intr($intr);
						if ($Astate) {
							$pg = $http_type.$key['wap_url'].'/?intr='.$Astate['intr'];
							echo '<script>location.href="'.$pg.'"</script>';exit;
						}
					}
					$pg = $http_type.$key['wap_url'];
					echo '<script>location.href="'.$pg.'"</script>';exit;
				}
			}
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
    if($d['Module'] || $d['Relation']){
        $data=array_merge($d['Module'],$d['Relation']);
        // p($data);
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
            $echo['status']=1;
            $url='http://'.$_SERVER['HTTP_HOST'].'/index.php/wh';
            //以下是定义输出类型
            if($type==1){//JS输出
                $url="<script>window.top.frames.location.href='$url'</script>";
                $echo['url']=$url;
            }elseif($type==2){//会员中心额度转换以及刷新额度使用
                return $url_;
            }elseif($type==3){//体育中心
                $echo['url']=json_encode(['sitestatus'=>$url]);
            }elseif($type==4){//彩票单个彩种
              header('Content-Type: application/json;charset=utf-8');
              $echo['url']=json_encode(["Success"=>0,"msg"=>"该玩法维护中","errId"=>1024]);
              // $echo['url']['Obj']['aaa']  = -999;
            }elseif($type==100){
                return $url_;
            }else{//维护URL地址输出
                $echo['url']=$url;
            }
        }
    }

    if ($echo['status']==1){
        echo $echo['url'];
        exit;
    }


}
	public function gojson($v,$k='',$msg=''){
		  if($k) $v[$k]=$msg;
		  echo json_encode($v);
		  exit();
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
				exit(json_encode(['login'=>2]));
			}else{
				return $token;
			}
		}else exit(json_encode(['login'=>2]));
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