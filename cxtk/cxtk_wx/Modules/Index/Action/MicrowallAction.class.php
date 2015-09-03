<?php
   //微信墙留言
    Class MicrowallAction extends Action{
     //微信墙留言设置  
     Public function siteid(){
       $siteid= $_GET["_URL_"][3]; 
       //$image_url=C("image_url");
         import("ORG.Util.Page");
         $num=M('microwall')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $wall=M('microwall')->where(array('siteid' => $siteid))->limit($limit)->order('id desc')->select();
         $this->wall=$wall;
         $this->page=$page->show();
         $this->assign('siteid',$siteid);
         $this ->display(index);
       
     }
     //微信发布留言
    Public function microwall(){
         $siteid= $_GET["_URL_"][3]; 
         //$siteid=$_SESSION['siteid']; 
         $this->assign('siteid',$siteid);
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

         //微信留言ajax提交
       Public function AJAX_POST(){
          if (IS_AJAX === false) return false;
          $data['name']=$_POST['name'];
          $data['siteid']=$_POST['siteid'];
          $data['content']=$_POST['content'];
          $data['date']=date('Y-m-d H:i:s');
          if ($data['name']!="" && $data['content']!="") {
             $User=M('microwall');
             $User->create();
             $User->add($data);
          }
           $result=array(
            'status'=>true,
            'data'=>$data
            );   
         exit(json_encode($result));

       }
}
?>
 