<?php
   Class GoodsViewModel extends ViewModel{
   	   Protected $viewFields=array(
   	    'shop_cate'=>array('id'=>'cate_id','name'=>'cate_name' ,'_type'=>'INNER'),

   	   	'shop_goods'=>array('id','name','siteid','goodsid','saleprice','price','property','details','num',
   	   	'_type'=>'INNER','_on'=>'shop_cate.id=shop_goods.classid'),

   	  	'shop_photo'=>array('goodsid','photo1','photo2','photo3','photo4',
          '_on'=>'shop_goods.id=shop_photo.gid'),
   	   	); 

   }
?>