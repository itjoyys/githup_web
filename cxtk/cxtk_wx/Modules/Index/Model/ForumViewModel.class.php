<?php

  //微信论坛帖子视图
   Class ForumViewModel extends ViewModel{


   	   Protected $viewFields=array(

   	   	'forumpost'=>array('id','postname','state','siteid','content','date',

   	   	'_type'=>'Left'),
   	   	'forumreplies'=>array('pid','repliesname','rcontent','rdate','_on'=>'forumpost.id=forumreplies.pid')

   	   	); 

   }
?>