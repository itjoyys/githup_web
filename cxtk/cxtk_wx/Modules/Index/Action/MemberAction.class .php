<?php 
/*
  订单控制器
*/


 Class MemberAction extends Action{
    //订单视图

    Public function index(){

       // $goodsid=$_POST['goodsid'];
       // $goods=D('GoodsView')->where(array('goodsid'=>$goodsid))->select();
       // $title=$_POST['title'];
       // $imgurl=$_POST['url'];
       // $saleprice=$_POST['saleprice'];
       // $buynum=$_POST['buynum'];

       
       $Cart=session('Cart');
      
     
       $this->display();

 }

?>