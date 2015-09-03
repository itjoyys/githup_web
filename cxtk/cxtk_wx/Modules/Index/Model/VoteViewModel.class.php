<?php

    //微信投票
   Class VoteViewModel extends ViewModel{


   	   Protected $viewFields=array(

   	   	'vote'=>array('id','name','img','start','finish',

   	   	'_type'=>'Left'),
   	   	'votedata'=>array('vid','vname','total','sort','_on'=>'vote.id=votedata.vid')

   	   	); 

   }


?>