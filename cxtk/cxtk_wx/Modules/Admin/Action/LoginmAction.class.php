<?php 

  /*后台登陆模块控制器*/
   Class ManagerAction extends Action {

   //登陆页面视图
   	Public function Index(){
   		$this ->display();
   	}
    Public function Login(){

    	if(!IS_POST) Halt('页面不存在！');
    	if(I('code','','md5')!=session('verify')){
    		$this->error('验证码错误');
    	}
    	//$name=I('name');
      $name=$_POST['name'];
    	//$pwd=I('password','','md5');
      $pwd=$_POST['password'];
    	

    	//$user=M('user')->where(array('name'=>$name))->find();

      p($name);
      p($user['password']);
    	
    	 // if(!$user || $user['password']!=$pwd){
    	     //$this->error('用户账户或者密码错误！');
     

    	 // }
    	 
         
    	 // session('uid',$user['uid']);
    	 // session('name',$user['name']);

    	 

    	 // $this->redirect('Admin/Index/index');
    	

    }


   	Public function verify(){
   		import('ORG.Util.Image');
   		Image::buildImageVerify(4,1,'png',60,25);

   	
   }
}
?>