<?php
   //关键词查询
   class MultiKeySearch{
      private $Keyword;//查询关键字
      private $result;//结果

      function __construct($Keyword,$Datalist,$Field)//查询关键字，数据表
      {

        $this->Keyword=$Keyword;
        $this->Datalist=$Datalist;
        $this->Field=$Field;//查询的关键字段

      }

    function DoSql()//查询关键字，取得值

    {
      $ArrayKeyword=preg_split('/\.|\+| |_/',$this->Keyword);
      //分割，返回数组.+空格下划线分割。

      $Query=M($this->Datalist);//实例化模型
      $conditon="";//条件集合
      $flag=1;
      //dump($ArrayKeyword);

      foreach($ArrayKeyword as $value)

      {
        //echo $value;
        if($flag==1)
        {

          $condition.=$this->Field." like '"."%".$value."%'";//模糊查询
          $flag=0;//首个字段不用加or

        }

        else

        {

          $condition.=' or '.$this->Field." like '"."%".$value."%'";

        }

      }

      //echo $condition;//打印SQL条件语句

      $this->result=$Query->where($condition)->select();

      foreach($this->result as &$value)//遍历数组添加引用,

      {

        foreach($ArrayKeyword as $keyword)

        {

          $value['Profession']= str_replace($keyword,"<font color=red>".$keyword."</font>",$value['Profession']);
        }

      }
      return $this->result;//返回一个高亮查询结果
    }

   }

?>