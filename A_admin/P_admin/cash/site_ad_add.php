<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include("../../lib/class/UPUPUP.class.php");

/**站内广告通知添加
**/
$Ad_position = array('1'=>array('name'=>'中间广告位','ad_width'=>'500','ad_height'=>'250'),
                     '2'=>array('name'=>'左上广告位','ad_width'=>'280','ad_height'=>'280'),
                     '3'=>array('name'=>'左下广告位','ad_width'=>'280','ad_height'=>'280'),
                     '4'=>array('name'=>'右下广告位','ad_width'=>'280','ad_height'=>'280'),
);

$textbgc = array('blue_3'=>'浅蓝色','blue_1'=>'蓝色','blue_2'=>'天蓝色','yellow_1'=>'黄色',
    'yellow_2'=>'土黄','green_1'=>'绿色','red_1'=>'红色','black_1'=>'黑棕色','black_2'=>'黑色','purple'=>'紫色','brown'=>'咖啡色');
$Ad_url = array(
    '0'=>array('name'=>'联系我们','url'=>'/index.php?a=about'),
    '1'=>array('name'=>'合作伙伴','url'=>'/index.php?a=cooperation'),
    '2'=>array('name'=>'存款帮助','url'=>'index.php?a=cunkuan'),
    '3'=>array('name'=>'取款帮助','url'=>'index.php?a=qukuan'),
    '4'=>array('name'=>'常见问题','url'=>'index.php?a=changjian'),
    '5'=>array('name'=>'优惠活动','url'=>'index.php?a=youhui'),
    '6'=>array('name'=>'会员注册','url'=>'/index.php?a=zhuce')
);
$objAd = M('site_ad',$db_config);
if (!empty($_GET['id'])) {
	$adData = $objAd ->where("id = '".$_GET['id']."'")->find();
}


$map['site_id']=SITEID;
//获取对应前台域名
$indexUrl = M('web_config',$db_config)->where("site_id = '".SITEID."'")->getField("conf_www");
if (!empty($_POST['act']) && $_POST['act'] == 'postData') {
	if(!empty($_POST['title'])){
		 $data=array();
		 $data['title']=$_POST['title'];
		 $data['type']=$_POST['wtype'];
		 $data['Ad_url'] = $_POST['Ad_url'];
		 $data['Ad_position'] = $_POST['Ad_position'];
		 $data['picwidth'] = $_POST['picwidth'];
		 $data['picheight'] = $_POST['picheight'];
		 $data['textbgc'] = $_POST['textbgc'];
		 if ($_POST['wtype'] == '1') {
		 	//文字类型
		 	$data['content']=$_POST['content'];
		 }else{
		 	if (!empty($_FILES["pic"]["name"])) {
// 		        $upImg = new UPUPUP();
// 		        $path = '../../../'.SITEID.'/images/siteAd/';
// 		        $upImg->up_path = $path;
// 		        $upImg->bestWidth = $_POST['picwidth'];
// 		        $upImg->height = $_POST['picheight'];
// 		        $returnUrl = $upImg -> upImg("pic");
// 		        $spos = strpos($returnUrl,SITEID);
		 	    
		 	     $filename_info 	= pathinfo($_FILES['pic']['name']);
		 	     $saveFileName 	= time().rand(0,999999999).".".$filename_info['extension'];
		 	     $savePath 	=  '../../../'.SITEID.'/images/siteAd/';         //上传图片目录
		 	     $img = new Image();
		 	     $file = $img->setsaveName($saveFileName)->upfile("pic", $savePath); //将图片上传至$savePath
                if($file){
		 	     $rootPath = '../../../'.SITEID;
		 	     $smallpath	= '/images/siteAd/';           //缩略图目录
		 	     $file_shrink = $img->setShrinkWidth($_POST['picwidth']) //缩略图宽度
		 	     ->setShrinkHeight($_POST['picheight'])//高度
		 	     ->setShrinkType("jpg")//缩略图类型
		 	     ->setsaveName("small_".$saveFileName) //缩略图名字
		 	     ->resizeImage($file,$rootPath,$smallpath);      //生成缩略图方法
		 	    if ($file_shrink){
		 	        unlink($file);
		 	        $data['img'] = $file_shrink;//substr($returnUrl,$spos+1);
		 	    }else{
		 	       message($img->showErrmsg());  //出错提示
		 	    }
              }else{
                  $error =$img->showErrmsg();
                  echo $error;
                  message($error);
              }				
		 	}else{
		 		//echo "没有图片数据可上传";
		 	}
		 }
		if (empty($_POST['id'])) {
			//添加
			$data['creator']=$_SESSION['login_name_1'];
			$data['add_date'] = date('Y-m-d H:i:s');
			$data['is_delete'] = 0;
		    $data['site_id'] = SITEID;
		    $isState = $objAd->add($data);
		}else{
			$isState = $objAd->where("id = '".$_POST['id']."'")->update($data);
		}

		if($isState){
		    message('操作成功！','site_ad.php');
	     }
		
	}
}
?>
<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">添加站内广告</div>
  <div class="con_menu">
  	<a  href="agent_ad.php" >代理廣告</a>
    <a  href="agent_ad_statistics.php">廣告統計</a>
    <a  href="site_ad.php"  style="color:red">站内廣告</a>
  	<input type="button" value="返回上一頁" onclick="javascript:history.go(-1);" class="button_d"></div>
</div>


<form method="post" name="withdrawal_form" enctype="multipart/form-data" action="site_ad_add.php" id="vform">
<table class="m_tab" style="width:650px;">
	<tbody><tr class="m_title">
		<td height="25" align="center" colspan="2">站内广告</td>
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1">广告类型</td>
          <td>
          	<select id="wtype" name="wtype" class="za_select" onchange="wtypechange();">
				<option value="1">文字类型</option>
				<option value="2">图片类型</option>
			</select>
          </td>
	</tr>
	<tr>
			<td height="25" align="center" class="table_bg1">背景框</td>
          <td>
          	<select id="textbgc" name="textbgc" class="za_select">
          	    <option value="0">请选择背景框</option>
					<?
		if(!empty($textbgc)){
		   foreach($textbgc as $k=>$v){
		       ?>
		       <option value="<?=$k ?>"><?=$v ?></option>
		       <?	       
		   }
		}?>
		
			</select>
          </td>
	</tr>
		<tr>
		<td height="25" align="center" class="table_bg1">广告位置</td>
          <td>
          	<select id="Ad_position" name="Ad_position" class="za_select">
          	    <option value="0">请选择广告位</option>
					<?
		if(!empty($Ad_position)){
		   foreach($Ad_position as $k=>$v){
		       ?>
		       <option ad_width="<?=$v['ad_width'] ?>" ad_height="<?=$v['ad_height'] ?>" value="<?=$k ?>"><?=$v['name'] ?></option>
		       <?	       
		   }
		}?>
		
			</select>
          </td>
	</tr>
		<tr>
		<td height="25" align="center" class="table_bg1">广告链接</td>
          <td>
          	<select id="Ad_url" name="Ad_url" class="za_select">
          	    <option value="0">请选择链接地址</option>
					<?
		if(!empty($Ad_url)){
		   foreach($Ad_url as $k=>$v){
		       ?>
		       <option value="<?=$v['url']?>"><?=$v['name'] ?></option>
		       <?	       
		   }
		}?>
		
			</select>
          </td>
	</tr>
	
	<tr>
		<td height="25" align="center" class="table_bg1" width="30%">广告标题</td>
          <td>
          	<div style="float:left"><input class="za_text" name="title" id="title" value="<?=$adData['title']?>"></div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
	<tr id="img_tr" style="display:none;">
		<td height="25" align="center" class="table_bg1" width="30%">广告图片</td>
          <td>
          	<div style="float:left">
             <input type="file" name="pic" id="pic" value="">
             <?if($adData['img']){ ?>
             <img alt="预览" src="http://<?=$indexUrl ?><?=$adData['img'] ?>" width="200" height="200" />
            <?} ?>
          	</div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
		<tr id="img_tr2" style="display:none;">
		<td height="25" align="center" class="table_bg1" width="30%">广告图片宽高</td>
          <td>
          	<div style="float:left">
             宽：<input type="text" size='4' name="picwidth" id="picwidth" value="<?=$adData['picwidth']?>">px
               高：<input type="text" size='4' name="picheight" id="picheight" value="<?=$adData['picheight']?>">px
          	</div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
	<tr id="txt_tr">
		<td height="25" align="center" class="table_bg1">广告内容</td>
		<td>
			<div style="float:left"><textarea datatype="*2-20000" class="za_text" name="content" id="content" style="width:500px;height:150px;"><?=$adData['content']?></textarea></div>
			<div class="Validform_checktip" style="float:left"></div>
		</td>
	</tr>
	
	<tr align="center">
		<td colspan="2" class="table_bg1">
			<input type="hidden" value="postData" name="act">
			<input value="確定" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
			<input type="hidden" value="<?=$adData['id']?>" name="id">
		</td>
	</tr>
</tbody></table> 
</form>

<script language="JavaScript" type="text/JavaScript">
var wtype = "<?=$adData['type']?>";
var Ad_position = "<?=$adData['Ad_position']?>";
var Ad_url = "<?=$adData['Ad_url']?>";
var textbgc = "<?=$adData['textbgc']?>";
if (wtype != '') {
   $('#wtype').val(wtype);
   if (wtype == 2) {
		$('#img_tr').show();
		$('#txt_tr').hide();
		$('#img_tr2').show();
	}
};

if (Ad_position != '') {
	   $('#Ad_position').val(Ad_position);
	};
	if (Ad_url != '') {
		   $('#Ad_url').val(Ad_url);
		};
		if (textbgc != '') {
			   $('#textbgc').val(textbgc);
			};
function wtypechange()
{
	var type = $('#wtype').val();
    if(type==2){
		$('#img_tr').show();
		$('#txt_tr').hide();
		$('#img_tr2').show();
	}else{
		$('#img_tr').hide();
		$('#txt_tr').show();
		$('#img_tr2').hide();
	}
}


$(function(){
	$("#vform").submit(function(event) {
		var type = $('#wtype').val();
		var Ad_position = $("#Ad_position").val();
		var ad_width = $("#Ad_position").find("option:selected").attr("ad_width");
		var ad_height = $("#Ad_position").find("option:selected").attr("ad_height");
		if(Ad_position=="0"){
			alert("请选择广告位！");
			return false;
			}
		if($("#title").val() == ''){
			alert("标题不能为空！");
			return false;
			
		}

		if($("#Ad_url").val() == ''){
			alert("广告链接不能为空！");
			return false;
			
		}
		if($("#textbgc").val() == ''){
			alert("请选择背景框！");
			return false;
			
		}
		if (type == '1') {
	        if($("#content").val() == ''){
				alert("内容不能为空！");
				return false;
			}

		}else if(type == '2'){
	        if($("#picwidth").val() == ''){
				alert("图片的宽度不能为空！");
				return false;
			}
	        if($("#picheight").val() == ''){
				alert("图片的高度能为空！");
				return false;
			}
/* 	        if(parseInt($("#picwidth").val())>parseInt(ad_width)){
				alert("图片的宽度不能超过"+ad_width);
				return false;
			}
	        if(parseInt($("#picheight").val())>parseInt(ad_height)){
				alert("图片的高度不能超过"+ad_height);
				return false;
			} */
	    }
		   
	});
})

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
