<?php
class CaiPaioType {
    var $Id;
    var $CpName;
    var $Cp=array(
        7=>'六合彩',
        5=>'福彩3D',
        6=>'排列三',
        2=>'重庆时时彩',
        10=>'天津时时彩',
        11=>'江西时时彩',
        12=>'新疆时时彩',
        8=>'北京快乐8',
        3=>'北京赛车PK拾',
        1=>'广东快乐十分',
         4=>'重庆快乐十分',
      /*  9=>'',*/
        13=>'江苏快3',
        14=>'吉林快3'
    );
    function GetId($CpName){
        $CpIdName=array();
        $CpTypeArray=$this->Cp;
       foreach($CpTypeArray as $k=>$r){
          //echo $k;
           if($CpName==$r ||  $CpName==$k) {
            //   print_r($r);
              $CpIdName=array('Cid'=>$k,'CpName'=>$r);
              break;
           }
       }
       return $CpIdName;
    }

}