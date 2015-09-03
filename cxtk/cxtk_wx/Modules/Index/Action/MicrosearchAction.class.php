<?php 
/*
  微检索
*/
 Class MicrosearchAction extends Action{
    //设置
    Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        session('siteid',$siteid);
        $image_url=C('image_url');
        $search=M('microsearch')->where(array('siteid' => $siteid))->select();

        $this->assign('name',$search[0]['name']);
        $this->assign('img',$car[0]['img']);
        $this->assign("image_url", $image_url);   
      
        $this->display(index); // 输出模板

    }   

  Public function search(){
      $siteid=$_SESSION['siteid'];
      $image_url=C('image_url');
  
      $keyword=$_GET['keyword'];
      $searchdata=M('searchdata')->where(array('siteid' => $siteid,'key'=>$keyword))->field('data')->find();
      $data=stripslashes($searchdata['data']);

      
       // $data=$image_url.$data;
        $this->assign('data',$data);
        $this->assign('image_url',$image_url);
       //var_dump($data);

        // $this->display();


    


    

  } 

 }

?>

