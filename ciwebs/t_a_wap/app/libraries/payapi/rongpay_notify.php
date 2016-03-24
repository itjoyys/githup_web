<?php
/*
 *类名：rongpay_notify
 *功能：付款过程中服务器通知类
 *详细：该页面是通知返回核心处理文件，不需要修改
 *修改日期：2012-09-01
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究融宝支付接口使用，只是提供一个参考。
*/
if (!class_exists('rongpay_function'))
    include 'rongpay_function.php';
	class Rongpay_notify
	{
		var $gateway;           //网关地址
		var $_key;  			//安全校验码
		var $merchant_ID;           //合作伙伴ID
		var $sign_type;         //签名方式 系统默认
		var $mysign;            //签名结果
		var $charset;    //字符编码格式
		var $transport;         //访问模式

			/**构造函数
			*从配置文件中初始化变量
			*$merchant_ID 合作身份者ID
			*$key 安全校验码
			*$sign_type 签名类型
			*$charset 字符编码格式
			*$transport 访问模式
			 */
		  function rongpay_notify($merchant_ID,$key,$sign_type,$charset = "utf-8",$transport= "http") 
		  {

			$this->transport = $transport;
			if($this->transport == "https") 
			{
				$this->gateway = "";
			}
			else 
			{
				$this->gateway = "http://interface.reapal.com/verify/notify?";
			}
			$this->merchant_ID          = $merchant_ID;
			$this->_key    			= $key;
			$this->mysign           = "";
			$this->sign_type	    = $sign_type;
			$this->charset   = $charset;
		}
	    /********************************************************************************/

		/**
		* 对notify_url的认证
		* 返回的验证结果：true/false
		*/
		 function notify_verify()
		 {
			 			//获取远程服务器ATN结果，验证是否是融宝支付服务器发来的请求
			if($this->transport == "https") 
			{
				$veryfy_url = $this->gateway. "service=notify_verify" ."&merchant_ID=" .$this->merchant_ID. "&notify_id=".$_GET["notify_id"];
			} 
			else 
			{
				$veryfy_url = $this->gateway. "merchant_ID=".$this->merchant_ID."&notify_id=".$_GET["notify_id"];
			}
	
			$veryfy_result =file_get_contents($veryfy_url);

			 //生成签名结果

				$post          = para_filter($_GET);	    //对所有GET反馈回来的数据去空
				$sort_post     = arg_sort($post);		    //对所有GET反馈回来的数据排序
				$this->mysign  = build_mysign($sort_post,$this->_key,$this->sign_type);    //生成签名结果
		
		
				//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
				//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
				//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
				if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"]) 
				{   
					return true;
				}
				else 
				{
					return false;
				}
			

		 }

	/********************************************************************************/

	/**对return_url的认证
	*return 验证结果：true/false
    */

		function return_verify($form_url)
		{
			//获取远程服务器ATN结果，验证是否是融宝支付服务器发来的请求
			if($this->transport == "https") 
			{
				$veryfy_url = $this->gateway. "service=notify_verify" ."&merchant_ID=" .$this->merchant_ID. "&notify_id=".$_GET["notify_id"];
			} 
			else 
			{
				$veryfy_url = $this->gateway. "merchant_ID=".$this->merchant_ID."&notify_id=".$_GET["notify_id"];
			}
	
			$veryfy_result =file_get_contents($veryfy_url);

			 //生成签名结果

				$post          = para_filter($_GET);	    //对所有GET反馈回来的数据去空
				$sort_post     = arg_sort($post);		    //对所有GET反馈回来的数据排序
				$this->mysign  = build_mysign($sort_post,$this->_key,$this->sign_type);    //生成签名结果
		
		
				//判断veryfy_result是否为ture，生成的签名结果mysign与获得的签名结果sign是否一致
				//$veryfy_result的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
				//mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
				if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"]) 
				{   
					return true;
				}
				else 
				{
					return false;
				}
			
		}

		/********************************************************************************/

    /**获取远程服务器ATN结果
	*$url 指定URL路径地址
	*return 服务器ATN结果集
     */
	
    function get_verify($url,$time_out = "60") 
	{
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") 
		{
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } 
		else 
		{   
            $transports = "tcp://";
            $urlarr["port"] = "18183";
        }
        $fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
        if(!$fp) 
		{
            die("ERROR: $errno - $errstr<br />\n");
        } 
		else 
		{
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) 
			{
                $info[]=@fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            return $info;
        }
      }


}

?>