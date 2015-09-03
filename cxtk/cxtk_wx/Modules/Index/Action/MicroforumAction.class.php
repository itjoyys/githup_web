<?php 
/*
  微信论坛控制器
*/
 Class MicroforumAction extends Action{

    //获取站点参数
    Public function siteid(){
       
        $siteid= $_GET["_URL_"][3];        
        $openid= $_GET['openid']; 
        session('siteid',$siteid); 
        session('openid',$openid); 
        //p($_SESSION);
        $siteid=$_SESSION['siteid'];
        $openid='13457';
        import("ORG.Util.Page");
        $forumconfig=M('forumconfig')->where(array('siteid' => $siteid))-> select();
           $this->assign('name',$forumconfig[0]['name']);
           $this->assign('img',$forumconfig[0]['img']);       
         $num=M('forumpost')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $posts=M('forumpost')->where(array('siteid' => $siteid))->limit($limit)->select();
         $this->posts=$posts;
         $this->page=$page->show();
         $this->assign('posts',$posts); 
         $this->assign('openid',$openid);          
         $this->display(index);
       
    }

    //微信论坛帖子回复列表
    Public function forumlist(){
        $pid=$_GET['pid'];
        $siteid=$_SESSION['siteid'];
        import("ORG.Util.Page");
        $posts=M('forumpost')->where(array('id' => $pid))->select();
        //$replies=M('forumreplies')->where(array('pid' => $pid))->select();
        
         $num=M('forumreplies')->where(array('pid' => $pid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $replies=M('forumreplies')->where(array('pid' => $pid))->limit($limit)->select();
         $this->replies=$replies;
         $this->page=$page->show();
         $this->assign('name',$posts[0]['postname']);
         $this->assign('content',$posts[0]['content']);
         $this->assign('date',$posts[0]['date']);
         $this->display();
       
    }
      //微信论坛发帖
    Public function forumpost(){
        $pid=$_GET['pid'];
        $openid=$_GET['openid'];
        $siteid=$_SESSION['siteid'];
        $member=M('forummember')->where(array('openid' => $openid))->select();
        if ($member=='') {
            //绑定会员信息
            $this->redirect('Index/Microforum/forummember',array('openid'=>$openid));
        }else{
            $this->assign('name',$member[0]['name']);
            $this->assign('openid',$openid);
            $this->assign('siteid',$siteid);
            $this->display();           
        }
            
       
    }

     //微信论坛发帖表单处理
    Public function runforumpost(){

           $User = M("forumpost"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $siteid=$_SESSION['siteid'];
           $openid=$_POST['openid'];
           $image_url=C("image_url");

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/forum/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
            $data['openid']=$_POST['openid'];
            $data['siteid']=$_SESSION['siteid'];
            $data['postname']=$_POST['name'];
            $data['content']=$_POST['content'];
            $data['state']=1;
            $User->add($data); // 写入用户数据到数据库
            $this->redirect('Index/Microforum/',array('siteid'=>$siteid,'openid'=>$openid));    
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
            $data['openid']=$_POST['openid'];
            $data['siteid']=$_SESSION['siteid'];
            $data['postname']=$_POST['name'];
            $data['content']='/Uploads/forum/'.$info[0]["savename"].$_POST['content'];
            $data['state']=1;
            $User->add($data); // 写入用户数据到数据库
            $postid=mysql_insert_id();
            
            $User = M("forumimg"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->postid = $postid; 
            $User->img = '/Uploads/forum/'.$info[0]["savename"];           
            $User->add(); // 写入用户数据到数据库
            $this->redirect('Index/Microforum/',array('siteid'=>$siteid,'openid'=>$openid));    
        
            }   
       
       
    }

     //微信论坛会员绑定
    Public function forummember(){
        $pid=$_GET['pid'];
        $openid=$_GET['openid'];
        $siteid=$_SESSION['siteid'];
         $this->assign('openid',$openid);
         $this->display();
       
    }
       //微信论坛会员表单处理
    Public function runforummember(){
           $User = M("forummember"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $siteid=$_SESSION['siteid'];
           $openid=$_POST['openid'];
            $data['openid']=$_POST['openid'];
            $data['siteid']=$_SESSION['siteid'];
            $data['name']=$_POST['name'];
            $data['phone']=$_POST['phone'];
            $data['state']=1;
            $User->add($data); // 写入用户数据到数据库
            $this->redirect('Index/Index/Microforum/',array('siteid'=>$siteid,'openid'=>$openid));       
    }

 }
?>