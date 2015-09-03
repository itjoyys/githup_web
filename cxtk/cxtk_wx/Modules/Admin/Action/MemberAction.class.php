<?php 
 Class MemberAction extends CommonAction{

    Public function Index(){
        $siteid=$_SESSION['siteid'];
        $member=M('member')->where(array('siteid' => $siteid))->select();
       
        $this->assign('member',$member);  
        $this->assign('siteid',$siteid);
        $this->display(); // 输出模板
    
    }

    //添加会员卡

    Public function add_member(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();
    }


    //添加会员卡处理
    Public function run_add_member(){
       //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/member/';// 设置附件上传目录
        $id=$_POST['id'];
        if ($id!="") {
           //编辑会员卡提交处理
              if (!empty($_FILES['img']['tmp_name']) && !empty($_FILES['pic']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])) {
               if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
                $data['img']='/Uploads/member/'.$info[1]["savename"]; 
                $data['logo']='/Uploads/member/'.$info[2]["savename"]; 
           }elseif (!empty($_FILES['img']['tmp_name']) && !empty($_FILES['pic']['tmp_name'])) {
               if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
                $data['img']='/Uploads/member/'.$info[1]["savename"]; 
           }elseif (!empty($_FILES['img']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])) {
              if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['img']='/Uploads/member/'.$info[0]["savename"]; 
                $data['logo']='/Uploads/member/'.$info[1]["savename"]; 
           }elseif (!empty($_FILES['pic']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])) {
              if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
                $data['logo']='/Uploads/member/'.$info[1]["savename"]; 
           }elseif (!empty($_FILES['img']['tmp_name'])) {
              if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['img']='/Uploads/member/'.$info[0]["savename"]; 
           }elseif (!empty($_FILES['pic']['tmp_name'])) {
              if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['pic']='/Uploads/member/'.$info[0]["savename"];  
           }elseif (!empty($_FILES['logo']['tmp_name'])) {
              if(!$upload->upload()) {// 上传错误提示错误信息
                 $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                }
                $data['logo']='/Uploads/member/'.$info[0]["savename"];  
           }
              $data['name']=$_POST['name'];
              $data['keyword']=$_POST['keyword'];
              $data['start']=$_POST['start'];
              $data['finish']=$_POST['finish'];
              $data['address']=$_POST['address'];
              $data['tel']=$_POST['tel'];
              $data['coordinate_x']=$_POST['coordinate_x'];
              $data['coordinate_y']=$_POST['coordinate_y'];
              $data['company']=$_POST['company'];
              $data['integral']=$_POST['integral'];
              $data['num']=$_POST['num'];
              $data['details']=$_POST['details'];
              $data['detailj']=$_POST['detailj'];
              $data['detailsj']=$_POST['detailsj'];

              $User=M('member');
              $User->create();
              $User->where(array('id'=>$id))->save($data);
              $this->success('更新成功');
           
        }else{
           //添加微信会员卡
          if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
              $info =  $upload->getUploadFileInfo();
        
            }

              $data['siteid']=$_POST['siteid'];
              $data['name']=$_POST['name'];
              $data['keyword']=$_POST['keyword'];
              $data['start']=$_POST['start'];
              $data['finish']=$_POST['finish'];
              $data['address']=$_POST['address'];
              $data['tel']=$_POST['tel'];
              $data['coordinate_x']=$_POST['coordinate_x'];
              $data['coordinate_y']=$_POST['coordinate_y'];
              $data['company']=$_POST['company'];
              $data['integral']=$_POST['integral'];
              $data['num']=$_POST['num'];
              $data['details']=$_POST['details'];
              $data['detailj']=$_POST['detailj'];
              $data['detailsj']=$_POST['detailsj'];
              $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
              $data['img']='/Uploads/member/'.$info[1]["savename"]; 
			  $data['logo']='/Uploads/member/'.$info[2]["savename"]; 
              
              $User=M('member');
              $User->create();
              $User->add($data);
              $mid=mysql_insert_id();
              $image_url=C("image_url");
              $murl=$image_url."/index.php/Index/Member/mid".'/'.$mid;
              $data['murl']=$murl;
              $User->where(array('id'=>$mid))->save($data);
              $this->success('添加成功');
        }
    }
    //会员卡编辑图片处理
    Public function run_img_member(){
           //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/member/';// 设置附件上传目录
        if (!empty($_FILES['img']['tmp_name']) && !empty($_FILES['pic']['tmp_name'])) {
           if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
            }
            $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
            $data['img']='/Uploads/member/'.$info[1]["savename"]; 
             p($data);
        }elseif (!empty($_FILES['img']['tmp_name'])) {
           if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
            }
            $data['img']='/Uploads/member/'.$info[0]["savename"]; 
             p($data);
        }elseif (!empty($_FILES['pic']['tmp_name'])) {
          if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
            }
            $data['pic']='/Uploads/member/'.$info[0]["savename"]; 
            p($data);
        }else{
           echo '没有文件';
        }

   
              
              $User=M('member');
              $User->create();
             // $User->where(array('id'=>$_POST['id']))->save($data);
             // $this->success('更新成功');


    }

   //微信会员卡修改
    Public function revise_member(){
      $mid=$_GET['mid'];
      $image_url=C('image_url');
      $murl=$image_url."/index.php/Index/Member/mid".'/'.$mid;
      $member=M('member')->where(array('id'=>$mid))->find();

      $details=htmlspecialchars($member['details']);
      $detailj=htmlspecialchars($member['detailj']);
      $detailsj=htmlspecialchars($member['detailsj']);

      $this->assign('member',$member);
      $this->assign('id',$mid);
      $this->assign('murl',$murl);
      $this->assign('image_url',$image_url);
      $this->assign('details',$details);
      $this->display();

    }

    //查看会员信息
    Public function member_data(){
     import("ORG.Util.Page");
     $mid = $_GET['mid'];
     $User = D('MemberDataView');
     $num=$User->where(array('mid'=>$mid))->count();
     $page = new Page($num, 15);
     $limit=$page->firstRow.','.$page->listRows;
     $member_data=$User-> where(array('mid'=>$mid))->order('date DESC')->limit($limit)->select();
     $this->page=$page->show();
     $this->mid = $mid;
     $this->siteid = $siteid;
     $this->member_data = $member_data;
   //  P($member_data);
     $this->display();
    }

    //会员冻结
    Public function freeze(){
        $id = $_GET['mid'];
        $m = M('member_data');
        $m->create();
        $m->state = 0;
        $m->where(array('id'=>$id))->save();
        $this->success('冻结成功');
    }

    //会员解冻
     Public function  not_freeze(){
         $id = $_GET['mid'];
         $m = M('member_data');
         $m->create();
         $m->state = 1;
         $m->where(array('id'=>$id))->save();
         $this->success('解冻成功');
     }

     //会员信息编辑
    Public function  r_member_data(){
        $id = $_GET['mid'];
        $member_data = M('member_data')->where(array('id'=>$id))->find();
        $level = M('member_level')->where(array('mid'=>$member_data['mid']))->select();
        $this->id = $id;
        $this->level = $level;
        $this->member_data = $member_data;
        $this->display();
    }

     //会员数据编辑处理
    Public function  run_r_data(){
       $data['name'] = $_POST['name'];
       $data['tel'] = $_POST['tel'];
       $data['address'] = $_POST['address'];
       $data['qq'] = $_POST['qq'];

        $m = M('member_data');
        $m->create();
        $m->where(array('id'=>$_POST['id']))->save($data);
        $this->success('编辑成功');
    }

    //会员管理
     Public function  manage(){
         $id = $_GET['mid'];
         echo '升级中.....';
     }

    //门店管理
    Public function store(){
	  $store_tpl=include "store_tpl.php";
      $siteid = $_SESSION['siteid'];
      $image_url=C("image_url");
      $surl=$image_url."/index.php/Index/Member/storeid".'/'.$siteid;
      $map['siteid'] = $siteid;
      $store = M('map_store_tpl')->where(array('siteid'=>$siteid))->find();
      $map['pid'] = I('pid','0','intval');
      $store_class = M('map_c_store')->where($map)->select();
	  $this->store = $store;
      $this->store_tpl = $store_tpl;
       if($map['pid'] == 0){
          $this->store_class = $store_class;
          $this->surl = $surl;
          $this->pid = $map['pid'];
          $this->siteid = $siteid;
          $this->display();
      }else{
        if(empty($store_class)){
           //为空则显示分店
           $store_class = M('map_store')->where(array('sid'=>$map['pid']))->select();
           $this->store_class = $store_class;
           $this->sid = $map['pid'];
           $this->siteid = $siteid;
           $this->display(store_list);
        }else{
            $this->store_class = $store_class;
            $this->surl = $surl;
            $this->pid = $map['pid'];
            $this->siteid = $siteid;
            $this->display();
        }

      }
    }
     //添加地区处理
    Public function run_class_store(){
        $data['siteid'] = $_POST['siteid'];
        $data['name'] = $_POST['name'];
        $data['sort'] = $_POST['sort'];
      if(empty($_POST['id'])){
        //为空表示新增
          $data['pid'] = I('pid','0','intval');
          $User = M('map_c_store');
          $User ->create();
          $User->add($data);
          $this->success('添加成功');
      }else{
         //为编辑提交更新
          $User = M('map_c_store');
          $User->create();
          $User->where(array('id'=>$_POST['id']))->save($data);
          $this->success('更新成功');
      }

    }

    //地区编辑
    Public function revise_c_store(){
      $id = $_GET['pid'];
      $siteid = $_SESSION['siteid'];
      $c_store = M('map_c_store')->where(array('id'=>$id))->find();
      $this->id = $id;
      $this->siteid = $siteid;
      $this->c_store = $c_store;
      $this->display();
    }

    //添加门店
    Public function add_store(){
      $siteid = $_SESSION['siteid'];
      $sid = $_GET['id'];
      $this->siteid = $siteid;
      $this->sid = $sid;
      $this->display();
    }
	   //门店样式
   Public function store_ajax(){
      $siteid=$_SESSION['siteid'];
      $data = M('map_store_tpl')->where(array('siteid'=>$siteid))->find();
      $User=M('map_store_tpl');
      $User->create();
      if (empty($data)) {
        //不存在添加
        $data['store_tpl']=$_POST['store_tpl'];
        $data['siteid']=$siteid;
        $User->add($data);
        $msg="保存首页模板成功";
      }else{
        $data['store_tpl']=$_POST['store_tpl'];
        $User->where(array('siteid'=>$siteid))->save($data);
        $msg="保存首页模板成功";
      }
      echo $msg;
   }
     //编辑门店
     Public function revise_store(){
         $siteid = $_SESSION['siteid'];
         $rid = $_GET['pid'];
         $store = M('map_store')->where(array('id'=>$rid))->find();
         $this->siteid = $siteid;
         $this->sid = $store['sid'];
         $this->rid = $rid;
         $this->store = $store;
         $this->display(add_store);
     }
     //添加门店处理
     Public function  run_add_store(){
         import("ORG.Net.UploadFile");
         $upload = new UploadFile();// 实例化上传类
         $upload->maxSize  = 2000000 ;// 设置附件上传大小
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->autoSub  = true;// 启用子目录保存文件
         $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
         $upload->dateFormat = 'Ym';
         $upload->savePath =  './Uploads/store/';// 设置附件上传目录
         $rid = $_POST['rid'];
         $data['siteid'] = $_POST['siteid'];
         $data['sid'] = $_POST['sid'];
         $data['name'] = $_POST['name'];
         $data['address'] = $_POST['address'];
         $data['tel'] = $_POST['tel'];
         $data['sort'] = $_POST['sort'];
         $data['coordinate_x'] = $_POST['coordinate_x'];
         $data['coordinate_y'] = $_POST['coordinate_y'];
         if(empty($rid)){
           //为空则添加
             $state = array_keys(array_map('trim', $data), '');
             if(!empty($state)){
                 //提交的数据有空值
                 echo "<script>alert('数据不能为空');</script>";
             }else{
			     if(!$upload->upload()) {// 上传错误提示错误信息
                      $this->error($upload->getErrorMsg());
                   }else{// 上传成功 获取上传文件信息
                      $info =  $upload->getUploadFileInfo();
                 }
                 $data['img'] = '/Uploads/store/'.$info[0]["savename"];
                 $User = M('map_store');
                 $User->create();
                 $User->add($data);
                 $this->success('添加分店成功');
             }
         }else{
            //编辑提交
			  if (!empty($_FILES['img']['tmp_name'])) {
                 if(!$upload->upload()) {// 上传错误提示错误信息
                      $this->error($upload->getErrorMsg());
                  }else{// 上传成功 获取上传文件信息
                      $info =  $upload->getUploadFileInfo();
                  }
                  $data['img'] = '/Uploads/store/'.$info[0]["savename"];
             }
             $User = M('map_store');
             $User->create();
             $User->where(array('id'=>$rid))->save($data);
             $this->success('更新成功');
         }
     }
     //删除门店分类
     Public function delete_class_store(){
       $id = I('id','0','intval');
       if(M('map_c_store')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
         }else{
             $this->error("删除失败");
         }

     }
     //删除门店
     Public function delete_store(){
         $id = I('id','0','intval');
         if(M('map_store')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
         }else{
             $this->error("删除失败");
         }

     }
    //积分兑换商城
    Public function member_mall(){
      $mid=$_GET['mid'];
      $siteid=$_SESSION['siteid'];
      $mall=M('member_mall')->where(array('mid'=>$mid))->select();
      $this->mall=$mall;
      $this->mid=$mid;
      $this->siteid = $siteid;
      $this->display();
    }
    //添加积分商品处理
     Public function run_member_mall(){
         //图片上传处理
         import("ORG.Net.UploadFile");
         $upload = new UploadFile();// 实例化上传类
         $upload->maxSize  = 2000000 ;// 设置附件上传大小
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->autoSub  = true;// 启用子目录保存文件
         $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
         $upload->dateFormat = 'Ym';
         $upload->savePath =  './Uploads/member/';// 设置附件上传目录

         if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
         }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
         }
          $data['mid'] = $_POST['mid'];
          $data['siteid'] = $_POST['siteid'];
          $data['title'] = $_POST['title'];
          $data['jifen'] = $_POST['jifen'];
          $data['img'] ='/Uploads/member/'.$info[0]["savename"];
          $data['content'] = $_POST['details'];
          $User = M('member_mall');
          $User->create();
          $User->add($data);
          $this->success('添加成功');
     }
    //积分商品删除
     Public function delete_mall(){
         $id = I('id','0','intval');
         if(M('member_mall')->where(array('id'=>$id))->delete()){
            $this->success('删除成功');
          }else{
            $this->error('删除失败');
         }
     }
   // 会员卡高级功能
    Public function grade_index(){
      $siteid=$_SESSION['siteid'];
      $mid = $_GET['mid'];
      $memberdata=M('member')->where(array('id'=>$mid))->field('id,name,index_tpl_css')->select();
      $member_function=M('member_function')->where(array('mid'=>$mid))->find();
      $this->assign('siteid',$siteid);
      $this->mid = $mid;
      $this->assign('memberdata',$memberdata);
      $this->assign('member_function',$member_function);
      $this->display();

    }

    //高级功能处理
    Public function run_grade(){
     // p($_POST);
       if ($_POST['mid']=="") {
         echo "<script>alert('请先选择会员卡！');</script>";
         //$this->redirect('Admin/Member/member_style');
      }else{
         $fundata=M('member_function')->where(array('mid'=>$_POST['mid']))->find();
         $User=M('member_function');
         $User->create();

          $data['level']=$_POST['member_function_level'];//会员等级
          $data['mid']=$_POST['mid'];
          $data['store']=$_POST['member_function_store'];//多门店会员
          $data['mstore']=$_POST['member_function_mstore'];//门店导航
          $data['order']=$_POST['member_function_order'];//订单开启
         if (empty($fundata)) {
          $User->add($data);
          $this->success('添加成功');
         }else{
           $User->where(array('mid'=>$_POST['mid']))->save($data);
           $this->success('数据更新成功');

         }
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

   // 会员卡样式自定义
    Public function member_style(){
    $siteid=$_SESSION['siteid'];
    $memberdata=M('member')->where(array('siteid'=>$siteid))->field('id,name,index_tpl_css')->select();
    $this->assign('siteid',$siteid);
    $this->assign('memberdata',$memberdata);
    $this->display();
    }

    //样式处理
    Public function run_member_style(){
      if ($_POST['mid']==""||$_POST['index_tpl_css']=="") {
         echo "<script>alert('请先选择会员卡！');</script>";
         //$this->redirect('Admin/Member/member_style');
      }else{
         $data['index_tpl_css']=$_POST['index_tpl_css'];
         $User=M('member');
         $User->create();
         $User->where(array('id'=>$_POST['mid']))->save($data);
         $this->success('添加成功');

      }
    
    }

    //等级管理
    Public function level_index(){
      $mid=$_GET['mid'];
      $siteid=$_SESSION['siteid'];
      $level=M('member_level')->where(array('mid'=>$mid))->select();

      $this->assign('memberdata',$memberdata);
      $this->assign('siteid',$siteid);
      $this->assign('level',$level);
      $this->assign('mid',$mid);
      $this->display();
    }

    //等级规则控制
    Public function run_level(){
 
         $data['mid']=$_POST['mid'];
         $data['level_name']=$_POST['level_name'];
         $data['level_rule']=$_POST['level_rule'];
         $data['level_favourable']=$_POST['level_favourable'];

         $t = array_keys(array_map('trim', $_POST), '');//不能为空
         if($t) { //有空数据项
             echo "<script>alert('数据不能为空');</script>";
         }else{

         $User=M('member_level');
         $User->create();
         $User->add($data);
         $this->success('数据添加成功');
         }
    }

    //等级规则删除
    Public function delete_level(){
        $id=$_GET['id'];
        if (M('member_level')->where(array('id'=>$id))->delete()) {
           $this->success('删除成功');
        }else{
           $this->error('删除成功');
        }

    }

    //分店管理
    Public function member_store(){
      $mid=$_GET['mid'];
      $siteid=$_SESSION['siteid'];
      $store=M('member_store')->where(array('mid'=>$mid))->select();

      $this->assign('siteid',$siteid);
      $this->assign('store',$store);
      $this->assign('mid',$mid);
      $this->display();
    }

    //分店添加处理
    Public function run_store(){
         $data['mid']=$_POST['mid'];
         $data['name']=$_POST['name'];
         $data['address']=$_POST['address'];
         $data['tel']=$_POST['tel'];
         $data['tel']=$_POST['siteid'];

         $t = array_keys(array_map('trim', $_POST), '');//不能为空
         if($t) { //有空数据项
             echo "<script>alert('数据不能为空');</script>";
         }else{

         $User=M('member_store');
         $User->create();
         $User->add($data);
         $this->success('数据添加成功');
         }

    }

   //删除会员卡
    Public function delete_member(){
       $id = $_GET['id'];
       if(M("member")->where(array('id'=>$id))->delete()){
           $this->success("删除成功");
       }else{
           $this->error("删除失败");
       }
    }


 }

?>