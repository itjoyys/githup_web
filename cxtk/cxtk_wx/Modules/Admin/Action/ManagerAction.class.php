<?php 

  /*管理后台控制器*/
   Class ManagerAction extends CommonAction {

   //管理后台页面视图
   	Public function Index(){
   		$this ->display();
   	}

    //退出登陆
    Public function login_out(){
      session('[destroy]'); 
      $this->redirect('Login/Index')
    }

  //一般用户站点列表
    Public function site_user(){
      $uid=$_SESSION['uid'];
      $site=D('SiteView')->where(array('uid'=>$uid))->SELECT();
      $this->site=$site;
      $this->display(site_user);
    }

  
   //用户下面站点添加
   Public function addsite(){

    $siteid= $_GET['sid'];
    $site=M('site')->where(array('sid' => $siteid))->select(); 
 
    $this->assign('name',$site[0]['sitename']);
    $this->assign('copyright',$site[0]['copyright']);
    $this->assign('support',$site[0]['support']);
    $this->assign('surl',$site[0]['surl']);
    $this->assign('siteid',$siteid);
    $this->display('add_site'); 


   }


   //站点表单处理
   Public function add_site(){

     $siteid=$_POST['siteid'];
    $module=$_POST['module'];
    $module=implode(' ',$module);//将每个站点的功能模块拼接成字符串
    $User = M("site"); // 实例化User对象  
    $list = M("site")->where(array('sid' => $siteid))->select();

           if (!$list) {
            $User->create(); // 创建数据对象    
            $User->uid=$_SESSION['userid'];       
            $User->sitename=$_POST['sitename'];
            $User->copyright=$_POST['copyright'];
            $User->support=$_POST['support'];
            $User->surl=$_POST['surl'];
            $User->module= $module;            
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

            $this->redirect('Admin/Manager/site_index');
     
           }else{

            $User->create(); // 创建数据对象    
            $User->uid=$_SESSION['userid'];       
            $User->sitename=$_POST['sitename'];
            $User->copyright=$_POST['copyright'];
            $User->support=$_POST['support'];
            $User->surl=$_POST['surl'];
            $User->module= $module;  
            $User->sid= $siteid;          
            $User->where(array('sid'=>$siteid))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");           
           }


   }

   //用户站点列表
   Public function site_index(){

     
      $userid=$_GET['uid'];
       $site=D('SiteView')->where(array('uid'=>$userid))->SELECT();
      session('userid',$userid);

      $this->site=$site;
      $this->display('site_index');

   }

    //站点删除
   Public function sitedelete(){
     $sid= I('sid','0','intval');
        if( M('site')->where(array('sid'=>$sid))->delete()){
                   $this->success('删除成功',U('Admin/Manager/site_index'));
                  }else{
                    $this->error('删除失败',U('Admin/Manager/site_index'));
                  }
   }



   //用户列表
    Public function userlist(){
       import("ORG.Util.Page");
	  $num=M('user')->count();
      $user=M('user')->SELECT();
	   $page = new Page($num, 16);
         $limit=$page->firstRow.','.$page->listRows;
         $user=M('user')->limit($limit)->order('uid DESC')->select();
         $this->user=$user;
         $this->page=$page->show();
      $this->display('user_index');

    }

    //添加用户
  Public function adduser(){
     $agent=M('agent')->SELECT();
     $this->agent=$agent;
     $this->display('add_user');
   }
      //用户信息编辑
   Public function revise_user(){
     $id = $_GET['uid'];
     $user_data = M('user')->where(array('id'=>$id))->find();
     $this->user_data = $user_data;
     $this->display();
   }
   //表单处理
    Public function add_user(){

       $User = M("user"); // 实例化User对象  
       $User->create(); // 创建数据对象   
       $User->username=$_POST['username'];
       $User->agentname=$_POST['agentname'];
       $User->name=$_POST['name'];
       $User->password=$_POST['password'];
       $User->linkman=$_POST['linkman'];
       $User->phone=$_POST['phone'];
       $User->tphone=$_POST['tphone'];
       $User->address=$_POST['address'];
       $User->state=$_POST['state'];
       $User->remark=$_POST['remark'];
      if (empty($_POST['id'])) {
         //为空表示添加
          $User->add(); // 写入用户数据到数据库
          $this->success("数据保存成功！");
      }else{
          $User->where(array('id'=>$_POST['id']))->save(); // 写入用户数据到数据库
          $this->success("数据更新成功！");   
      }
    }

    //代理商列表

    Public function agentlist(){

      $agent=M('agent')->SELECT();
    
      $this->agent=$agent;
      $this->display('agent_index');


    }

     //添加代理商

   Public function addagent(){

    $this->display('add_agent');

   }

   //添加代理商表单处理
  Public function add_agent(){



            $User = M("agent"); // 实例化User对象  
            $User->create(); // 创建数据对象           
            $User->balance=$_POST['balance'];
            $User->agentname=$_POST['agentname'];
            $User->name=$_POST['name'];
            $User->password=$_POST['password'];
            $User->linkman=$_POST['linkman'];
            $User->phone=$_POST['phone'];
            $User->tphone=$_POST['tphone'];
            $User->address=$_POST['address'];
            $User->state=$_POST['state'];
            $User->remark=$_POST['remark'];
                     
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

  }






    Public function Login(){

    	if(!IS_POST) Halt('页面不存在！');
    	if(I('code','','md5')!=session('verify')){
    		$this->error('验证码错误');
    	}
    	$username=I('username');
    	$pwd=I('password','','md5');
    	

    	$user=M('user')->where(array('username'=>$username))->find();
    	
    	 if(!$user || $user['password']!=$pwd){
    	 	$this->error('用户账户或者密码错误！');
    	 }
    	 
         
    	 session('uid',$user['id']);
    	 session('username',$user['name']);

    	 

    	 $this->redirect('Admin/Index/index');
    	

    }


   	Public function verify(){
   		import('ORG.Util.Image');
   		Image::buildImageVerify(4,1,'png',60,25);

   	
   }
   
      //添加首页模板
   Public function addi_tpl(){
         $image_url=C('image_url');
         import("ORG.Util.Page");
         $num=M('websitei_tpl')->count();
         $page = new Page($num, 8);
         $limit=$page->firstRow.','.$page->listRows;
         $index= M('websitei_tpl')->limit($limit)->select();
         $this->page=$page->show();
         $this->assign("index", $index);  
         $this->assign("image_url", $image_url);  
         $this->display(); // 输出模板
   }
   Public function runaddi_tpl(){
      
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

            $User = M("websitei_tpl");  

            $User->create();         
            $User->tpl_id = $_POST['tpl_id']; 
            $User->img = '/Uploads/website/'.$info[0]["savename"]; 
            $User->add(); 
            $this->success("数据保存成功！");  

   }
   Public function deletei_tpl(){
        $id= I('id','0','intval');
        if( M('websitei_tpl')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
            }else{
              $this->error('删除失败');
            }
   }

    //列表页模板
   Public function addl_tpl(){
         $image_url=C('image_url');
         import("ORG.Util.Page");
         $num=M('websitel_tpl')->count();
         $page = new Page($num, 8);
         $limit=$page->firstRow.','.$page->listRows;
         $list= M('websitel_tpl')->limit($limit)->select();
         $this->page=$page->show();
         $this->assign("list", $list);  
         $this->assign("image_url", $image_url);  
         $this->display(); // 输出模板
   }
   Public function runaddl_tpl(){
      
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

            //p($_POST);

               $User = M("websitel_tpl");  

               $User->create();         
               $User->tpl_id = $_POST['tpl_id']; 
               $User->img = '/Uploads/website/'.$info[0]["savename"]; 
               $User->add(); 
            $this->success("数据保存成功！");  

   }
   Public function deletel_tpl(){
        $id= I('id','0','intval');
        if( M('websitel_tpl')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
            }else{
              $this->error('删除失败');
            }
   }

       //详细页模板
   Public function addd_tpl(){
         $image_url=C('image_url');
         import("ORG.Util.Page");
         $num=M('websited_tpl')->count();
         $page = new Page($num, 8);
         $limit=$page->firstRow.','.$page->listRows;
         $detail= M('websited_tpl')->limit($limit)->select();
         $this->page=$page->show();
         $this->assign("detail", $detail);  
         $this->assign("image_url", $image_url);  
         $this->display(); // 输出模板
   }
   Public function runaddd_tpl(){
      
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }


               $User = M("websited_tpl");  

               $User->create();         
               $User->tpl_id = $_POST['tpl_id']; 
               $User->img = '/Uploads/website/'.$info[0]["savename"]; 
               $User->add(); 
            $this->success("数据保存成功！");  

   }
   Public function deleted_tpl(){
        $id= I('id','0','intval');
        if( M('websited_tpl')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
            }else{
              $this->error('删除失败');
            }
   }
}
?>