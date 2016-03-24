<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Common_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}

	//根据uid 获取用户基本信息
	public function get_user_info($uid){
		if(!empty($uid)){
			$this->db->from('k_user');
			$this->db->where('uid',$uid);
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$userinfo = $this->db->get()->row_array();
			return $userinfo;
		}
	}

	//根据用户的层级ID 获取相应的支付参数如果当前层级未设置支付参数，则读取网站的默认支付参数(根据SITEID查ID最小)
	public function get_user_level_info($level){
		if(!empty($level)){
			$this->db->from('k_user_level');
			$this->db->select('RMB_pay_set');
			$this->db->where('id',$level);
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$userinfo = $this->db->get()->row_array();
			if(!empty($userinfo['RMB_pay_set'])){
				$this->db->from('k_cash_config_view');
				$this->db->where('site_id',SITEID);
				$this->db->where('id',$userinfo['RMB_pay_set']);
				$levelinfo = $this->db->get()->row_array();
			}else{
				$this->db->from('k_cash_config_view');
				$this->db->where('site_id',SITEID);
				$this->db->order_by('id','asc');
				$levelinfo = $this->db->get()->row_array();
			}
			return $levelinfo;
		}
	}

	//获取网站的版权信息
	public function get_copyright(){
		$map = array();
		$map['table'] = 'web_config';
		$map['select'] = 'web_name,keywords,description,remember,email,tel,qq,copy_right,web_name,agent_url,video_module,online_service,topbarbgcolor';
		$map['where']['site_id'] = SITEID;
		$map['where']['index_id'] = INDEX_ID;
		$con_data = $this->rfind($map);

		if (!empty($con_data)) {
			if ($con_data['tel']) {
				$tel = explode(',',$con_data['tel']);
			}
			if ($con_data['qq']) {
				$qq = explode(',',$con_data['qq']);
			}
			if ($con_data['email']) {
				$email = explode(',',$con_data['email']);
			}
		}

		$con_data['tel'] = $tel;
		$con_data['qq'] = $qq;
		$con_data['email'] = $email;
		return $con_data;
	}


	public function cash_type_r($cash_type){
		switch ($cash_type) {
			case '1':
				return '額度轉換';
				break;
			case '2':
				return '体育下注';
				break;
			case '3':
				return '彩票下注';
				break;
			case '4':
				return '视讯下注';
				break;
			case '5':
				return '彩票派彩';
				break;
			case '6':
				return '活动优惠';
				break;
			case '7':
				return '系统拒绝出款';
				break;
			case '8':
				return '系统取消出款';
				break;
			case '9':
				return '优惠退水';
				break;
			case '10':
				return '在线存款';
				break;
			case '11':
				return '公司入款';
				break;
			case '12':
				return '存入取出';
				break;
			case '13':
				return '优惠冲销';
				break;
			case '14':
				return '彩票派彩';
				break;
			case '15':
				return '体育派彩';
				break;
			case '19':
				return '线上取款';
				break;
			case '20':
				return '和局返本金';
			case '22':
				return '体育无效注单';
			case '23':
				return '系统取消出款';
			case '24':
				return '系统拒绝出款';
			case '25':
				return '彩票无效注单';
			case '26':
				return '彩票无效注单(扣本金)';
			case '27':
				return '注单取消(彩票)';
			case '28':
				return '注单取消(体育)';
		}
	}
	//返回交易类别
	public function cash_do_type_r($do_type){
		switch ($do_type) {
			case '1':
				return '存入';
				break;
			case '2':
				return '取出';
				break;
			case '3':
				return '人工存入';
				break;
			case '4':
				return '人工取出';
				break;
			case '5':
				return '扣除派彩';
				break;
			case '6':
				return '返回本金';
				break;
		}
	}

	//银行类别区分
	public function bank_type($type) {
		$this->db->from('k_bank_cate');
		$this->db->where('id',$type);
		$this->db->select('bank_name');
		$result = $this->db->get()->row_array();
		return $result['bank_name'];
	}

	//线下入款方式
	public function in_type($type) {
		switch ($type) {
			case '1':
				return '网银转帐';
				break;
			case '2':
				return 'ATM自动柜员机';
				break;
			case '3':
				return 'ATM现金入款';
				break;
			case '4':
				return '银行柜台';
				break;
			case '5':
				return '手机转帐';
				break;
			case '6':
				return '支付宝转账';
				break;
			case '7':
				return '财付通';
				break;
			case '8':
				return '微信支付';
				break;
		}
	}

	public function str_cut($str){
		$arr = array();
		if(strstr($str, ',操作者') || strstr($str, ',返水操作者')){
			if(strstr($str, ',操作者')){
				$arr = explode(',操作者', $str);
				return $arr[0];
			}elseif(strstr($str, ',返水操作者')){
				$arr = explode(',返水操作者', $str);
				return $arr[0];
			}

		}else{
			return $str;
		}
	}
	//系统维护
	public function GetSiteStatus($d,$type=0,$cate_type,$site_type=1){
	    $echo['status']=0;
	    $echo['url']=null;
	    if($d['Module'] || $d['Relation']){
	        $data=array_merge($d['Module'],$d['Relation']);
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
	            $url='http://'.$_SERVER['HTTP_HOST'].'/wh/';
	            if($type==1){
	                $url="<script>window.top.frames.location.href='$url'</script>";
	                $echo['url']=$url;
	            }elseif($type==2){//会员中心额度转换以及刷新额度使用
	                return $url_;
	            }elseif($type==100){
	                return $url_;
	            }else{
	                $echo['url']=$url;
	            }
	        }
	    }
	    if ($echo['status']==1){
	        echo $echo['url'];
	        exit;
	    }
	}

	function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	function JSON($array) {
		$this->arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}
}