<?php 
/*
  返利模块
*/
 Class RebateAction extends CommonAction{
    Public function rebate_config(){
       $siteid=$_SESSION['siteid'];
       $config=M('rebate_config')->where(array('siteid' => $siteid))->find();
       $image_url=C("image_url");
       $url=$image_url."/Index/Groupbuy/siteid".'/'.$siteid;
       $this->display();  

    }
    Public function run_rebate_config(){
        $data['rule'] = $_POST['rule']; //返利规则
        $data['cash_max'] = $_POST['cash_max']; //提现最大值
        $data['siteid'] = $_SESSION['siteid']; 
        $r = M('rebate_config');
        $r->create(); 
        if (empty($_POST['id'])) {
            //表示添加
            $r->add($data); 
            $this->success("数据添加成功！");
           }else{      
            $r->where(array('siteid'=>$_POST['id']))->save($data); 
            $this->success("数据更新成功！");
        }
    }
    //返利人员列表
    Public function rebate_user(){
        $siteid = $_SESSION['siteid'];
        import("ORG.Util.Page");
        $num=M('rebate_user')->where(array('siteid' => $siteid))->count();
        $page = new Page($num, 12);
        $limit=$page->firstRow.','.$page->listRows;
        $user=M('rebate_user')->where(array('siteid' => $siteid))->limit($limit)->order('id DESC')->select();
        $this->user=$user;
        $this->page=$page->show();
        $this->assign('siteid',$siteid);
        $this->display();
    }
    //返利列表
    Public function rebate_data(){
        $map['r_id'] = $_GET['id'];//返利人id
        $map['siteid'] = $_SESSION['siteid'];
        import("ORG.Util.Page");
        $num=M('rebate_data')->where($map)->count();
        $page = new Page($num, 12);
        $limit=$page->firstRow.','.$page->listRows;
        $data=M('rebate_data')->where($map)->limit($limit)->order('id DESC')->select();
        $this->data=$data;
        $this->page=$page->show();
        $this->assign('siteid',$siteid);
        $this->display();
    }

 }

?>