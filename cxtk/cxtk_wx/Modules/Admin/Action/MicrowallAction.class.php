<?php
   //微信墙留言
    Class MicrowallAction extends CommonAction{
     //微信墙留言设置  
     Public function index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       //$wallconfig=M('microwallconfig')->where(array('siteid' => $siteid))->select();
       $wallurl=$image_url."/index.php/Index/Microwall/siteid".'/'.$siteid;
       $this->assign('wallurl',$wallurl);   
       $this ->display();
       
     }
     //微信墙留言管理
    Public function microwall(){

         $siteid=$_SESSION['siteid'];
        
         import("ORG.Util.Page");
         $num=M('microwall')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $wall=M('microwall')->where(array('siteid' => $siteid))->limit($limit)->order('id desc')->select();
         $this->wanll=$wall;
         $this->page=$page->show();
          $this->assign('wall',$wall);// 赋值数据集 
         // $this->assign('page',$show);
        
         $this ->display();

    }       


        //删除留言
        Public function microwalldelete(){

            $pid= $_GET["id"];
            if( M('microwall')->where(array('id'=>$pid))->delete()){
                   $this->success('删除成功');
                  }else{
                   // echo M('photo')->getLastSql();

                    $this->error('删除失败');
                  }

           
         
         }


}
?>
 