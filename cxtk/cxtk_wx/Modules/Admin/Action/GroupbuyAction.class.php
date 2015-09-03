<?php 
/*
  微团购
*/
 Class GroupbuyAction extends CommonAction{
      //团购设置
    Public function index(){
       $siteid=$_SESSION['siteid'];
       $config=M('groupbuy_config')->where(array('siteid' => $siteid))->find();
       $image_url=C("image_url");
       $url=$image_url."/index.php/Index/Groupbuy/siteid".'/'.$siteid;
       $this->assign('url',$url);
       $this->assign('title',$config['title']);
       $this->assign('Keywords',$config['Keywords']);
       $this->assign('Description',$config['Description']);
       $this->assign('coordinate_x',$config['coordinate_x']);
       $this->assign('coordinate_y',$config['coordinate_y']);
       $this->assign('address',$config['address']);
       $this->assign('tel',$config['tel']);
       $this->assign("image_url", $image_url);   
       $this->assign('siteid',$siteid);
       $this->display();  
    }

    //团购配置表单处理
      Public function groupbuy_config(){
        $data['title'] = $_POST['title']; 
        $data['Keywords'] = $_POST['Keywords']; 
        $data['Description'] = $_POST['Description'];
        $data['siteid'] = $_POST['siteid']; 
        $data['coordinate_x'] = $_POST['coordinate_x']; 
        $data['coordinate_y'] = $_POST['coordinate_y']; 
        $data['tel'] = $_POST['tel']; 
        $data['address'] = $_POST['address'];
        $list = M('groupbuy_config')->where(array('siteid'=>$_POST['siteid']))->find();
        if (empty($list)) {
            //表示添加
            $User = M('groupbuy_config');
            $User->create();               
            $User->add($data); 
            $this->success("数据添加成功！");
           }else{
            $User = M('groupbuy_config');
            $User->create();        
            $User->where(array('siteid'=>$data['siteid']))->save($data); 
            $this->success("数据更新成功！");
        }
    }
    //团购商品列表
    Public function goods(){
        $siteid = $_SESSION['siteid'];
        import("ORG.Util.Page");
        $num=M('groupbuy_goods')->where(array('siteid' => $siteid))->count();
        $page = new Page($num, 12);
        $limit=$page->firstRow.','.$page->listRows;
        $goods=M('groupbuy_goods')->where(array('siteid' => $siteid))->limit($limit)->order('id DESC')->select();
        $this->goods=$goods;
        $this->page=$page->show();
        $this->assign('siteid',$siteid);
        $this->display();
    }

    //添加商品
    Public function add_goods(){
       $siteid = $_SESSION['siteid'];
       $goods['sort'] = 1;
       $this->goods = $goods;
       $this->siteid = $siteid;
       $this->display();
    }

    //添加商品表单处理
    Public function run_add_goods(){
        $data['name'] = $_POST['name']; 
        $data['discount'] = $_POST['discount']; //折扣
        $data['finish'] = $_POST['finish'];//结束时间
        $data['siteid'] = $_POST['siteid']; 
        $data['num_buyer'] = $_POST['num_buyer']; //已经参团人数
        $data['price'] = $_POST['price']; 
        $data['saleprice'] = $_POST['saleprice'];//价格 
        $data['sort'] = $_POST['sort'];
        $data['num'] = $_POST['num'];
        $state = array_keys(array_map('trim', $data), '');//分隔
        if (!empty($state)) {
           echo "<script>alert('数据不能为空');</script>";
        }
        import('ORG.Net.UploadFile'); 
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = false;// 启用子目录保存文件
        $upload->savePath =  './Uploads/Groupbuy/';// 设置附件上传目录
      //  $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';    

        if (empty($_FILES['img']['tmp_name']) && empty($_FILES['pic']['tmp_name'])) {
        }else{
            if(!$upload->upload()) {// 上传错误提示错误信息
               $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
               $info =  $upload->getUploadFileInfo();
            }
            if (!empty($_FILES['img']['tmp_name']) && !empty($_FILES['pic']['tmp_name'])) {
               $data['img'] = '/Uploads/Groupbuy/'.$info[0]["savename"]; 
               $data_p['pic'] = '/Uploads/Groupbuy/'.$info[1]["savename"]; 
            }elseif (empty($_FILES['img']['tmp_name'])) {
               $data_p['pic'] = '/Uploads/Groupbuy/'.$info[0]["savename"]; 
            }else{
               $data['img'] = '/Uploads/Groupbuy/'.$info[0]["savename"]; 
            }
            $data_p['siteid'] = $_POST['siteid'];
        }
       $data['detail'] = $_POST['detail'];
       if (empty($_POST['id'])) {
         //表示新添加
         //商品图片添加
        $g = M('groupbuy_goods');
        $g ->create();
        $gid = $g ->add($data);
        $data_p['gid'] = $gid;
        $data_p['siteid'] = $_POST['siteid'];
        $u = M('groupbuy_img');
        $u ->create();
        $u ->add($data_p);
        $this->success('数据添加成功');
       }else{
         //表示编辑
         $g = M('groupbuy_goods');
         $g ->create();
         $g ->where(array('id'=>$_POST['id']))->save($data);

         $u = M('groupbuy_img');
         $u ->create();
         $u ->where(array('gid'=>$_POST['id']))->save($data_p);
         $this->success('数据更新成功');
       }
    }

    //商品编辑
    Public function revise_goods(){
       $siteid = $_SESSION['siteid'];
       $gid = $_GET['id'];//商品id
       $goods = D('Groupbuy_goodsView')->where(array('id'=>$gid))->find();
       $this->id = $gid;
       $this->siteid = $siteid;
       $this->image_url = C('image_url');
       $this->goods = $goods;
       $this->display(add_goods);
    }
	
    //删除商品
     Public function delete_goods(){
        $pid= $_GET["id"];
        if( M('groupbuy_goods')->where(array('id'=>$pid))->delete()){
             if( M('groupbuy_img')->where(array('gid'=>$pid))->delete()){
                  $this->success('删除成功');
               }else{ 
                  $this->error('删除失败');
               }
         }else{ 
             $this->error('删除失败');
         }
      }

           //编辑器图片上传处理
   Public function upload(){    
        import('ORG.Net.UploadFile'); 
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = false;// 启用子目录保存文件
      //  $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';       
        if ($upload->upload('./Uploads/groupbuy/')) {
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

      //团购订单
      Public function order(){
         $siteid=$_SESSION['siteid'];
         import("ORG.Util.Page");
         $num=M('groupbuy_order')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 12);
         $limit=$page->firstRow.','.$page->listRows;
         $order=M('groupbuy_order')->where(array('siteid' => $siteid))->limit($limit)->order('id DESC')->select();
         $this->order=$order;
         $this->page=$page->show();
         $this->assign('siteid',$siteid);
         $this ->display();
      }

 }

?>