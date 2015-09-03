<?php 
/*
  微检索
*/
 Class MicrosearchAction extends CommonAction{
    //设置
    Public function index(){
     
        $siteid=$_SESSION['siteid'];
        $search=M('microsearch')->where(array('siteid' => $siteid))->select();

        $image_url=C("image_url");
        $url=$image_url."/index.php/Index/Microsearch/siteid".'/'.$siteid;
        $this->assign('url',$url);
     
        $this->assign('title',$search[0]['name']);
        $this->assign('img',$search[0]['img']);
      
        $this->assign("image_url", $image_url);   
        $this->assign('siteid',$siteid);
    
        $this->display(); // 输出模板

    }  

    //微查询处理
    Public function run_search(){
      $siteid=$_POST['siteid'];

      $list=M('microsearch')->where(array('siteid'=>$siteid))->select();
      $User=M('microsearch');
      if (!$list) {
        //如果不存在就添加

        $User->create();
        $User->siteid=$_POST['siteid'];
        $User->name=$_POST['title'];
        $User->add();
        $this->success('添加成功');
        
      }else{
         //存在就更新
        $User->create();
        $User->name=$_POST['title'];
        $User->where(array('siteid'=>$_POST['siteid']))->save();
        $this->success('更新成功');

      }
    } 

        //微查询图片添加
    Public function search_img(){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/microsearch/';// 设置附件上传目录

        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

       $User=M('microsearch');

         //存在就更新
        $User->create();
        $User->img = '/Uploads/microsearch/'.$info[0]["savename"]; 
        $User->where(array('siteid'=>$_POST['siteid']))->save();
        $this->success('更新成功');

      

    } 


  //添加检索内容
  Public function search(){
      $siteid=$_SESSION['siteid'];
      $searchdata=M('searchdata')->where(array('siteid' => $siteid))->select();
      $this->assign('searchdata',$searchdata);
      $this->display();
  }

   //添加检索内容
  Public function add_search(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);

      $this->display();
  } 

  //添加资料处理
  Public function run_addsearch(){
     $cid=$_POST['cid'];
     if ($cid=="1") {

      //资料为图片

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/microsearch/';// 设置附件上传目录

        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

        $User=M('searchdata');
        $User->create();
        $User->siteid=$_POST['siteid'];
        $User->key_s=$_POST['key'];
		$User->number=$_POST['number'];
        $User->data = '/Uploads/microsearch/'.$info[0]["savename"]; 
        $User->add();
        $this->success('添加成功');

       
     }else{

        
        $User=M('searchdata');
        $User->create();
        $User->siteid=$_POST['siteid'];
        $User->key_s=$_POST['key'];
        $User->data =$_POST['data']; 
        $User->add();
        $this->success('添加成功');

     }


  } 

  //删除
  Public function search_delete(){

        $id=$_GET['id'];
        $User=M('searchdata');
        $User->create();
        $User->where(array('id'=>$id))->delete();
        $this->success('删除成功');


  }



 }

?>

