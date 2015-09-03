<?php 
     //微信订餐系统
 Class BookdinnerAction extends Action{

    Public function siteid(){

      $siteid= $_GET["_URL_"][3]; 
      $name=M('dinner_config')->where(array('siteid'=>$siteid))->getField('name');
      session("siteid",$siteid);
      $cate=M('dinner_category')->where(array('siteid'=>$siteid))->select();
       $id=$cate[0]['id'];
      $food=M('dinnerfood')->where(array('siteid' => $siteid,'cid'=>$id))-> select();

      if (!isset($_SESSION['DinnerCart'])) {
           $goodsNum=0;  
      }else {
          $goodsNum=count($_SESSION['DinnerCart']);
      } 
      for ($i=0; $i <$fnum ; $i++) { 
         if ($food[$i]['id']==$key_food[$i]) {
            $food[$i]['state']=1;
         }else{
            $food[$i]['state']=0;
         }
      }

      $this->assign('name',$name);
      $this->assign('cate',$cate);
      $this->assign('food',$food);
      $this->assign('goodsNum',$goodsNum);

     // p($_SESSION);

      $this->display(index);
   
   }

   //菜品列表
    Public function foodlist(){
      $siteid=$_SESSION['siteid'];
      $cid=$_GET['cid'];
      $cate=M('dinner_category')->where(array('siteid'=>$siteid))->select();
      $food=M('dinnerfood')->where(array('siteid' => $siteid,'cid'=>$cid))-> select();

      if (!isset($_SESSION['DinnerCart'])) {
           $goodsNum=0;  
          
      }else {
          $goodsNum=count($_SESSION['DinnerCart']);
      }

     $this->assign('cate',$cate);
     $this->assign('food',$food);
     $this->assign('goodsNum',$goodsNum);
    // p($_SESSION);
   
 
     $this->display();
   
   }


   //添加商品到购物车
   Public function updateGoodsNum(){
     if (IS_AJAX === false) return false;
     $siteid=$_SESSION['siteid'];
     $gid=$_POST['gid'];
     $state=$_POST['state']; //状态判断
     $goodsNum=$_POST['num'];
     if (!isset($_SESSION['tailprice'])) {
        $_SESSION['tailprice']=0;
     }
      if ($state==1) {
     	 unset($_SESSION['DinnerCart'][$gid]);  
     	 $num=count($_SESSION['DinnerCart']);
     	 $result=array(
     	 	'status'=>true,
     	 	'num'=>$num
     	 	); 
     	
     }elseif ($state==2) {
     
       $_SESSION['DinnerCart'][$gid]['goodsid']=$gid;
       $name=M('dinnerfood')->where(array('id'=>$gid))->getField('name');
       $price=M('dinnerfood')->where(array('id'=>$gid))->getField('price');
       $_SESSION['DinnerCart'][$gid]['name']=$name;
       $_SESSION['DinnerCart'][$gid]['price']=$price;

       $_SESSION['DinnerCart'][$gid]['goodsNum']=1;
       $_SESSION['tailprice']=$_SESSION['tailprice']+$price*$_SESSION['DinnerCart'][$gid]['goodsNum'];
        $num=count($_SESSION['DinnerCart']);
        $result=array(
     	 	'status'=>true,
     	 	'num'=>$num
     	 	);       
     }

     exit(json_encode($result));

   }




      //更新购物车商品数量
   Public function updateCartNum(){
     if (IS_AJAX === false) return false;
     $gid=$_POST['gid'];
     $num=$_POST['num'];
     $result=array();
     $foodnum=intval($_SESSION['DinnerCart'][$gid]['goodsNum']);
     $foodprice=intval($_SESSION['DinnerCart'][$gid]['price']);
   
     $_SESSION['tailprice']=intval($_SESSION['tailprice'])+($num-$foodnum)*$foodprice;
     $_SESSION['DinnerCart'][$gid]['goodsNum']=$num;
  
        $result=array(
     	 	'status'=>true,
     	 	'num'=>$num,
        'tailprice'=>$_SESSION['tailprice']
     	 	); 

      exit(json_encode($result));

      
   }


   //订餐购物车页面
   Public function cart(){

    $siteid=$_SESSION['siteid'];
    $goodsNum=count($_SESSION['DinnerCart']);
    $goods=$_SESSION['DinnerCart'];
    $tailprice=$_SESSION['tailprice'];
   
    $image_url=C("image_url");
    $url=$image_url.'/index.php/Index/Bookdinner/siteid'.'/'.$siteid;
    $this->assign('url',$url);
    $this->assign('siteid',$siteid);
    $this->assign('goods',$goods);
    $this->assign('tailprice',$tailprice);
    $this->display(cart);
   }

   //清空购物车信息
   Public function clearcart(){
    $siteid=$_SESSION['siteid'];
    unset($_SESSION['DinnerCart']);
    unset($_SESSION['tailprice']);
    $this->redirect('Index/Bookdinner/', array('siteid' => $siteid));

   }

   //ajax提交表单
   Public function ajaxorder(){
     if (IS_AJAX === false) return false;
     $result=array();

     $siteid=$_SESSION['siteid'];
     $data['name']=$_POST['order_name'];
     $data['tel']=$_POST['order_tel'];
     $data['address']=$_POST['order_address'];
     $data['note']=$_POST['order_note'];
     $data['tailprice']=$_POST['order_tailprice'];
     $data['siteid']=$siteid;
     $data['date']=date('Y-m-d');

       //生成随机20位订单编号
     $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
     $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
     $data['orderid']=$orderSn;

     $User=M('dinner_order');
     $User->create();
     $User->add($data);
     
     $result=array(
          'status'=>true,
          'data'=>$data
      );

     exit(json_encode($result));

   }

   //订单列表
   Public function orderlist(){
    $siteid=$_SESSION['siteid'];

   }


 }
?>