<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/24 0024
 * Time: 13:08
 */
if (!class_exists('CryptDes'))
    include 'CryptDes.php';

class Befpay{

    public $useragent = 'Befpay PHPSDK v1.1x';
    public $connecttimeout = 30;
    public $timeout = 30;
    public $ssl_verifypeer = FALSE;

    //支付商户相关配置参数
    protected $_MERCHANTKEY;
    protected $_MERCHANTACCOUNT;

    //DES 加密类
    private $DES;

    /**
     * @param $merchantkey 商户加密key
     * @param $merchantaccount 商户号
     */
    public function __construct($merchantkey,$merchantaccount){
        $this->_MERCHANTKEY = $merchantkey;
        $this->_MERCHANTACCOUNT=$merchantaccount;
        $this->DES = new CryptDes($this->_MERCHANTKEY,"BEBE_PAY");
    }

    /**
     * 资金结算，目前只支持委托结算
     * @param $query
     * @return mixed
     * @throws bebepayException
     */
    public  function fundSettle($query){
        //结算类型 1自助结算 2委托结算
        $data['SAT']=$query['SAT'];
        //商户号
        $data['BN']=$this->_MERCHANTACCOUNT;

        //查询data
        $data['DATA']= $this->DES->DESEncrypt($query['DATA']);
        //data加密
        $data['SIGN']=  md5($query['DATA'].$this->_MERCHANTKEY);
        //post
        $return = $this->http('http://www.5dd.com/frontpage/payout', 'POST',http_build_query($data));
        if($this->http_info['http_code'] == 405)
            throw new bfpayException('此接口不支持使用POST方法请求',1003);

        // return $query['DATA'].'0564fsfs654fsf';
        return $return;
    }

    /**
     * 订单查询接口
     * @param array $query
     * @return mixed
     * @throws bebepayException
     */
    public function queryOrder($query=array()){
        //商户号
        $data['BN']=$this->_MERCHANTACCOUNT;
        //平台订单号
        $data['SN']=$query['SN'];
        //商户订单号
        $data['XN']=$query['XN'];
        //查询日期
        $data['DATE']=$query['DATE'];
        //签名值
        $data['SIGN']=md5($this->_MERCHANTACCOUNT.$this->_MERCHANTKEY);
        //get
        $return = $this->get('http://www.5dd.com/frontpage/OrderInfo',$data);

        return $return;
    }

    /**
     * @param $postData  支付相关参数
     * @return string  post str
     * @throws Exception
     */
    public function webPay($postData=array(),$m_okurl,$form_url){

        if(!is_array( $postData )){

            throw new ErrorException('参数必须为数组类型' , 1001);
        }
        if( empty( $postData ) ){

            throw new ErrorException('非法传入，数组不能为空！' , 1002);
        }
        $parameter = array(

            /* 业务参数 */
            'p1_md'  				 =>  $postData['p1_md'],
            'p2_xn'  				 =>  $postData['p2_xn'],
            'p3_bn'  				 =>  $postData['p3_bn'],
            'p4_pd'  				 =>  $postData['p4_pd'],
            'p5_name'  			 =>  $postData['p5_name'],
            'p6_amount'  			 =>  $postData['p6_amount'],
            'p7_cr'  				 =>  $postData['p7_cr'],
            'p8_ex'  				 =>  $postData['p8_ex'],
            'p9_url'  				 =>  $postData['p9_url'],
            'p10_reply'  			 =>  $postData['p10_reply'],
            'p11_mode'  			 =>  $postData['p11_mode'],
            'p12_ver'  			 =>  $postData['p12_ver']

        );
        $sign=$this->EncryptKey($parameter);
        $parameter['sign']=$sign;

        $param='';

        $param .= '<html>
					   <head>
					   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					   </head>
					   <body onLoad="document.payForm.submit();">
						正在跳转请勿关闭页面 ...
					   <form name="payForm" method="post" action="'.$m_okurl.'">';


        foreach($parameter AS $key => $value)
        {
            $param .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
        }
            $param .= '<input type="hidden" name="form_url" value="'.$form_url.'" />';
			$param .= '<input type="hidden" name="act" value="bfpay" />';
        return $param .= '</form></body></html>';

    }

	    public function web_tmp_Pay($postData=array(),$form_url){

        if(!is_array( $postData )){

            throw new ErrorException('参数必须为数组类型' , 1001);
        }
        if( empty( $postData ) ){

            throw new ErrorException('非法传入，数组不能为空！' , 1002);
        }
        $parameter = array(

            /* 业务参数 */
            'p1_md'  				 =>  $postData['p1_md'],
            'p2_xn'  				 =>  $postData['p2_xn'],
            'p3_bn'  				 =>  $postData['p3_bn'],
            'p4_pd'  				 =>  $postData['p4_pd'],
            'p5_name'  			 =>  $postData['p5_name'],
            'p6_amount'  			 =>  $postData['p6_amount'],
            'p7_cr'  				 =>  $postData['p7_cr'],
            'p8_ex'  				 =>  $postData['p8_ex'],
            'p9_url'  				 =>  $postData['p9_url'],
            'p10_reply'  			 =>  $postData['p10_reply'],
            'p11_mode'  			 =>  $postData['p11_mode'],
            'p12_ver'  			 =>  $postData['p12_ver']

        );
        $sign=$this->EncryptKey($parameter);
        $parameter['sign']=$sign;
		$parameter['act']=$befpay;
        $param='';

        $param .= '<html>
					   <head>
					   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					   </head>
					   <body onLoad="document.payForm.submit();">
						正在跳转请勿关闭页面 ...
					   <form name="payForm" method="post" action="'.$form_url.'">';


        foreach($parameter AS $key => $value)
        {
            $param .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
        }
            $param .= '<input type="hidden" name="form_url" value="'.$form_url.'" />';
        return $param .= '</form></body></html>';

    }

    /**
     * @return array
     * @throws Exception
     */
    public  function returnData(){
        return $this->DecryptData();
    }

    /**
     * @param $paymentData  支付所需参数
     * @return array 返回类型
     * @throws Exception
     */
    protected function EncryptKey($paymentData){

        $paymentStr='';

        foreach($paymentData as $key=>$value){

            $paymentStr.=$key.'='.$value.'&';
        }
       // $paymentSign=md5(rtrim($paymentStr,"&").'5abc70d02db6426bb52f7ef0c502dfdc');
        $paymentSign=md5(rtrim($paymentStr,"&").$this->_MERCHANTKEY);

        return $paymentSign;
    }


    /**
     * @return array  支付完毕后的参数
     * @throws Exception
     */
    protected  function DecryptData(){
        if(empty($this->_MERCHANTACCOUNT)){
            throw new Exception("Null merchantaccount",1001);  //商户号为空抛出异常
        }
        if(empty($_GET['sign'])){
            throw new Exception("System error",1002);  //返回的加密串为空，联系币付宝
        }

        $parameter = array(
            /* 返回参数 */
            'p1_md'  				 =>  $_GET['p1_md'],
            'p2_sn'  				 =>  $_GET['p2_sn'],
            'p3_xn'  				 =>  $_GET['p3_xn'],
            'p4_amt'  				 =>  $_GET['p4_amt'],
            'p5_ex'  			     =>  $_GET['p5_ex'],
            'p6_pd'  			     =>  $_GET['p6_pd'],
            'p7_st'  				 =>  $_GET['p7_st'],
            'p8_reply'  			 =>  $_GET['p8_reply']
        );
        $sign=$this->EncryptKey($parameter);

        if($sign != strtolower($_GET['sign'])){
            throw new Exception("invalid sign",1003);  //返回的sign和返回的参数加密的sign不相等
        }

        return $parameter;
    }

    /**
     *
     * @param string $url
     * @param string $method
     * @param string $postfields
     * @return mixed
     */
    protected function http($url, $method, $postfields = NULL) {
        $this->http_info = array();
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        $method = strtoupper($method);
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields))
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields))
                    $url = "{$url}?{$postfields}";
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        curl_close ($ci);
        return $response;
    }

    /**
     * Get the header info to store.
     */
    public function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }


    /**
     *
     * 使用GET的模式发出API请求
     *
     * @param string $type
     * @param string $method
     * @param array $query
     * @return array
     */
    protected function get($url,$query){
        $url .= '?'.http_build_query($query);
        $data = $this->http($url, 'GET');
        if($this->http_info['http_code'] == 405)
            throw new yeepayMPayException('此接口不支持使用GET方法请求',1003);
        return $data;
    }

}


