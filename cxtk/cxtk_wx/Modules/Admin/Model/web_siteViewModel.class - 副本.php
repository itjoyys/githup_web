<?php


   Class SiteViewModel extends ViewModel{


   	   Protected $viewFields=array(

   	   	'site'=>array('sid','uid','sitename','copyright','support','surl','module',

   	   	'_type'=>'INNER'),
   	   	'user'=>array('name','agentname','_on'=>'site.uid=user.uid')

   	   	); 

   }







?>