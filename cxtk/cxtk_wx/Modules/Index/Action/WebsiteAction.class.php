<?php 
 Class WebsiteAction extends Action{
    Public function siteid(){
       
        $siteid= $_GET["_URL_"][3]; 
        $image_url=C("image_url");
        $openid= $_GET['openid']; 
        $websiteconfig=M('websiteconfig')->where(array('siteid' => $siteid))-> select();
    
           $this->assign('title',$websiteconfig[0]['title']);
           $this->assign('Keywords',$websiteconfig[0]['Keywords']);
           $this->assign('Description',$websiteconfig[0]['Description']);
        
           $w_index_tpl=$websiteconfig[0]['index_tpl'];
           $w_list_tpl=$websiteconfig[0]['list_tpl'];
           $w_detail_tpl=$websiteconfig[0]['detail_tpl'];

           session('siteid',$siteid);
           session('openid',$openid);
           session('w_list_tpl',$w_list_tpl);
           session('w_detail_tpl',$w_detail_tpl);
      
           $column= M('websitecolumn') ->where(array('siteid' => $siteid))-> order('sort ASC')-> select();
           $cnum=count($column);
        for ($i=0; $i < $cnum; $i++) { 
          if ($column[$i]['url']=='' && $column[$i]['classid']=='1' ) {
             //栏目地址
             $column[$i]['url']=$image_url.'/index.php/Index/Website/websitelist/pid'.'/'.$column[$i]['id'];
           
          }elseif($column[$i]['url']=='' && $column[$i]['classid']=='0'){
           $column[$i]['url']=$image_url.'/index.php/Index/Website/websitedetail/pid'.'/'.$column[$i]['id'];

          }
        }

       

        //p($column);
         $this->cnum=$cnum;
         $this->column=$column;
         $this->assign("column", $column);
         $this->assign("cnum", $cnum);

       
      
         //幻灯片读取
         $this->slide=$slide=M('websiteslide')->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $this->assign("slide", $slide);
         $this->assign("image_url", $image_url);

         $i=$websiteconfig[0]['index_tpl'];

         session('website_list_tpl',$shopconfig[0]['list_tpl']);
         session('website_detail_tpl',$shopconfig[0]['detail_tpl']);
         session('copyright', $shopconfig[0]['title']);
         $tel[0]=123;
         $tel[1]=123;
         $tel[2]=123;
         $tel[3]=123;
       //  $html_d = '<p><foreach name="'.'tel" item="'.c.'"><{$c}><br></foreach></p>';
         $html_d = '<p><foreach name="tel" item="c"><{$c}><br></foreach></p>';
         $this->tel = $tel;
         $this->html_d = $html_d;


         $this->display(index.$w_index_tpl);
         $this->show($this->fetch());
    }

    Public function web_list(){
      $id=intval($_GET['id']);
      if (empty($id)) {
         exit('system error 0000');
      }
      if (!empty($_GET['type']) && $_GET['type'] == 'd') {
         $map['id'] = $id;
         $cate = D('Common')->rfind('web_cate','',$map);
         $cate['content']=stripslashes($cate['content']);
      }else{
         $map['pid'] = $id;
         $cate= D('Common')->rselect('web_cate','',$map,'sort desc');
      }

      $this->cate=$cate;
      if (!empty($_GET['type'])) {
         $this->display('detail');
      }else{
         $this->display('list');
      }
      
    }
    Public function web_contact(){
       $siteid= $_GET["_URL_"][3];
       $store=M('websitestore')->where(array('siteid' => $siteid))->order('id ESC')->select();
       $Websitecontact=M('websitecontact')->where(array('siteid' => $siteid))->select();

       $this->assign('contacturl',$contacturl);
       $this->assign('siteid',$siteid);
       $this->assign('store',$store);
       $this->assign('img',$Websitecontact[0]['img']);
       $this ->display();

    }
   Public function uform(){
       $siteid= $_GET["_URL_"][3]; 
       $image_url=C("image_url");
       $form=M('universalform')->where(array('siteid' => $siteid))->select();
       $this->assign('form',$form);
       $this->assign('siteid',$siteid);
       $this ->display();

   }

   Public function wbook(){
       $siteid= $_GET["_URL_"][3]; 
       $booking=M('websitebooking')->where(array('siteid' => $siteid))->order('sort ASC')->select();
       $booki=M('websitebooki')->where(array('siteid' => $siteid))->select();
       session('siteid',$siteid);
       $num=count($booking);
       for ($i=0; $i <$num ; $i++) { 
           if ($booking[$i]['wfield']==2) {
                $options= explode('|', $booking[$i]['content']); 
                $booking[$i]['content']=$options;         
           }
       }

       $this->assign('booking',$booking);
       $this->assign('image_url',$image_url);
       $this->assign('siteid',$siteid);
       $this->assign('instructions',$booki[0]['instructions']);
       $this->assign('tel',$booki[0]['tel']);
       $this->assign('address',$booki[0]['address']);
       $this->assign('url',$booki[0]['url']);
       //p($booking);
       $this->display();
   }
   //在线预约表单提交
    Public function runwbook(){

       $siteid=$_SESSION['siteid'];
       $num=count($_POST);
       $keys=array_keys($_POST);
       $ckey=array_values($_POST);
        $User = M("websitebook"); // 实例化User对象  
        $User->create(); // 创建数据对象
       if(in_array('', $_POST)){
        $this->error('请填写完整信息!');
       }else{
           
           for ($i=0; $i < $num; $i++) { 
            $User->fielid = $keys[$i]; 
            $User->siteid = $siteid; 
            $User->content =$ckey[$i]; 
            $User->fid =$lastid; 
            $User->add(); // 写入用户数据到数据库
            $this->success("订单提交成功！");
            if ($lastid=='') {
              $lastid = mysql_insert_id();
            }     
            }

       }

   
   }

   Public function storeid(){
     $siteid= $_GET["_URL_"][3]; 
     import('Class.Category', APP_PATH);
     $store_class=M('store_class')->where(array('siteid'=>$siteid))->select();
     $store_class = Category::menuLayer($store_class);
     p($store_class);

   }


 }

 

?>