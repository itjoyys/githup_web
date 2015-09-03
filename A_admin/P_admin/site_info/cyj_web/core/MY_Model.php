<?php
/**
 *
 */
class MY_Model extends CI_Model {

	protected $public_db = null;
	protected $private_db = null;
	protected $video_db = null;

	public function __construct() {

	}

	//初始私有化数据库
	protected function init_db() {
		if ($this->public_db == null) {
			$this->public_db = $this->load->database("public", TRUE, TRUE);
		}
		//连接私有数据库
		if ($this->private_db == null) {
			$this->private_db = $this->load->database("private", TRUE, TRUE);
		}

		if ($this->video_db == null) {
			$this->video_db = $this->load->database("video", TRUE, TRUE);
		}
	}

	//PHP stdClass Object转array
	protected function object_array($array) {
	    if(is_object($array)) {
	        $array = (array)$array;
	    } if(is_array($array)) {
	        foreach($array as $key=>$value) {
	            $array[$key] = $this->object_array($value);
	        }
	    }
	    return $array;
	}

	//删除
	public function rdel($tab,$field,$value){
		$this->db->where($field,$value);
        return $this->db->delete($tab);
	}
	//更新
	public function rupdate($tab,$field,$value,$arr=array()){
        $this->db->where($field,$value);
        return $this->db->update($tab,$arr);
	}
	//添加
	public function radd($tab,$arr=array()){
		$this->db->insert($tab,$arr);
    	return $this->db->insert_id();
	}
	//查询单条
	public function rfind($tab,$wfield,$value,$field='*'){
		$data=$this->db->select($field)->from($tab)->where($wfield,$value)->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
	}
	//查询列表
	public function rget($tab,$wfield,$value,$field='*'){
		return $this->db->select($field)->from($tab)->where($wfield,$value)->get()->result_array();
	}

	public function Syslog($arr){
		include_once(dirname(__FILE__)."/ip.php");
		$arr['log_ip'] = self::get_client_ip();
		$arr['site_id'] = $_SESSION['site_id'];
    	$arr['login_name'] = $_SESSION['login_name'];
    	$arr['log_time'] = date('Y-m-d H:i:s');
    	$arr['uid'] = $_SESSION['adminid'];
		return self::radd('sys_log',$arr);
	}

	public function get_client_ip(){  
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
}
