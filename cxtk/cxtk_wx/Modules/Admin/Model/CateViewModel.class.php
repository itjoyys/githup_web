<?php

//属性表 商品 分类 组合
   Class PropertyViewModel extends ViewModel{


   	   Protected $viewFields=array(

   	   	'site'=>array('sid','uid','sitename','copyright','support','surl','module',

   	   	'_type'=>'INNER'),
   	   	'user'=>array('name','agentname','_on'=>'site.uid=user.uid')

   	   	); 

   }







?>