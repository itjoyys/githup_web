<?php
   
    Class CategoryAction extends CommonAction{
          //分类列表视图
      Public function index(){
       $siteid=$_SESSION['siteid'];
       $this->assign('siteid',$siteid);
       import('Class.Category', APP_PATH);
       $cate= M('cate') ->where(array('siteid' => $siteid))->order('sort ASC')-> select();
       $this->cate = Category::unlimitedForLevel($cate); 
       $this->display(); // 输出模板
      }
        //删除分类
      Public function deleteCate(){
         $id= I('id', 0, 'intval');
         if(M('Cate')->delete($id)){
            $this->success('删除成功',U('Admin/Category/index'));
          }else{
            $this->error('删除失败',U('Admin/Category/index'));
          }   
      }

          //添加分类视图
         
          Public function addCate(){
            
              $siteid=$_SESSION['siteid'];
              $this->assign('siteid',$siteid);
              $this->pid= I('pid', 0 , 'intval');
              $this-> display();
          }

          //添加分类表单处理

    Public function runAddCate(){
		    import("ORG.Net.UploadFile");
	     	$upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/goods/';// 设置附件上传目录    
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
           $info =  $upload->getUploadFileInfo();
        }
		      	$User = M("cate"); // 实例化User对象  
            $User->create(); // 创建数据对象           
            $User->name = $_POST['name']; 
            $User->pid = $_POST['pid']; 
		      	$User->sort = $_POST['sort']; 
            $User->img = '/Uploads/goods/'.$info[0]["savename"];  
            $User->siteid = $_POST['siteid'];   
            $User->add(); // 写入用户数据到数据库
            $this->success("数据保存成功！");
          }
		  
		  
     //商品分类修改
      Public function revisecate(){
           $id=I('id',0,'intval');
		       // $id=$_GET['id'];
           $cate=M('cate')->where(array('id'=>$id))->select();
           $this->assign('id',$id);
           $this->assign('name',$cate[0]['name']);
		       $this->assign('img',$cate[0]['img']);
		       $this->assign('sort',$cate[0]['sort']);
           $this->display();
      }
      Public function runrevisecate(){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 2000000 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->autoSub  = true;// 启用子目录保存文件
        $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
        $upload->dateFormat = 'Ym';  
        $upload->savePath =  './Uploads/goods/';// 设置附件上传目录
         
        if(!$upload->upload()) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        
            }else{// 上传成功 获取上传文件信息
        
            $info =  $upload->getUploadFileInfo();
        
            }

          $User=M('cate');
          $User->create();
          $data['name']=$_POST['name'];
          $data['img']= '/Uploads/goods/'.$info[0]["savename"]; 
          $User->where(array('id'=>$_POST['id']))->save($data);
          $this->success('更新成功');
		   // p($data);

      }

     //分类排序
          Public function sortCate(){
            $db =M('cate');
            foreach ($_POST as $id => $sort) {
              $db->where(array('id' => $id))->setField('sort' ,$sort);
            }
              $this ->redirect(GROUP_NAME . '/Category/index');
          }



    }



?>
 