<?php 
/*
  前台列表页控制器
*/


 Class ListAction extends Action{
    //前台列表页视图

    Public function Index(){   
      
          	
          $image_url=C("image_url");
          $this->assign("image_url", $image_url); 
          $classid=$_GET['id'];         
          $siteid=$_SESSION['siteid'];
          $l=$_SESSION["l"];
          $goods=D('GoodsView')->where(array('classid'=>$classid))->select();
          $ads=M('ads')->where('cid=2' AND array('siteid' => $siteid))->select();
          $this->assign("goods", $goods);
          $this->assign("ads", $ads[0]['url']);
          $this->assign("copyright", $_SESSION["copyright"]);
          $this->display(index.$l);


  }
 }

?>