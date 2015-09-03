<?php 
/*
  微楼盘控制器
*/
 Class HousesAction extends CommonAction{
      //微楼书列表
    Public function Houses_index(){
       $siteid=$_SESSION['siteid'];
       $houses=M('housesconfig')->where(array('siteid' => $siteid))->select();
       $this->assign('houses',$houses);
	  // p($houses);
       $this->display();

    }
 
    
    //微楼书
    Public function Index(){
        $siteid=$_SESSION['siteid'];
      
  
        $this->assign('siteid',$siteid);
        $this->display(); // 输出模板
    }
	
	  //微楼盘编辑
    Public function revisehouses(){
        $siteid=$_SESSION['siteid'];
        $hid=$_GET['id'];
        $housesconfig=M('housesconfig')->where(array('siteid' => $siteid,'hid'=>$hid))->select();
        $image_url=C("image_url");
        $housesurl=$image_url."/index.php/Index/Houses/housesid".'/'.$hid;
        $this->assign('housesurl',$housesurl);
        $this->assign('title',$housesconfig[0]['title']);
		$this->assign('tel',$housesconfig[0]['tel']);
        $this->assign('Keywords',$housesconfig[0]['keywords']);
        $this->assign('Description',$housesconfig[0]['description']);
        $this->assign("image_url", $image_url);   
        $this->assign('siteid',$siteid);
		$this->assign('hid',$hid);
        $this->display(index); // 输出模板
    }
	


    //微楼书处理

    Public function run_houses(){
	        $hid= I('hid', 0, 'intval');
    	    $siteid=$_SESSION['siteid'];
            $User = M("housesconfig"); // 实例化User对象  
         

           if ($hid==0) {
            $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
			$User->tel = $_POST['tel']; 
            $User->keywords = $_POST['Keywords']; 
            $User->description = $_POST['Description']; 
            $User->siteid = $_POST['siteid']; 
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
             
           }else{

            $User->create(); // 创建数据对象   
            $User->title = $_POST['title'];
            $User->tel = $_POST['tel']; 			
            $User->keywords = $_POST['Keywords']; 
            $User->description = $_POST['Description']; 
            $User->where(array('id'=>$hid))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           
           }

    }

    //微楼盘图片上传
    Public function housesimg(){
	   $hid=$_GET['id'];
       $siteid=$_SESSION['siteid'];
       $image_url=C('image_url');
         import("ORG.Util.Page");
         $num=M('housesimg')->where(array('siteid' => $siteid,'hid'=>$hid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $img=M('housesimg')->where(array('siteid' => $siteid,'hid'=>$hid))->limit($limit)->select();
         $this->img=$img;
         $this->page=$page->show();

       $this->assign('img',$img);
	   $this->assign('hid',$hid);
       $this->assign('siteid',$siteid);
       $this->assign('image_url',$image_url);
       $this->display();

    }

    Public function run_housesimg(){

       //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        // $upload->autoSub  = true;// 启用子目录保存文件
       // $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/houses/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

        
            $siteid=$_POST['siteid'];
            $User = M("housesimg"); // 实例化User对象  


            $User->create(); // 创建数据对象           
            $User->sort = $_POST['sort']; 
            $User->siteid = $_POST['siteid']; 
		    $User->hid = $_POST['hid']; 
            $User->img = '/Uploads/houses/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
    }

      //删除图片
        Public function deletephoto(){

            $pid= $_GET["id"];
            if( M('housesimg')->where(array('id'=>$pid))->delete()){
                   $this->success('删除成功');
                  }else{
                  
                    $this->error('删除失败');
                  }

           
         
         }

 }

?>