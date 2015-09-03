<?php 
/*
  微会员控制器
*/
 Class MemberAction extends Action{
    //检测
    function check_session(){
      $member_card=$_SESSION['member_card'];
      if(empty($member_card)){
          $this->error('请重新访问');
      }

    }
    //微会员首页
    Public function mid(){
        $mid=$_GET['_URL_'][3];
        $image_url = C('image_url');
        $openid=$_GET['openid'];
       // $openid='dd132';
        if(empty($openid)){
            //没有获取到用户，
           echo '请重新访问';
           die();
        }
        $member=M('member')->where(array('id'=>$mid))->find();
        $member_data=M('member_data')->where(array('mid' => $mid,'openid'=>$openid))->field('id,name,card_id')->find();
        $member_card = array(
               'mid' =>$mid ,
               'openid' =>$openid,
               'siteid' =>$member_data['siteid']
               );
        session('member_card',$member_card);
        
        //门店判断
        $store_c = M('map_c_store')->where(array('siteid'=>$member['siteid']))->select();
        if (empty($store_c)) {
            $store_c = 0;
        }else{
            $store_url = $image_url.'/index.php/Index/Member/storeid/'.$member['siteid'];
        }
        //一键导航url
        $url='http://api.map.baidu.com/marker?location='.$member['coordinate_x'].','.$member['coordinate_y'].
        '&title='.$member['company'].'&content=地址：'.$member['address'].'&output=html&src=yourComponyName|yourAppName';
        $this->image_url = $image_url;
        if ($member_data=="") {
          $this->assign('member',$member);
          $this->assign('mid',$mid);
          $this->display(index_h);
        }else{
          $integral=M('member_integral')->where(array('openid'=>$openid))->find();
          if ($integral['date'] == date('Y-m-d')) {
            //表示今天已经签到

          }
          $this->assign('member',$member);
          $this->member_data=$member_data;
          $this->integral=$integral;
          $this->url = $url;
          $this->assign('mid',$mid);
          $this->display(index_m);
        }

    }

    //用户获取会员卡

    Public function h_member(){
      $this->check_session();
      $member_card=$_SESSION['member_card'];
      $this->siteid=$_SESSION['siteid'];
      $this->member_card=$member_card;
      $this->display();
    }

    //个人资料完善
    Public function perfect_member_data(){
      $this->check_session();
      $member_card=$_SESSION['member_card'];
      $member_data=M('member_data')->where(array('mid' =>$member_card['mid'],'openid'=>$member_card['openid']))->field('id,name,card_id')->find();
      $this->member_data=$member_data;
      $this->member_card=$member_card;
      $this->display();

    }


    //会员卡信息提交处理
    Public function h_add_member(){
        $id=$_POST['id'];
        if ($id=="") {
           //添加会员卡处理
              $image_url = C('image_url');
              $cardid=M('member_data')->where(array('mid'=>$_POST['mid']))->max('id');
              $data['name']=$_POST['name'];
              $data['tel']=$_POST['tel'];
              $data['openid']=$_POST['openid'];
              $data['mid']=$_POST['mid'];
              $data['date']=date('Y-m-d');
              $data['card_id']='0000'.++$cardid;
              $data['siteid']=$_POST['siteid'];
              $User=M('member_data');
              $User->create();
              $User->add($data);

              $t=M('member_integral');
              $t->create();
              $t_data['card_id']=$data['card_id'];
              $t_data['openid']=$data['openid'];
              $t->add($t_data);
             // $this->redirect('/Index/Member/', array('mid' => $_POST['mid']));
              $murl = $image_url.'/index.php/Index/Member/mid/'.$_POST['mid'].'?openid='.$_POST['openid'];
              header("Location:".$murl."");
        }else{
           //完善信息
            
              $data['siteid']=$_POST['siteid'];
              $data['name']=$_POST['name'];
              $data['sex']=$_POST['sex'];
              $data['start']=$_POST['start'];
              $data['finish']=$_POST['finish'];
              $data['address']=$_POST['address'];
              $data['tel']=$_POST['tel'];
              $data['address_url']=$_POST['address_url'];
              $data['num']=$_POST['num'];
              $data['details']=$_POST['details'];
              $data['detailj']=$_POST['detailj'];
              $data['detailsj']=$_POST['detailsj'];

              
              $User=M('member_data');
              $User->create();
              $User->where(array('id'=>$mid))->save($data);

              $this->success('添加成功');

        }
    }

   //最新通知
    Public function news_member(){
      $this->check_session();
      $member_card=$_SESSION['member_card'];
      $image_url=C('image_url');
      $member=M('member')->where(array('id'=>$member_card['mid']))->field('id,details')->find();
      $details=htmlspecialchars($member['details']);
      $this->assign('id',$id);
      $this->assign('details',$details);
      $this->display();
    }
     //会员卡说明
     Public function info_member(){
         $this->check_session();
         $member_card = $_SESSION['member_card'];
         $image_url = C('image_url');
         $member = M('member')->where(array('id'=>$member_card['mid']))->field('id,details,detailj,detailsj')->find();

         $this->assign('id',$id);
         $this->member = $member;
         $this->display();

     }

     //会员优惠券
     Public function coupon_member(){
         $this->check_session();
         $member_card = $_SESSION['member_card'];
         $image_url = C('image_url');
         $member = M('member')->where(array('id'=>$member_card['mid']))->field('id,details')->find();
         $details = htmlspecialchars($member['details']);
         $map['siteid'] = $member_card['siteid'];
         $map['openid'] = $member_card['openid'];
         $coupon_data = M('coupon_data')->where($map)->select();
         $this->coupon_data = $coupon_data;
         $this->assign('id',$id);
         $this->assign('details',$details);
         $this->display();

     }

     //积分兑换
     Public function mall_member(){
         $this->check_session();
         $member_card = $_SESSION['member_card'];
         $image_url = C('image_url');
         $member_mall = M('member_mall')->where(array('mid'=>$member_card['mid']))->select();
         $jifen = M('member_data')->where(array('openid'=>$member_card['openid']))->field('total_integral')->find();
         $this->member_mall = $member_mall;
         $this->jifen = $jifen;
         $this->display();
     }

     //每日签到
     Public function sign_member(){
         $this->check_session();
         $member_card=$_SESSION['member_card'];
         $count_day = date('d');
         $map['openid'] = $member_card['openid'];
         $l_date=date('Y-m').'%';
         $map['sign_date'] = array(like,$l_date);
         $t=M('member')->where(array('id'=>$member_card['mid']))->field('integral')->find();
         $sign_day = M('member_data_sign')->where($map)->select();
         $countNum=count($sign_day)*$t['integral'];
         $integral = M('member_integral')->where(array('openid'=>$member_card['openid']))->find();
         for($i = 0;$i<$count_day;$i++){
            //生成签到日期数组
            $str='-'.$i.'day';
            $t_day = date('Y-m-d' , strtotime($str));
            $day =date('m月d日' , strtotime($str)).' '.week($t_day);
            $dateArray[$i]['wdate'] = $day;
            $dateArray[$i]['date'] = $t_day;
            foreach($sign_day as $key=>$val){
                if($dateArray[$i]['date'] == $val['date']){
                    $dateArray[$i]['sign'] = '已签到';
                    $dateArray[$i]['jifen'] = '+'.$t['integral'];
                }else{
                    $dateArray[$i]['sign'] = '未签到';
                    $dateArray[$i]['jifen'] = '+0';
                }
            }
         }
         if($integral['date'] == date('Y-m-d')){
           //表示今天已经签到
            $state=1;
         }else{
            $state=0;
         }
         $this->cardid = $integral['card_id'];
         $this->openid = $member_card['openid'];
         $this->state = $state;
         $this->countNum = $countNum;
         $this->integral = $integral;
         $this->dateArray = $dateArray;
         $this->display();
     }

     //签到AJAX
     Public function signAjax(){
         //判断是否为 ajax 请求
         if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
             // ajax请求
             $this->check_session();
             $member_card = $_SESSION['member_card'];
             $data['openid'] = $_POST['openid'];
             $data['card_id'] = $_POST['cardid'];
             $data['date'] = date('Y-m-d');
             $m=M('member')->where(array('id'=>$member_card['mid']))->field('integral')->find();
             $sign_data = M('member_data_sign')->where(array('date'=>$data['date'],'openid'=>$data['openid']))->field('id')->find();
             if(empty($sign_data)){
                //表示今天未签到
                $User = M('member_data_sign');//签到记录表
                $User->create();
                $User->add($data);
                //总积分添加
                $tmp = M('member_integral')->where(array('card_id'=>$data['cardid']))->field('total_integral,in_integral')->find();
                $tmp_t['total_integral'] = $tmp['total_integral']+$m['integral'];//总积分
                $tmp_t['in_integral'] = $tmp['in_integral']+$m['integral'];//签到积分
                $tmp_t['date'] = date('Y-m-d');
                $t = M('member_integral');
                $t->create();
                $t_map['card_id']=$data['card_id'];
                $t_map['openid']=$data['openid'];
                $t->where($t_map)->save($tmp_t);
                $msg = true;
             }else{
                $msg = false;
             }
             echo json_decode($msg);
         }else{
             // 非ajax
             echo '请勿非法访问';
         };
     }

 //门店导航
  Public function storeid(){
     $siteid = $_GET['_URL_']['3'];
     $image_url = C('image_url');
     $store_c = M('map_c_store')->where(array('siteid'=>$siteid))->select();
     $store_tpl = M('map_store_tpl')->where(array('siteid'=>$siteid))->find();
     $store = M('map_store') ->where(array('siteid'=>$siteid))->order('sort DESC')->select();
     foreach ($store as $key => $val) {
      //一键导航url
      $store[$key]['url'] ='http://api.map.baidu.com/marker?location='.$val['coordinate_x'].','.$val['coordinate_y'].
       '&title='.$val['name'].'&content=地址：'.$val['address'].'&output=html&src=yourComponyName|yourAppName';
     }
    $this->store = $store;
    $this->image_url = $image_url;
    $this->display(store.$store_tpl['store_tpl']); 
  }

 }
?>