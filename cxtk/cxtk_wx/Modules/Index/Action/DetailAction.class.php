<?php 
/*
  商品详细页页控制器
*/


 Class DetailAction extends Action{
    //商品详细页视图
    Public function Index(){    
         $siteid=$_SESSION['siteid'];
	       $image_url=C("image_url");
		     $this->assign("file_url", 'http://127.0.0.1:8077/dingcan/APP/Modules/Index/Tpl');
         $this->assign("image_url", $image_url);   
         $this->assign("copyright", $_SESSION["copyright"]);	
         $homeurl=$image_url."/index.php/Index/Index/siteid".'/'.$siteid;

   //       //读取分类数据
   //       import('Class.Category', APP_PATH);
   //       $cate= M('cate') -> order('sort ASC')-> select();
   //       $cate = Category::unlimitedForLevel($cate);
   //       $smarty->assign("cate", $cate);

   //       //读取新上架商品
   //       $newgoods=D('GoodsView')->where('property=2')->select();
   //       $smarty->assign("newgoods", $newgoods);
        
         
         

   //       //读取特价商品
   //       $salegoods=D('GoodsView')->where('property=3')->select();
   //       $smarty->assign("salegoods", $salegoods);

   //        //读取热卖商品
   //       $hotgoods=D('GoodsView')->where('property=1')->select();
   //       $smarty->assign("hotgoods", $hotgoods);

          $goodsid=$_GET['goodsid'];
         
          $goods=D('GoodsView')->where(array('goodsid'=>$goodsid))->select();
                  
          $this->assign('name',$goods[0]['name']);
          $this->assign('goodsid',$goods[0]['goodsid']);
          $this->assign('saleprice',$goods[0]['saleprice']);
          $this->assign('price',$goods[0]['price']);
          $this->assign('saleprice',$goods[0]['saleprice']);
          $this->assign('photo1',$goods[0]['photo1']);
          $this->assign('photo2',$goods[0]['photo2']);
          $this->assign('photo3',$goods[0]['photo3']);
          $this->assign('photo4',$goods[0]['photo4']);
          $this->assign('details',$goods[0]['details']);
          $this->assign('homeurl',$homeurl);

          //$this->assign('title',$goods.name);
          $d=$_SESSION["d"];
         
        
         $this->display(index.$d);


  }

  //商品加入购物车
  Public function addcart(){
    if (IS_AJAX===false) return false;
    $data=array();
    $goodsid=$_POST['cart_goodsid'];
    $data[$goodsid]['goodsid']=$_POST['cart_goodsid'];
    $data[$goodsid]['goodsname']=$_POST['cart_goodsname'];
    $data[$goodsid]['goodsnum']=$_POST['cart_goodsnum'];
    $data[$goodsid]['goodsprice']=M('goods')->where(array('goodsid'=>$goodsid))->getField('saleprice');
    $data[$goodsid]['img']=M('photo')->where(array('goodsid'=>$goodsid))->getField('photo1');
  
    if (array_key_exists($goodsid,$_SESSION['Cart'])) {
      $_SESSION['Cart'][$goodsid]['goodsnum']=$_SESSION['Cart'][$goodsid]['goodsnum']+ $data[$goodsid]['goodsnum'];
      $_SESSION['Cart'][$goodsid]['tailprice'] = $_SESSION['Cart'][$goodsid]['goodsprice']*$_SESSION['Cart'][$goodsid]['goodsnum'];
                           
    }else {
       $_SESSION['Cart'][$goodsid]['goodsid'] = $data[$goodsid]['goodsid'];
       $_SESSION['Cart'][$goodsid]['goodsname'] = $data[$goodsid]['goodsname'];
       $_SESSION['Cart'][$goodsid]['goodsnum'] = $data[$goodsid]['goodsnum'];
       $_SESSION['Cart'][$goodsid]['goodsprice'] = $data[$goodsid]['goodsprice'];
       $_SESSION['Cart'][$goodsid]['tailprice'] = $data[$goodsid]['goodsprice']*$data[$goodsid]['goodsnum'];
       $_SESSION['Cart'][$goodsid]['img'] = $data[$goodsid]['img'];
    }
   

    $result=array();
    $result=array(
        'status'=>true
        ); 
 
   exit(json_encode($result));
  }

  Public function add_address(){
    $this->siteid = $_GET['siteid'];
    $this->openid = $_GET['openid'];
    $map['siteid'] = $_GET['siteid'];
    $map['pid'] = 0;
    $this->area = M('wash_area')->where($map)->select();
    foreach ($area as $key => $val) {
      $data = M('wash_area')->where(array('pid'=>$val['id']))->select();
      foreach ($data as $k => $v) {
        $area_d[$key] = $area_d[$key].'<option value="'.$v['id'].'">'.$v['name'].'</option>';
      } 
    }
    $this->area_d = $area_d;
    $this->display();
    foreach ($user_car as $key => $val) {
       if (empty($val['owners'])) {
          $user_car[$key]['owners'] = $user_data['name'];
       }
    }
  }

 }
?>