<?php 
/*
  微信订餐设置控制器
*/
 Class BookdinnerAction extends CommonAction{

    Public function Index(){
       $siteid=$_SESSION['siteid'];
       $image_url=C("image_url");
	   $name=M('dinner_config')->where(array('siteid'=>$siteid))->getField('name'); 
       $websiteconfig=M('websiteconfig')->where(array('siteid' => $siteid))->select();
       $url=$image_url.'/index.php/Index/Bookdinner/siteid'.'/'.$siteid;
        
       $this->assign("url", $url);  
	   $this->assign("siteid", $siteid); 
       $this->assign("name",$name); 	   

       $this ->display();
      
    }
	
	
    //微信餐饮设置
    Public function config(){

        $list=M('dinner_config')->where(array('siteid'=>$_POST['siteid']))->select();
        
        if ($list) {
        $User=M('dinner_config');
        $User->create();
        $User->siteid=$_POST['siteid'];
        $User->name=$_POST['name'];
        $User->where(array('siteid'=>$_POST['siteid']))->save();
        $this->success("数据更新成功！");
            
        }else{

        $User=M('dinner_config');
        $User->create();
        $User->siteid=$_POST['siteid'];
        $User->name=$_POST['name'];
        $User->add();
        $this->success("数据保存成功！");


        }

       
    }


    //菜品分类管理
    Public function category(){
      $siteid=$_SESSION['siteid'];
      $cate=M('dinner_category')->where(array('siteid' => $siteid))->select();
      $this->assign('cate',$cate);
      $this->assign('siteid',$siteid);

      $this->display();

    }

    //菜品分类提交表单处理
    Public function runcategory(){
     $data['name']=$_POST['name'];
     $data['siteid']=$_POST['siteid'];
     if ($data['name']=='') {
        $this->error("信息不能为空");
     }

      $User = M("dinner_category"); // 实例化User对象  

         $User->create(); // 创建数据对象           
             
         $User->add($data); // 写入用户数据到数据库
         $this->success("数据保存成功！");

     
    }

    //菜品管理
    Public function food(){
             
            $siteid=$_SESSION['siteid'];
            $image_url=C("image_url");
          
             import("ORG.Util.Page");
             $num=M('dinnerfood')->where(array('siteid' => $siteid))->count();
             $page = new Page($num, 12);
             $limit=$page->firstRow.','.$page->listRows;
             $food=M('dinnerfood')->where(array('siteid' => $siteid))->limit($limit)->select();
             $this->food=$food;
             $this->page=$page->show();

            $this->assign('image_url',$image_url);
            $this->assign('food',$food);
            $this->display();
           

    }
 
    Public function addfood(){
            $siteid=$_SESSION['siteid'];
            $cate=M('dinner_category')->where(array('siteid' => $siteid))->select();
            $this->assign('cate',$cate);
            $this->assign('siteid',$siteid);
            $this->display();
         
    }

    //提交菜品表单处理
      Public function runaddfood(){
          //图片上传处理
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/Bookdinner/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

         $User = M("dinnerfood"); // 实例化User对象  

         $User->create(); // 创建数据对象           
         $User->name = $_POST['name']; 
         $User->price = $_POST['price']; 
         $User->cid = $_POST['cid']; 
         $User->siteid = $_POST['siteid']; 
         $User->pic = '/Uploads/Bookdinner/'.$info[0]["savename"];      
         $User->add(); // 写入用户数据到数据库
         $this->success("数据保存成功！");
 
        }
		
		   //订单列表
    Public function order(){
      $siteid=$_SESSION['siteid'];

       import("ORG.Util.Page");
       $num=M('dinner_order')->where(array('siteid' => $siteid))->count();
       $page = new Page($num, 12);
       $limit=$page->firstRow.','.$page->listRows;
       $order=M('dinner_order')->where(array('siteid' => $siteid))->limit($limit)->select();
       $this->order=$order;
       $this->page=$page->show();


      $this->display();
    }

    //订单删除
    Public function orderdelete(){

       $id= I('id','0','intval');
        if( M('dinner_order')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
            }else{
              $this->error('删除失败');
            }

    }
 

  
    


 //微网站幻灯片删除
    Public function deleteslide(){

       $id= I('id','0','intval');
        if( M('websiteslide')->where(array('id'=>$id))->delete()){
             $this->success('删除成功',U('Admin/Website/websiteslide'));
            }else{
              $this->error('删除失败',U('Admin/Website/websiteslide'));
            }

    }
   //模板管理
    Public function Websitetpl(){    
    
     $image_url=C("image_url");
     $index = M("websitei_tpl")->select();
     $list = M("websitel_tpl")->select();
     $detail = M("websited_tpl")->select();
     $this->assign('index',$index);
     $this->assign('list',$list);
     $this->assign('detail',$detail);
     $this->assign('image_url',$image_url);
     $this->display();

    }



    //用户模板设置
    Public function runTemplate(){    
     
       $siteid=$_SESSION['siteid'];
            $User = M("shopconfig"); // 实例化User对象  
            $list = M("shopconfig")->where(array('siteid' => $siteid))->select();

           if (!$list) {
            $this->error('用户不存在');
          
             
           }else{

            $User->create(); // 创建数据对象   
            $User->index_tpl = $_POST['index_tpl']; 
            $User->list_tpl = $_POST['list_tpl']; 
            $User->detail_tpl = $_POST['detail_tpl'];     
            $User->save(); // 写入用户数据到数据库
            $this->success("数据更新成功！");
           
           }
      
    }


  //微信网站栏目管理
    Public function Websitecolumn(){
       $siteid=$_SESSION['siteid'];
        $image_url=C("image_url");
         import("ORG.Util.Page");
         $num=M('websitecolumn')->where(array('siteid' => $siteid))->count();
         $page = new Page($num, 8);
         $limit=$page->firstRow.','.$page->listRows;
         $column=M('websitecolumn')->where(array('siteid' => $siteid))->limit($limit)->select();
         $this->column=$column;
         $this->page=$page->show();

         $this->assign('siteid',$siteid);
         $this->assign('image_url',$image_url);
       
         // $this->assign('list',$list);// 赋值数据集 
         // $this->assign('page',$show);
        
         $this ->display();
    }
    //添加栏目表单处理
    Public function runWebsitecolumn(){


        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
          
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

        
            $siteid=$_SESSION['siteid'];
            $User = M("websitecolumn"); // 实例化User对象  
            $User->create(); // 创建数据对象           
            $User->title = $_POST['title']; 
            $User->introduce = $_POST['introduce']; 
            $User->sort = $_POST['sort']; 
            $User->pid = $_POST['pid']; 
            $User->classid =1; //栏目类别    
            $User->siteid = $_POST['siteid']; 
            $User->img = '/Uploads/website/'.$info[0]["savename"];      
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");


    }

    //子栏目添加
    Public function addW_column(){
         $siteid=$_SESSION['siteid'];
         $this->assign('siteid',$siteid);
         $pid=$_GET['pid'];
        if ($pid==''){
           $pid=0;
        }; //判断字符串是否为空
         $this->assign('pid',$pid);
         $this->display();
    }

     //图文添加
    Public function addT_column(){
  
        $siteid=$_SESSION['siteid'];
         $this->assign('siteid',$siteid);
            $pid=$_GET['pid'];
        if ($pid==''){
           $pid=0;
        }; //判断字符串是否为空
         $this->assign('pid',$pid); 
      
        $this->display();
    }

     //子栏目图文添加
    Public function i_column(){
  
        $siteid=$_SESSION['siteid'];
        $this->assign('siteid',$siteid);        
        $pid= I('pid', 0, 'intval');  
        $image_url=C("image_url");
        $column=M('websitecolumn')->where(array('pid'=>$pid,'siteid'=>$siteid))->select();
        $this->assign('pid',$pid);
        $this->assign('image_url',$image_url);
        $this->assign('column',$column);
        $this->display();
    }


     //图文添加
    Public function runaddT_column(){

        $siteid=$_SESSION['siteid'];
         $this->assign('siteid',$siteid);
         $this->pid= I('pid', 0 , 'intval');
        
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }
        
            // 保存表单数据 包括附件数据
        
            $User = M("websitecolumn"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->title = $_POST['title']; 
            $User->siteid = $siteid; 
            $User->pid = $_POST['pid']; 
            $User->introduce = $_POST['introduce'];
            $User->sort = $_POST['sort'];
            $User->classid =0; //图文类别               
            $User->img = '/Uploads/website/'.$info[0]["savename"]; 
              
            $User->add(); // 写入用户数据到数据库
            $lastid = mysql_insert_id();
            $this->success("数据保存成功！");

            $User = M("websitecontent"); // 实例化User对象  
            $User->create(); // 创建数据对象
            $User->cid = $lastid; 
            $User->siteid = $siteid; 
            $User->content = $_POST['details'];  
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
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

   //栏目修改
        Public function revisecolumn(){
         $siteid=$_SESSION['siteid'];
         $image_url=C("image_url");
         $this->assign('image_url',$image_url);
         $pid= I('id', 0 , 'intval');
         $this->assign('pid',$pid);
         $content=M('websitecontent')->where(array('cid'=>$pid,'siteid'=>$siteid))->select();
         $column=M('websitecolumn')->where(array('id'=>$pid,'siteid'=>$siteid))->select();
        
         if (!$content) {
              $this->assign('title',$column[0]['title']);
              $this->assign('introduce',$column[0]['introduce']);
              $this->assign('img',$column[0]['img']);
              $this->assign('sort',$column[0]['sort']);
              $this->assign('url',$column[0]['url']);
              $this->display(t_revisecolumn);
         }else{

              $this->assign('title',$column[0]['title']);
              $this->assign('introduce',$column[0]['introduce']);
              $this->assign('img',$column[0]['img']);
              $this->assign('sort',$column[0]['sort']);
              $this->assign('content',$content[0]['content']);
           
              $this->display(w_revisecolumn);

         }
       
        }
         //栏目修改表单处理
        Public function runrevisecolumn(){
         $siteid=$_SESSION['siteid'];
         $image_url=C("image_url");
         $this->assign('image_url',$image_url);
         $pid=$_POST['pid'];

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/website/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }


         $content=M('websitecontent')->where(array('cid'=>$pid,'siteid'=>$siteid))->select();
         $column=M('websitecolumn')->where(array('id'=>$pid,'siteid'=>$siteid))->select();
        
         if (!$content) {
            $User = M("websitecolumn"); 
            $User->create();  
            $data['title'] = $_POST['title']; 
            $data['introduce'] = $_POST['introduce']; 
            $data['url'] = $_POST['url']; 
            $data['sort'] = $_POST['sort'];
            $data['img'] = '/Uploads/website/'.$info[0]["savename"];  
            $User->where(array('id'=>$pid))->save($data); 
            $this->success("数据更新成功！");
           // echo M("websitecolumn")->getLastSql();
              
         }else{

            $User = M("websitecolumn"); 
            $User->create();  
            $data['title'] = $_POST['title']; 
            $data['introduce'] = $_POST['introduce']; 
            $data['sort'] = $_POST['sort'];
            $data['img'] = '/Uploads/website/'.$info[0]["savename"];  
            $User->where(array('id'=>$pid))->save($data); 
            $this->success("数据更新成功！");

            $User = M("websitecontent"); 
            $User->create();  
            $User->content = $_POST['details'];
            $User->siteid = $siteid;
            $User->where(array('cid'=>$pid))->save(); 
            $this->success("数据更新成功！");
            // echo M("websitecontent")->getLastSql();
              

         }
       
        }
     //栏目图文删除
        Public function deletecolumn(){
          $id= I('id','0','intval');

          $content=M('websitecontent')->where(array('cid'=>$id))->select();
  
        
         if (!$content) {
             if( M('websitecolumn')->where(array('id'=>$id))->delete()){
                   $this->success('删除成功',U('Admin/Website/websitecolumn'));
                  }else{
                    $this->error('删除失败',U('Admin/Website/websitecolumn'));
                  }
              
         }else{


             if( M('websitecolumn')->where(array('id'=>$id))->delete()){

                   $this->success('删除成功',U('Admin/Website/websitecolumn'));
                  }else{
                    $this->error('删除失败',U('Admin/Website/websitecolumn'));
                  }

               if( M('websitecontent')->where(array('cid'=>$id))->delete()){
                   $this->success('删除成功',U('Admin/Website/websitecolumn'));
                  }else{
                    $this->error('删除失败',U('Admin/Website/websitecolumn'));
                  }
       

         }
             
    }

    //万能表单
    Public function universalform(){
      $image_url=C("image_url");
      $siteid=$_SESSION['siteid'];
      $uformurl=$image_url.'/index.php/Index/Website/uform'.'/'.$siteid;
      $form=M('universalform')->where(array('siteid'=>$siteid))->select();
      $this->assign('uformurl',$uformurl);
      $this->assign('form',$form);
      $this->display();

    }
    //增加字段
    Public function adduform(){
      $siteid=$_SESSION['siteid'];
      $this->assign('siteid',$siteid);
      $this->display();

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

    

 }

?>