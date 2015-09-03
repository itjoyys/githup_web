<?php
/*
  商品信息表 分类表 图片表 属性信息表
*/

   Class GoodsViewModel extends ViewModel{

   	   Protected $viewFields=array(
        'shop_cate'=>array('id'=>'cate_id','name','_type'=>'INNER'),

   	   	'shop_goods'=>array('id','name','classid','siteid','saleprice','price','details','property','num',
   	   		'_type'=>'INNER','_on'=>'shop_cate.id=shop_goods.classid'),

   	   	 'shop_photo'=>array('gid','photo1','photo2','photo3','photo4',
   	   	 	'_on'=>'shop_goods.id=shop_photo.gid')
   	   	); 

   }



?>