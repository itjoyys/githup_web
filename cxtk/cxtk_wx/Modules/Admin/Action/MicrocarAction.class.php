<?php 
/*
  微汽车控制器
*/
 Class MicrocarAction extends CommonAction{
    //设置
    Public function Index(){
        $siteid=$_SESSION['siteid'];
        $car=M('microcar')->where(array('siteid' => $siteid))->select();
        $bg=M('microcar')->where(array('siteid' => $siteid))->field('bg')->select();
        $image_url=C("image_url");
        $carurl=$image_url."/index.php/Index/Microcar/siteid".'/'.$siteid;
        $this->assign('carurl',$carurl);
        $this->assign('title',$car[0]['title']);
		$this->assign('tel',$car[0]['tel']);
        $this->assign('Keywords',$car[0]['keywords']);
        $this->assign('Description',$car[0]['description']);
        $this->assign('bg',$bg[0]['bg']);
        $this->assign("image_url", $image_url);   
        $this->assign('siteid',$siteid);
       // p($bg);
        $this->display(); // 输出模板

    }

    //微汽车设置
    Public function run_microcar(){

         $siteid=$_SESSION['siteid'];
            $User = M("microcar"); // 实例化User对象  
            $list = M("microcar")->where(array('siteid' => $siteid))->select();

           if (!$list) {
            $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
			$User->tel = $_POST['tel']; 
            $User->keywords = $_POST['Keywords']; 
            $User->description = $_POST['Description']; 
            $User->siteid = $_POST['siteid']; 
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          
             
           }else{

            $User->create(); // 创建数据对象   
            $User->title = $_POST['title']; 
			$User->tel = $_POST['tel']; 
            $User->keywords = $_POST['Keywords']; 
            $User->description = $_POST['Description'];         
            $User->where(array('siteid'=>$siteid))->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");

           }

    }

      //微汽车图片设置
    Public function run_microcar_img(){

           //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/microcar/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
        $siteid=$_SESSION['siteid'];
        $User=M('microcar');
        $User->create(); // 创建数据对象    
        $User->bg = '/Uploads/microcar/'.$info[0]["savename"];        
        $User->where(array('siteid'=>$siteid))->save(); // 写入用户数据到数据库
        $this->success("数据更新成功！");

    }

    //汽车分类管理
    Public function cate_index(){
         $siteid=$_SESSION['siteid'];
         import('Class.Category', APP_PATH);
         $catecar = M("catecar")->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $catecar =Category::menuLevel($catecar); 

         $id=I('id',0,'intval');
         $image_url=C("image_url");
         $this->assign('siteid',$siteid);
         $this->assign('pid',$id);
         $this->assign('catecar',$catecar);
         $this->assign('image_url',$image_url);
         $this ->display();
    }

    //添加汽车分类
    Public function run_cate(){

        $id=$_POST['pid'];


            import("ORG.Net.UploadFile");
            $upload = new UploadFile();// 实例化上传类
            $upload->maxSize  = 2000000 ;// 设置附件上传大小
            $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->autoSub  = true;// 启用子目录保存文件
            $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
            $upload->dateFormat = 'Ym';  
            $upload->savePath =  './Uploads/microcar/';// 设置附件上传目录
             
            if(!$upload->upload()) {// 上传错误提示错误信息
               $this->error($upload->getErrorMsg());
            
                }else{// 上传成功 获取上传文件信息
            
                $info =  $upload->getUploadFileInfo();
            
                }

              $User=M('catecar');
              $User->create();
              $User->name=$_POST['name'];
              $User->siteid=$_POST['siteid'];
              $User->pid=$_POST['pid'];
              $User->img= '/Uploads/microcar/'.$info[0]["savename"]; 
              $User->add();
              $this->success('添加成功');
            
    }

      //添加子分类视图 
    Public function addCate(){
         $siteid=$_SESSION['siteid'];
      
         $id=I('pid',0,'intval');
         $this->assign('siteid',$siteid);
         $this->assign('pid',$id);
         $this->display();

    }
    //汽车管理
    Public function car_index(){

        $siteid=$_SESSION['siteid'];
         import('Class.Category', APP_PATH);
         $catecar = M("catecar")->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $catecar =Category::menuLevel($catecar); 
        
         import("ORG.Util.Page");
         $num=M('cardata')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $cardata=M('cardata')->where(array('siteid' => $siteid))->limit($limit)->select();
         $this->cardata=$cardata;
         $this->page=$page->show();
         $this->assign('cardata',$cardata);
         $this->assign('siteid',$siteid);
         $this->assign('catecar',$catecar);      
         $this->display();

    }

    //添加汽车
    Public function add_car(){

         $siteid=$_SESSION['siteid'];
         import('Class.Category', APP_PATH);
         $catecar = M("catecar")->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $catecar =Category::unlimitedForLayer1($catecar);

         $this->assign('siteid',$siteid);
         $this->assign('catecar',$catecar);

       //  p($catecar);
         $this ->display();

    }

    Public function run_add_car(){
        $cidarray=array();       
        $cidarray=$_POST['cid'];
        $cid_string=implode(' ',$cidarray);

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/Microcar/';// 设置附件上传目录

        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

           //生成随机10位编号
         $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
         $carid = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . sprintf('%04d%02d', rand(100, 999),rand(0,99));

      
        $data['siteid']=$_POST['siteid'];
        $data['date']=$_POST['birthday'];
        $data['carid']=$carid;
        $data['name']=$_POST['name'];
        $data['img']='/Uploads/Microcar/'.$info[0]["savename"]; 
        $data['property']=$cid_string;
        $data['saleprice']=$_POST['saleprice'];
        $data['price']=$_POST['price'];
        $data['details']=$_POST['details'];
        $data['actual']=$_POST['actual'];

        $User=M('cardata');
        $User->create();
        $User->add($data);
        $this->success('添加成功');

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
        if ($upload->upload('./Uploads/Microcar/')) {
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


   //汽车编辑
   Public function carrevise(){
      $id=$_GET['id'];
      $cardata=M('cardata')->where(array('id' => $id))->field('id,carid,name,price,saleprice,details,actual,date')->find();
      $details=stripslashes($cardata['details']);
      $this->assign('name',$cardata['name']);
      $this->assign('id',$id);
      $this->assign('actual',$cardata['actual']);
      $this->assign('date',$cardata['date']);
      $this->assign('carid',$cardata['carid']);
      $this->assign('saleprice',$cardata['saleprice']);
      $this->assign('price',$cardata['price']);
      $this->assign('details',$details);
      $this->display();  

   }   

     //汽车编辑处理
   Public function run_carrevise(){
      
        $data['date']=$_POST['birthday'];
        $data['name']=$_POST['name'];
        $data['saleprice']=$_POST['saleprice'];
        $data['price']=$_POST['price'];
        $data['details']=$_POST['details'];
        $data['actual']=$_POST['actual'];

        $User=M('cardata');
        $User->create();
        $User->where(array('id'=>$_POST['id']))->save($data);
        $this->success('更新成功');
     
   }   
       //汽车删除
   Public function cardelete(){
        $id=$_GET['id'];
        if( M('cardata')->where(array('id'=>$id))->delete()){
           $this->success('删除成功');
         }else{
           $this->error('删除失败');
         }
     
   }   
   
   //汽车分类删除
   Public function deleteCate(){
   
        $id=$_GET['id'];
        if( M('catecar')->where(array('id'=>$id))->delete()){
           $this->success('删除成功');
         }else{
           $this->error('删除失败');
         }
   
   }

     //汽车检索
    Public function carsearch(){
      $carid=$_POST['carid'];
      $classid=$_POST['classid'];
      $siteid=$_POST['siteid'];

      import('Class.Category', APP_PATH);
      $cate= M('cate') -> where(array('siteid' => $siteid))->order('sort')-> select();
      $this ->cate = Category::unlimitedForLevel($cate);

      if ($goodsid!="") {
         import("ORG.Util.Page");
         $num=M('cardata')->where(array('carid'=>$carid,'siteid'=>$siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $cardata=M('cardata')->where(array('carid'=>$carid,'siteid'=>$siteid))->limit($limit)->select();
         $this->cardata=$cardata;
         $this->page=$page->show();
         $this->assign('siteid',$siteid);     

      }elseif ($goodsid==""&&$classid!="") {
         import("ORG.Util.Page");
         $num=M('cardata')->where(array('car'=>$classid,'siteid'=>$siteid))->count();
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

 }

?>