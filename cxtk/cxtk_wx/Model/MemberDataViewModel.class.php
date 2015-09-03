<?php


   Class MemberDataViewModel extends ViewModel{

   	   Protected $viewFields=array(

   	   	'member_data'=>array('id','openid','card_id','mid','siteid','name','qq','tel','address','birth',
   	   	'_type'=>'INNER'),
   	   	'member_integral'=>array('openid','card_id','_on'=>'member_data.openid=member_integral.openid')

   	   	);
   }

?>