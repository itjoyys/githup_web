<?php

   Class Groupbuy_goodsViewModel extends ViewModel{

   	   Protected $viewFields=array(

   	   	'groupbuy_goods'=>array('id','name','num','num_buyer','finish','saleprice','price','sort','img','discount','detail','_type'=>'INNER'),

   	   	'groupbuy_img'=>array('pic','_on'=>'groupbuy_goods.id=groupbuy_img.gid')

   	   	); 

   }







?>