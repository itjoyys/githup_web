<?php 
/**

  万能表单

*/
 Class FormAction extends CommonAction{
    //表单列表
    Public function form_index(){
       $form_id=$_GET['_URL_'][3];//表单id
       $openid = $_GET['openid'];//用户标识
       $form = M('form')->where(array('id'=>$form_id))->find();
       $fields = M('form_fields')->where(array('form_id'=>$form_id))->order('sort DESC')->select();
       if ($form['is_openid'] == 1) {
          //开启了用户关注
          if (empty($openid)) {
             echo "请先关注公众账号";
             die();
          }
       }
       foreach ($fields as $key => $val) {
         if (!empty($val['content'])) {
            //不为空转换成子数组
            $arr = explode('|', $val['content']);
            $fields[$key]['f_content'] = $arr;
         }
         if ($val['cate'] == 5) {
            //表示有图片上传
            $is_img = 1;
         }
       }
       $this->form = $form;
       $this->openid = $openid;
       $this->form_id = $form_id;
       $this->fields = $fields;
       $this->is_img = $is_img;
       $this->display();
    }


    //提交表单处理
    Public function run_add_form(){
       $fields = M('form_fields')->where(array('form_id'=>$_POST['form_id']))->order('sort DESC')->select();
       foreach ($fields as $key => $val) {
          if ($val['required'] == 1) {
             //表示改字段必填 不能为空
             if (empty($_POST[$val['field_name']])) {
                $this->error('数据不能为空');
             }
          }
       }
       if ($_POST['is_img'] == 1) {
          //表示有图片上传
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
               $_POST['img'] = '/Uploads/form/'.$info[0]["savename"];  
           } 
       }
       $data['form_id'] = $_POST['form_id'];
       $data['openid'] = $_POST['openid'];
       unset($_POST['is_img']);
       unset($_POST['form_id']);
       unset($_POST['openid']);
       $data_post=implode('|',$_POST);//转换成字符串
       $data['content'] = $data_post; 
      
       $f = M('form_data');
       $f->create();
       $f->add($data);
       $this->success('提交成功');
    }
 }

?>