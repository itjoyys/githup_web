<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ValidateLogin extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}


	/*
	錯誤代碼(除1以外，其餘代碼業主可自訂)
	1：成功
	3：用戶名或密碼錯誤
	5：登入錯誤次數達到上限
	6：用戶未激活
	8：自我限制用戶
	100：未知錯誤
	101：xml 錯誤
	102：未知請求
	103: Element ID 錯誤 
	999：内部錯誤
	*/
	public function index(){
/*
		$xml_txt = <<<Eof
<?xml version="1.0"?>
<request action="ValidateLogin">
	<element id="6">
		<username>phili</username>
		<password>qwe123</password>
	</element>
</request>
Eof;
*/

		$bbin_sessionid = $username = $password  = $msg = $site_id = "" ;
		$status = 1;
		$bbin_sessionid = 999;
		
		$get_data = file_get_contents("php://input");
		if (empty($get_data )){
			$status = 102;
			$msg = "未知請求";
		}

		if ($status == 1 ){
			try{
				//解析xml文件
				$xml = simplexml_load_string($get_data);
				//var_dump($xml,$xml->element);
				if(is_object($xml) && is_object($xml->element)){
					$bbin_sessionid = (string) $xml->element->attributes()->id;
					$username = (string) $xml->element->username;
					$password = (string) $xml->element->password;
				}else{
					$status = 101;
					$msg = "xml 錯誤";
				}
			}catch(Exception $e){
				$status = 101;
				$msg = "xml 錯誤";
			}

			if ($status != 1 && empty($bbin_sessionid)){
				$status = 103;
				$msg = "Element ID 錯誤";
			}

			if ($status != 1 && (empty($username) || empty($password))){
				$status = 3;
				$msg = "用戶名或密碼不能为空";
			}
			
		}

		//去验证用户
		if($status == 1){
			$u_arr = explode("_", $username);
			if (count($u_arr) != 2){
				$status = 102;
				$msg = "用戶名格式错误";
			}

			if($status == 1){
				$site_id = $u_arr[0];
				$this->load->model('member/Member_model');
				$status = $this->Member_model->bbin_app_login($u_arr[1],$password ,$u_arr[0]);
			}
			if($status == 1){
				$msg = "登录成功";
			}else if($status == 8){
				$msg = "用户已停用";
			}else if($status == 6){
				$msg = "用户为激活";
			}else if($status == 3){
				$msg = "用戶名或密碼錯誤";
			}
		}

        $username  = $site_id.$username ;

	$xml_tpl = <<<Eof
<?xml version="1.0"?>
<request action="ValidateLogin">
	<element id="$bbin_sessionid">
		<username>$username</username>
		<status>$status</status>
		<desc>$msg</desc>
	</element>
</request>
Eof;
header("Content-type: text/xml");
		echo $xml_tpl ;
		exit();
	}

	public function test(){
				$xml_txt = <<<Eof
<?xml version="1.0"?>
<request action="ValidateLogin">
	<element id="6">
		<username>phili</username>
		<password>qwe123</password>
	</element>
</request>
Eof;
		$ch = curl_init();
		$url = "http://m.pkbet.org/ValidateLogin";
		$header[] = "Content-type: text/xml";//定义content-type为xml
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_txt);
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
		    print curl_error($ch);
		}
		curl_close($ch);

		print_r($response);
	}
}



?>
