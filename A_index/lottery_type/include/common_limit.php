<div id="old_val" style="display:none;"></div>
<script>

  function check_submit(){

    if($("#allgold").text()==0){
      alert("请填写下注金额！");
      return false;
    }
  }
</script>
<?php

if(empty($_SESSION['uid']))
{
  echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
  echo '<script type="text/javascript">top.location.href="/";</script>';
  exit;
}

include_once("../common/user_set.php");
include("../include/private_config.php");
$user = M('k_user',$db_config);
// var_dump($_GET);
if (!empty($_SESSION['uid'])) {
   $user_id = $_SESSION['uid'];
   $user_data = $user->field('username,agent_id,money')->where("uid = '".$_SESSION['uid']."'")->find();
}

if(!empty($_SESSION['agent_id'])){
    $agent_id = $_SESSION['agent_id'];
}

if(empty($style)){
	$style = $_GET["style"];
	if(empty($style)){
		$style = 'liuhecai';
	}
}
if(empty($title_3d)){
	$title_3d = trim($_GET['leixing_1']);
}
if(empty($title_3d)){
	$title_3d = trim($_GET['leixing']);
}

if($title_3d=='正特'){
	$title_3d='正码特';
}elseif(trim($_GET['haoma'])=='正码一' || trim($_GET['haoma'])=='正码二'  || trim($_GET['haoma'])=='正码三'  || trim($_GET['haoma'])=='正码四'  || trim($_GET['haoma'])=='正码五'  || trim($_GET['haoma'])=='正码六'  ){
	$title_3d='正码1-6';
}


// echo $style;
// echo $title_3d;

$fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8',
		'bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3',
		'liuhecai'
);
$Fcgame = M('fc_games_view',$db_config);
$Uagent = M('k_user_agent_fc_set',$db_config);
$Uaset = M('k_user_fc_set',$db_config);

foreach ($fcArr as $key => $val) {
	$tmpA = $tmpB = array();
	$tmpA = $Fcgame->join("join k_user_agent_fc_set on k_user_agent_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_agent_fc_set.aid = '".$agent_id."'")->select("type");
	$tmpB= $Fcgame->join("join k_user_fc_set on k_user_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_fc_set.uid = '".$user_id."'")->select("type");
	$fcType[$val] = array_merge($tmpA,$tmpB);
}

$sets = $fcType[$style];
if(!empty($sets)){

	foreach ($sets as $k => $v) {
		if($v['name'] == $title_3d){
			$single_field_max = $v['single_field_max'];
			$single_note_max = $v['single_note_max'];
            $single_note_min = $v['min'];
		break;
		}
	}
}

?>

 <div id="single_field_max" style="display:none;"><?=$single_field_max ?></div>
<div id="single_note_max" style="display:none;"><?=$single_note_max ?></div>
<div id="single_note_min" style="display:none;"><?=$single_note_min ?></div>
<div id="ball_limit_num" style="display:none;"><?=$ball_limit_num ?></div>
<script>
function isPositiveNum(s){//是否为正整数
    var re = /^[0-9]*[1-9][0-9]*$/ ;
    return re.test(s)
}

$(function(){

  	//下注总金额计算及表单验证
       // var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
       // var single_note_max = $("#single_note_max").text();//单注金额上限20000

 		var single_field_max = <?=$single_field_max?$single_field_max:50000 ?>; //单注总金额上限200000
    var single_note_max = <?=$single_note_max?$single_note_max:2000 ?>;
       	//单注金额上限20000
    var single_note_min = <?=$single_note_min?$single_note_min:0 ?>;
    var ball_limit_num = <?=$ball_limit_num ?>;//已投注的金额
        // alert(ball_limit_num);
     //下注金额

	  var all_val=0;
    var i;
    var input1=$("input[type='text'][name]");


    var old_val;
    var new_val;
    var val;
      // for(i=0;i<input1.length;i++){

        //验证非法字符
        input1.keyup(function() {
          var newval3 = $(this).val();

          var reg1 = /([a-zA-Z])/ig;
          var reg = /([`~!@#$%^&*()_+<>?:"{},\/;'[\]])/ig;
           var reg2 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;

          if(isNaN(newval3)){
            $(this).val($(this).val().replace(reg, ""));

            $(this).val($(this).val().replace(reg1, ""));

            $(this).val($(this).val().replace(reg2, ""));
            // $(this).val("");
          }

        });

       input1.focus(function(){
          if($(this).val() == ""){
             old_val=0;
             $("#old_val").text(old_val);

          }else{
            old_val=$(this).val();
            $("#old_val").text(old_val);

          }

        })


      input1.blur(function(){
        new_val=$(this).val();
        if(new_val==""){
          new_val=0;
        }
        val=new_val-$("#old_val").text();
        if(new_val == "" || new_val == 0){
          new_val=0;
          all_val += val;
      	}else if(parseInt(new_val)<0){
           alert("下注金额必须为数字!");
              $(this).val("0");
              all_val-=Math.abs($("#old_val").text());

      	}else if(!isPositiveNum(new_val) && new_val!=0){
		    	alert("下注金额必须为正整数!");
			    $(this).val(0);
        }else{
          if(!isNaN($(this).val()) && new_val>=0){

              //判断下注金额 是否超过余额
			if( all_val > <?=$user_data['money'] ?>){


				alert("下注金额不足!");
                $(this).val(0);
                $("#allgold").text() -= $("#old_val").text();
			}

              //判断是否超上限
            if(val>=0 && (all_val+val) >parseInt(single_field_max)){
                alert("下注金额超过上限!");
                $(this).val(0);
                all_val-=Math.abs($("#old_val").text());
            }else{
              // alert(new_val);
              // alert(single_note_max);
              // alert(single_field_max);
              // alert(ball_limit_num);
               if(parseInt(new_val) <=parseInt(single_note_max) && all_val<=parseInt(single_field_max) && (parseInt(new_val)+parseInt(ball_limit_num)) <=parseInt(single_field_max) && parseInt(new_val) >=parseInt(single_note_min)){
                    if(val>=0){
                      all_val+=val;
                    }else{
                      all_val-=Math.abs(val);
                    }
               }else{
                 alert("下注金额超过上限或低过下限!");
                  $(this).val(0);
                  all_val-=Math.abs($("#old_val").text());
               }
            }
          }else{
                alert("下注金额必须为数字!");
                $(this).val(0);
                all_val-=Math.abs($("#old_val").text());
          }
        }
       document.getElementById("allgold").innerHTML=all_val;
    })
      // }

})



</script>




