<?php 
/*
  微信接口文件
*/

define('TOKEN','Itachi'); //定义TOKEN 常量 在微信公众平台上验证的时候就填写这个值 imtoken
$WeiXin = new WeiXin();  //实例化 WeiXin 类 
$WeiXin->Send(); //请求该接口文件时 执行 Send 函数

class WeiXin {
    function __construct(){
        $key = $this->checkSignature();
        if($key != true) exit; //根据TOKEN 判断来路
    }
    public function Send(){

        $siteid= $_GET["_URL_"][3]; 

        session('siteid',$siteid);
        $microtoken=M('microtoken')->where(array('siteid' => $siteid))-> select();

        //获取输入流并取出主要对象的值
        $postStr = file_get_contents("php://input");
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fu = (string)$postObj->FromUserName;  //取出用户的账号
        $tu = (string)$postObj->ToUserName;      //公众账号
        $MsgType = (string)$postObj->MsgType; //取出消息类型
          //根据消息类型进入相应操作
          switch($MsgType){
              case 'text': //文字消息类型
                $content = trim($postObj->Content); // 取出消息内容
                    //这里就是用户输入了文本信息
                    //根据发送的内容回复相应的内容
                    switch($content){
                       case '0': //0-公司简介
                          $this->txt($fu,$tu,'杭州微盘信息技术有限公司欢迎您！<br/>地址：杭州拱墅区新文路33号<br/>咨询电话：0571—58118926');
                       break;
//                     case '1': //1-在线留言或者预约
//                        $this->txt($fu,$tu,'您可以在此留言或者在线预约，在线留言内容中一定要包含手机或者电话，这样方便我们与你您联系，预约回复指令格式：姓名 年龄 病 电话或手机');
//
//                          if($content!=""){
//                                $sql="INSERT INTO ns_yy (content, inputtime) VALUES ('{$content}', '{$_SERVER['REQUEST_TIME']}')";
//                                $rs=mysql_query($sql);
//                                if(mysql_affected_rows()>=1){
//                                    echo "信息保存成功！稍候我们会联系您！";
//                                }else{
//                                    echo "错误，请联系0377-63876387";
//                                } 
//                          }
//                          
//                     break;
//                       case '2': //2-查电话
//                          $this->txt($fu,$tu,'请输入你要查询的科室名称，建议模糊查询，如：烧伤');
//                       break;
//                       case '3': //3-查科室
//                          $this->txt($fu,$tu,'请输入你要查询的科室名称，建议模糊查询，如：烧伤');
//                       break;
                       case '首页': //4-查医生
                    $msg[0]['title'] = '杭州微盘信息技术有限公司';
                    $msg[0]['intro'] = '点击进入微信生意通';
                    $msg[0]['pic'] ='http://weipan.wx0571.com/images/lianxi.jpg' ; //图片URL 这里是返回发送的原图 URL 要使用绝对完整地址
                    $msg[0]['url'] = 'http://weipan.wx0571.com/index.html'; //这里是超链接
                   $this->news($fu,$tu,$msg);
                       break;
//                       case '5': //5-联系我们
//                          $this->txt($fu,$tu,'如果您有任何问题，可随时联系我们。<br/>24小时健康咨询：0377-63876387<br/>医院办公室：0377-63876969<br/>人力资源部(招聘)：0377-63876927<br/>医院地址：南阳市中州西路988号<br/>公交线路：乘6路公交车到南石医院下车(中州西路与北京路交叉口向西500米)<br/>QQ:2801762870');
//                       break;
//                      break;
//                       case '6': //6-聊天机器人
//                        include('simsimi.php');
//                        $contentStr = SimSimi($content); //返回消息内容
//                          $this->txt($fu,$tu,$contentStr);
//                       break;

                       /*
                       case '2': //如果发送的是数字2 则回复以下内容
                        $msg['title']       =       '音频文件标题';
                        $msg['intro']   =   '音频文件简介';
                        $msg['url']     =   'http://facebowl.in/EverythingIsBetter.mp3'; //音频文件的绝对完整 url
                        $msg['hqurl']       =   'http://facebowl.in/EverythingIsBetter.mp3'; //音频文件的高清绝对完整 url  wifi下优先播放此url.
                        $this->audio($fu,$tu,$msg);  //回复一个可播放的音频消息
                        break;
                        */
                       default:  //如果是其他内容则回复
                          $this->txt($fu,$tu,'你发送的内容是。'.$content);
                       break;
                    }
              break;
              case 'image': //图像消息类型
                $pic    =   (string)$postObj->PicUrl; //取出图片url
                //回复一个图文 开始定义数组
                $msg[0]['title'] = '这里是标题';
                $msg[0]['intro'] = '简介';
                $msg[0]['pic'] = $pic; //图片URL 这里是返回发送的原图 URL 要使用绝对完整地址
                $msg[0]['url'] = '#'; //这里是超链接
                $this->news($fu,$tu,$msg);
                //如要回复多条图文，可对该二维数组赋值多个元素 如 $msg[0]    $msg[1]     $msg[2]     。
              break;
              case 'location': //地理位置消息类型
                $l_x = $postObj->Location_X; //取出 x 坐标
                $l_y = $postObj->Location_Y; //取出 y 坐标
                $scale = $postObj->Scale; //取出 缩放等级
                $lable = $postObj->Label; //取出 位置信息
                //回复文字消息
                $this->txt($fu,$tu,'你所处位置是:'.$lable.'坐标为 X：'.$l_x.'Y：'.$l_y);
              break;
              case 'event': //事件消息类型
                $event = $postObj->Event;   //取出事件内容
                $eventKey = $postObj->EventKey; //取出事件标识
                switch($event){
                    case 'subscribe':  //如果为 订阅 事件
                    $msg[0]['title'] = '杭州微盘信息技术有限公司';
                    $msg[0]['intro'] = '点击进入微信生意通';
                    $msg[0]['pic'] ='http://weipan.wx0571.com/images/lianxi.jpg' ; //图片URL 这里是返回发送的原图 URL 要使用绝对完整地址
                    $msg[0]['url'] = 'http://weipan.wx0571.com/index.html'; //这里是超链接
                    $this->news($fu,$tu,$msg);

                        
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
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
/* 
    以下是回复消息XML结构方法
    图文使用二维数组
*/
    function txt($fu,$tu,$content,$flag = 0){
        $tpl    =   "<xml> 
                <ToUserName><![CDATA[".$fu."]]></ToUserName> 
                <FromUserName><![CDATA[".$tu."]]></FromUserName> 
                <CreateTime>".$_SERVER['REQUEST_TIME']."</CreateTime> 
                <MsgType><![CDATA[text]]></MsgType> 
                <Content><![CDATA[".$content."]]></Content> 
                <FuncFlag>".$flag."</FuncFlag>
                </xml>";
        echo $tpl;
    }
    
    function news($fu,$tu,$data,$flag = 0){
        $num    =   count($data);
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
        $add    =   "";
            foreach ($data as $k){
            $add    .= "<item> 
                 <Title><![CDATA[".$k['title']."]]></Title> 
                 <Description><![CDATA[".$k['intro']."]]></Description> 
                 <PicUrl><![CDATA[".$k['pic']."]]></PicUrl> 
                 <Url><![CDATA[".$k['url']."]]></Url> 
                 </item>  ";
            }
            return $add;
    }
    
    function audio($fu,$tu,$data,$flag = 0){
        $tpl    =   "<xml>
                     <ToUserName><![CDATA[".$fu."]]></ToUserName>
                     <FromUserName><![CDATA[".$tu."]]></FromUserName>
                     <CreateTime>".$_SERVER['REQUEST_TIME']."</CreateTime>
                     <MsgType><![CDATA[music]]></MsgType>
                     <Music>
                     <Title><![CDATA[".$data['title']."]]></Title>
                     <Description><![CDATA[".$data['intro']."]]></Description>
                     <MusicUrl><![CDATA[".$data['url']."]]></MusicUrl>
                     <HQMusicUrl><![CDATA[".$data['hqurl']."]]></HQMusicUrl>
                     </Music>
                     <FuncFlag>".$flag."</FuncFlag>
                     </xml>";
        echo $tpl;
    }

?>