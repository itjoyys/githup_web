<?php if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->cismarty->assign('site_url',URL);
	}
    //登陆检测
	public function login_check(){
        if (empty($_SESSION['adminid'])) {
        	session_destroy();
	    	echo "<script>alert('请重新登录!!');</script>";
    	    echo "<script>top.parent.location.href='http://".$_SERVER['HTTP_HOST']."/login.html';</script>";
    	    //window.location.reload();
	    	exit();
        }else{
        	$redis = new Redis();
			$redis->connect(REDIS_HOST,REDIS_PORT);
			$redis_akey = 'alg'.$_SESSION['site_id'].$_SESSION['adminid'];
			$redis->setex($redis_akey,'1200','1');
        }
	}

	public function add($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function assign($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function display($html) {
		$this->cismarty->display($html);
	}
}
?>