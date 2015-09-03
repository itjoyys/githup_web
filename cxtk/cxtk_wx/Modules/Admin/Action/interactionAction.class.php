<?php
   //微信互动模块
  Class interactionAction extends Action{
   //微信优惠券
    Public function coupon_index(){
      $siteid=$_SESSION['siteid'];
      $coupon=M('coupon')->where(array('siteid'=>$siteid))->select();
      $this->assign('coupon',$coupon);
      $this->assign('siteid',$siteid);
      $this->display();
    }

    //微信优惠券设置
    Public function coupon_config(){
      $siteid=$_SESSION['siteid'];

      $this->assign('siteid',$siteid);
      $this->display();
    }
    //添加优惠券
    Public function add_coupon(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();
    }

    //微信优惠券表单处理
    Public function coupon_config_run(){

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/interaction/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
      $data['siteid']=$_POST['siteid'];
      $data['name']=$_POST['name'];
      $data['start']=$_POST['start'];
      $data['finish']=$_POST['finish'];
      $data['description']=$_POST['description'];
      $data['use']=$_POST['use'];
      $data['num']=$_POST['num'];
      $data['img']='/Uploads/interaction/'.$info[0]["savename"];

      $User=M('coupon');
      $User->create();
      $User->add($data);
      $this->success('添加优惠券成功');

    }

        //微信优惠券表编辑
    Public function coupon_config_revise(){
      $id=$_GET['id'];
      $image_url=C("image_url");
      $couponurl=$image_url.'/index.php/Index/interaction/coupon/couponid'.'/'.$id;
      $coupon=M('coupon')->where(array('id'=>$id))->field('id,name,start,finish,description,use,num')->find();
      $this->assign('id',$coupon['id']);
      $this->assign('couponurl',$couponurl);
      $this->assign('name',$coupon['name']);
      $this->assign('start',$coupon['start']);
      $this->assign('finish',$coupon['finish']);
      $this->assign('description',$coupon['description']);
      $this->assign('use',$coupon['use']);
      $this->assign('num',$coupon['num']);
      $this->display(revise_coupon);
 
    }

        //微信优惠券表单编辑处理
    Public function coupon_revise_run(){
      $id=$_POST['id'];
      $data['name']=$_POST['name'];
      $data['start']=$_POST['start'];
      $data['finish']=$_POST['finish'];
      $data['description']=$_POST['description'];
      $data['use']=$_POST['use'];
      $data['num']=$_POST['num'];

      $User=M('coupon');
      $User->create();
      $User->where(array('id'=>$id))->save($data);
      $this->success('更新优惠券成功');

    }
    //查看微信优惠券数据
    Public function coupon_data(){
      $cid=$_GET['cid'];
      $coupondata=M('coupondata')->where(array('couponid'=>$cid))->select();
      $this->assign('cid',$cid);
      $this->assign('coupondata',$coupondata);
      $this->display();
    }
	
	//微信优惠券处理
	Public function coupon_use(){
	  $id=$_GET['cid'];
	  $siteid=$_SESSION['siteid'];
	  
	  
	  
	}

        //微信优惠券删除
    Public function coupon_delete(){

         $id= I('id', 0, 'intval');

             if(M('coupon')->delete($id)){
                   $this->success('删除成功');

                  }else{
                    $this->error('删除失败');
                  }

    }


    //微信投票
    Public function vote_index(){

      $siteid=$_SESSION['siteid'];
      $coupon=M('vote')->where(array('siteid'=>$siteid))->select();
      $this->assign('vote',$coupon);
      $this->assign('siteid',$siteid);
      $this->display();
 

    }

     //微信投票设置
    Public function vote_config(){
      $siteid=$_SESSION['siteid'];

      $this->assign('siteid',$siteid);
      $this->display();
    }
    //添加微信投票
    Public function add_vote(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();
    }

    //微信投票表单处理
    Public function vote_config_run(){

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/interaction/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
      $data['siteid']=$_POST['siteid'];
	  $data['votetotal']=intval($_POST['votetotal']);
      $data['name']=$_POST['name'];
	  $data['tpl']=$_POST['tpl'];
      $data['start']=$_POST['start'];
      $data['finish']=$_POST['finish'];
      $data['description']=$_POST['description'];
      $data['img']='/Uploads/interaction/'.$info[0]["savename"];

      $User=M('vote');
      $User->create();
      $User->add($data);
      $this->success('添加微投票成功');

    }

        //微信投票编辑
    Public function vote_config_revise(){
	  $siteid=$_SESSION['siteid'];
      $id=$_GET['id'];
      $image_url=C("image_url");
      $voteurl=$image_url.'/index.php/Index/interaction/vote/voteid'.'/'.$id;
      $vote=M('vote')->where(array('id'=>$id))->field('id,name,start,finish,description,votetotal')->find();
      $this->assign('id',$vote['id']);
	  $this->assign('siteid',$siteid);
      $this->assign('voteurl',$voteurl);
      $this->assign('name',$vote['name']);
	  $this->assign('votetotal',$vote['votetotal']);
      $this->assign('start',$vote['start']);
      $this->assign('finish',$vote['finish']);
      $this->assign('description',$vote['description']);
      $this->display(revise_vote);
 
    }

       //微信投票表单编辑处理
    Public function vote_revise_run(){
      $id=$_POST['id'];
      $data['name']=$_POST['name'];
	  $data['siteid']=$_POST['siteid'];
	  $data['votetotal']=intval($_POST['votetotal']);
      $data['start']=$_POST['start'];
      $data['finish']=$_POST['finish'];
      $data['description']=$_POST['description'];

      $User=M('vote');
      $User->create();
      $User->where(array('id'=>$id))->save($data);
      $this->success('更新微投票成功');

    }

    //微信投票添加投票选项
    Public function add_vote_data(){
	   
      $vid=$_GET['vid'];
      $vote=M('votedata')->where(array('vid'=>$vid))->select();
      $this->assign('vote',$vote);
      $this->assign('vid',$vid);
      $this->display();
    }

        //微信投票添加投票选项表单处理
    Public function run_vote_data(){
	   import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/interaction/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
	
      $data['vid']=$_POST['vid'];
      $data['vname']=$_POST['name'];
      $data['sort']=$_POST['sort'];
	  $data['img']='/Uploads/interaction/'.$info[0]["savename"];

      $User=M('votedata');
      $User->create();
      $User->add($data);
      $this->success('添加微投票成功');

    }
	
	//投票底部图片处理
	Public function vote_footimg(){
	
	   import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/interaction/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
      $data['siteid']=$_POST['siteid'];
   
      $data['url']=$_POST['url'];
      $data['foot_img']='/Uploads/interaction/'.$info[0]["savename"];

      $User=M('vote');
      $User->create();
      $User->where(array('id'=>$_POST['id']))->save($data);
      $this->success('更新成功');
	
	
	
	}

        //删除微信投票
      Public function vote_delete(){
             $id= I('id', 0, 'intval');

             if(M('vote')->delete($id)){
                   $this->success('删除成功');

                  }else{
                    $this->error('删除失败');
                  }
          
      }
      //删除微信投票选项
       Public function votedata_delete(){
             $id= I('id', 0, 'intval');
		
             if(M('votedata')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功');

                  }else{
                    $this->error('删除失败');
                  }
          
      }

  /**

   刮刮乐

  */
  //刮刮乐列表
   Public function scratch_index(){
    $siteid = $_SESSION['siteid'];
    import("ORG.Util.Page");
    $num=M('scratch')->where(array('siteid' => $siteid))->count();
    $page = new Page($num, 12);
    $limit=$page->firstRow.','.$page->listRows;
    $scratch_data = M('scratch')->where(array('siteid'=>$siteid))->limit($limit)->order('id DESC')->select();
    $this->scratch_data=$scratch_data;
    $this->page=$page->show();
    $this->display();
   }

   //刮刮乐添加
   Public function add_scratch(){
    $this->siteid = $_SESSION['siteid'];
    $this->display();
   }

   //奖项添加
   Public function ajax_prize(){
     $level_name = $_POST['level_name'];//比如一等奖 二等奖
     $name = $_POST['name'];//奖项
     $num = $_POST['num'];//奖项数量
     $chance = $_POST['chance'];//奖项概率

     foreach ($level_name as $key => $val) {
        $data['sid'] = $_POST['pid'];//刮刮乐id
        $data['level_name'] = $val;//比如一等奖
        $data['name'] = $name[$key];//奖项名称
        $data['num'] = $num[$key];//奖项数量
        $data['chance'] = $chance[$key];//奖项概率
        $r = M('scratch_prize');
        $r->create();
        $r->add($data);
     }


   }
   //刮刮乐编辑
   Public function revise_scratch(){
     $id = $_GET['id'];
     $scratch = M('scratch')->where(array('id'=>$id))->find();
     $prize_data = M('scratch_prize')->where(array('sid'=>$id))->select();
     $image_url=C("image_url");
     $surl=$image_url.'/index.php/Index/interaction/scratch_id'.'/'.$id;
     $this->surl = $surl;
     $this->image_url = $image_url;
     $this->prize_data = $prize_data;
     $this->id = $id;
     $this->scratch = $scratch;
     $this->siteid = $_SESSION['siteid'];
     $this->display(add_scratch);
   }

   //刮刮乐添加表单处理
   Public function run_add_scratch(){
     $id = $_POST['id'];//判断id
     $level_name = $_POST['level_name'];//奖项等级名称
     $name = $_POST['name'];//奖项名称
     $num = $_POST['num'];//奖项数量
     $chance = $_POST['chance'];//奖品概率

     $data['siteid'] = $_POST['siteid'];
     $data['title'] = $_POST['title'];
     $data['keyword'] = $_POST['keyword'];
     $data['finish_info'] = trim($_POST['finish_info']);//活动结束说明
     $data['draw_info'] = trim($_POST['draw_info']);//中奖说明
     $data['start'] = $_POST['start'];//开始时间
     $data['description'] = trim($_POST['description']);//活动说明
     $data['finish'] = $_POST['finish'];//结束时间
     $data['person_max_num'] = $_POST['person_max_num'];//每个人总次数
     $data['day_max_num'] = $_POST['day_max_num'];//每天每个人最大次数
     $data['is_show_num'] = $_POST['is_show_num'];//是否显示奖品数量
     $data['is_member'] = $_POST['is_member'];//是否开启只有会员才有权
     //图片上传处理
      import("ORG.Net.UploadFile");
      $upload = new UploadFile();// 实例化上传类
      $upload->maxSize  = 2000000 ;// 设置附件上传大小
      $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
      $upload->autoSub  = false;// 启用子目录保存文件
      $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
      $upload->dateFormat = 'Ym';  
      $upload->savePath =  './Uploads/interaction/';// 设置附件上传目录

      if (!empty($_FILES['img']['tmp_name'])) {
        //不为空上传
           if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error($upload->getErrorMsg());
           }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
           }
           $data['img'] = '/Uploads/interaction/'.$info[0]["savename"]; 
      }
        
     $s = M('scratch');
     $s->create();
     if (empty($id)) {
       //为空表示添加
       $sid = $s->add($data);
       $this->success('添加成功');

       foreach ($name as $key => $val) {
         $data_p['level_name'] = $level_name[$key];
         $data_p['name'] = $val;
         $data_p['sid'] = $sid;//刮刮乐id
         $data_p['num'] = $num[$key];
         $data_p['chance'] = $chance[$key];
         $n = M('scratch_prize');
         $n->create();
         $n->add($data_p);
       }

        $this->success('添加成功');

     }else{
       //表示编辑
       $s->where(array('id'=>$_POST['id']))->save($data);
       $this->success('更新成功');

       $prize_data = M('scratch_prize')->where(array('sid'=>$_POST['id']))->select();
       $i = count($prize_data);//原来奖品数量
       $j = count($name);//现在奖品数量
       if ($i > $j) {
          foreach ($prize_data as $key_p => $val_p) {
             $data_p['level_name'] = $level_name[$key_p];
             $data_p['name'] = $name[$key_p];
             $data_p['sid'] = $_POST['id'];//刮刮乐id
             $data_p['num'] = $num[$key_p];
             $data_p['chance'] = $chance[$key_p];
             $n = M('scratch_prize');
             $n->create();
             if ($key_p >= $j) {
                //表示删除
                 $n->where(array('id'=>$val_p['id']))->delete();
             }else{
                //表示编辑
                 $n->where(array('id'=>$val_p['id']))->save($data_p);
             }
          } 
       }else{
          foreach ($name as $key_p => $val_p) {
             $data_p['level_name'] = $level_name[$key_p];
             $data_p['name'] = $val_p;
             $data_p['sid'] = $_POST['id'];//刮刮乐id
             $data_p['num'] = $num[$key_p];
             $data_p['chance'] = $chance[$key_p];
             $n = M('scratch_prize');
             $n->create();
             if ($key_p >= $i) {
                //表示添加
                 $n->add($data_p);
             }else{
                //表示编辑
                 $n->where(array('id'=>$prize_data[$key_p]['id']))->save($data_p);
             }
          } 
       }
         $this->success('更新成功');
     }

   }




}
?>
 