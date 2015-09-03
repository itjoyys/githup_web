<?php 
   Class LoginAction extends Action {

   	Public function Index(){        
   		$this ->display();
   	}

    Public function UserLogin(){
      if(!IS_POST) Halt('页面不存在！');
      $name=I('username');
      $pwd=I('password');
      $user=M('user')->where(array('name'=>$name))->find();
      if(!$user || $user['password']!=$pwd){
          $this->error('用户账户或者密码错误！');
       }
       session('uid',$user['uid']);
       session('uname',$user['name']);
       $this->redirect('Admin/Manager/site_user');
      
    }
    Public function Login(){
    	if(!IS_POST) Halt('页面不存在！');
    	if(I('code','','md5')!=session('verify')){
    		$this->error('验证码错误');
    	}
    	$name=I('name');
    	$pwd=I('password','','md5');
    	$user=M('manager')->where(array('name'=>$name))->find();
    	if(!$user || $user['password']!=$pwd){
    	 	$this->error('用户账户或者密码错误！');
    	 }else{
    	 session('uid',$user['id']);
    	 session('uname',$user['name']);
    	 $this->redirect('Admin/Manager/index');}
    	
    }


   	Public function verify(){
                 ob_clean();
   		import('ORG.Util.Image');
   		Image::buildImageVerify(4,1,'png',60,25);

   	
   }
}
?>