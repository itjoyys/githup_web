<?php 
/*
  微信设置控制器
*/
 Class MicroAction extends CommonAction{
    //微信设置视图
    Public function Index(){
       $siteid=$_SESSION['siteid'];
       $token=M('microtoken')->where(array('siteid' => $siteid))->select();
       $image_url=C("image_url");
       $tokenurl=$image_url."/WX.php?siteid=".$siteid;
       $this->assign('tokenurl',$tokenurl);
       $this->assign('token',$token[0]['token']);
       $this->assign('siteid',$siteid);
       $this ->display();
      
    }

    Public function runmicrotoken(){
        
          $siteid=$_POST['siteid'];
            $User = M("microtoken"); // 实例化User对象  
            $list = M("microtoken")->where(array('siteid' => $siteid))->select();

           if (!$list) {
             $User->create(); // 创建数据对象           
            $User->token = $_POST['token'];    
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          
             
           }else{

            $User->create(); // 创建数据对象   
            $User->token = $_POST['token']; 
            $User->siteid =$siteid;        
            $User->where(array('siteid'=>$siteid))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           
           }
    	

    }
    //首次关注图文
    Public function microconcern(){
             
            $siteid=$_SESSION['siteid'];
            $image_url=C("image_url");
            $concern = M("microconcern")->where(array('siteid' => $siteid))->select();
            $this->assign('concern',$concern);
            $this->assign('image_url',$image_url);
            $this ->display();
          

    }

    Public function r_microconcern(){
             
            $siteid=$_SESSION['siteid'];
            $image_url=C("image_url");
            $concern = M("microconcern")->where(array('siteid' => $siteid))->select();
            $this->assign('title',$concern[0]['title']);
            $this->assign('intro',$concern[0]['intro']);
            $this->assign('url',$concern[0]['url']);
            $this->assign('img',$concern[0]['img']);
            $this->assign('sort',$concern[0]['sort']);
            $this->assign('image_url',$image_url);
            $this ->display();          
    }
   
    Public function runmicroconcern(){

       $siteid=$_SESSION['siteid'];
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/micro/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

   

    }
	
	Public function runmicromatch_txt(){
	        $User=M('keyworddata');
	        $User->create(); // 创建数据对象  
            $data['keyid']=$_POST['kid'];
            $data['siteid']=$_POST['siteid'];			
            $data['intro'] = $_POST['txt'];    
            $User->add($data); // 写入用户数据到数据库
            $this->success("数据保存成功！");
	
	}
   
        Public function deleteconcern(){
          $id= I('id','0','intval');  
             if( M('microconcern')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
             
    }

	
	  //关键词图文
    Public function microkeyword(){
             
            $siteid=$_SESSION['siteid'];
            import("ORG.Util.Page");
            $num=M('microkeyword')->where(array('siteid' => $siteid))->count();
             $page = new Page($num, 12);
             $limit=$page->firstRow.','.$page->listRows;
             $keyword=M('microkeyword')->where(array('siteid' => $siteid))->limit($limit)->select();
             $this->goods=$goods;
             $this->page=$page->show();    
            $this->assign('keyword',$keyword);
            $this ->display();
          

    }
   
    Public function runmicrokeyword(){

       $siteid=$_SESSION['siteid'];

            $User = M("microkeyword"); // 实例化User对象  

            $User->create(); // 创建数据对象   
            $User->keyword = $_POST['keyword']; 
            $User->siteid = $siteid;   
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
    }

    //添加关键词
       Public function keyword_x(){
          $kid= I('id','0','intval');  
          $siteid=$_SESSION['siteid'];
            $image_url=C("image_url");
            $keyword = M("keyworddata")->where(array('keyid' => $kid))->select();
            $this->assign('keyword',$keyword);
            $this->assign('image_url',$image_url);
            $this->assign('kid',$kid);
            $this->assign('siteid',$siteid);

          $this->display();
            
    }

    //关键词添加图文
    Public function key_word_x(){
        $kid=$_GET['id']; 
        $siteid=$_SESSION['siteid'];
        $this->assign('kid',$kid);
        $this->assign('siteid',$siteid);

        $this->display();
    }

    //关键词图文添加
    Public function runkey_word_x(){
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/micro/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
      $data['keyid']=$_POST['kid'];
      $data['siteid']=$_POST['siteid'];
      $data['title']=$_POST['title'];
      $data['intro']=$_POST['intro'];
      $data['img']='/Uploads/micro/'.$info[0]["savename"];  ;
      $data['url']=$_POST['url'];
      $data['sort']=$_POST['sort'];

      $User = M("keyworddata"); // 实例化User对象  
      $User->create(); // 创建数据对象 
      $User->add($data);
      $this->success('添加图文信息成功');

    }

    //关键词图片添加
    Public function runkeyword_img(){
          //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/micro/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
      $data['keyid']=$_POST['kid'];
      $data['siteid']=$_POST['siteid'];
      $data['img']='/Uploads/micro/'.$info[0]["savename"];  ;

      $User = M("keyworddata"); // 实例化User对象  
      $User->create(); // 创建数据对象 
      $User->add($data);
      $this->success('添加图片信息成功');
    }

    //删除关键词
          Public function deletekeyword(){
          $id= I('id','0','intval');  
             if( M('microkeyword')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
             
    }
     //删除关键词下的信息
          Public function deletekeyword_x(){
          $id= I('id','0','intval');  
             if( M('keyworddata')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
             
    }
	
	


      //无匹配图文
    Public function micromatch(){
             
            $siteid=$_SESSION['siteid'];
            $image_url=C("image_url");
            $match = M("micromatch")->where(array('siteid' => $siteid))->select();
            $this->assign('match',$match);
            $this->assign('image_url',$image_url);
            $this ->display();
          

    }
   
    Public function runmicromatch(){

       $siteid=$_SESSION['siteid'];
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/micro/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

            $User = M("micromatch"); // 实例化User对象  

            $User->create(); // 创建数据对象   
            $User->title = $_POST['title'];  
            $User->intro = $_POST['intro']; 
            $User->url = $_POST['url'];        
            $User->siteid = $siteid; 
            $User->sort = $_POST['sort']; 
            $User->img = '/Uploads/micro/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
    }
   
      Public function deletematch(){
          $id= I('id','0','intval');  
             if( M('micromatch')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
             
    }












   //栏目修改
        // Public function revisecolumn(){
        //  $siteid=$_SESSION['siteid'];
        //  $image_url=C("image_url");
        //  $this->assign('image_url',$image_url);
        //  $pid= I('id', 0 , 'intval');
        //  $this->assign('pid',$pid);
        //  $content=M('websitecontent')->where(array('cid'=>$pid,'siteid'=>$siteid))->select();
        //  $column=M('websitecolumn')->where(array('id'=>$pid,'siteid'=>$siteid))->select();
        
        //  if (!$content) {
        //       $this->assign('title',$column[0]['title']);
        //       $this->assign('introduce',$column[0]['introduce']);
        //       $this->assign('img',$column[0]['img']);
        //       $this->assign('sort',$column[0]['sort']);
        //       $this->assign('url',$column[0]['url']);
        //       $this->display(t_revisecolumn);
        //  }else{

        //       $this->assign('title',$column[0]['title']);
        //       $this->assign('introduce',$column[0]['introduce']);
        //       $this->assign('img',$column[0]['img']);
        //       $this->assign('sort',$column[0]['sort']);
        //       $this->assign('content',$content[0]['content']);
           
        //       $this->display(w_revisecolumn);

        //  }
       
        // }
        //  //栏目修改表单处理
        // Public function runrevisecolumn(){
        //  $siteid=$_SESSION['siteid'];
        //  $image_url=C("image_url");
        //  $this->assign('image_url',$image_url);
        //  $pid=$_POST['pid'];

        // import("ORG.Net.UploadFile");
        // $upload = new UploadFile();// 实例化上传类
        // $upload->maxSize  = 2000000 ;// 设置附件上传大小
        // $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        // $upload->autoSub  = true;// 启用子目录保存文件
        // $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        // $upload->dateFormat = 'Ym';  
        // $upload->savePath =  './Uploads/website/';// 设置附件上传目录
         
        // if(!$upload->upload()) {// 上传错误提示错误信息
        //    $this->error($upload->getErrorMsg());
        
        //     }else{// 上传成功 获取上传文件信息
        
        //     $info =  $upload->getUploadFileInfo();
        
        //     }


        //  $content=M('websitecontent')->where(array('cid'=>$pid,'siteid'=>$siteid))->select();
        //  $column=M('websitecolumn')->where(array('id'=>$pid,'siteid'=>$siteid))->select();
        
        //  if (!$content) {
        //     $User = M("websitecolumn"); 
        //     $User->create();  
        //     $data['title'] = $_POST['title']; 
        //     $data['introduce'] = $_POST['introduce']; 
        //     $data['url'] = $_POST['url']; 
        //     $data['sort'] = $_POST['sort'];
        //     $data['img'] = '/Uploads/website/'.$info[0]["savename"];  
        //     $User->where(array('id'=>$pid))->save($data); 
        //     $this->success("数据更新成功！");
        //    // echo M("websitecolumn")->getLastSql();
              
        //  }else{

        //     $User = M("websitecolumn"); 
        //     $User->create();  
        //     $data['title'] = $_POST['title']; 
        //     $data['introduce'] = $_POST['introduce']; 
        //     $data['sort'] = $_POST['sort'];
        //     $data['img'] = '/Uploads/website/'.$info[0]["savename"];  
        //     $User->where(array('id'=>$pid))->save($data); 
        //     $this->success("数据更新成功！");

        //     $User = M("websitecontent"); 
        //     $User->create();  
        //     $User->content = $_POST['details'];
        //     $User->siteid = $siteid;
        //     $User->where(array('cid'=>$pid))->save(); 
        //     $this->success("数据更新成功！");
        //     // echo M("websitecontent")->getLastSql();
              

        //  }
       
        // }
  

 }

?>