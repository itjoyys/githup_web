<?php 

if($type!="ka_kithe"){
 
 $table=M("c_auto_".$type."",$db_config);

$page =$table->showPage($totalPage,$page);
 }else{
   $table=M("ka_kithe",$db_config);

  $page =$table->showPage($totalPage,$page);

 }
 ?>
<script>
  
  //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
      <form  name="myFORM"  id="myFORM"  action=""  method="get">
        <input type="hidden" value="<?=$_GET['type']?>" name="type"/>
          类型:<select  id="cp_cate"  name="enable" onchange="document.getElementById('myFROM').sumit"  class="za_select">
                   
                   <option  value="fucai_3D.php?type=fucai_3D">福彩3D</option>
                   <option  value="pailie_3.php?type=pailie_3">排列三</option>
                   <option  value="chongqing_ssc.php?type=chongqing_ssc">重庆时时彩</option>
                   <option  value="tianjin_ssc.php?type=tianjin_ssc">天津时时彩</option>
                   <option  value="jiangxi_ssc.php?type=jiangxi_ssc">江西时时彩</option>
                   <option  value="xinjiang_ssc.php?type=xinjiang_ssc">新疆时时彩</option>
                   <option  value="beijing_pk.php?type=beijing_pk">北京PK拾</option>
                   <option  value="beijing_8.php?type=beijing_8">北京快乐8</option>
                   <option  value="guangdong_ten.php?type=guangdong_ten">广东快乐十分</option>
                   <option  value="chongqing_ten.php?type=chongqing_ten">重庆快乐十分</option>
                   <option  value="liuhecai.php?type=liuhecai">六合彩</option>
    			   <option  value="jiangsu_3.php?type=jiangsu_3">江苏快3</option>
                   <option  value="jilin_3.php?type=jilin_3">吉林快3</option>
               </select>
          期数：
          <input  type="text"  name="qishu"  value="<?=$_GET['qishu']?>"  class="za_text"  style="min-width:80px;width:80px;"  size="15"  maxlength="15">
	

      选择日期：
		   <input style="width:75px;height:18px;" name="date" type="text" id="date_kaijiang" value="<?=$_GET['date']?>"  onClick="WdatePicker()" size="10" maxlength="10" readonly class="Wdate" />
		  
          <input  type="submit"  name="buname"  value="搜索"   class="za_button">

            每页记录数：
        <select name="page_num" id="myFORM" onchange="document.getElementById('myFORM').submit()" class="za_select">
          <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
          <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
          <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
          <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
        </select>
        &nbsp;<?=$page?>&nbsp;
      </form>
<script>
  $(document).ready(function(){
   $("#cp_cate").val("<?=$_GET[type].'.php'?>?type=<?=$_GET[type]?>");
})
</script>