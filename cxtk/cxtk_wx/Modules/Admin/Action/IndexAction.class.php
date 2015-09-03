<?php 
/*
  后台首页控制器
*/
 Class IndexAction extends CommonAction{
    //后台主页视图
    Public function Index(){
       $siteid=$_GET['siteid'];
        session('siteid',$siteid);
        $this->modules=$modules=M('site')->field('module')->where(array('sid' => $siteid))->select();
        $modules=$modules[0]['module'];
        $modules= explode(' ', $modules);

       
        $num=COUNT($modules);
        
         for ($i=0; $i <$num ; $i++) { 
         	 $this->url=$url=M('modules')->field('url')->where(array('mid' => $modules[$i]))->select();
         	 $url=$url[0]['url'];
         	 $reurl=$reurl.$url;

         }

       
        $this->assign('reurl',$reurl);
       
        $this->display(); // 输出模板

    }

 }

?>