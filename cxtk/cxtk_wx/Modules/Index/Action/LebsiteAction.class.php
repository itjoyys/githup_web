<?php 
     //微信网站盛昌带权限
 Class LebsiteAction extends Action{

    //获取站点参数
    Public function siteid(){
       
        $siteid= $_GET["_URL_"][3]; 
        $image_url=C("image_url");

        if ($_SESSION['openid']) {
           $openid=$_SESSION['openid'];
        }else{
           $openid= $_GET['openid']; 
           if ($openid=="") {
              echo "请从首页进入";
           }else{


           $websiteconfig=M('websiteconfig')->where(array('siteid' => $siteid))-> select();
    
           $this->assign('title',$websiteconfig[0]['title']);
           $this->assign('Keywords',$websiteconfig[0]['Keywords']);
           $this->assign('Description',$websiteconfig[0]['Description']);
        
        
           session('siteid',$siteid);
           session('openid',$openid);
           session('w_list_tpl',$w_list_tpl);
           session('w_detail_tpl',$w_detail_tpl);
      
           $column= M('websitecolumn') ->where(array('siteid' => $siteid))-> order('sort ASC')-> select();
           $cnum=count($column);
        for ($i=0; $i < $cnum; $i++) { 
          if ($column[$i]['url']=='' && $column[$i]['classid']=='1' ) {
             //栏目地址
             $column[$i]['url']=$image_url.'/index.php/Index/Lebsite/lebsitelist/pid'.'/'.$column[$i]['id'];
           
          }elseif($column[$i]['url']=='' && $column[$i]['classid']=='0'){
           $column[$i]['url']=$image_url.'/index.php/Index/Lebsite/lebsitedetail/pid'.'/'.$column[$i]['id'];

          }
        }
     
         $this->cnum=$cnum;
         $this->column=$column;
         $this->assign("column", $column);
         $this->assign("cnum", $cnum);

       
      
         //幻灯片读取
         $this->slide=$slide=M('websiteslide')->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $this->assign("slide", $slide);
         $this->assign("image_url", $image_url);

         $i=$websiteconfig[0]['index_tpl'];

         session('copyright', $shopconfig[0]['title']);
         $this->display(index);



           }
        }
        
      
       

      
       

    }


    //微信网站列表页
    Public function lebsitelist(){
      
      $openid=$_SESSION['openid'];
      $pid=$_GET['pid'];

      $column= M('websitecolumn') ->where(array('pid' => $pid))-> order('sort ASC')-> select();
      $level=M('level')->where(array('openid'=>$openid))->getField('level');
      
    

      
      $this->column=$column;
      $this->assign("column", $column);
      $this->assign("openid", $openid);
      $this->assign("ourl", $ourl);
     if ($level=="") {
       $this->display(w_order);
     }elseif ($level=='2') {
       $this->display(w_list);
     }else{
       echo "您无权访问";
     }
      
     
      
      
    }
    //微信网站详细页

      Public function websitedetail(){

      $id=$_GET['id'];
      $column= M('websitecolumn') ->where(array('id' => $id))-> select();
      $content=M('websitecontent')->where(array('cid' => $id))-> select(); 
      $this->column=$column;
      $this->content=$content;
      $this->assign("title", $column[0]['title']);
      $this->assign("content", $content[0]['content']);
     
      $this->display(w_detail);
    }

    //表单提交
    Public function ajaxorder(){
      
   
     $openid=$_SESSION['openid'];
     $siteid=$_SESSION['siteid'];
     $data['openid']=1234;

    
     $data['name']=$_POST['order_name'];
     $data['tel']=$_POST['order_tel'];   
     $data['siteid']=$siteid;
     if ($data['name']==""||$data['tel']=="") {
       
        $this->error('请填写完整信息');
     }else{
       $User=M('level');
         $User->create();
        $User->add($data);
  
       $this->redirect('Lebsite/index', array('siteid' => $siteid));
      
         
     }
        
    }
  


    //在线预约
   Public function wbook(){
       $siteid= $_GET["_URL_"][3]; 
       $image_url=C("image_url");
       $booking=M('websitebooking')->where(array('siteid' => $siteid))->order('sort ASC')->select();
       $booki=M('websitebooki')->where(array('siteid' => $siteid))->select();
       session('siteid',$siteid);
       $num=count($booking);
       //下拉选项截取处理
       for ($i=0; $i <$num ; $i++) { 
           if ($booking[$i]['wfield']==2) {
                $options= explode('|', $booking[$i]['content']); 
                $booking[$i]['content']=$options;         
           }
       }

       $this->assign('booking',$booking);
       $this->assign('image_url',$image_url);
       $this->assign('siteid',$siteid);
       $this->assign('instructions',$booki[0]['instructions']);
       $this->assign('tel',$booki[0]['tel']);
       $this->assign('address',$booki[0]['address']);
       $this->assign('url',$booki[0]['url']);
       //p($booking);
       $this->display();
   }
   //在线预约表单提交
    Public function runwbook(){

       $siteid=$_SESSION['siteid'];
       $num=count($_POST);
       $keys=array_keys($_POST);
       $ckey=array_values($_POST);
        $User = M("websitebook"); // 实例化User对象  
        $User->create(); // 创建数据对象
       if(in_array('', $_POST)){
        $this->error('请填写完整信息!');
       }else{
           
           for ($i=0; $i < $num; $i++) { 
            $User->fielid = $keys[$i]; 
            $User->siteid = $siteid; 
            $User->content =$ckey[$i]; 
            $User->fid =$lastid; 
            $User->add(); // 写入用户数据到数据库
            $this->success("订单提交成功！");
            if ($lastid=='') {
              $lastid = mysql_insert_id();
            }     
            }

       }

   
   }


 }

 

?>