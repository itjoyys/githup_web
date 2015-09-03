<?php 
/*
  微信互动
*/
 Class MicrointeractionAction extends CommonAction{
    //微信答题系统
     //题目管理
    Public function answer(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $answer=M('interanswer')->where(array('siteid' => $siteid))->select();
       $websiteurl=U('Index/Website/siteid','','');
       $answerurl=$image_url.'/index.php/Index/Microinteraction/siteid'.'/'.$siteid;
     
       $this->assign('answerurl',$answerurl);
      
       $this->assign("answer", $answer);   
       $this->assign('siteid',$siteid);
       $this ->display();
      
    }

  //添加题目

    Public function runanswer(){
          $siteid=$_SESSION['siteid'];

           $data['problem']=$_POST['problem'];
           $data['answer']=$_POST['answer'];
           $data['siteid']=$siteid;
           $data['optionsa']=$_POST['optionsa'];
            $data['optionsb']=$_POST['optionsb'];
             $data['optionsc']=$_POST['optionsc'];
              $data['optionsd']=$_POST['optionsd'];
           $data['sort']=$_POST['sort'];
           $User = M("interanswer"); // 实例化User对象  
          
            $User->create(); // 创建数据对象    
            $User->add($data); // 写入用户数据到数据库
            $this->success("数据保存成功！");
    	

    }
    //题目删除
    Public function answerdelete(){
             
           $pid= $_GET["id"];
            if( M('interanswer')->where(array('id'=>$pid))->delete()){
                   $this->success('删除成功');
                  }else{
                   // echo M('photo')->getLastSql();

                    $this->error('删除失败');
                  }

    }
   
  
 }

?>