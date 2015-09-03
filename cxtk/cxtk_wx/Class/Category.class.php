<?php
 
   class Category{
     Static Public function unlimitedForLevel ($cate ,$html='----',$pid = 0,$level=0){

         $arr=array();
         foreach ($cate as $v){
         	if(  $v['pid'] ==$pid){
                 $v['level'] = $level + 1;
                 $v['html'] =str_repeat($html, $level);
                 $arr[]=$v;
                 $arr = array_merge($arr,self::unlimitedForLevel($cate, $html ,$v['id'], $level+1));
         	}
         }
         return $arr;
     }


       //商城分类组合
    Public static function shopCate($cate,$pid=0){
          $arr=array();
          foreach($cate as $v){
              if($v['pid']==$pid){
                 $v['child']=self::shopCate($cate,$v['id']);
                 $arr[]=$v;
              }
          }
          return $arr;
        }


   //自定义菜单组合多维数组
     Static Public function menuLevel ($menu ,$html='--------',$pid = 0,$level=0){

         $arr=array();
         foreach ($menu as $v){

            if( $v['pid'] ==$pid){
                 $v['level'] = $level + 1;
                 $v['html'] =str_repeat($html, $level);
                 $arr[]=$v;
                 $arr = array_merge($arr,self::menuLevel($menu, $html ,$v['id'], $level+1));
            }
         }
         return $arr;

     }


     //微信自定义菜单组合二维数组
      Public static function menuLayer($menu,$pid=0){
          $arr=array();
          foreach($menu as $v){
              if($v['pid']==$pid){
                 $v['sub_button']=self::menuLayer($menu,$v['id']);
                 $arr[]=$v;
              }
          }
          return $arr;
        }


     //微信门店导航多维组合

       Public static function storeLayer($store_class,$pid=0){
          $arr=array();
          foreach($store_class as $v){
              if($v['pid']==$pid){
                 $v['child']=self::storeLayer($store_class,$v['id']);
                 if (count($v['child'])==4) {
                   $v['child_l']==$v['child'];
                 }
                 $arr[]=$v;
              }
          }
          return $arr;
        }


   }

?>