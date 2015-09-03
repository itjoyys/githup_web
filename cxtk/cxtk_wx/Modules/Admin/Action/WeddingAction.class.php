<?php
   //微信喜帖
    Class WeddingAction extends Action{
     //微信喜帖设置  
     Public function index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $url_url=C("url_url");
       $weddingurl=$image_url."/index.php/Index/Wedding/siteid".'/'.$siteid;
       $config = M("weddingconfig")->where(array('siteid' => $siteid))->select();
       $this->assign('weddingurl',$weddingurl); 
       $this->assign('siteid',$siteid);
       $this->assign('title',$config[0]['title']); 
       $this->assign('name',$config[0]['name']); 
       $this->assign('date',$config[0]['date']);
       $this->assign('address',$config[0]['address']);
       $this->assign('url',$config[0]['url']); 
       $this->assign('logo',$config[0]['logo']);            
       $this ->display();
       
     }
     //微信喜帖设置
    Public function config(){

         //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        // $upload->autoSub  = true;// 启用子目录保存文件
       // $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/wedding/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

        
          $siteid=$_POST['siteid'];
            $User = M("weddingconfig"); // 实例化User对象  
            $list = M("weddingconfig")->where(array('siteid' => $siteid))->select();

           if (!$list) {
             $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
            $User->name = $_POST['name']; 
            $User->date = $_POST['date']; 
            $User->address = $_POST['address']; 
            $User->url = $_POST['url']; 
            $User->siteid = $_POST['siteid']; 
            $User->logo = '/Uploads/wedding/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          
             
           }else{

            $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
            $User->name = $_POST['name']; 
            $User->date = $_POST['date']; 
            $User->address = $_POST['address']; 
            $User->url = $_POST['url']; 
            $User->siteid = $_POST['siteid']; 
            $User->logo = '/Uploads/wedding/'.$info[0]["savename"];       
            $User->where(array('siteid'=>$_POST['siteid']))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           
           }
    }
	
	
	    //喜帖里面背景图片上传
    Public function weddingbg(){

       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $photo = M("weddingbg")->where(array('siteid' => $siteid))->select();
       $this->assign('siteid',$siteid);
       for ($i=1; $i <7 ; $i++) { 
         $j='p'.$i;
         $p[$i]['img']=$photo[0][$j];

       }
       $this->assign('p',$p);
       $this->assign('image_url',$image_url);
       $this->display();

    }

        //喜帖里面背景图片上传处理
    Public function addweddingbg(){

        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/wedding/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

       $siteid=$_POST['siteid'];
       $User = M("weddingbg"); // 实例化User对象  
       $list = M("weddingbg")->where(array('siteid' => $siteid))->select();

        if (!$list) {
            $User->create(); // 创建数据对象           
            $User->siteid = $_POST['siteid']; 
            $User->$_POST['bid'] = '/Uploads/wedding/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          
             
           }else{

            $User->create(); // 创建数据对象                     
            $User->$_POST['bid'] = '/Uploads/wedding/'.$info[0]["savename"];       
            $User->where(array('siteid'=>$_POST['siteid']))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");
         
           }


    }

	

   //喜帖里面相册管理
    Public function photo(){

       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $photo = M("weddingphoto")->where(array('siteid' => $siteid))->select();
       $this->assign('photo',$photo);
       $this->assign('siteid',$siteid);
       $this->assign('image_url',$image_url);
       $this->display();

    }
    //微信喜帖添加图片处理
    Public function addphoto(){

       //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        // $upload->autoSub  = true;// 启用子目录保存文件
       // $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/wedding/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

        
            $siteid=$_POST['siteid'];
            $User = M("weddingphoto"); // 实例化User对象  


            $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
            $User->sort = $_POST['sort']; 
            $User->siteid = $_POST['siteid']; 
            $User->photo = '/Uploads/wedding/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

    }

        //删除图片
        Public function deletephoto(){

            $pid= $_GET["id"];
            if( M('weddingphoto')->where(array('id'=>$pid))->delete()){
                   $this->success('删除成功');
                  }else{
                  
                    $this->error('删除失败');
                  }

           
         
         }

    //微信祝福管理
   Public function wish(){

       $siteid=$_SESSION['siteid'];
       import("ORG.Util.Page");
         $num=M('weddingwish')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $wish=M('weddingwish')->where(array('siteid' => $siteid))->limit($limit)->select();
         $this->wish=$wish;
         $this->page=$page->show();
       $this->assign('wish',$wish);
       $this->display();
   }

   //祝福删除
   Public function deletwish(){

      $pid= $_GET["id"];
            if( M('weddingwish')->where(array('id'=>$pid))->delete()){
                   $this->success('删除成功');
                  }else{
                  
                    $this->error('删除失败');
                  }

   }


}
?>
 