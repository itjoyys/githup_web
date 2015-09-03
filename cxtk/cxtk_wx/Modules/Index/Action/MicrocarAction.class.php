<?php 
/*
  微汽车控制器
*/
 Class MicrocarAction extends Action{
    //设置
    Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        session('siteid',$siteid);
        $car=M('microcar')->where(array('siteid' => $siteid))->select();
        $cardata=M('cardata')->where(array('siteid' => $siteid))->limit(15)->select();
        $catecar=M('catecar')->where(array('siteid' => $siteid,'pid'=>'0'))->select();
        $cate_car=M('catecar')->where(array('pid'=>$catecar[0]['id']))->select();
        $this->assign('title',$car[0]['title']);
        $this->assign('Keywords',$car[0]['keywords']);
        $this->assign('Description',$car[0]['description']);
        $this->assign('bg',$car[0]['bg']);
        $this->assign("image_url", $image_url);   
        $this->assign('siteid',$siteid);
        $this->assign('catecar',$catecar);
        $this->assign('cate_car',$cate_car);
        $this->assign('cardata',$cardata);
        //p($cate_car);
        $this->display(index); // 输出模板

    }   

  Public function carlist(){
      $siteid=$_SESSION['siteid'];
      $id=$_GET['id'];
      $catecar=M('catecar')->where(array('siteid' => $siteid,'pid'=>'0'))->select();
      $cate_car=M('catecar')->where(array('pid'=>$id))->select();

      $this->assign('cate_car',$cate_car);
      $this->assign('catecar',$catecar);

      if ($id=="") {
        $this->display();
      }else{
     
        $this->display();
      }

  } 

  //汽车属性检索
  Public function car_list(){
      $siteid=$_SESSION['siteid'];
      $id=$_GET['id'];
      $cardata=M('cardata')->where(array('siteid'=>$siteid))->select();
      //检索属性
      foreach ($cardata as $key => $value) {
        $tmparray = strpos($value['property'],$id); 
        if (is_bool($tmparray)) {
          //如果不存在
          unset($cardata[$key]);
        }
      }

      $this->assign('cardata',$cardata);
  }


  //汽车详页
  Public function cardetail(){
    $id=$_GET['carid'];
    $cardata=M('cardata')->where(array('carid'=>$id))->field('name,date,img,saleprice,price,details,actual')->find();
    $this->assign('name',$cardata['name']);
    $this->assign('date',$cardata['date']);
    $this->assign('img',$cardata['img']);
    $this->assign('saleprice',$cardata['saleprice']);
    $this->assign('price',$cardata['price']);
    $this->assign('details',$cardata['details']);
    $this->assign('actual',$cardata['actual']);
    $this->display();

    $pid=M('catecar')->where(array('id'=>$id))->field('pid')->find();
  }



 }

?>

