<?php 
/*
  微信互动控制器
*/


 Class MicrointeractionAction extends Action{
    //微信调研

    Public function siteid(){   
        $siteid= $_GET["_URL_"][3]; 
       //$image_url=C("image_url");
         //import("ORG.Util.Page");
        // $num=M('interanswer')->where(array('siteid' => $siteid))->count();
        // $page = new Page($num, 12);
        // $limit=$page->firstRow.','.$page->listRows;
         $answer=M('interanswer')->where(array('siteid' => $siteid))->order('id ASC')->select();
         $this->answer=$answer;
         //$this->page=$page->show();
       
         $this ->display(answer);
     
	
      }
      //答案提交处理
      Public function runanswer(){
         $anum=count($_POST[answer]);
         $answer=$_POST[answer];
         $k=array_count_values($answer);

        








      }

    
 }

?>