<?php
 
   class Modules{

     Static Public function unlimitedForLevel ($modules ,$html='----',$pid = 0,$level=0){

         $arr=array();
         foreach ($modules as $v){

         	if(  $v['pid'] ==$pid){
                 $v['level'] = $level + 1;
                 $v['html'] =str_repeat($html, $level);
                 $arr[]=$v;
                 $arr = array_merge($arr,self::unlimitedForLevel($modules, $html ,$v['id'], $level+1));



         	}
         }
         return $arr;



     }



   }





?>