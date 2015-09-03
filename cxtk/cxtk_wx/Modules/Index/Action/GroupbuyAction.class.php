<?php 
/*
   微信团购
*/
 Class GroupbuyAction extends Action{
    //商城首页
     Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        $openid= $_GET['openid']; 
        $image_url = C('image_url');
        $config=M('groupbuy_config')->where(array('siteid' => $siteid))-> find();
        $group_config = array(
          'shop_siteid' => $siteid,
          'shop_openid' => $openid,
          'group_l_tpl' => $config['list_tpl'],
          'group_d_tpl' => $config['detail_tpl']
        );  
        session('group_config',$group_config);
        
         //读取商品
          $goods=M('groupbuy_goods')->where(array('siteid'=>$siteid))->order('sort DESC')->limit(6)->select();
          foreach ($goods as $key => $val) {
               //配置每天的活动时间段
              $starttimestr = date('Y-m-d');
              $endtimestr = $val['finish'];
              $endtime = strtotime($endtimestr);
              $nowtime = time();
              if ($nowtime<$starttime){
                 die("活动还没开始,活动时间是：{$starttimestr}至{$endtimestr}");
              }
              if ($endtime>=$nowtime){
                $goods[$key]['lefttime'] = $endtime-$nowtime; //实际剩下的时间（秒）
                $goods[$key]['url'] ='#'; //实际剩下的时间（秒）
               }else{
                 $lefttime=0;
                 die("活动已经结束！");
              }
          }
         $this->assign('config',$config);
         $this->goods=$goods;
         $this->siteid = $siteid;
         $js_goods = json_encode($goods);
         $this->js_goods = $js_goods;
         $this->image_url = $image_url;
         $this->display(tuangou.$group_config['group_l_tpl']);
    }

    //详情页
    Public function glist(){
       $shop_config = $_SESSION['shop_config'];
       $cid = $_GET['cid'];
       $goods = D('GoodsView')->where(array('classid'=>$cid))->select();
       $this->goods = $goods;
       $this->image_url = C('image_url');
       $this->display(slist.$shop_config['shop_l_tpl']); 
    }

    function return_pid($id){
     $pstate = M('productclass')->where(array('id'=>$id))->getField('pid');
      if (!empty($pstate)) {
         $pid = $pstate;
      }else{
         $pid = $id;
      }
      return $pid;
    }


    //详细页面
    Public function detail(){
       $shop_config = $_SESSION['shop_config'];
       $gid = $_GET['gid'];
       $goods = D('GoodsView')->where(array('id'=>$gid))->find();
       $property_goods = M('shop_g_property')->where(array('gid'=>$gid))->select();
       $this->goods = $goods;
       $this->image_url = C('image_url');
       $this->property_goods = $property_goods;
       $this->display(detail.$shop_config['shop_d_tpl']); 
       
    }

  //获取更多
    Public function getMore(){
      $page = $_POST['page'];
      $siteid = $_POST['siteid'];
      $goods=M('groupbuy_goods')->where(array('siteid'=>$siteid))->order('sort DESC')->select();
      $start=$page*6;
      $goods = array_slice($goods, $start,6);
      $t = count($goods);
      $js_goods=M('groupbuy_goods')->where(array('siteid'=>$siteid))->order('sort DESC')->limit($t)->select();
      
      if (empty($goods)) {
            //没有更多
        $data = 0;
      }else{
          foreach ($goods as $key => $val) {
               //配置每天的活动时间段
              $starttimestr = date('Y-m-d');
              $endtimestr = $val['finish'];
              $endtime = strtotime($endtimestr);
              $nowtime = time();
              if ($nowtime<$starttime){
                 die("活动还没开始,活动时间是：{$starttimestr}至{$endtimestr}");
              }
              if ($endtime>=$nowtime){
                $goods[$key]['lefttime'] = $endtime-$nowtime; //实际剩下的时间（秒）
                $goods[$key]['url'] = '#'; //实际剩下的时间（秒）
               }else{
                 $lefttime=0;
                 die("活动已经结束！");
              }
          }
          $data = $goods;
      }
      echo JSON($data);
    }

 //购物车
    Public function Cart_index(){
       //unset($_SESSION['Cart']);
       $Cart=$_SESSION['Cart'];
       foreach ($Cart as $key => $val) {
         $tmp = D('GoodsView')->where(array('id' =>$val['gid']))->field('photo1,saleprice')->find();
         $Cart[$key]['img'] = $tmp['photo1'];
         $Cart[$key]['price'] = $tmp['saleprice'];
         $Cart['totalprice'] = $Cart['totalprice'] + $tmp['saleprice']*$Cart[$key]['goodsnum'];
       }
              p($Cart);
       $this->assign('Cart',$Cart);
      // $this->display();
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

     //商品加入购物车
  Public function addcart(){
    if (IS_AJAX===false) return false;
    $gid = $_POST['gid'];
    $data[$gid]['gid']=$gid;
    $data[$gid]['goodsname']=$_POST['goodsname'];
    $data[$gid]['goodsnum']=$_POST['goodsnum'];
    //判断提交的数量是否超过库存
    if (!empty($_POST['property1']) && !empty($_POST['property2'])) {
      //有两属性时
      $map['property1'] = $_POST['property1'];
      $map['property2'] = $_POST['property2'];
      $map['gid'] = $_POST['gid'];
      $t_num = M('shop_g_property')->where($map)->field('goodsNum')->find(); 
    }elseif (!empty($_POST['property1']) && empty($_POST['property2'])) {
       //单属性时
      $map['property1'] = $_POST['property1'];
      $map['gid'] = $_POST['gid'];
      $t_num = M('shop_g_property')->where($map)->field('goodsNum')->find(); 
    }else{
      $t_num = M('shop_goods')->where(array('id' =>$gid))->field('num')->find(); 
    }
    if ($_POST['goodsnum'] > $t_num['goodsNum']) {
         $msg = false;
    }else{
           //判断购物车是否有相同商品
        if (array_key_exists($gid,$_SESSION['Cart'])) {
          $_SESSION['Cart'][$gid]['goodsnum']=$_SESSION['Cart'][$gid]['goodsnum']+ $_POST['goodsnum'];                   
        }else {
           $_SESSION['Cart'][$gid]['gid'] = $data[$gid]['gid'];
           $_SESSION['Cart'][$gid]['goodsname'] = $data[$gid]['goodsname'];
           $_SESSION['Cart'][$gid]['goodsnum'] = $data[$gid]['goodsnum'];
        }
        $msg = true;
    }

   exit(json_encode($msg));
  }










 }

?>