<?php


   Class MemberDataViewModel extends ViewModel{

   	   Protected $viewFields=array(

       'member_integral'=>array('openid','card_id','total_integral','in_integral','xiaofei_integral',

           '_type'=>'INNER'  ),

   	   	'member_data'=>array('id'=>'did','openid','card_id','mid','siteid','name','state','tel','qq','sex','birth',
         'money','date','level_id', '_on'=>'member_data.openid=member_integral.openid',
   	   	'_type'=>'Left'),

        'member_level'=>array('id','mid','level_name','level_rule',
         '_on'=>'member_data.level_id = member_level.id')

   	   	);
   }

?>