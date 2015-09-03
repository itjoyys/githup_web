<?php
   //微信互动模块
  Class interactionAction extends Action{
   //微信优惠券
    Public function coupon(){
      $id=$_GET['couponid'];
      $coupon=M('coupon')->where(array('id'=>$id))->field('id,name,start,img,siteid,finish,description,use,num')->find();

      $sn=$this->random_str(15);
      $num=$coupon['num'];
      $this->assign('name',$coupon['name']);
      $this->assign('sn',$sn);
      $this->assign('id',$coupon['id']);
      $this->assign('siteid',$coupon['siteid']);
      $this->assign('start',$coupon['start']);
      $this->assign('finish',$coupon['finish']);
      $this->assign('description',$coupon['description']);
      $this->assign('use',$coupon['use']);
     // p($id);
      $this->display();
    }


    function random_str($length)
   {
    //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组

    $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $str = '';
    $arr_len = count($arr);
    for ($i = 0; $i < $length; $i++)
    {
        $rand = mt_rand(0, $arr_len-1);
        $str.=$arr[$rand];
    }
    return $str;
   }


    //微信优惠券处理
    Public function coupon_ajax(){
      if (IS_AJAX === false) return false;
      $data['name']=$_POST['name'];
      $data['id']=$_POST['id'];
      $data['mobile']=$_POST['mobile'];
      $data['sn']=$_POST['sn'];

      $result=array();

      $result=array(
          'status'=>1,
          'data'=>$data
        );
      exit(json_encode($result));

    }
  /**
 
      微信投票

  */


    Public function vote(){
      $vid=$_GET['voteid'];
      $image_url=C('image_url');
      $vote=M('vote')->where(array('id'=>$vid))->field('id,name,start,img,siteid,finish,description')->find();
      $votedata=M('votedata')->where(array('vid'=>$vid))->select();

      $this->assign('vid',$vid);
      $this->assign('image_url',$image_url);
      $this->assign('votedata',$votedata);
      $this->assign('name',$vote['name']);
      $this->assign('img',$vote['img']);
      $this->assign('description',$vote['description']);
      $this->assign('start',$vote['start']);
      $this->assign('finish',$vote['finish']);

      $this->display();
    
    }

    //微信投票ajax接收
    Public function vote_ajax(){
      if (IS_AJAX === false) return false;
       $msg=array();
       $data['vid']=$_POST['sid'];
       $data['votedataid']=$_POST['voteid'];
       $data['sid']=session_id();
       $data['mip']=$_SERVER["REMOTE_ADDR"];
     
     $total=M('votedata')->where(array('id'=>$_POST['voteid']))->field('total')->find();
     $total=$total['total']+1;      
 
       $list=M('vote_data')->where(array('sid'=>$data['sid'],'vid'=>$data['vid']))->find();
       if (!$list) {
          
         $User=M('vote_data');
         $User->create();
         $User->add($data);
       //  $this->success('成功');
    
       //更新票数
          $db=M('votedata');
          $db->create();
          $tota['total'] = intval($total); // 修改数据对象
          $db->where(array('id'=>$data['votedataid']))->save($tota); // 保存当前数据对象
         // $this->success('成功');
     
         $msg=array(
             'status'=>true,
             'data'=>$total
          );    
       }else{ 

         $msg=array(
              'status'=>false,
              'data'=> $total
           );
       }
      exit(json_encode($msg));

    }


  /**

     刮刮乐 
  
  */
  //刮刮乐
  Public function scratch_id(){
     $sid = $_GET['_URL_'][3];
     $openid = $_GET['openid'];
     $scratch_data = M('scratch') ->where(array('id'=>$sid))->find();
     $prize_data = M('scratch_prize')->where(array('sid'=>$sid))->select();
     $map['openid'] = $openid;
     $map['sid'] = $sid;
     $num = M('scratch_data') ->where($map)->select();
     $now_date = date('Y-m-d');
     if (strtotime($scratch_data['start']) > strtotime($now_date)) {
        //表示活动还没有开始
        $msg = '活动还未开始';
     }elseif(strtotime($scratch_data['finish']) < strtotime($now_date)){
        //表示活动结束
        $msg = '活动已经结束';
     }else {
         $i_num = count($num)+1;//已经参与活动的次数
         $map['date'] = $now_date;
         $d_num = M('scratch_data') ->where($map)->count();
         $d_num = $d_num + 1;
         if ($i_num > $scratch_data['person_max_num']) {
           //参与次数已经用完
           $msg = '您参与的次数已经用完';
         }elseif ($d_num > $scratch_data['day_max_num']) {
           //每日参与次数用完
           $msg = '您今日的次数已经用完';
         }
     }
     $this->person_max_num = $scratch_data['person_max_num'];//总次数
     $this->day_max_num = $scratch_data['day_max_num'];//每天最大次数
     $this->scratch_data = $scratch_data;
     $this->msg = $msg;
     $this->prize_data = $prize_data;
     $this->display(scratch);
  }
}
?>
