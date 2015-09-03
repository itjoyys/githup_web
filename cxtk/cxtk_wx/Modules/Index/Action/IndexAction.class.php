<?php 
/*
  前台首页控制器
*/
 Class IndexAction extends Action{
    //前台主页视图
    //获取站点参数
    Public function siteid(){
        $siteid= $_GET["_URL_"][3]; 
        $openid= $_GET['openid']; 
        $shopconfig=M('shopconfig')->where(array('siteid' => $siteid))-> select();
        $this->assign('title',$shopconfig[0]['title']);
        $this->assign('Keywords',$shopconfig[0]['Keywords']);
        $this->assign('Description',$shopconfig[0]['Description']);
        $this->assign('logo',$shopconfig[0]['shoplogo']);
        $shop_config = array(
          'shop_siteid' => $siteid,
          'shop_openid' => $openid,
          'shop_siteid' => $siteid,
        );  
        session('siteid',$siteid);
        session('openid',$openid);
        import('Class.Category', APP_PATH);
        if (!$cate=S('index_cate')) {
             $cate= M('cate') ->where(array('siteid' => $siteid))-> order('sort DESC')-> select();
             $cate = Category::unlimitedForLevel($cate);
             S('index_cate',$cate,10);
         }
   
         $this->cate=$cate;
         $this->assign("cate", $cate);     
         //幻灯片读取
         $this->adlist=$adlist=M('shopadlist')->where(array('siteid' => $siteid))->order('sort ASC')->select();
         $this->assign("adlist", $adlist);
         //读取新上架商品
         if (!$newgoods=S('index_ngoods')) {
            
            $newgoods=D('GoodsView')->where(array('property'=>'2','siteid'=>$siteid))->select();
            S('index_ngoods',$newgoods,10);

         }
         $this->newgoods=$newgoods;
         $this->assign("newgoods", $newgoods);
         $this->assign("image_url", 'http://www.weipan.wx0571.com/weipan');   
         //读取特价商品
         if (!$salegoods=S('index_sgoods')) {
            $salegoods=D('GoodsView')->where(array('property'=>'3','siteid'=>$siteid))->select();
            S('index_sgoods',$salegoods,10);
         }
         $this->salegoods=$salegoods;
         $this->assign("salegoods", $salegoods);
          //读取热卖商品
         if (!$hotgoods=S('hgoods')) {
             $hotgoods=D('GoodsView')->where(array('property'=>'1','siteid'=>$siteid))->select();
             S('index_hgoods',$hotgoods,10);
         }
         $this->hotgoods=$hotgoods;
         $this->assign("hotgoods", $hotgoods);
 
         // $cate_url=U(GROUP_NAME .'/List/Index/id/'.'$id');
          //p($_SESSION);
        // unset($_SESSION['Cart']);

         $i=$shopconfig[0]['index_tpl'];

         session('l',$shopconfig[0]['list_tpl']);
         session('d',$shopconfig[0]['detail_tpl']);
         session('copyright', $shopconfig[0]['title']);
    
         $this->display(index.$i);
    }
 }

?>