<?php
   
    Class GoodsAction extends CommonAction{
       //商品列表
      Public function index(){

         $siteid=$_SESSION['siteid'];
		 
		    import('Class.Category', APP_PATH);
        $cate= M('cate') -> where(array('siteid' => $siteid))->order('sort')-> select();
        $this ->cate = Category::unlimitedForLevel($cate);
        
         import("ORG.Util.Page");
         $num=M('goods')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $goods=M('goods')->where(array('siteid' => $siteid))->limit($limit)->select();
         $this->goods=$goods;
         $this->page=$page->show();
         // $this->assign('list',$list);// 赋值数据集 
         $this->assign('siteid',$siteid);
        
         $this ->display();
      }

       //添加商品
      Public function Goods(){
        $siteid=$_SESSION['siteid'];
        $this->assign('siteid',$siteid);
        import('Class.Category', APP_PATH);
        $cate= M('cate') -> where(array('siteid' => $siteid))->order('sort')-> select();
        $this ->cate = Category::unlimitedForLevel($cate);
        $this ->display();
      }
      //添加商品表单处理
      Public function addGoods(){
        //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/goods/';// 设置附件上传目录
		
		 //生成随机20位订单编号
         $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
         $goodsid = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
         }else{// 上传成功 获取上传文件信息
           $info =  $upload->getUploadFileInfo();
         }
        
            // 保存表单数据 包括附件数据
        
            $User = M("photo"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->goodsid = $goodsid; 
            $User->photo1 = '/Uploads/goods/'.$info[0]["savename"]; 
            $User->photo2 = '/Uploads/goods/'.$info[1]["savename"]; 
            $User->photo3 = '/Uploads/goods/'.$info[2]["savename"];
            $User->photo4 = '/Uploads/goods/'.$info[3]["savename"];
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

          //商品数据处理写入到数据库

            $User = M("goods"); // 实例化User对象  
            $User->create(); // 创建数据对象           
            $User->name = $_POST['name']; 
            $User->classid = $_POST['cid']; 
            $User->goodsid = $goodsid; 
            $User->siteid = $_POST['siteid']; 
            $User->saleprice = $_POST['saleprice']; 
            $User->price = $_POST['price']; 
             if (isset($_POST['property'])) {
                $User->property = $_POST['property'];   
              }else{
                $data['property']=0;
              }
            $User->details = $_POST['details'];            
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");

       }
   
   
          //商品检索
    Public function goodssearch(){
      $goodsid=$_POST['goodsid'];
      $classid=$_POST['classid'];
      $siteid=$_POST['siteid'];

      import('Class.Category', APP_PATH);
      $cate= M('cate') -> where(array('siteid' => $siteid))->order('sort')-> select();
      $this ->cate = Category::unlimitedForLevel($cate);

      if ($goodsid!="") {
         import("ORG.Util.Page");
         $num=M('goods')->where(array('goodsid'=>$goodsid,'siteid'=>$siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $goods=M('goods')->where(array('goodsid'=>$goodsid,'siteid'=>$siteid))->limit($limit)->select();
         $this->goods=$goods;
         $this->page=$page->show();
         $this->assign('siteid',$siteid);     

      }elseif ($goodsid==""&&$classid!="") {
         import("ORG.Util.Page");
         $num=M('goods')->where(array('classid'=>$classid,'siteid'=>$siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $goods=M('goods')->where(array('classid'=>$classid,'siteid'=>$siteid))->limit($limit)->select();
         $this->goods=$goods;
         $this->page=$page->show();
         $this->assign('siteid',$siteid);          
      }

      $this->assign('goods',$goods);
      $this->display(index);

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
		
		
		 //商品修改
    
    Public function goodsrevise(){
      $goodsid= $_GET["goodsid"];
      $goods=M('goods')->where(array('goodsid' => $goodsid))->select();
	    $details=stripslashes($goods[0]['details']);
      $this->assign('name',$goods[0]['name']);
      $this->assign('saleprice',$goods[0]['saleprice']);
      $this->assign('price',$goods[0]['price']);
      $this->assign('details',$details);
      $this->assign('goodsid',$goodsid); 
      $this->display();  
    }

    //商品修改表单处理

     Public function rungoodsrevise(){
   
      $data['name']=$_POST['name'];
      $data['saleprice']=$_POST['saleprice'];
      $data['price']=$_POST['price'];
      $data['details']=$_POST['details'];

       $User = M("goods"); // 实例化User对象  
       $User->create(); // 创建数据对象
       $User->where(array('goodsid'=>$_POST['goodsid']))->save($data);
       
       $this->success("数据更新成功！");

    }

        //删除商品
        Public function goodsdelete(){

              $goodsid= $_GET["goodsid"];
               
               if( M('goods')->where(array('goodsid'=>$goodsid))->delete()){

                if( M('photo')->where(array('goodsid'=>$goodsid))->delete()){
                   $this->success('删除成功',U('Admin/Goods/index'));
                  }else{
                   // echo M('photo')->getLastSql();

                    $this->error('删除失败',U('Admin/Goods/index'));
                  }

           
                   }else{
                    $this->error('删除失败',U('Admin/Goods/index'));
                 }
           
         
         }


}
?>
 