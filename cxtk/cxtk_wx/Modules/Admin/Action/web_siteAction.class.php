<?php 

Class Web_siteAction extends CommonAction{
    
    Public function Index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $data=M('web_config')->where(array('siteid' => $siteid))->find();
       $url=$image_url."/index.php/Index/Website/siteid".'/'.$siteid;
       $this->assign('url',$url);
       $this->assign('data',$data); 
       $this ->display();
    }

    Public function web_config_do(){
        $arr['title'] = I('title');
        $arr['address'] = I('address');
        $arr['copyright'] = I('copyright');
        $arr['tel'] = I('tel');
        $arr['qq'] = I('qq');
        if (empty($_POST['id'])) {
            $arr['site_id'] = $_SESSION['site_id'];
            if (M('web_config')->add($arr)) {
               $this->success("数据保存成功！");
            }else{
               $this->error("数据保存失败");
            }
        }else{
           if (M('web_config')->where(array('id'=>$_POST['id']))->save($arr)) {
               $this->success("更新成功！");
           }else{
               $this->error("更新失败！");
           }
        }
    }
	
    Public function CustomWebsitetpl(){
        $siteid=$_SESSION['siteid'];
        $index_tpl=M('Customindextpl')->where(array('siteid'=>$siteid))->select();
        $list_tpl=M('Customlisttpl')->where(array('siteid'=>$siteid))->select();
        $detail_tpl=M('Customdetailtpl')->where(array('siteid'=>$siteid))->select();
        $this->assign('siteid',$siteid);

        $index_tpl=$index_tpl[0]['tpl'];
        $this->assign('index_tpl',$index_tpl);
        $this->assign('detail_tpl',$detail_tpl[0]['tpl']);
        $this->assign('list_tpl',$list_tpl[0]['tpl']);
        $this->display();

    }

    Public function runCustomWebsitetpl(){
     $index_tpl=$_POST['index_tpl'];
     $list_tpl=$_POST['list_tpl'];
     $detail_tpl=$_POST['detail_tpl'];
     $siteid=$_POST['siteid'];

     $i_list=M('Customindextpl')->where(array('siteid'=>$siteid))->select();
     $l_list=M('Customlisttpl')->where(array('siteid'=>$siteid))->select();
     $d_list=M('Customindextpl')->where(array('siteid'=>$siteid))->select();


     if ($index_tpl!="") {
        $User=M('Customindextpl');
        $User->create();      
        $User->tpl=$index_tpl;
        $User->siteid=$siteid;
        if ($i_list!="") {
          $User->where(array('siteid'=>$siteid))->save();  
          $this->success('更新成功');   
        }else{
          $User->add();
          $this->success('添加成功');   
        }
        
     }elseif ($list_tpl!="") {
        $User=M('Customlisttpl');
        $User->create();      
        $User->tpl=$list_tpl;
        $User->siteid=$siteid;
        if ($l_list!="") {
          $User->where(array('siteid'=>$siteid))->save();  
          $this->success('更新成功');   
        }else{
          $User->add();
          $this->success('添加成功');   
        }  
     }elseif ($list_tpl!="") {
        $User=M('Customdetailtpl');
        $User->create();      
        $User->tpl=$detail_tpl;
        $User->siteid=$siteid;
        if ($d_list!="") {
          $User->where(array('siteid'=>$siteid))->save();  
          $this->success('更新成功');  
        }else{
          $User->add();
          $this->success('添加成功');   
        } 
     }


    }
	
    Public function Website_tpl(){
             
            $siteid=$_SESSION['siteid'];
            $User = M("websiteconfig"); // 实例化User对象  
            $list = M("websiteconfig")->where(array('siteid' => $siteid))->select();
            if (!$list) {
                   echo "该站点不存在";
            }else{
            $User->create(); // 创建数据对象   
            $User->index_tpl = $_POST['index_tpl']; 
            $User->list_tpl = $_POST['list_tpl']; 
            $User->detail_tpl = $_POST['detail_tpl']; 
            $User->siteid = $siteid; 
            $User->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");
          }

    }

    Public function Websitecontact(){

       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $store=M('websitestore')->where(array('siteid' => $siteid))->select();
       $Websitecontact=M('websitecontact')->where(array('siteid' => $siteid))->select();

       $contacturl=U('Index/Website/w_contact','','');
       $contacturl=$contacturl.'/'.$siteid;
       $this->assign('contacturl',$contacturl);
       $this->assign('image_url',$image_url);
       $this->assign('siteid',$siteid);
       $this->assign('store',$store);
       $this->assign('img',$Websitecontact[0]['img']);
       $this ->display();
    }

    Public function web_site_flash(){
       $image_url=C("image_url");
       //$map['site_id'] = $_SESSION['site_id'];
       $map['type'] = 'a';
       $data=M('flash')->where($map)->select();
       $this->assign('data',$data);
       $this->display();

    }

    Public function add_web_flash(){
       $this->display();  
    }

    Public function web_flash_edit(){ 
       $id = intval($_GET['id']);
       if (empty($id)) {
           exit('system error 0000');
       }
       $data=M('flash')->where("id = '".$id."'")->find();
       $this->assign('data',$data);
       $this->display('add_web_flash');
    }

    Public function add_web_flash_do(){
       $arr['name'] = $_POST['name'];
       $arr['sort'] = $_POST['sort'];
       $arr['url'] = $_POST['url'];
       if (!empty($_POST['id'])) {
           if (M('flash')->where("id = '".$_POST['id']."'")->save($arr)) {
               $this->success('更新成功','web_site_flash');
           }else{
               $this->error('更新失败','web_site_flash'); 
           }
       }else{
           $arr['type'] = 'a';
           $arr['add_date'] = date('Y-m-d H:i:s');
           $arr['site_id'] = $_SESSION['site_id'];
           if (M('flash')->add($arr)) {
               $this->success('添加成功','web_site_flash');
           }else{
               $this->error('添加失败','web_site_flash'); 
           }
       }
       
    }

   Public function web_flash_up(){
       $id = intval($_POST['id']);
       if (empty($id)) {
           exit('system error 0000');
       }
       if ($this->up_img($id,'flash')) {
          $this->success("上传成功！",'web_site_flash');
       }else{
          $this->error("上传失败！",'web_site_flash');
       }
   }

   Public function web_flash_del(){
       $id = intval($_GET['id']);
       if (empty($id)) {
           exit('system error 0000');
       }
       $arr['img'] = 2;
       if (M('flash')->where(array('id'=>$id))->save($arr)) {
            $this->success("删除成功！");
       }else{
            $this->error("删除失败！");
       }
       
   }

    Public function column_index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $pid = I('pid');
       $map = array();
       if (empty($pid)) {
          $map['pid'] = 0; 
       }else{
          $map['pid'] = $pid;
       }
       $map['state'] = 1;
       $obj = M('web_cate');
       import("ORG.Util.Page");
       $num=$obj->where($map)->count();
       $page = new Page($num,8);
       $limit=$page->firstRow.','.$page->listRows;
       $column=$obj->where($map)->limit($limit)->order('sort DESC')->select(); 
       $this->column=$column;
       $this->assign('pid',$pid);
       $this->assign('page',$page->show());
       $this ->display();
    }

    
    public function up_web_cate_img(){
        $id = intval($_POST['id']);
        if (empty($id)) {
           exit('system error 0000');
        }

        if ($this->up_img($id,'web_cate')) {
            $this->success("上传成功！");
        }else{
            $this->error("上传失败！");
        }
    }

    public function up_img($id,$tab){
      import("ORG.Net.UploadFile");
      $upload = new UploadFile();
      $upload->maxSize  = 2000000 ;
      $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
      $upload->autoSub  = true;
      $upload->subType  =  'date';
      $upload->dateFormat = 'Ym';  
      $upload->savePath =  './uploads/website/';
        
      if(!$upload->upload()) {
          $this->error($upload->getErrorMsg());
      }else{
          $info = $upload->getUploadFileInfo();
          $arr['img'] = './uploads/website/'.$info[0]["savename"];
          if (M($tab)->where(array('id'=>$id))->save($arr)) {
              return TRUE;
          }else{
              return FALSE;
          }
      }

    }

    public function web_cate_img_del(){
        $id = intval($_GET['id']);
        $arr['img'] = 0;
        if (M('web_cate')->where(array('id'=>$id))->save($arr)) {
            $this->success("删除成功！");
        }else{
            $this->error("删除失败！");
        }
    }

    Public function web_cate_del(){
      $id = intval($_GET['id']);
      $type = $_GET['type'];
      if (empty($id)) {
          exit('system error 0000');
      }
      $arr['state'] = 2;//表示删除
      $obj = M('web_cate');
      if ($type == '1') {
         $map = array();
         $map['state'] = 1;
         $map['pid'] = $id;
         $is_state = $obj->where($map)->find();
         if ($is_state) {
            $this->error("请先删除二级栏目！");
         }
         $log_1 = $obj->where(array("id"=>$id))->save($arr);
         if ($log_1) {
             $this->success("删除成功！");
         }else{
             $this->error("删除失败！");
         }
      }elseif($type == '0'){
         $log_1 = M('web_cate')->where(array("id"=>$id))->save($arr);
         $log_2 = M('web_cate_content')->where(array("cid"=>$id))->save($arr);
         if ($log_1 && $log_2) {
             $this->success("删除成功！");
         }else{
             $this->error("删除失败！");
         }
      }
    }

    Public function add_web_cate(){
         $pid=$_GET['pid'];
         $type=$_GET['type'];
         if (empty($pid)){
              $pid=0;
         }
         $this->assign('pid',$pid);
         $this->assign('type',$type);
         if (I('type') == '1') {
             $this->display();
         }else{
             $this->display('addT_column');
         }  
    }

    public function add_web_cate_do(){
        $arr['name'] = $_POST['title'];
        $arr['intro'] = $_POST['introduce'];
        $arr['sort'] = $_POST['sort'];
        $arr['pid'] = $_POST['pid'];
        $arr['type'] = $_POST['type'];
        $arr['add_date'] = date('Y-m-d H:i:s');
        if ($_POST['type'] == 1) {
             $arr['url'] = $_POST['url'];
             if (!empty($_POST['id'])) {
                if(M('web_cate')->where("id = '".$_POST['id']."'")->save($arr)){
                   $this->success("数据更新成功！");
                }else{
                   $this->error("数据更新失败！");
                } 
             }else{
                if(M('web_cate')->add($arr)){
                   $this->success("数据保存成功！");
                }else{
                   $this->error("数据保存失败！");
                } 
             }
        }else{
          if (!empty($_POST['id'])) {
              $drr['name'] = $_POST['title'];
              $drr['sort'] = $_POST['sort'];
              $drr['intro'] = $_POST['introduce'];
              $webObj = M('web_cate');
              $webObj->startTrans();
              $log_1 = $webObj->where("id = '".$_POST['id']."'")
                       ->save($drr);
              $arr['content'] = $_POST['content'];
              $arr['read_num'] = $_POST['read_num'];
              $conObj = M('web_cate_content');
              $log_2 = $conObj->where("cid = '".$_POST['id']."'")
                       ->save($arr);
              if ($log_1 && $log_2) {
                  $webObj->commit();
                  $this->success("更新成功！");
              }else{
                  $webObj->rollback(); 
                  $this->error("更新失败！");
              }
          }else{
            $webObj = M('web_cate');
            $webObj->startTrans();
            $log_1 = $webObj->add($arr);

            $cateObj = M('web_cate_content');
            $drr['cid'] = $log_1;
            $drr['site_id'] = $_SESSION['site_id'];
            $drr['content'] = $_POST['content'];
            $drr['read_num'] = $_POST['read_num'];
            
            $log_2 = $cateObj->add($drr);
            if($log_1 && $log_2){
               $webObj->commit(); 
               $this->success("数据保存成功！");
            }else{
               $webObj->rollback(); 
               $this->error("数据保存失败！");
            }
          }
        }
    }

    public function web_cate_edit(){
      $id = intval($_GET['id']);
      $type = $_GET['type'];
      if (empty($id) || $type == '') {
          exit('system error 0000');
      }
      switch ($type) {
        case '1':
          $map['id'] = $id;
          $data = D('web_site')->get_catelist($map);
          $this->assign('data',$data);
          $this->assign('type',$data['type']);
          $this->assign('pid',$data['pid']);
          $this->display('add_web_cate');
          break;
        case '0':
          $map['id'] = $id;
          $data = D("web_site")->get_cate($map);
          $this->assign('data',$data);
          $this->assign('type',$data['type']);
          $this->assign('pid',$data['pid']);
          $this->display('addT_column');
          break;
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
      if ($upload->upload('./Uploads/website/')) {
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

    Public function form_index(){
        $this->display();
    }
    //增加字段
    Public function adduform(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();

    }
	
	Public function rununiversal(){
	 $User = M("universal"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->siteid = $_POST['siteid']; 
           
            $User->title = $_POST['title']; 
                    
              
            $User->add(); // 写入用户数据到数据库
           
            $this->success("数据保存成功！");
	
	}
    //删除字段
    Public function deleteuform(){
        $id= I('id','0','intval');
         if( M('universalform')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
    
    }
     //增加字段处理
    Public function runuform(){

            $User = M("universalform"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->siteid = $_POST['siteid']; 
            $User->wfielid = $_POST['wfielid']; 
            $User->name = $_POST['name']; 
            $User->content = $_POST['content'];
            $User->sort = $_POST['sort'];             
              
            $User->add(); // 写入用户数据到数据库
           
            $this->success("数据保存成功！");

    }

    //在线预约
    Public function websitebooking(){
      $image_url=C("image_url");
      $siteid=$_SESSION['siteid'];
      $wbookingurl=$image_url.'/index.php/Index/Website/wbook'.'/'.$siteid;
      $booking=M('websitebooking')->where(array('siteid'=>$siteid))->select();
      $this->assign('wbookingurl',$wbookingurl);
      $this->assign('siteid',$siteid);
      $this->assign('booking',$booking);
      $this->display();
    }

	//在线预约数据
	Public function Appointment(){
	
	  
      $siteid=$_SESSION['siteid'];
	  
	    import("ORG.Util.Page");
		
		$title=M('websitebooking')->where(array('siteid'=>$siteid))->select();
		$inum=COUNT($title);

        $num=M('websitebook')->where(array('siteid'=>$siteid))->count();
		
		$ipage=12*$inum;
        $page = new Page($num,$ipage);
        $limit=$page->firstRow.','.$page->listRows;
        $data=M('websitebook')->where(array('siteid' => $siteid))->limit($limit)->order('id ASC')->select();
        $this->data=$data;
        $this->page=$page->show();
     
        $this->assign('siteid',$siteid);
        $this->assign('title',$title);
	  
	    $dnum=COUNT($data);
	  
		  if($inum==0){
			 $jnum=0;
		  }else{
			 $jnum=$dnum/$inum;
		  }
	
	 for($j=0;$j<$jnum;$j++){
		for($i=0;$i<$inum;$i++){
		 $datas[$j][$title[$i][fieldname]]=$data[$j*8+$i][content];
			  
		 }
	  } 
	  // p($data);
	   // p($title);
	  $this->assign('datas',$datas);
      $this->display();
	
	
	}
     //增加字段
    Public function addwbooking(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();

    }
     //删除字段
    Public function deletewbooking(){
        $id= I('id','0','intval');
         if( M('websitebooking')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');
                  }else{
                    $this->error('删除失败');
                  }
    
    }
	

    //提交预约信息处理
    Public function runinstructions(){
         $siteid=$_POST['siteid'];
         $content=M('websitebooki')->where(array('siteid'=>$siteid))->select();
         if ($content) {
            $User = M("websitebooki"); 
            $User->create();  
            $data['instructions'] = $_POST['instructions']; 
            $data['address'] = $_POST['address']; 
            $data['url'] = $_POST['url']; 
            $data['tel'] = $_POST['tel'];
          
            $User->where(array('siteid'=>$siteid))->save($data); 
            $this->success("数据更新成功！");
              
         }else{

            $User = M("websitebooki"); 
            $User->create();  
            $data['instructions'] = $_POST['instructions']; 
            $data['address'] = $_POST['address']; 
            $data['url'] = $_POST['url']; 
            $data['siteid'] = $_POST['siteid']; 
            $data['tel'] = $_POST['tel'];
            $User->add($data); 
            $this->success("数据保存成功！");             

         }
    }

     //增加字段处理
    Public function runwbooking(){
            $User = M("websitebooking"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->siteid = $_POST['siteid']; 
            $User->wfielid = $_POST['wfielid']; 
            $User->name = $_POST['name']; 
            $User->content = $_POST['content'];
            $User->fieldname = $_POST['fieldname'];
            $User->sort = $_POST['sort'];             
         
            $User->add(); // 写入用户数据到数据库
           
            $this->success("数据保存成功！");

    }
	
    Public function Website_index(){
     $siteid=$_SESSION['siteid'];
     $image_url=C("image_url");
     $index = M("websitei_tpl")->select();
     $index_tpl = M("websiteconfig")->where(array('siteid'=>$siteid))->find();
     $this->assign('index',$index);
     $this->assign('index_tpl',$index_tpl['index_tpl']);
     $this->assign('image_url',$image_url);
     $this->display(website_index);

    }

        //列表模板
    Public function Website_list(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
       $list = M("websitel_tpl")->select();
       $list_tpl = M("websiteconfig")->where(array('siteid'=>$siteid))->find();
       $this->assign('image_url',$image_url);
       $this->assign('list_tpl',$list_tpl['list_tpl']);
       $this->assign('list',$list);
       $this->display(website_list);
    }

        //详细页模板
    Public function Website_details(){
        $siteid=$_SESSION['siteid'];
        $image_url=C("image_url");
        $detail_tpl = M("websiteconfig")->where(array('siteid'=>$siteid))->find();
        $detail = M("websited_tpl")->select();
        $this->assign('image_url',$image_url);
        $this->assign('detail_tpl',$detail_tpl['detail_tpl']);
        $this->assign('detail',$detail);
        $this->display(website_details);
      
    }

    //模板选中处理

    Public function index_ajax(){
      $siteid=$_SESSION['siteid'];
      $User=M('websiteconfig');
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

  //栏目模板选择
  Public function column_l_tpl(){
    $column_id= I('id','0','intval');//传人栏目id
     $siteid=$_SESSION['siteid'];
     $image_url=C("image_url");
     $list = M("websitel_tpl")->select();
       $tpl = M("websitecolumn")->where(array('id'=>$column_id))->find();
       $this->assign('image_url',$image_url);
       $this->assign('column_l_tpl',$tpl['column_tpl']);
       $this->assign('list',$list);
	
     $this->assign('column_id',$column_id);
     $this->display();

  }
    //栏目内容模板选择
  Public function column_d_tpl(){
    $column_id= I('id','0','intval');//传人栏目id
     $siteid=$_SESSION['siteid'];
     $image_url=C("image_url");
     $tpl = M("websitecolumn")->where(array('siteid'=>$siteid))->find();
        $detail = M("websited_tpl")->select();
        $this->assign('image_url',$image_url);
        $this->assign('column_d_tpl',$tpl['column_tpl']);
        $this->assign('detail',$detail);
     $this->assign('column_id',$column_id);
     $this->display();

  }

      //栏目页模板选中处理

    Public function column_ajax(){
      $siteid=$_SESSION['siteid'];
      $User=M('websitecolumn');
      $User->create();
     
     if (!empty($_POST['list_tpl'])) {
                $data['column_tpl']=$_POST['list_tpl'];//列表页模板
               if ($User->where(array('id'=>$_POST['column_id']))->save($data)) {
                    $msg="保存列表页模板成功";
                }else{ 
                    $msg="保存列表页模板失败";
                }
               
           }else{
                $data['column_tpl']=$_POST['detail_tpl'];//内容页模板
                 if ($User->where(array('id'=>$_POST['column_id']))->save($data)) {
                      $msg="保存内容页模板成功";
                  }else{
                      $msg="保存内容页模板失败";
                  }
                
           }
      echo $msg;
    }



    



 }

?>