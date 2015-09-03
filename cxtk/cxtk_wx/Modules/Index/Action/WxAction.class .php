<?php 
/*
  会员中心控制器
*/


 Class WxAction extends Action{
    //会员中心视图

    <?php
$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='123456';
$mysql_database='wx_shop';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database,$conn);
mysql_query('SET NAMES utf8');    
$tokenid=$_GET['siteid'];
$tokensql = "SELECT token FROM comm_microtoken where siteid='$tokenid'";
$result_t = mysql_query($tokensql,$conn);
$row = mysql_fetch_row($result_t); 
//$keyword = mysql_fetch_row($result_k);
//$a=mysql_num_rows($result_c);

define("TOKEN", "$row[0]");//与管理平台的TOKEN设置一致
//define('TOKEN','Itachi1989'); //定义TOKEN 常量 在微信公众平台上验证的时候就填写这个值 imtoken
$WeiXin = new WeiXin();  //实例化 WeiXin 类 
$WeiXin->Send(); //请求该接口文件时 执行 Send 函数

class WeiXin {
  function __construct(){
    $key = $this->checkSignature();
    if($key != true) exit; //根据TOKEN 判断来路
  }
  
  public function Send(){
    $postStr = file_get_contents("php://input");
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    $fu = (string)$postObj->FromUserName;  //取出用户的账号
    $tu = (string)$postObj->ToUserName;      //公众账号
    
    $mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='123456';
$mysql_database='wx_shop';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database,$conn);

mysql_query('SET NAMES utf8');

$tokenid=$_GET['siteid'];
$concernsql = "SELECT * FROM comm_microconcern where siteid='$tokenid'";
$result_c = mysql_query($concernsql,$conn);

        $imageurl= "http://www.weipan.wx0571.com/weipan";
    $i=0;
            $openid="?openid=$fu";
      while ( $list=mysql_fetch_array($result_c)){
        
        $msg[$i]['title'] = $list['title'];
        $msg[$i]['desription'] = $list['intro'];
        $msg[$i]['image'] = $imageurl.$list['img'];
        $msg[$i]['turl'] = $list['url'].$openid;
        $i++;
       } 
                $textTpl = "<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Content><![CDATA[%s]]></Content>
              <FuncFlag>0</FuncFlag>
              </xml>";
              
            $num=count($msg);
          for ($i = 0; $i <$num; $i++){
            $str1 = "title$i";
            $str2 = "desription$i";
            $str3 = "image$i";
            $str4 = "turl$i";
                    
                $$str1 =$msg[$i]['title']; 
              $$str2=$msg[$i]['desription']; 
              $$str3 =$msg[$i]['image']; 
              $$str4=$msg[$i]['turl'];
          
            $Content.="<item>
              <Title><![CDATA[%s]]></Title>
              <Description><![CDATA[%s]]></Description>
              <PicUrl><![CDATA[%s]]></PicUrl>
              <Url><![CDATA[%s]]></Url>
              </item>";
                      
          }
          
          $picTpl = "<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <ArticleCount>%s</ArticleCount>
              <Articles>
                ".$Content."                                                  
              </Articles>
              <FuncFlag>1</FuncFlag>
              </xml> ";
    $MsgType = (string)$postObj->MsgType; //取出消息类型
    switch($MsgType){
       case 'text': //文字消息类型
          $msgType = "news";
            //  $resultStr = sprintf($picTpl, $fu, $tu, $time, $msgType,$num,$title0,$desription0,$image0,$turl0,
        //  $title1,$desription1,$image1,$turl1,$title2,$desription2,$image2,$turl2,$title3,$desription3,$image3,$turl3,
        //  $title4,$desription4,$image4,$turl4,$title5,$desription5,$image5,$turl5);
               // echo $resultStr;
        $this->news($fu,$tu,$msg);
       break;      
       case 'event': //事件消息类型
        $event = $postObj->Event;   //取出事件内容
        $eventKey = $postObj->EventKey; //取出事件标识
        switch($event){
           case 'subscribe':  //如果为 订阅 事件
                          $msgType = "news";        
              $resultStr = sprintf($picTpl, $fu, $tu, $time, $msgType,$num,$title0,$desription0,$image0,$turl0,
              $title1,$desription1,$image1,$turl1,$title2,$desription2,$image2,$turl2,$title3,$desription3,$image3,$turl3,
              $title4,$desription4,$image4,$turl4,$title5,$desription5,$image5,$turl5);
              echo $resultStr;            
          break;
        }
        break;
      default:
        //默认执行接口验证方法
        $this->valid();
       break;
    
    }   
     
  }
  
  //验证接口的方法 也可直接 echo $_GET["echostr"]; TOKEN 任意设置。
  public function valid(){
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
          echo $echoStr;
          exit;
        }
    }
  //检查TOKEN是否一致 可用来检测请求来路是否为微信
  private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];  
    $token = TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    if( $tmpStr == $signature ){
      return true;
    }else{
      return false;
    }
  }
  
  
    function news($fu,$tu,$data,$flag = 0){
    $num = count($data);
    if($num > 1){
      $add = $this->news_add($data);
      $tpl = " <xml>
           <ToUserName><![CDATA[".$fu."]]></ToUserName>
           <FromUserName><![CDATA[".$tu."]]></FromUserName>
           <CreateTime>".$_SERVER['REQUEST_TIME']."</CreateTime> 
           <MsgType><![CDATA[news]]></MsgType> 
           <Content><![CDATA[%s]]></Content> 
           <ArticleCount>".$num."</ArticleCount> 
           <Articles> 
           ".$add."
           </Articles> 
           <FuncFlag>".$flag."</FuncFlag> 
           </xml> ";
      echo $tpl;
    }else{
      $tpl = " <xml>
           <ToUserName><![CDATA[".$fu."]]></ToUserName>
           <FromUserName><![CDATA[".$tu."]]></FromUserName>
           <CreateTime>".$_SERVER['REQUEST_TIME']."</CreateTime> 
           <MsgType><![CDATA[news]]></MsgType> 
           <Content><![CDATA[%s]]></Content> 
           <ArticleCount>1</ArticleCount> 
           <Articles> 
           <item> 
           <Title><![CDATA[".$data[0]['title']."]]></Title> 
           <Description><![CDATA[".$data[0]['intro']."]]></Description> 
           <PicUrl><![CDATA[".$data[0]['pic']."]]></PicUrl> 
           <Url><![CDATA[".$data[0]['url']."]]></Url> 
           </item>
           </Articles> 
           <FuncFlag>".$flag."</FuncFlag> 
           </xml> ";
      echo $tpl;
    }
  }
  
  function news_add($data){
    $add  = "";
      foreach ($data as $k){
      $add  .= "<item> 
         <Title><![CDATA[".$k['title']."]]></Title> 
         <Description><![CDATA[".$k['intro']."]]></Description> 
         <PicUrl><![CDATA[".$k['pic']."]]></PicUrl> 
         <Url><![CDATA[".$k['url']."]]></Url> 
         </item>  ";
      }
      return $add;
  }
  
  
}

?>