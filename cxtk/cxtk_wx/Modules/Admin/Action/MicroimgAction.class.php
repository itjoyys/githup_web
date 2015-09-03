<?php
   
    Class MicroimgAction extends CommonAction{
       //微信相册设置
      Public function Microconfig(){
       
         $siteid=$_SESSION['siteid'];
		 $image_url=C("image_url");
         $microimgurl=$image_url."/index.php/Index/Microimg/siteid".'/'.$siteid;
        
         $this->microconfig=$microconfig;
       
         $this->assign('siteid',$siteid);// 赋值数据集 
         $this->assign('microimgurl',$microimgurl);
        
         $this ->display();
      }

      //微信相册设置表单处理
        Public function runMicroconfig(){

            $siteid=$_SESSION['siteid'];
            $User = M("microconfig"); // 实例化User对象  
            $microconfig = M("microconfig")->where(array('siteid' => $siteid))->select();
       
           if (!$microconfig) {
           $User = M("microconfig"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $User->tpl = $_POST['microimg_tpl']; 
           $User->siteid = $siteid; 
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
         }else{

           $User = M("microconfig"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $User->tpl = $_POST['microimg_tpl']; 
           $User->siteid = $siteid;
            $User->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

         }

      }

      //微信相册列表
    Public function Microcate(){

         $siteid=$_SESSION['siteid'];
         $image_url=C('image_url');
         $microcate=M('microcate') ->where(array('siteid' => $siteid))->order('sort ASC')-> select();
         $this->assign('siteid',$siteid);// 赋值数据集 
         $this->assign('microcate',$microcate);
         $this->assign("image_url", $image_url);
       
         $this->display();
    }

    //删除微信相册分类
  
      Public function deletecate(){
      $id= I('id','0','intval');
              if( M('microcate')->where(array('id'=>$id))->delete()){
                    $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
      }

     //添加微信相册
    Public function Addmicrocate(){

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/microimg/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
     
           $User = M("microcate"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $User->title = $_POST['title']; 
           $User->siteid = $_POST['siteid']; 
           $User->sort = $_POST['sort']; 
       
		   $User->img = '/Uploads/microimg/'.$info[0]["savename"];
       
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

    }
    //图片列表
    Public function microimg(){

         import("ORG.Util.Page");
         $siteid=$_SESSION['siteid'];
         $mid=$_GET['mid'];
         $image_url=C('image_url');
         $num=M('microimg')->where(array('siteid' => $siteid,'mid'=>$mid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $microimg=M('microimg')->where(array('siteid' => $siteid,'mid'=>$mid))->order('sort ASC')->limit($limit)->select();
         $this->microimg=$microimg;
         $this->page=$page->show();

         $this->assign('siteid',$siteid);// 赋值数据集 
         $this->assign('microimg',$microimg);
         $this->assign('mid',$mid);
         $this->assign("image_url", $image_url);
       
         $this->display();
    }
   //相册添加图片处理
    Public function Addmicroimg(){

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/microimg/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
        
            // 保存表单数据 包括附件数据
        
           $User = M("microimg"); // 实例化User对象  
           $User->create(); // 创建数据对象
           $User->title = $_POST['title']; 
           $User->mid = $_POST['mid']; 
           $User->siteid = $_POST['siteid']; 

           $User->sort = $_POST['sort']; 
		    $User->mimg = '/Uploads/microimg/'.$info[0]["savename"]; 
            $User->img = '/Uploads/microimg/'.$info[1]["savename"]; 
       
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");


    }
        //删除图片
        Public function microimgdelete(){

            $id= I('id','0','intval');
              if( M('microimg')->where(array('id'=>$id))->delete()){
                    $this->success('删除成功',U('Admin/Microimg/microimg'));
                  }else{
                    $this->error('删除失败',U('Admin/Microimg/microimg'));
                  }
                    
         }

     //图片排序
          Public function sortmicro(){
            $db =M('microimg');

            foreach ($_POST as $id => $sort) {
              $db->where(array('id' => $id))->setField('sort' ,$sort);
            }
              $this ->redirect(GROUP_NAME . '/Microimg/Microcate');
          }
}
?>
 