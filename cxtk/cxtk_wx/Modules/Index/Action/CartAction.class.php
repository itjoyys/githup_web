<?php 
/*
  购物车控制器
*/


 Class CartAction extends Action{
    
        //添加商品到购物车
    Public function index(){
       $Cart=$_SESSION['Cart'];
       $this->assign('Cart',$Cart);
       $this->display();
    }

    //清空购物车
    Public function clearCart(){
       $siteid=$_SESSION['siteid'];

       unset($_SESSION['Cart']);

      $this->redirect('Index/Index', array('siteid' => $siteid));

    }

    //删除购物车里面的商品
    Public function deleteCart(){
      if (IS_AJAX === false) return false;
      $Cart_goodsid=$_POST['cart_goodsid'];
      unset($_SESSION['Cart'][$Cart_goodsid]);
         $result=array();
         $result=array(
          'status'=>true,
          'goodsid'=>$_SESSION['Cart']
      );

     exit(json_encode($result));  
    }
 }

?>