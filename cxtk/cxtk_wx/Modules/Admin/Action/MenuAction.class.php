<?php 
/*
  微信自定义菜单控制器
*/
 Class MenuAction extends CommonAction{
    //微信设置视图
    Public function Index(){
       $siteid=$_SESSION['siteid'];
       $meau=M('menuappid')->where(array('siteid' => $siteid))->select();
       $this->assign('appid',$meau[0]['appid']);
       $this->assign('appsecret',$meau[0]['appsecret']);
       $this->assign('siteid',$siteid);
       $this ->display();
      
    }

    Public function runappid(){
        
          $siteid=$_POST['siteid'];
            $User = M("menuappid"); // 实例化User对象  
            $list = M("menuappid")->where(array('siteid' => $siteid))->select();

           if (!$list) {
            $User->create(); // 创建数据对象           
            $User->appid = $_POST['appid'];    
            $User->appsecret = $_POST['appsecret'];  
            $User->siteid = $_POST['siteid'];   
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          
             
           }else{

            $User->create(); // 创建数据对象   
            $User->appid = $_POST['appid']; 
            $User->appsecret = $_POST['appsecret'];        
            $User->where(array('siteid'=>$siteid))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           
           }
    	

    }

    Private function getAccessToken() //获取access_token
     {
           $siteid=$_SESSION['siteid'];
           $menu=M('menuappid')->where(array('siteid' => $siteid))->find();
           define(AppId, $menu['appid']);//定义AppId
 
           define(AppSecret, $menu['appsecret']);//定义AppSecret
 
         $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".AppId."&secret=".AppSecret;

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//不输出内容
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         $result =  curl_exec($ch);
         curl_close ($ch);

         $data = $result ;//通过自定义函数getCurl得到https的内容
         $resultArr = json_decode($data, true);//转为数组
         return $resultArr["access_token"];//获取access_token
        
     }
  //发布菜单到服务器
  public function creatMenu() {
    import('Class.Category', APP_PATH);
     $siteid=$_SESSION['siteid'];
     $menu = M("menu")->where(array('siteid' => $siteid))->order('sort desc')->select();
     $menu =Category::menuLayer($menu); 
     $accessToken = $this->getAccessToken();//获取access_token
     $menuPostUrl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;//POST的url

    $menu=$this->Deletearray($menu);
	
	
   // p($menu);

    $result = $this->vpost($menuPostUrl,$menu);
    echo $result;
 }

 //删除数组里面的字段
 function Deletearray($array){
 // import('Class.Category', APP_PATH);
      // $array = M("menu")->where(array('siteid' => '59'))->select();
    // $array =Category::menuLayer($array); 
     $data = '{"button":[';
     $k=1; 
     $m=count($array)-1; 
   foreach ($array as $k => &$v) { 
 
   if (count($v['sub_button'])==0) {
     // $data.='{"name":"'.$v['name'].'",';
      if ($v['type']=="view") {
           $data.='{"type":"view","name":"'.$v['name'].'","url":"'.$v['data'].'"';     
        }elseif ($v['type']=="click") {
           $data.='{"type":"click","name":"'.$v['name'].'","key":"'.$v['data'].'"';
           
        } 
        $data.='},';
   }else{
        $data.='{"name":"'.$v['name'].'",';
        $data.='"sub_button":[';
        $i=count($v['sub_button']);
        $j=1;
        foreach ($v['sub_button'] as $dk => &$dv) {

           if ($i==$j) {
              if ($dv['type']=="view") {
                  $data.='{"type":"view","name":"'.$dv['name'].'","url":"'.$dv['data'].'"}';
              }elseif ($dv['type']=="click") {
                  $data.='{"type":"click","name":"'.$dv['name'].'","key":"'.$dv['data'].'"}';
              }

              if ($k==$m AND $j==$i) {
                 $data.=']}';
              }else{
                 $data.=']},';
              }
            
           }else{

             if ($dv['type']=="view") {
                  $data.='{"type":"view","name":"'.$dv['name'].'","url":"'.$dv['data'].'"},';
              }elseif ($dv['type']=="click") {
                  $data.='{"type":"click","name":"'.$dv['name'].'","key":"'.$dv['data'].'"},';
              }
             
           }
		    $j++;
         //  p($j);
		    // p($i);
         
        }
       // $data.=']},';
      //  $data.='},';
   }
   $k++;

}
$data.=']}';

   return $data;
 }

 //添加自定义菜单
 Public function Custommenu(){
    $siteid=$_SESSION['siteid'];

    import('Class.Category', APP_PATH);
    $menu = M("menu")->where(array('siteid' => $siteid))->order('sort DESC')->select();
    $menu = Category::menuLevel($menu); 
    $this->assign('siteid',$siteid);
    $this->assign('menu',$menu);
    $this->display();

 }

 //提交自定义菜单处理
 Public function runCustommenu(){
   $pid= I('id','0','intval');
   $data['siteid']=$_SESSION['siteid'];
   $data['pid']=$pid;
   $data['name']=$_POST['name'];
   $data['type']=$_POST['type'];
   $data['sort']=$_POST['sort'];
   $data['data']=$_POST['data'];

   $User = M("menu"); // 实例化User对象  
   $User->create(); // 创建数据对象            
   $User->add($data); // 写入用户数据到数据库
   $this->success("数据保存成功！");

 }

 //二级菜单添加
 Public function r_menu(){
     $pid= I('id','0','intval');
     $siteid=$_SESSION['siteid'];

     $this->assign('siteid',$siteid);
     $this->assign('pid',$pid);
     $this->display();

 }
 //自定义菜单修改
 Public function revise_menu(){
     $id=$_GET['id'];
	 $menu = M("menu")->where(array('id' => $id))->field('id,siteid,name,data,pid,sort,type')->find();
	 $this->assign('menu',$menu);
	 $this->display();
 
 }

 //二级菜单表单处理
 Public function addr_menu(){
   $tid= I('id','0','intval');
  if($tid==0){
   $data['siteid']=$_SESSION['siteid'];
   $data['pid']=$_POST['pid'];
   $data['name']=$_POST['name'];
   $data['type']=$_POST['type'];
   $data['sort']=$_POST['sort'];
   $data['data']=$_POST['data'];

   $User = M("menu"); // 实例化User对象  
   $User->create(); // 创建数据对象            
   $User->add($data); // 写入用户数据到数据库
   $this->success("数据保存成功！");
  

  }else{
   $data['name']=$_POST['name'];
   $data['type']=$_POST['type'];
   $data['sort']=$_POST['sort'];
   $data['data']=$_POST['data'];


   $User = M("menu"); // 实例化User对象  
   $User->create(); // 创建数据对象            
   $User->where(array('id'=>$_POST['id']))->save($data); // 写入用户数据到数据库
   $this->success("数据更新成功！");
  
  }
   



 }


 function vpost($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包x
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据

}

   

   
      Public function deletemenu(){
          $id= I('id','0','intval');  
             if( M('menu')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
             
    }
  

 }

?>