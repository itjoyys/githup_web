<?php
   
    Class MicroimgAction extends Action{
       //微信相册首页
      Public function siteid(){
       
         $siteid= $_GET["_URL_"][3]; 
         session('siteid',$siteid);
         $image_url=C('image_url');
         $microcate = M("Microcate")->where(array('siteid' => $siteid))->order('sort ASC')->select();
        
         $this->microcate=$microcate;
      
         $this->assign('microcate',$microcate);
         $this->assign('image_url',$image_url);
        
         $this ->display(index);
      }
    //相册列表
     Public function Microlist(){
  
         import("ORG.Util.Page");
         $siteid=$_SESSION['siteid'];         
         $mid=$_GET['mid'];
         $image_url=C('image_url');
         $num=M('microimg')->where(array('siteid' => $siteid,'mid'=>$mid))->count();
         $page = new Page($num, 4);
         $limit=$page->firstRow.','.$page->listRows;
         $microimg=M('microimg')->where(array('siteid' => $siteid,'mid'=>$mid))->order('sort ASC')->limit($limit)->select();
         $this->microimg=$microimg;
         $this->page=$page->show();

         $this->assign('microimg',$microimg);
         $this->assign("image_url", $image_url);

         $this->assign("mid", $mid);
       
        $this->display();
     }

     //相册图片AJAX请教
     Public function getImg(){
       $mid=$_GET['mid'];
       $page=isset($_GET['page'])?intval($_GET['page']):0;
       $product=M('product')->where(array('classid'=>19))->select();
       $start=$page*10;
   
        
       $j=COUNT($product);
       for ($i=0; $i <$j ; $i++) { 
           $product[$i]['img'] = 'http://www.xiongmaozhan.com/'.$product[$i]['img'];
           $img_info = getimagesize($product[$i]['img']);
           $product[$i]['width'] = $img_info[0];
           $product[$i]['height'] = $img_info[1];
           $product[$i] = (object)$product[$i];
        }    

        $json = array_slice($product, $start,10);
 
        echo json_encode( $json );
     }

}
?>
 