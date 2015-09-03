<?php 
/*
  微商城设置控制器
*/
 Class ShopAction extends CommonAction{
    //微信商城设置视图
    Public function Index(){
       $siteid=$_SESSION['siteid'];
       $shopconfig=M('shopconfig')->where(array('siteid' => $siteid))->find();
	   $image_url=C("image_url");
	   $shopurl=$image_url."/index.php/Index/Shop/siteid".'/'.$siteid;
       $this->assign('shopurl',$shopurl);
       $this->assign('title',$shopconfig['title']);
       $this->assign('Keywords',$shopconfig['Keywords']);
       $this->assign('Description',$shopconfig['Description']);
       $this->assign('logo',$shopconfig['shoplogo']);
       $this->assign('bg',$shopconfig['shopbg']);
	   $this->assign('notes',$shopconfig['notes']);
       $this->assign('tel',$shopconfig['tel']);
       $this->assign('tprice',$shopconfig['tprice']);
       $this->assign("image_url", $image_url);   
       $this->assign('siteid',$siteid);
       $this ->display();  
    }

    Public function Shopconfig(){
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/shop/';// 设置附件上传目录
        $data['title'] = $_POST['title']; 
        $data['Keywords'] = $_POST['Keywords']; 
        $data['Description'] = $_POST['Description'];
        $data['title'] = $_POST['title']; 
        $data['siteid'] = $_POST['siteid']; 
        $data['tel'] = $_POST['tel']; 
        $data['tprice'] = $_POST['tprice']; 
        if (!empty($_FILES['shoplogo']['tmp_name']) && !empty($_FILES['shopbg']['tmp_name'])) {
           if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
            }
            $data['shoplogo']='/Uploads/shop/'.$info[0]["savename"]; 
            $data['shopbg']='/Uploads/shop/'.$info[1]["savename"]; 
        }elseif (!empty($_FILES['shoplogo']['tmp_name'])) {
           if(!$upload->upload()) {// 上传错误提示错误信息
               $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
               $info =  $upload->getUploadFileInfo();
            }
            $data['shoplogo']='/Uploads/shop/'.$info[0]["savename"]; 
        }elseif (!empty($_FILES['shopbg']['tmp_name'])) {
            if(!$upload->upload()) {// 上传错误提示错误信息
               $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
               $info =  $upload->getUploadFileInfo();
            }
            $data['shopbg']='/Uploads/shop/'.$info[0]["savename"];
        } 
            $siteid=$_SESSION['siteid'];
            $User = M("shopconfig"); // 实例化User对象  
            $list = M("shopconfig")->where(array('siteid' => $siteid))->select();
           if (!$list) {
            $User->create(); // 创建数据对象               
            $User->add($data); // 写入用户数据到数据库
            $this->success("数据保存成功！");
           }else{
            $User->create(); // 创建数据对象         
            $User->where(array('siteid'=>$data['siteid']))->save($data); // 写入用户数据到数据库
            $this->success("数据更新成功！");
           }
    }
	
	//商城模板
	Public function shop_index(){
	 $siteid = $_SESSION['siteid'];
	 $shop_index=include "shop_index.php";
   $index_tpl = M("shopconfig")->where(array('siteid'=>$siteid))->field('index_tpl')->find();
	 $this->shop_index = $shop_index;
   $this->image_url = C('image_url');
   $this->index_tpl = $index_tpl['index_tpl'];
	 $this->siteid = $siteid;
	 $this->display();
	}
	
    Public function shop_list(){
   $siteid = $_SESSION['siteid'];
	 $shop_list=include "shop_list.php";
   $list_tpl = M("shopconfig")->where(array('siteid'=>$siteid))->field('list_tpl')->find();
   $this->list_tpl = $list_tpl['list_tpl'];
   $this->image_url = C('image_url');
	 $this->shop_list = $shop_list;
	 $this->siteid = $siteid;
	 $this->display();
	}
	
    Public function shop_detail(){
   $siteid = $_SESSION['siteid'];
	 $shop_detail=include "shop_detail.php";
   $detail_tpl = M("shopconfig")->where(array('siteid'=>$siteid))->field('detail_tpl')->find();
   $this->detail_tpl = $detail_tpl['detail_tpl'];
   $this->image_url = C('image_url');
	 $this->shop_detail = $shop_detail;
	 $this->siteid =$siteid;
	 $this->display();
	}

    //模板选中处理
    Public function shop_ajax(){
      $siteid=$_SESSION['siteid'];
      $User=M('shopconfig');
      $User->create();
      if (!empty($_POST['index_tpl'])) {
          $data['index_tpl']=$_POST['index_tpl'];//首页模板
          if ($User->where(array('siteid'=>$siteid))->save($data)) {
              $msg="保存首页模板成功";
          }else{
              $msg="保存首页模板失败";
           }
       }elseif (!empty($_POST['list_tpl'])) {
           $data['list_tpl']=$_POST['list_tpl'];//列表页模板
           if ($User->where(array('siteid'=>$siteid))->save($data)) {
               $msg="保存列表页模板成功";
           }else{ 
               $msg="保存列表页模板失败";
           }
               
       }else{
           $data['detail_tpl']=$_POST['detail_tpl'];//内容页模板
           if ($User->where(array('siteid'=>$siteid))->save($data)) {
               $msg="保存内容页模板成功";
            }else{
              $msg="保存内容页模板失败";
            }
                
       }
      echo $msg;
    }


	
	//购买须知
	Public function notes(){
	    $User = M("shopconfig"); // 实例化User对象  
      $User->create(); // 创建数据对象   
      $siteid = $_POST['siteid']; 
      $data['notes'] = $_POST['notes'];        
      $User->where(array('siteid'=>$siteid))->save($data); // 写入用户数据到数据库
      $this->success("数据更新成功！"); 
	}

	//商城广告管理 幻灯片 列表广告 详细页
  Public function shopad (){
    $siteid = $_SESSION['siteid'];
    $image_url = C('image_url');
    $adlist = M('shopadlist') ->where(array('siteid' => $siteid))->order('sort ASC')-> select();
    $ads= M('ads') ->where(array('siteid' => $siteid))->order('sort ASC')-> select();
    foreach ($ads as $key => $val) {
      if ($val['cid'] == 2) {
         $ads[$key]['cid'] = '详情页广告';
      }else{
         $ads[$key]['cid'] = '列表页广告';
      }
    }
    $this->ads = $ads;
    $this->adlist = $adlist;
    $this->image_url = $image_url;   
    $this->display();  
  }
  //添加幻灯片
Public function add_adlist(){
  $this->siteid = $_SESSION['siteid'];
  $this->display();
}
//添加幻灯片表单
Public function run_add_adlist(){
  import("ORG.Net.UploadFile");
  $upload = new UploadFile();// 实例化上传类
  $upload->maxSize  = 2000000 ;// 设置附件上传大小
  $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
  $upload->autoSub  = true;// 启用子目录保存文件
  $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
  $upload->dateFormat = 'Ym';  
  $upload->savePath =  './Uploads/adlist/';// 设置附件上传目录 
  if(!$upload->upload()) {// 上传错误提示错误信息
     $this->error($upload->getErrorMsg());
  }else{// 上传成功 获取上传文件信息
     $info =  $upload->getUploadFileInfo();  
  }
   $User = M("shopadlist"); // 实例化User对象  
   $User->create(); // 创建数据对象
   $User->title = $_POST['title']; 
   $User->siteid = $_POST['siteid']; 
   $User->sort = $_POST['sort']; 
   $User->photo = '/Uploads/adlist/'.$info[0]["savename"]; 
   $User->add(); // 写入用户数据到数据库
   $this->success("添加幻灯片成功！");
}

//删除幻灯片
 Public function adlist_delete(){
    $id= I('id','0','intval');
     if( M('shopadlist')->where(array('id'=>$id))->delete()){
          $this->success('删除成功',U('Admin/Shop/shopad'));
      }else{
           $this->error('删除失败',U('Admin/Shop/shopad'));
      }  
 }

 //添加广告
 Public function add_ad(){
  $this->siteid = $_SESSION['siteid'];
  $this->display();
 }

 //添加广告处理
 Public function run_add_ad(){
     import("ORG.Net.UploadFile");
     $upload = new UploadFile();// 实例化上传类
     $upload->maxSize  = 2000000 ;// 设置附件上传大小
     $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
     $upload->autoSub  = true;// 启用子目录保存文件
     $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
     $upload->dateFormat = 'Ym';  
     $upload->savePath =  './Uploads/adlist/';// 设置附件上传目录
      if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
      }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
      }
      $User = M("shop_ads"); // 实例化User对象  
      $User->create(); // 创建数据对象
      $User->title = $_POST['title']; 
      $User->siteid = $_POST['siteid']; 
      $User->cid = $_POST['ad_c']; 
      $User->sort = $_POST['sort']; 
      $User->img = '/Uploads/adlist/'.$info[0]["savename"]; 
      $User->add(); // 写入用户数据到数据库
      $this->success("数据保存成功！");
 }
 //删除广告
 Public function ad_delete(){
     $id= I('id','0','intval');
     if( M('shop_ads')->where(array('id'=>$id))->delete()){
         $this->success('删除成功',U('Admin/Shop/shopad'));
     }else{
        $this->error('删除失败',U('Admin/Shop/shopad'));
     }
 }

  //商城分类
  Public function shop_cate(){
    $id = I('id',0,'intval');
    $siteid = $_SESSION['siteid'];
    import('Class.Category', APP_PATH);
    if ($id != 0) {
      //表示下级分类
      $cate = M('shop_cate')->where(array('pid'=>$id))->order('sort ASC')-> select();
    }else{
      $cate = M('shop_cate')->where(array('siteid'=>$siteid))->order('sort ASC')-> select();
      $cate = Category::shopCate($cate); 
      foreach ($cate as $key => $val) {
      $cate[$key]['down_state'] = count($val['child']);
      }
    }
    $this->cate_id = $id;
    $this->image_url = C('image_url');
    $this->cate = $cate;
    $this->siteid = $siteid;
    $this->display();
  }

  //添加分类处理
  Public function run_shop_cate(){
    if (empty($_POST['name'])) {
      $this->error('数据不能为空');
    }
    $id = I('id',0,'intval');
    import("ORG.Net.UploadFile");
    $upload = new UploadFile();// 实例化上传类
    $upload->maxSize  = 2000000 ;// 设置附件上传大小
    $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->autoSub  = true;// 启用子目录保存文件
    $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
    $upload->dateFormat = 'Ym';  
    $upload->savePath =  './Uploads/shop/';// 设置附件上传目录
    if (!empty($_FILES['img']['tmp_name'])) {
      if(!$upload->upload()) {// 上传错误提示错误信息
         $this->error($upload->getErrorMsg());
      }else{// 上传成功 获取上传文件信息
         $info =  $upload->getUploadFileInfo();  
      }
      $data['img'] = '/Uploads/shop/'.$info[0]["savename"]; 
    }
    if (empty($id)) {
      //为空代表添加
      $data['pid'] = I('pid',0,'intval');//上级分类
      $data['siteid'] = $_POST['siteid'];
      $data['name'] = $_POST['name'];
      $data['sort'] = $_POST['sort'];
      $c = M('shop_cate');
      $c->create();
      $c->add($data);
      $this->success('添加分类成功！');
    }else{
      //非空代表修改
      $data['pid'] = I('pid',0,'intval');//上级分类
      $data['siteid'] = $_POST['siteid'];
      $data['name'] = $_POST['name'];
      $data['sort'] = $_POST['sort'];
      $c = M('shop_cate');
      $c->create();
      $c->where(array('id'=>$id))->save($data);
      $this->success('编辑分类成功！');
    }
  }
   //删除分类
  Public function cate_delete(){
     $id= I('id', 0, 'intval');
     if(M('shop_cate')->delete($id)){
          $this->success('删除成功',U('Admin/Shop/shop_cate'));
      }else{
          $this->error('删除失败',U('Admin/Shop/shop_cate'));
      }   
  }      
     //商品分类修改
  Public function revisecate(){
      $id=I('id',0,'intval');//当前id
      $map['siteid'] = $_SESSION['siteid'];
      $map['pid'] = 0;
      $up_cate= M('shop_cate') ->where($map)->order('sort ASC')-> select();
      $image_url = C('image_url');
      $cate=M('shop_cate')->where(array('id'=>$id))->find();
      $this->id = $id;//当前id
      $this->up_id = $cate['pid'];
      $this->image_url = $image_url;
      $this->up_cate = $up_cate;
      $this->siteid = $_SESSION['siteid'];
      $this->assign('name',$cate['name']);
      $this->assign('img',$cate['img']);
      $this->assign('sort',$cate['sort']);
      $this->display();
  }

  //商品属性添加
  Public function add_property(){
     import('Class.Category',APP_PATH);
     $id = I('id',0,'intval');
     $map_u['pid'] = 0;
     $map_u['cid'] = $id;
     $map_u['siteid'] = $_SESSION['siteid'];
     $up_property = M('shop_property')->where($map_u)->select();
     $map['siteid'] = $_SESSION['siteid'];
     $map['cid'] = $id;
     $property = M('shop_property')->where($map)->select();
     $property = Category::shopCate($property); 
     $this->cid = $id;
     $this->property = $property;
     $this->up_property = $up_property;
     $this->siteid = $_SESSION['siteid'];
     $this->display();
  }

  //商品属性添加
  Public function run_add_property(){
    $data['name'] = $_POST['name'];
    $data['cid'] = $_POST['cid'];
    $data['siteid'] = $_SESSION['siteid'];
    $data['pid'] = $_POST['pid'];
    $data['sort'] = $_POST['sort'];
    $p = M('shop_property');
    $p->create();
    $p->add($data);
    $this->success('添加属性成功！');
    
  }
  //删除属性
  Public function property_delete(){
    $id = I('id',0,'intval');
    if(M('shop_property')->where(array('id'=>$id))->delete()){
      $this->success('删除属性成功');
    }else{
      $this->error('删除属性失败');
    }
  }
//添加商品
  Public function add_goods(){
     $siteid=$_SESSION['siteid'];
     $this->assign('siteid',$siteid);
     import('Class.Category', APP_PATH);
     $map['siteid'] = $siteid;
     $map['cid'] = I('id',0,'intval');
     $property = M('shop_property')->where($map)->order('sort ASC')-> select();
     $property = Category::shopCate($property);
     $this->property = $property;
     $this->classid = I('id',0,'intval');
     $this->display();
  }

  //AJAX添加商品属性 添加商品时
   Public function goods_property(){
     $tNum = $_POST['t'];//表示属性个数
     $property = trim($_POST['property']);
     $property = explode(',',$property);//将字符串分隔成数组
     $property = array_filter($property);//去掉空元素
     $i = 1;
     if (2 == $tNum) {
         $property_a[0]['p1'] = $_POST['sname1'];
         $property_a[0]['p2'] = $_POST['sname2'];
         foreach ($property as $key => $val) {
           if (strstr($val, 'p1')) {
               $wNum = strrpos($val,'_')+1;//获取位置
               $p1 = substr($val,$wNum);//获取属性值
               $iNum = strrpos($val,'_')-3;
               $p1_id = substr($val,3,$iNum);//获取属性值id
                foreach ($property as $key_i => $val_i) {
                   if (strstr($val_i, 'p2')) {
                      $wNum = strrpos($val_i,'_')+1;//获取位置
                      $p2 = substr($val_i,$wNum);//获取属性值
                      $iNum = strrpos($val_i,'_')-3;
                      $p2_id = substr($val_i,3,$iNum);//获取属性值id
                      $property_a[$i]['p1'] = $p1;
                      $property_a[$i]['p1_id'] = $p1_id;
                      $property_a[$i]['p2'] = $p2;
                      $property_a[$i]['p2_id'] = $p2_id;
                      $i++;
                   }
                }
           }
         }
     }else{
           $property_a[0]['p1'] = $_POST['sname1'];
         foreach ($property as $key => $val) {
           $wNum = strrpos($val,'_')+1;//获取位置
           $p1 = substr($val,$wNum);//获取属性值
           $iNum = strrpos($val,'_')-3;
           $p1_id = substr($val,3,$iNum);//获取属性值id
           if (strstr($val, 'p1')) {
               $property_a[$i]['p1'] = $p1;
               $property_a[$i]['p1_id'] = $p1_id;
               $map['property1'] = $p1_id;
               $map['gid'] = $gid;
               $tmp = M('shop_g_property')->where($map)->find();
               $property_a[$i]['goodsSaleprice'] =$tmp['goodsSaleprice'];
               $property_a[$i]['goodsPrice'] = $tmp['goodsPrice'];
               $property_a[$i]['goodsNum'] = $tmp['goodsNum'];
               $property_a[$i]['shop_p'] = $tmp['id'];
               $i++;
           }
         }
     }
     echo JSON($property_a);
   }

   //添加商品处理
   Public function run_add_goods(){
        $shop_p = $_POST['shop_p'];//商品属性信息对应id
        $property1 = $_POST['property1'];//属性一
        $property2 = $_POST['property2'];//属性二
        $goodsSaleprice = $_POST['goodsSaleprice'];//销售价格
        $goodsPrice = $_POST['goodsPrice'];//市场价格
        $goodsNum = $_POST['goodsNum'];//库存数量‘

        $data['name'] = $_POST['name'];  //商品标题
        $data['siteid'] = $_POST['siteid']; //站点id
        $data['classid'] = $_POST['classid']; //商品分类id
        if (empty($_POST['property'])) { //商品是否推荐热门
           $data['property'] = 0;
        }else{
           $data['property'] = $_POST['property'];
        }  
        $data['num'] = $_POST['num']; //商品库存
        $data['saleprice'] = $_POST['saleprice'];//销售价格
        $data['price'] = $_POST['price'];//市场价格
        $data['details'] = $_POST['details'];  //商品图文详情
            //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/goods/';// 设置附件上传目录
    
        if (empty($_FILES['photo1']['tmp_name']) && empty($_FILES['photo2']['tmp_name']) && empty($_FILES['photo3']['tmp_name']) &&
          empty($_FILES['photo4']['tmp_name'])) {
        }else{
            if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
                $info =  $upload->getUploadFileInfo();
            }
            $data_p['siteid'] = $_POST['siteid'];
            $e = 0;
            for ($i=1; $i < 5; $i++) { 
              $t_str = 'photo'.$i;
              if (!empty($_FILES[$t_str]['tmp_name'])) {
                  $data_p[$t_str] = '/Uploads/goods/'.$info[$e]["savename"]; 
                  $e++;
              }
            }
        }

    if (empty($_POST['gid'])) {
        //为空表示添加
        //生成随机20位订单编号
         $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
         $goodsid = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
         $data['goodsid'] = $goodsid;
         $data_p['goodsid'] = $goodsid;
         //添加商品信息
         $g = M('shop_goods');
         $g->create();
         $data_p['gid']=$g ->add($data);
         //添加图片到数据库
         $p = M('shop_photo');
         $p ->create();
         $p ->add($data_p);

         if (!empty($_POST['property1'])) {
           //商品属性添加
          foreach ($property1 as $key => $val) {
               $u = M('shop_g_property');
               $u ->create();
               $u->gid = $data_p['gid'];
               $u->goodsid = $goodsid;
               $u->property1 = $val;
               $u->goodsSaleprice = $goodsSaleprice[$key];
               $u->goodsPrice = $goodsPrice[$key];
               $u->goodsNum = $goodsNum[$key];
               if (!empty($_POST['property2'])) {
                 //两种属性
                  $u->property2 = $property2[$key];
               }
                  $u->add();
              }
        }
        $this->success('添加成功');
    }else{
        //表示编辑
        $g = M('shop_goods');
        $g->create();
        $g ->where(array('id'=>$_POST['gid']))->save($data);
 
        $p = M('shop_photo');
        $p ->create();
        $p ->where(array('gid'=>$_POST['gid']))->save($data_p);
        $shop_g_property = M('shop_g_property')->where(array('gid'=>$_POST['gid']))->select();
           //商品属性更新
        $User = M('shop_g_property');
        $User->create();
        foreach ($shop_g_property as $k_pg => $val_pg) {
            $astate = in_array($val_pg['id'],$shop_p);
            if ($astate) {        
            }else{
                $User->where(array('id'=>$val_pg['id']))->delete();
            }
        }
        foreach ($property1 as $key_z => $val_z) {
            $data_u['gid'] = $_POST['gid'];
               //$data_u['goodsid'] = $goodsid;
            $data_u['property1'] = $val_z;
            $data_u['goodsSaleprice'] = $goodsSaleprice[$key_z];
            $data_u['goodsPrice'] = $goodsPrice[$key_z];
            $data_u['goodsNum'] = $goodsNum[$key_z];
            if (!empty($_POST['property2'])) {
                 //两种属性
               $data_u['property2'] = $property2[$key_z];
             }
             if (empty($shop_p[$key_z])) {
                //如果提交的id为空，则添加
                $User->add($data_u);
             }else{
                    //更新
                $User->where(array('id'=>$shop_p[$key_z]))->save($data_u);   
             }
        }
      $this->success('更新数据成功');
   }
 }
       //编辑器图片上传处理
   Public function upload(){    
        import('ORG.Net.UploadFile'); 
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';       
        if ($upload->upload('./Uploads/details/')) {
             $info=$upload->getUploadFileInfo();
             echo json_encode(array(
                     'url'=>$info[0]['savename'],
                     'title'=>htmlspecialchars($_POST['pictitle'],ENT_QUOTES),
                     'original'=>$info[0]['name'],
                     'state' =>'SUCCESS'
              ));  
              return $info;    
        }else{
          echo json_encode(array(
            'state' =>$upload->getErrorMsg()
            ));
        }
        }
  //商城商品管理
   Public function goods_index(){
     $siteid = $_SESSION['siteid'];
     import('Class.Category', APP_PATH);
     $cate= M('shop_cate') -> where(array('siteid' => $siteid))->order('sort')-> select();
     $this ->cate = Category::unlimitedForLevel($cate);
        
     import("ORG.Util.Page");
     $num=D('GoodsView')->where(array('siteid' => $siteid))->count();
     $page = new Page($num, 12);
     $limit=$page->firstRow.','.$page->listRows;
     $goods=D('GoodsView')->where(array('siteid' => $siteid))->limit($limit)->order('id DESC')->select();
     $this->goods=$goods;
     $this->page=$page->show();
     $this->assign('siteid',$siteid);
     $this->display();
   }
   //商品修改
     Public function goods_revise(){
      $gid= $_GET["gid"];
      import('Class.Category', APP_PATH);
      $t= M('shop_goods')->where(array('id'=>$gid))->field('classid')->find();
      $g_property = M('shop_g_property')->where(array('gid'=>$gid))->select();//商品拥有的属性
      $map['siteid'] = $_SESSION['siteid'];
      $map['cid'] = $t['classid'];
      $property = M('shop_property')->where($map)->order('sort ASC')-> select();
      $property_g = M('shop_g_property')->where(array('gid'=>$gid))-> select();
      $property = Category::shopCate($property);
      
      foreach ($property as $k => $v) {
        foreach ($v['child'] as $k_i => $v_i) {
            foreach ($g_property as $k_g => $v_g) {
              if ($v_i['id'] == $v_g['property1'] || $v_i['id'] == $v_g['property2']) {
                $property[$k]['child'][$k_i]['pstate'] = 1;
               }
            }
        }
      }

      $this->property = $property;
      $goods = D('GoodsView')->where(array('id'=>$gid))->find();

      $u_map['pid'] = 0;
      $u_map['cid'] = $t['classid'];
      $u_property = M('shop_property')->where($u_map)->select();

      $property_a[0]['p1'] = $u_property[0]['name'];
      $property_a[0]['p2'] = $u_property[1]['name'];
      $property_t[0]['p1'] = $u_property[0]['name'];
      $property_t[0]['p2'] = $u_property[1]['name'];
      $i = 1;
      $g_map['cid'] = $t['classid'];
      $g_map['pid'] = array('neq',0);
      $tmp_g = M('shop_property')->where($g_map)->select();
      $p_name1 = M('shop_property')->where(array('id'=>$g_property[0]['property1']))->field('name')->find();
      $p_name2 = M('shop_property')->where(array('id'=>$g_property[0]['property2']))->field('name')->find();
      foreach ($g_property as $key => $val) {
          foreach ($tmp_g as $k_tmp => $v_tmp) {
             if ($val['property1'] == $v_tmp['id']) {
                 $property_a[$i]['p1'] = $v_tmp['name'];
                 $property_t[$i]['p1'] = $v_tmp['name'];
             }elseif($val['property2'] == $v_tmp['id']){
                 $property_a[$i]['p2'] = $v_tmp['name'];
                 $property_t[$i]['p2'] = $v_tmp['name'];
             }
          }
           $property_a[$i]['p1_id'] = $val['property1'];
           $property_a[$i]['p2_id'] = $val['property2'];
           $property_a[$i]['goodsSaleprice'] = $val['goodsSaleprice'];
           $property_a[$i]['goodsPrice'] = $val['goodsPrice'];
           $property_a[$i]['goodsNum'] = $val['goodsNum'];

           $property_t[$i]['p1_id'] = $val['property1'];
           $property_t[$i]['p2_id'] = $val['property2'];
           $property_t[$i]['goodsSaleprice'] = $val['goodsSaleprice'];
           $property_t[$i]['goodsPrice'] = $val['goodsPrice'];
           $property_t[$i]['goodsNum'] = $val['goodsNum'];
           $property_t[$i]['shop_p'] = $val['id'];
           $i++;
      }
      $this->goods = $goods;
      $this->siteid = $_SESSION['siteid'];
      $this->gid = $gid;
      $this->classid = $t['classid'];
      $this->image_url = C('image_url');
      $this->property_t = $property_t;
      $this->property_g = $property_g;//判断该商品是否有对应属性信息
      // p($tmp_g);
      $this->property_a = JSON($property_a);
      $this->display();  
    }


  //AJAX添加商品属性 编辑商品时
   Public function r_goods_property(){
     $tNum = $_POST['t'];//表示属性个数
     $gid = $_POST['gid']; //商品id
     $property = trim($_POST['property']);
     $property = explode(',',$property);//将字符串分隔成数组
     $property = array_filter($property);//去掉空元素
     $i = 1;
     if (2 == $tNum) {
         $property_a[0]['p1'] = $_POST['sname1'];
         $property_a[0]['p2'] = $_POST['sname2'];
         foreach ($property as $key => $val) {
           if (strstr($val, 'p1')) {
               $wNum = strrpos($val,'_')+1;//获取位置
               $p1 = substr($val,$wNum);//获取属性值
               $iNum = strrpos($val,'_')-3;
               $p1_id = substr($val,3,$iNum);//获取属性值id
                foreach ($property as $key_i => $val_i) {
                   if (strstr($val_i, 'p2')) {
                      $wNum = strrpos($val_i,'_')+1;//获取位置
                      $p2 = substr($val_i,$wNum);//获取属性值
                      $iNum = strrpos($val_i,'_')-3;
                      $p2_id = substr($val_i,3,$iNum);//获取属性值id
                      $property_a[$i]['p1'] = $p1;
                      $property_a[$i]['p1_id'] = $p1_id;
                      $property_a[$i]['p2'] = $p2;
                      $property_a[$i]['p2_id'] = $p2_id;
                      $map['property1'] = $p1_id;
                      $map['property2'] = $p2_id;
                      $map['gid'] = $gid;
                      $tmp = M('shop_g_property')->where($map)->find();
                      $property_a[$i]['goodsSaleprice'] = $tmp['goodsSaleprice'];
                      $property_a[$i]['goodsPrice'] = $tmp['goodsPrice'];
                      $property_a[$i]['goodsNum'] = $tmp['goodsNum'];
                      $property_a[$i]['shop_p'] = $tmp['id'];
                      $i++;
                   }
                }
           }
         }
     }else{
         $property_a[0]['p1'] = $_POST['sname1'];
         foreach ($property as $key => $val) {
           $wNum = strrpos($val,'_')+1;//获取位置
           $p1 = substr($val,$wNum);//获取属性值
           $iNum = strrpos($val,'_')-3;
           $pid = substr($val,3,$iNum);//获取属性值id
           if (strstr($val, 'p1')) {
               $property_a[$i]['p1'] = $p1;
               $property_a[$i]['p1_id'] = $pid;
               $map['property1'] = $p1_id;
               $map['gid'] = $gid;
               $tmp = M('shop_g_property')->where($map)->find();
               $property_a[$i]['goodsSaleprice'] =$tmp['goodsSaleprice'];
               $property_a[$i]['goodsPrice'] = $tnp['goodsPrice'];
               $property_a[$i]['goodsNum'] = $tmp['goodsNum'];
               $i++;
           }
         }
     }
     //p($property_a);
     echo JSON($property_a);
   }

   //商品删除
     Public function goods_delete(){
       $gid= $_GET["gid"]; 
       $property = M('shop_g_property')->where(array('gid'=>$gid))->select();
       if (!empty($property)) {
           M('shop_g_property')->where(array('gid'=>$gid))->delete();
        } 
       if( M('shop_goods')->where(array('id'=>$gid))->delete()){
            if( M('shop_photo')->where(array('gid'=>$gid))->delete()){
                 $this->success('删除成功',U('Admin/Shop/goods_index'));
            }else{
                 $this->error('删除失败',U('Admin/Shop/goods_index'));
            }
            }else{
                 $this->error('删除失败',U('Admin/Shop/goods_index'));
           }
    }

  //商城订单管理
    Public function order(){
       $siteid=$_SESSION['siteid'];
         import("ORG.Util.Page");
         $num=M('order')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $order=M('order')->where(array('siteid' => $siteid))->limit($limit)->order('id DESC')->select();
         $this->order=$order;
         $this->page=$page->show();
       
         $this->assign('siteid',$siteid);
         $this->assign('order',$order);
        
         $this ->display();
    }

     //商城订单删除
        Public function orderdelete(){
          $id= I('id','0','intval');
              if( M('order')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功',U('Admin/Shop/order'));
                  }else{
                    $this->error('删除失败',U('Admin/Shop/order'));
                  }
           
    }
	      //支付方式设置
  Public function Pay(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display(pay);
  }


     //支付方式表单处理
  Public function runpay(){
     $data['siteid']=$_POST['siteid'];
     $data['account']=$_POST['account'];
     $data['key']=$_POST['key'];
     $data['pid']=$_POST['pid'];

     $list=M('zpay')->where(array('siteid'=>$data['siteid']))->select();
     $User=M('zpay');
     $User->create();
     if ($list=="") {
       $User->add($data);
       $this->success('添加成功');
     }else {
       $User->where(array('siteid'=>$data['siteid']))->save($data);
       $this->success('更新成功');
     }
  }

	//商城物流运费管理
     Public function wuliu(){
      $siteid=$_SESSION['siteid'];
      $list=M('wuliu')->where(array('siteid'=>$siteid))->select();
      $this->assign('list',$list);
      $this->assign('siteid',$siteid);

      $this->display();
     }
     //物流运费表单处理
      Public function wuliu_post(){
      $siteid=$_POST['siteid'];
     

      $data['siteid']=$_POST['siteid'];
      $data['area']=$_POST['area'];
      $data['freight']=$_POST['freight'];

     // p($data);

      $list=M('wuliu')->where(array('siteid'=>$siteid,'area'=>$_POST['area']))->select();
      $User = M("wuliu"); // 实例化User对象  

      if (!$list ) {
            $User->create(); // 创建数据对象                     
            $User->add($data); // 写入用户数据到数据库
            $this->success("数据保存成功！");
 
           }else{
            
            $User->create(); // 创建数据对象   
                 
            $User->where(array('id'=>$list[0]['id']))->save($data); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           
           }

     
      
     }


      //物流信息修改
    Public function wuliu_revise(){
         $id= I('id','0','intval');
         $list=M('wuliu')->where(array('id'=>$id))->select();
         $this->assign('id',$id);
         $this->assign('area',$list[0]['area']);
         $this->assign('freight',$list[0]['freight']);

         $this->display();
             
    }

       //物流信息修改提交处理
    Public function wuliu_revise_post(){
      
      $id=$_POST['id'];
      $data['area']=$_POST['area'];
      $data['freight']=$_POST['freight'];
      $User=M('wuliu');
      $User->create(); // 创建数据对象           
      $User->where(array('id'=>$id))->save($data); // 写入用户数据到数据库
      $this->success("数据更新成功！");      
    }

    //物流信息删除
    Public function wuliu_delete(){
         $id= I('id','0','intval');
         if( M('wuliu')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
          }else{
                    $this->error('删除失败');
          }
    }
	
	
	 //订单详情查看
  Public function orderdetails(){
    $orderid= $_GET['orderid'];
    $goods=M('orderdetails')->where(array('orderid'=>$orderid))->select();
    $this->assign('goods',$goods);
	
    $this->display();

  }

   //订单检索
    Public function ordersearch(){
      $orderid=$_POST['orderid'];
      $siteid=$_POST['siteid'];

  

      if ($orderid!="") {
         import("ORG.Util.Page");
         $num=M('order')->where(array('orderid'=>$orderid,'siteid'=>$siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $order=M('order')->where(array('orderid'=>$orderid,'siteid'=>$siteid))->limit($limit)->select();
         $this->order=$order;
         $this->page=$page->show();
         $this->assign('siteid',$siteid); 
         $this->assign('order',$order);
         $this->display(Order);    

      }

    }




 }

?>