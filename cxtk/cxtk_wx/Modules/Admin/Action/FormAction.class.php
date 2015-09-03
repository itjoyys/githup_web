<?php 
/**

  万能表单设计

*/
 Class FormAction extends CommonAction{
    //表单列表
    Public function form_index(){
       $siteid=$_SESSION['siteid'];
       $form = M('form')->where(array('siteid'=>$siteid))->order('id DESC')->select();
       $this->form = $form;
       $this ->display();
      
    }
    //添加在线表单
    Public function add_form(){
       $this->siteid = $_SESSION['siteid'];
       $this->display();
    }
       //编辑在线表单
    Public function revise_form(){
       $id =$_GET['id'];//表单id
       $form = M('form')->where(array('id'=>$id))->find();
       $this->form = $form;
       $this->siteid = $_SESSION['siteid'];
       $image_url=C("image_url");
       $form_url=$image_url."/index.php/Index/Form/form_index".'/'.$id;
       $this->image_url = $image_url;
       $this->form_url = $form_url;
       $this->display(add_form);
    }

    //提交表单处理
    Public function run_add_form(){
       if (empty($_POST['title'])) {
         //为空报错
          $this->error('数据不能为空');
       }
       $data['title'] = $_POST['title'];//表单名称
       $data['siteid'] = $_POST['siteid'];
       $data['tel'] = $_POST['tel'];//预约电话
       $data['address'] = $_POST['address'];//商家地址
       $data['intro'] = $_POST['intro'];//预约说明
       $data['coordinate_x'] = $_POST['coordinate_x'];
       $data['coordinate_y'] = $_POST['coordinate_y'];
       $data['is_openid'] = $_POST['is_openid'];//是否开启只有关注了才可以提交

         //图片上传处理
       import("ORG.Net.UploadFile");
       $upload = new UploadFile();// 实例化上传类
       $upload->maxSize  = 2000000 ;// 设置附件上传大小
       $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
       // $upload->autoSub  = true;// 启用子目录保存文件
       // $upload->subType  =  'date';// 子目录创建方式 可以使用hash date custom
       $upload->dateFormat = 'Ym';  
       $upload->savePath =  './Uploads/form/';// 设置附件上传目录
        
       if (!empty($_FILES['img']['tmp_name'])) {
           //表示选择了图片文件
           if(!$upload->upload()) {// 上传错误提示错误信息
              $this->error($upload->getErrorMsg());
           }else{// 上传成功 获取上传文件信息
              $info =  $upload->getUploadFileInfo();
           }
           $data['img'] = '/Uploads/form/'.$info[0]["savename"];  
       }  
       $f = M('form');
       $f->create();
       if (empty($_POST['id'])) {
          //为空表示添加
          $f->add($data);
          $this->success('数据添加成功');
       }else{
          //非空表示编辑添加
          $f->where(array('id'=>$_POST['id']))->save($data);
          $this->success('数据更新成功');
       }
    }

    //表单添加字段
    Public function add_field(){
       $form_id = $_GET['id'];//表单id
       $fields = M('form_fields')->where(array('form_id'=>$form_id))->order('sort DESC')->select();
       $siteid=$_SESSION['siteid'];
       foreach ($fields as $key => $val) {
          switch ($val['cate']) {
            case '1':
              $fields[$key]['cate'] = '单行文字';
              break;
            case '2':
              $fields[$key]['cate'] = '下拉字段';
              break;
            case '3':
              $fields[$key]['cate'] = '单项选择';
              break;
            case '4':
              $fields[$key]['cate'] = '复项选择';
              break;
            case '5':
              $fields[$key]['cate'] = '图片上传';
              break;
            case '6':
              $fields[$key]['cate'] = '文本内容';
              break;
          }
       }
       $this->form_id = $form_id;
       $this->fields = $fields;
       $this->siteid = $siteid;
       $this ->display();
    }
       //表单字段修改
    Public function revise_field(){
       $id = $_GET['id'];//表单id
       $field = M('form_fields')->where(array('id'=>$id))->find();
       $this->field = $field;
       $this->id = $id;
       $this->display();
    }
    //添加表单字段处理
    Public function run_add_field(){
			$data['name'] = $_POST['name'];//名称 前端显示中文
      $data['content'] = $_POST['content'];//内容
      $data['field_name'] = $_POST['field_name'];//字段的名称 数据库里面 英文
      /**
       
       1表示文本字段，2表示下拉选择，3表示单选
       4表示复选，5表示图片上传,6文本内容

      */
      $data['cate'] = $_POST['cate'];//字段的类别
      if (empty($_POST['sort'])) {
         $data['sort'] = 1;
      }else{
         $data['sort'] = $_POST['sort'];
      }
      $data['form_id'] = $_POST['form_id'];//表单id
      $data['required'] = $_POST['required'];//是否必填
      $f = M('form_fields');
      $f->create();
      if (empty($_POST['id'])) {
        //为空表示添加
         $f->add($data);
         $this->success('添加成功');
      }else{
        //非空表示编辑
         $f->where(array('id'=>$_POST['id']))->save($data);
         $this->success('更新成功');
      } 
    }

      //删除表单字段
    Public function delete_field(){
         $id= I('id','0','intval');
         if( M('form_fields')->where(array('id'=>$id))->delete()){
             $this->success('删除成功');
          }else{
              $this->error('删除失败');
         }
    
    }

	//表单数据查看
	Public function form_data(){
      $siteid=$_SESSION['siteid'];
      $id = $_GET['id'];//表单id
      $form_field = M('form_fields')->where(array('form_id'=>$id))->order('sort DESC')->select();//表单字段

      foreach ($form_field as $k_f => $val_f) {
        //如果有图片上传，排序
        if ($val_f['cate'] == 5) {
           $tmp = $val_f;
           unset($form_field[$k_f]);
        }
      }
      $form_field[] =$tmp;
	    $this->form_field = $form_field;
 
      import("ORG.Util.Page");
      $num=M('form_data')->where(array('form_id'=>$id))->count();
		  $ipage=12*$inum;
      $page = new Page($num,$ipage);
      $limit=$page->firstRow.','.$page->listRows;
      $form_data=M('form_data')->where(array('form_id' => $id))->limit($limit)->order('id DESC')->select();
      foreach ($form_data as $key => $val) {
        $arr = explode('|', $val['content']);
        //$form_data[] = $arr;
         // foreach ($form_field as $k => $v) {
         //  //  $form_data[$key][$v['field_name']] = $arr[$k];
         //    $form_data[$key]['form_data'] = $arr;
         // }
       $form_data[$key]['form_data'] = $arr;
      }
 
      $this->form_data=$form_data;
      $this->page=$page->show();
      $this->display();
	}
 }
?>