<?php
   //微信楼盘
    Class HousesAction extends Action{
     //微信楼盘
     Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        $image_url=C("image_url");
        $openid= $_GET['openid']; 
        $housesconfig=M('housesconfig')->where(array('siteid' => $siteid))-> select();
        $img = M("housesimg")->where(array('siteid' => $siteid))->select();
        $this->assign('title',$housesconfig[0]['title']);
        $this->assign('Keywords',$housesconfig[0]['keywords']);
        $this->assign('Description',$housesconfig[0]['description']);
        $this->assign('img',$img);
        $this->display(index);

     }

       
   

}
?>
 