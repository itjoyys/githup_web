<?php 
/*
  微网站设置控制器
*/
 Class LebsiteAction extends CommonAction{
    //网站权限设置
    Public function index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
     
       import("ORG.Util.Page");
       $num=M('level')->where(array('siteid' => $siteid))->count();
       $page = new Page($num, 12);
       $limit=$page->firstRow.','.$page->listRows;
       $level=M('level')->where(array('siteid' => $siteid))->limit($limit)->order('id desc')->select();
       $this->level=$level;
       $this->page=$page->show();

       $this->assign('level',$level);      
       $this->assign('siteid',$siteid);   	
       $this ->display();
      
    }

    //添加权限
    Public function addlevel(){

      $siteid=$_SESSION['siteid'];
      $id= I('id','0','intval');
      $this->assign('siteid',$siteid);
      $this->assign('id',$id);
      $this->display();
    }

    //权限表单提交处理
    Public function runlevel(){

      $data['level']=$_POST['level'];
      $User=M('level');
      $User->create();
      $User->where(array('id'=>$_POST['id']))->save($data);
      $this->success('添加成功');

    }

    //删除用户
    Public function deletelevel(){

         $id= I('id','0','intval');
        if( M('level')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
            }else{
              $this->error('删除失败');
            }
    }

 
   


    

 }

?>