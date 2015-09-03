<?php
   

Class WeChatAction extends CommonAction{
    Public function index(){
        $siteid=$_SESSION['siteid'];
        $image_url = C('image_url');
        $data= M('api') ->where(array('siteid' => $_SESSION['site_id']))-> find();
        $this->data=$data;
        $this ->display();
    }
     
    Public function wechat_do(){
        $arr['token'] = I('token');
        $arr['appid'] = I('appid');
        $arr['appsecret'] = I('appsecret');
        $arr['oid'] = I('oid');
        $arr['EncodingAESKey'] = I('EncodingAESKey');
        if (empty($_POST['id'])) {
            $arr['site_id'] = $_SESSION['site_id'];
            if (M('api')->add($arr)) {
               $this->success("数据保存成功！");
            }else{
               $this->error("数据保存失败");
            }
        }else{
           if (M('api')->where(array('id'=>$_POST['id']))->save($arr)) {
               $this->success("更新成功！");
           }else{
               $this->error("更新失败！");
           }
        }
    }

    Public function wechat_reply(){
        
        $map['site_id'] = $_SESSION['site_id'];
        $map['type'] = 'f';//表示首次关注回复
        $data = M('wechat_reply')->where($map)->order("sort desc")->select();
        $this->assign('data',$data);
        $this->display();
    }
}
?>
 