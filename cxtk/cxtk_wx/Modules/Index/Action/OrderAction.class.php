<?php



  Class OrderAction extends Action{

    
     public function _initialize() {
        vendor('Alipay.lib.Corefunction');
        vendor('Alipay.lib.Md5function');
        vendor('Alipay.lib.Notify');
        vendor('Alipay.lib.Submit');    
    }


  	//订单确认

  	Public function index(){
         
        $Cart=$_SESSION['Cart'];
         if ($_SESSION['address']=="") {
       
         	 $this->redirect('Index/Order/address');

         }else{
         	 
           $this->redirect('Index/Order/order');
         }


  		//$this->display();
  	}

   Public function address(){
    $this->display(address);
   }

    //添加收货地址

  	Public function addaddress(){
      // $name=$_POST['name'];
        $address=$_POST['address'];
      // $mobile=$_POST['mobile'];
        $as1= $_POST["s_province"];
        $as2= $_POST["s_city"];
        $as3= $_POST["s_county"];

        if ($as1!=$as2) {

        	$address=$as1.$as2.$as3.$address;
        	
        }else{

        	$address=$as1.$as3.$address;

        }



      $Add=array('name' => $_POST['name'], 
        'address' => $address, 
        'mobile' => $_POST['mobile']

        );

      session('address',$Add);

       // p($_POST);
       // p($_SESSION);
  	
       $this->redirect('Index/Order/index');
  		// $this->display(address);
  	}



  	//订单确定
  	Public function order(){
            $Cart=$_SESSION['Cart'];
  		        
  		      $name=$_SESSION['address']['name'];
		        $mobile=$_SESSION['address']['mobile'];
		        $address=$_SESSION['address']['address'];

		        $this->assign("image_url", 'http://www.weipan.wx0571.com/weipan');   	
		        $this->assign('name',$name);
		        $this->assign('mobile',$mobile);
		        $this->assign('address',$address);

		        $this->assign('Cart',$Cart);
            $address1=preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}'.

                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,3}).*#s',

                       '$1',$address);

            $address2=preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}'.

                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,4}).*#s',

                       '$1',$address);
   
            $User = M("wuliu"); // 实例化User对象

            $siteid=$_SESSION['siteid'];
            $i_pay=M('zpay')->where(array('siteid'=>$siteid))->select();
            if ($i_pay!="") {
              $pay[0]['pay']="货到付款";
              $pay[1]['pay']="支付宝付款";
               
            }else{
               $pay[0]['pay']="货到付款";
            }


            $map['siteid']=$siteid;
            $map['area']=array('in',"$address1,$address2");
            $freight = $User->where($map)->getField('freight');
            $tprice=M('shop_config')->where(array('siteid'=>$siteid))->getField('tprice');
     
            $price=0;
     
            foreach($Cart as $k=>$v){
              $price += $v['tailprice'];
            }

          if ($price < $tprice) {
             $price=$price+$freight;
          }else{
            $freight='免运费';
          }
     
          $this->assign('tailprice',$price);
          $this->assign('freight',$freight);
          $this->assign('pay',$pay);

		       $this->display();
       
  	}


    //截取utf8字符串

    function utf8Substr($str, $from, $len){

    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.

                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',

                       '$1',$str);

      }


  	//订单提交
  	Public function addorder(){

        

        $Pay= $_POST["ddlPay_Type"];
         //生成随机20位订单编号
         $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
         $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));

      $price=0;
      $Cart=$_SESSION['Cart'];
      foreach($Cart as $k=>$v){
        $price += $v['tailprice'];
        $User=M('orderdetails');
        $User->create();
        $User->siteid=$_SESSION['siteid'];
        $User->orderid=$orderSn;
        $User->goodsid=$v['goodsid']; 
        $User->goodsname=$v['title'];
        $User->goodsnum=$v['buynum'];
        $User->goodsprice=$v['tailprice'];
        $User->add();
      }
    

        if ($Pay==1) {
            //货到付款提交订单
            $User = M("order"); // 实例化User对象  
            $User->create(); // 创建数据对象 
     
            $User->orderid = $orderSn;
            session('orderid',$orderSn);
            $User->name = $_SESSION['address']['name'];
            $User->siteid = $_SESSION['siteid']; 
            $User->openid= $_SESSION['openid'];
            $User->address = $_SESSION['address']['address']; 
            $User->phone = $_SESSION['address']['mobile']; 
            $User->pay = $Pay; 
            $User->date = date("Y-m-d H:i:s");
            $User->tailprice = $price;  
             $User->add(); // 写入用户数据到数据库
             $this->success("订单提交成功！");

             $this->redirect('Index/Order/success_order');

        }elseif ($Pay==2) {
        	//支付宝支付提交订单

            $User = M("order"); // 实例化User对象  
            $User->create(); // 创建数据对象 
     
            $User->orderid = $orderSn;
            session('orderid',$orderSn);
            $User->name = $_SESSION['address']['name']; 
            $User->siteid = $_SESSION['siteid']; 
            $User->openid = session_id();  
            $User->address = $_SESSION['address']['address']; 
            $User->phone = $_SESSION['address']['mobile']; 
            $User->pay = $Pay; 
            $User->date = date("Y-m-d H:i:s");
            $User->tailprice =$price; 
            $data['orderid']=$orderSn;
            $data['name']="支付宝支付";
            $data['tailprice']=$price;
            $User->add(); // 写入用户数据到数据库
          //  $this->success("订单提交成功！");
            $this->doalipay($data);
          



        	
        }else{

        	 echo "请选择支付方式";
        	 $this->redirect('Index/Order/order');
        }

  	}

    Public function success_order(){

    	  $orderid=$_SESSION['orderid'];
        $siteid=$_SESSION['siteid'];
    	  $Cart=session('Cart');
      
        $price=0;

        foreach($Cart as $k=>$v){
				$price += $v['tailprice'];
		  	}


        $this->assign('orderid',$orderid);
        $this->assign('siteid',$siteid);
    	  $this->assign('tailprice',$price);
        unset($_SESSION['Cart']);
    	  $this->display();
    	
    }



     public function doalipay($data){ 
       //这里我们通过TP的C函数把配置项参数读出，赋给$alipay_config；
         $alipay_config=C('alipay_config');  

        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
        $notify_url = C('alipay.notify_url'); //服务器异步通知页面路径
        $return_url = C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填
        $out_trade_no =  $data['orderid'];//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $subject = $data['name'];  //订单名称 //必填 通过支付页面的表单进行传递
        $total_fee = $data['tailprice'];   //付款金额  //必填 通过支付页面的表单进行传递
        $body = $_POST['ordbody'];  //订单描述 通过支付页面的表单进行传递
        $show_url = $_POST['ordshow_url'];  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址 
        /************************************************************/
    
        //构造要请求的参数数组，无需改动
    $parameter = array(
        "service" => "create_direct_pay_by_user",
        "partner" => trim($alipay_config['partner']),
        "payment_type"    => $payment_type,
        "notify_url"    => $notify_url,
        "return_url"    => $return_url,
        "seller_email"    => $seller_email,
        "out_trade_no"    => $out_trade_no,
        "subject"    => $subject,
        "total_fee"    => $total_fee,
        "body"            => $body,
        "show_url"    => $show_url,
        "anti_phishing_key"    => $anti_phishing_key,
        "exter_invoke_ip"    => $exter_invoke_ip,
        "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;

      
    }


    function notifyurl(){
                //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
               //验证成功
                   //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
           $out_trade_no   = $_POST['out_trade_no'];      //商户订单号
           $trade_no       = $_POST['trade_no'];          //支付宝交易号
           $trade_status   = $_POST['trade_status'];      //交易状态
           $total_fee      = $_POST['total_fee'];         //交易金额
           $notify_id      = $_POST['notify_id'];         //通知校验ID。
           $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
           $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；
          $parameter = array(
             "out_trade_no"     => $out_trade_no, //商户订单编号；
             "trade_no"     => $trade_no,     //支付宝交易号；
             "total_fee"     => $total_fee,    //交易金额；
             "trade_status"     => $trade_status, //交易状态
             "notify_id"     => $notify_id,    //通知校验ID。
             "notify_time"   => $notify_time,  //通知的发送时间。
             "buyer_email"   => $buyer_email,  //买家支付宝帐号；
           );
           if($_POST['trade_status'] == 'TRADE_FINISHED') {
                       //
           }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {                           if(!checkorderstatus($out_trade_no)){
               orderhandle($parameter); 
                           //进行订单处理，并传送从支付宝返回的参数；
               }
            }
                echo "success";        //请不要修改或删除
         }else {
                //验证失败
                echo "fail";
        }    
    }
    
    /*
        页面跳转处理方法；
        这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
        */
    function returnurl(){
                //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config=C('alipay_config');
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
        $trade_no       = $_GET['trade_no'];          //支付宝交易号
        $trade_status   = $_GET['trade_status'];      //交易状态
        $total_fee      = $_GET['total_fee'];         //交易金额
        $notify_id      = $_GET['notify_id'];         //通知校验ID。
        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
            
        $parameter = array(
            "out_trade_no"     => $out_trade_no,      //商户订单编号；
            "trade_no"     => $trade_no,          //支付宝交易号；
            "total_fee"      => $total_fee,         //交易金额；
            "trade_status"     => $trade_status,      //交易状态
            "notify_id"      => $notify_id,         //通知校验ID。
            "notify_time"    => $notify_time,       //通知的发送时间。
            "buyer_email"    => $buyer_email,       //买家支付宝帐号
        );
        
 if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
        if(!checkorderstatus($out_trade_no)){
             orderhandle($parameter);  //进行订单处理，并传送从支付宝返回的参数；
    }
        $this->redirect(C('alipay.successpage'));//跳转到配置项中配置的支付成功页面；
    }else {
        echo "trade_status=".$_GET['trade_status'];
        $this->redirect(C('alipay.errorpage'));//跳转到配置项中配置的支付失败页面；
    }
 }else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "支付失败！";
    }
 }
  	
   
    
}



?>