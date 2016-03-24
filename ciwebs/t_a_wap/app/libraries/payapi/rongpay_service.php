<?php
/**
 *功能：融宝支付接口公用函数
 *详细：该页面是请求、通知返回两个文件所调用的公用函数核心处理文件，不需要修改
 *修改日期：2012-01-04
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究融宝支付接口使用，只是提供一个参考。

*/
if (!class_exists('rongpay_function'))
    include 'rongpay_function.php';
class Rongpay_service
{
	var $gateway;			//网关地址
    var $_key;				//安全校验码
    var $mysign;			//签名结果
    var $sign_type;			//签名类型
    var $parameter;			//需要签名的参数数组
    var $charset;    //字符编码格式

	/**构造函数
	*从配置文件及入口文件中初始化变量
	*$parameter 需要签名的参数数组
	*$key 安全校验码
	*$sign_type 签名类型
    */

	function rongpay_sign($parameter,$key,$sign_type)
	{
        $this->gateway		= "https://epay.reapal.com/portal?";
        $this->_key  		= $key;
        $this->sign_type	= $sign_type;
        $this->parameter	= para_filter($parameter);

        //设定charset的值,为空值的情况下默认为GBK
        if($parameter['charset'] == '')
		{
            $this->parameter['charset'] = 'utf-8';
		}
        $this->charset   = $this->parameter['charset'];

        //获得签名结果
        $sort_array   = arg_sort($parameter);    //得到从字母a到z排序后的签名参数数组
        $this->mysign = build_mysign($sort_array,$key,$sign_type);
		return build_mysign($sort_array,$key,$sign_type);
    }
	/**
	 * 功能：构造表单提交HTML
	 * @param merchant_ID 合作身份者ID
	 * @param seller_email 签约融宝支付账号或卖家融宝支付帐户
	 * @param return_url 付完款后跳转的页面 要用 以http开头格式的完整路径，不允许加?id=123这类自定义参数
	 * @param notify_url 交易过程中服务器通知的页面 要用 以http开格式的完整路径，不允许加?id=123这类自定义参数
	 * @param order_no 请与贵网站订单系统中的唯一订单号匹配
	 * @param subject 订单名称，显示在融宝支付收银台里的“商品名称”里，显示在融宝支付的交易管理的“商品名称”的列表里。
	 * @param body 订单描述、订单详细、订单备注，显示在融宝支付收银台里的“商品描述”里
	 * @param total_fee 订单总金额，显示在融宝支付收银台里的“交易金额”里
	 * @param buyer_email 默认买家融宝支付账号
	 * @param input_charset 字符编码格式 目前支持 GBK 或 utf-8
	 * @param key 安全校验码
	 * @param sign_type 签名方式 不需修改
	 * @return 表单提交HTML文本
	 */
	function BuildForm($action,$form_url,$mysign,$sign_type)
	{
		//GET方式传递
        $sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$action."' method='post'>";
		//POST方式传递（GET与POST二必选一）
		//$sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$this->gateway."charset=".$this->parameter['charset']."' method='post'>";

        while (list ($key, $val) = each ($this->parameter))
		{
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<input type='hidden' name='form_url' value='".$form_url."'/>";
        $sHtml = $sHtml."<input type='hidden' name='sign' value='".$mysign."'/>";
        $sHtml = $sHtml."<input type='hidden' name='sign_type' value='".$sign_type."'/>";
		$sHtml = $sHtml."<input type='hidden' name='act' value='reapal'/>";
		//submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='融宝支付确认付款'></form>";
		$sHtml = $sHtml."<script>document.forms['rongpaysubmit'].submit();</script>";
        echo $sHtml;die;
	}
}
?>