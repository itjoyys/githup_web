<?php
   //微信喜帖
    Class WeddingAction extends Action{
     //微信喜帖 
     Public function siteid(){
       $siteid= $_GET["_URL_"][3]; 
       $image_url=C("image_url");      
         $config=M('weddingconfig')->where(array('siteid' => $siteid))->select();
         $this->config=$config;
      
       $photo = M("weddingbg")->where(array('siteid' => $siteid))->select();
       for ($i=1; $i <7 ; $i++) { 
         $j='p'.$i;
         $j=$photo[0][$j];
       }
       $this->assign('p1',$p1);
        $this->assign('p2',$p2);
         $this->assign('p3',$p3);
          $this->assign('p4',$p4);
           $this->assign('p5',$p5);
            $this->assign('p6',$p6);



         $this->assign('image_url',$image_url);
          $this->assign('title',$config[0]['title']);
          $this->assign('name',$config[0]['name']);
          $this->assign('date',$config[0]['date']);
          $this->assign('address',$config[0]['address']);
          $this->assign('logo',$config[0]['logo']);
         $this ->display(index);
       
     }
     //相册加载
    Public function photo(){
        
         $siteid=$_SESSION['siteid']; 
         $photo=M('weddingphoto')->where(array('siteid' => $siteid))->select();
         $this->assign('photo',$photo);
         $this ->display();
    }       

   //微信留言提交处理
        Public function runmicrowall(){
           $data['name']=$_POST['name'];
           $data['siteid']=$_POST['siteid'];
           $data['content']=$_POST['content'];
           $data['date']=date('Y-m-d H:i:s');
           $User = M("microwall"); // 实例化User对象  

            $User->create(); // 创建数据对象    
            $User->add($data); // 写入用户数据到数据库
            $this->redirect('Index/Microwall/',array('siteid'=>$data['siteid']));

         
         }


}
?>
 