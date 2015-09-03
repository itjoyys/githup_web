<?php 
/*
   微信商城
*/
 Class ShopAction extends Action{
     //判断session
     Public function check_openid(){
       $shop_config = $_SESSION['shop_config'];
       if (empty($shop_config)) {
          //为空则重新访问
          echo "请重新访问";
           die();
       }

     }
    //商城首页
     Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        $openid= $_GET['openid']; 
        $image_url = C('image_url');
        $shopconfig=M('shopconfig')->where(array('siteid' => $siteid))-> find();
        $shop_config = array(
          'shop_siteid' => $siteid,
          'shop_openid' => $openid,
          'shop_i_tpl' => $shopconfig['index_tpl'],
          'shop_l_tpl' => $shopconfig['list_tpl'],
          'shop_d_tpl' => $shopconfig['detail_tpl']
        );  
        session('shop_config',$shop_config);
        import('Class.Category', APP_PATH);
        if (!$cate=S('index_cate')) {
             $cate= M('shop_cate') ->where(array('siteid' => $siteid,'pid'=>0))-> order('sort DESC')-> select();
            // $cate = Category::unlimitedForLevel($cate);
             S('index_cate',$cate,10);
         }
  
         //幻灯片读取
        if (!$adlist=S('index_adlist')) {
             $adlist=M('shopadlist')->where(array('siteid' => $siteid))->order('sort ASC')->select();
             S('index_adlist',$adlist,10);
         }
        
         //读取新上架商品
         if (!$newgoods=S('index_ngoods')) {
            
            $newgoods=D('GoodsView')->where(array('property'=>'2','siteid'=>$siteid))->select();
            S('index_ngoods',$newgoods,10);
         }

         //读取特价商品
         if (!$salegoods=S('index_sgoods')) {
            $salegoods=D('GoodsView')->where(array('property'=>'3','siteid'=>$siteid))->select();
            S('index_sgoods',$salegoods,10);
         }


          //读取热卖商品
         if (!$hotgoods=S('index_hgoods')) {
             $hotgoods=D('GoodsView')->where(array('property'=>1,'siteid'=>$siteid))->select();
             S('index_hgoods',$hotgoods,10);
         }

         session('l',$shopconfig[0]['list_tpl']);
         session('d',$shopconfig[0]['detail_tpl']);
         session('copyright', $shopconfig['title']);
         $this->assign('title',$shopconfig['title']);
         $this->assign('Keywords',$shopconfig['Keywords']);
         $this->assign('Description',$shopconfig['Description']);
         $this->assign('logo',$shopconfig['shoplogo']);
         $this->assign("adlist", $adlist);
         $this->newgoods=$newgoods;
         $this->hotgoods=$hotgoods;

         $this->cate=$cate; 
         $this->image_url = $image_url;
         $this->display(index.$shopconfig['index_tpl']);
    }

    //列表页
    Public function slist(){
         $this->check_openid();
       $shop_config = $_SESSION['shop_config'];
       $cid = $_GET['cid'];
       $goods = D('GoodsView')->where(array('classid'=>$cid))->select();
       $this->goods = $goods;
       $this->image_url = C('image_url');
       $this->display(slist.$shop_config['shop_l_tpl']); 
    }



    //详细页面
    Public function detail(){
       $this->check_openid();
       $image_url = C('image_url');
       $shop_config = $_SESSION['shop_config'];
       $gid = $_GET['gid'];
       $goods = D('GoodsView')->where(array('id'=>$gid))->find();
       $property_goods = M('shop_g_property')->where(array('gid'=>$gid))->select();
       $this->goods = $goods;
       $this->image_url = $image_url;
       $this->property_goods = $property_goods;
       $this->display(detail.$shop_config['shop_d_tpl']); 
       
    }

    //所有商品
    Public function all(){
       $this->check_openid();
       $shop_config = $_SESSION['shop_config'];
       $goods_all = M('shop_goods')->where(array('siteid'=>$shop_config['shop_siteid']))->select();
       $this->goods_all = $goods_all;
       $this->display(slist1);
    }
 
  /**
   商城购物车 订单

  */
  //个人中心
   Public function person(){
      $this->check_openid();
      
      $this->display();
   }


 //购物车
    Public function Cart_index(){
       $this->check_openid();
       $image_url = C('image_url');
       $shop_config = $_SESSION['shop_config'];
       $home_url = $image_url.'/index.php/Index/Shop/siteid/'.$shop_config['shop_siteid'].'?openid='.$shop_config['shop_openid'];
       $Cart=$_SESSION['Cart'];
       foreach ($Cart as $key => $val) {
         $tmp = D('GoodsView')->where(array('id' =>$val['gid']))->field('photo1,saleprice')->find();
         $Cart[$key]['img'] = $tmp['photo1'];
         $Cart[$key]['price'] = $tmp['saleprice'];
         $totalprice = $totalprice + $tmp['saleprice']*$Cart[$key]['goodsnum'];
       }
       $this->image_url = $image_url;
       $this->totalprice = $totalprice;
       $this->siteid = $shop_config['shop_siteid'];
       $this->openid = $shop_config['shop_openid'];
       $this->home_url = $home_url;
       $this->cart = $Cart;
       $this->display();
    }


      //删除购物车里面的商品
    Public function deleteCart(){
        $this->check_openid();
        $Cart_gid=$_POST['gid'];
        $totalprice = $_POST['totalprice'];
        $tmp = $_SESSION['Cart'][$Cart_gid];
        unset($_SESSION['Cart'][$Cart_gid]);
        $num = count($_SESSION['Cart']);
        if($num == 0){
            $msg['state'] = false;
        }else{
            $tmp_i = D('GoodsView')->where(array('id' =>$Cart_gid))->field('saleprice')->find();
            $totalprice = $totalprice - $tmp_i['saleprice']*$tmp['goodsnum'];
            $msg['state'] = true;
            $msg['totalprice'] = $totalprice;
        }
        exit(json_encode($msg));
    }

     //商品加入购物车
  Public function addcart(){
      $this->check_openid();
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
    if ($_POST['goodsnum'] > $t_num['num']) {
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

  //提交订单
   Public function Order(){
     $this->check_openid();
    //订单信息组合
    $gid = $_POST['gid'];
    $Cart = $_SESSION['Cart'];
    if(count($gid) != count($Cart)){
        echo '错误信息，请重新访问';
        die();
    }

    //订单处理
      foreach ($Cart as $key => $val) {
           $tmp = D('GoodsView')->where(array('id' =>$val['gid']))->field('photo1,saleprice')->find();
           $Cart[$key]['img'] = $tmp['photo1'];
           $Cart[$key]['price'] = $tmp['saleprice'];
           $o_num = $o_num + $Cart[$key]['goodsnum'];//商品总数
           $totalprice = $totalprice + $tmp['saleprice']*$Cart[$key]['goodsnum'];
       }
      $_POST['openid'] = 145;
    //地址读取
     $s_address = M('address')->where(array('openid' => $_POST['openid'],'state'=>1))->find();
     if(!empty($s_address)){
         //非空
         if($s_address['province'] == $s_address['city']){
             //直辖市 自治区等等
             $address = $s_address['city'].$s_address['address'];
         }else{
             $address = $s_address['province'].$s_address['city'].$s_address['address'];
         }
     }else{
         //地址为空，则添加地址
         $address_html = '点击添加地址';
     }
     $address1 = utf8Substr($address,0,3);
     $address2 = utf8Substr($address,0,4);
     $map['siteid'] = $shop_config['shop_siteid'];
     $map['area']=array('in',"$address1,$address2");
     $User = M('wuliu');
     $User->create();
     $freight = $User->where($map)->getField('freight');//读取邮费
     $tprice = M('shopconfig')->where(array('siteid'=>$_POST['siteid']))->getField('tprice');//获取包邮设置
     if (empty($tprice)) {
       //包邮费用为空
        $freight = '免运费';
     }else{
       //设置包邮费用
        if ($totalprice < $tprice) {
          $totalprice = $totalprice + $freight;
          $freight='运费:'.$freight;
       }else{
          $freight='免运费';
       }
     }
     $this->goods_num = $o_num;//商品数量
     $this->freight = $freight;
     $this->address = $address;
     $this->image_url = C('image_url');
     $this->totalprice = $totalprice;//总费用
     $this->name = $s_address['name'];
     $this->tel = $s_address['tel'];
     $this->openid = $_POST['openid'];
     $this->siteid = $_POST['siteid'];
     $this->address_id = $s_address['id'];//默认地址id;
     $this->goods = $Cart;
     $this->display();
   }


   //订单处理
  Public function run_order(){
      //地址读取
      $s_address = M('address')->where(array('openid' => $_POST['openid'],'state'=>1))->find();

      $data['siteid'] = $_POST['siteid'];
      $data['openid'] = $_POST['openid'];
      $data['remark'] = $_POST['remark'];//备注信息
      $data['date'] = date("Y-m-d H:i:s"); //订单生成时间
      $data['coupon_id'] = $_POST['coupon_id'];//使用的优惠券id,默认为0，表示无
      $data['pay_state'] = 0;//支付状态,0表示未支付
      //订单状态,0表示关闭订单,1表示待发货,2表示已经发货,3表示已经签收
      $data['order_state'] = 1;


       //生成随机20位订单编号
      $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
      $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
      $data['order_id'] = $orderSn;//订单编号
      $data['pay_type'] = $_POST['pay_type'];//订单支付方式
  
      $data['name'] = $s_address['name'];
      $data['address_id'] = $s_address['id'];
      $data['tel'] = $s_address['tel'];//联系电话
      //订单信息
      $gid = $_POST['gid'];
      $goodsnum = $_POST['goodsnum'];
      $data['totalprice'] = $_POST['totalprice'];//订单总金额

      $u = M('shop_order');
      $u ->create();
      $oid = $u->add($data);

      foreach ($gid as $key => $val) {
          $data_o['gid'] = $val;
          $data_o['goodsnum'] = $goodsnum[$key];//购买商品个数
          $data_o['order_id'] = $orderSn;//订单编号
          $data_o['oid'] = $oid;//订单号

          $o = M('shop_order_detail');
          $o ->create();
          $o ->add($data_o);
      }
      $this->name = $data['name'];//姓名
      $this->tel = $data['tel'];//电话
      $this->address = $s_address['address_d'];//地址
      $this->order_id = $orderSn;//订单编号
      $this->date = $data['date'];//订单时间
      $this->order_state = $data['order_state'];//订单状态
      $this->order_price = $data['totalprice'];//订单金额

      $seller = M('shop_config')->where(array('siteid'=>$_POST['siteid']))->find();
      $this->seller_name = $seller['title'];//商家
      $this->seller_tel = $seller['tel'];//商家客服电话
      if ($pay_type == 0) {
         //表示货到付款
         $this->display(order_success);
      }
  }

  //收货人信息
  Public function address_index(){
    $shop_config = $_SESSION['shop_config'];
    $map['openid'] = $shop_config['shop_openid']=145;
    $map['detele_s'] = 1;
    $map_n['state'] = 1;
    $map_n['openid'] = $shop_config['shop_openid']=145;
    $map_n['detele_s'] = 1;
    $address_d = M('address')->where($map)->order('state DESC')->select();
    $nid = M('address')->where($map_n)->field('id')->find();
    $this->nid = $nid['id'];
    $this->address_d = $address_d;
    $this->display();
  }

  //添加收货人信息
  Public function add_address(){
   $shop_config = $_SESSION['shop_config'];
   $openid = $shop_config['shop_openid'];
   $this->openid = $openid=145;
   $this->siteid = $shop_config['shop_siteid'];
   $this->display();
  }

  //添加收货人处理
  Public function run_add_address(){
        $data['name'] = $_POST['name'];
        $data['siteid'] = $_POST['siteid'] = 1;
        $data['openid'] = $_POST['openid'];
        $data['address'] = $_POST['address'];
        $data['tel'] = $_POST['tel'];
        $data['province'] = $_POST["s_province"];//省份
        $data['city'] = $_POST["s_city"];//省份
        $data['province'] = $_POST["s_province"];//省份
        $data['section'] = $_POST["s_county"];//省份
        $as1 = $_POST["s_province"];//省份
        $as2 = $_POST["s_city"];//市
        $as3 = $_POST["s_county"];
        if ($as1!=$as2) {
          $data['address_d'] = $as1.$as2.$as3.$_POST['address'];//地址全部
        }else{
          $data['address_d'] = $as1.$as3.$_POST['address'];
        }
        $data['detele_s'] = 1;//删除的状态，0表示删除
        $list = M('address')->where(array('openid'=>$data['openid']))->find();
        if (empty($list)) {
           $data['state'] = 1;//表示默认地址
        }
        $User = M('address');
        $User->create();
        $User->add($data);
        $this->redirect('Index/Shop/address_index');
  }

  //地址删除
  Public function delete_address(){
     $id = $_POST['id'];
     $data['delete_s'] = 0;
     $u = M('address');
     $u->create();
     $u->where(array('id'=>$id))->save($data);
     $msg = true;
     echo json_encode($id);
  }
  //设置默认地址
  Public function do_address(){
    $cid = $_POST['aid'];//之前默认地址id
    $nid = $_POST['nid'];//现在默认地址di

     $u = M('address');
     $u->delete_s = 0;
     $u->create();
     $u->where(array('id'=>$cid))->save();

     $n = M('address');
     $n->delete_s = 1;
     $n->create();
     $n->where(array('id'=>$nid))->save();
     $msg = true;
     echo json_encode($msg);
  }




 }
?>