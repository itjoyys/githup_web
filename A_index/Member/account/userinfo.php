<?php
	include_once "../common/member_config.php";
	include_once "../../include/config.php";
	include_once "../../common/login_check.php";
	include_once "../../class/user.php";

	$map = '';
	$map .= "k_user_cash_record.uid='" . $_SESSION['uid'] . "'";
	$map .= "and k_user.site_id = '" . SITEID . "' ";

	$userinfo = user::getinfo($_SESSION["uid"]);
	$data = M('k_user_cash_record', $db_config)->join("join k_user on k_user.uid = k_user_cash_record.uid")->where($map)->order('k_user_cash_record.id desc')->limit("0,10")->select();

	$allmoney = $userinfo["money"] + $userinfo["ag_money"] + $userinfo["og_money"] + $userinfo["mg_money"] + $userinfo["ct_money"] + $userinfo["bbin_money"] + $userinfo["lebo_money"];
	$allmoney = round($allmoney, 2);

?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
        <script language="javascript" type="text/javascript" src="../public/js/jquery-1.8.3.min.js"></script>
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
	</head>
	<body style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
		<div id="MAMain" style="width:767px;">
			<div id="MACenter-content" >
				<div id="MACenterContent">
					<div id="MNav">
						<span class="mbtn">基本资讯</span>
						<div class="navSeparate"></div>
						<a target="k_memr"  class="mbtn"
						href="sport.php">投注资讯</a>
					</div>
					<div style="overflow-y:scroll; height:370px">
						<div id="MMainData" style="margin-top: 8px;">
							<h2 class="MSubTitle"><?=$userinfo['username']?>的基本信息:</h2>
							<table class="MMain" border="1" style="margin-bottom: 8px;">
								<tbody>
									<tr>
										<th nowrap="">数字ID</th>
										<th nowrap="">币别</th>
										<th nowrap="">系统余额</th>
										<th nowrap="">AG余额</th>
										<th nowrap="">OG余额</th>
										<th nowrap="">MG余额</th>
										<th nowrap="">CT余额</th>
										<th nowrap="">BBIN余额</th>
										<th nowrap="">LEBO余额</th>
										<th>合计</th>
										<th nowrap="">密码</th></tr>
									<tr>
										<td style="text-align: center;" class=""><?=$userinfo["uid"]?></td>
										<td style="text-align: center;" class="">人民幣(RMB)</td>
										<td style="text-align: center;" id="local_money" class="MNumber"><?=$userinfo["money"]?></td>
										<td style="text-align: center;" id="ag_money" class=""><?=$userinfo["ag_money"]?></td>
										<td style="text-align: center;" id="og_money" class=""><?=$userinfo["og_money"]?></td>
										<td style="text-align: center;" id="mg_money"><?=$userinfo["mg_money"]?></td>
										<td style="text-align: center;" id="ct_money"><?=$userinfo["ct_money"]?></td>
										<td style="text-align: center;" id="bbin_money"><?=$userinfo["bbin_money"]?></td>
										<td style="text-align: center;" id="lebo_money"><?=$userinfo["lebo_money"]?></td>
										<td style="text-align: center;" id="allmoney" class=""><?=$allmoney?>&nbsp;RMB</td>
										<td style="text-align: center;" class="">
											<a target="k_memr"  href="password.php"><input type="button" value="修改密码"  ></a>
										</td>
									</tr>
									<tr>
										<td style="text-align: center;" class="">最后登入时间：</td>
										<td style="" class="" colspan="11"><?php echo $userinfo["login_time"];?><span style="float:right;margin-right: 50px"><input type='button' value="刷新额度" id="bet">&nbsp;&nbsp;&nbsp;<a target="k_memr" href='../cash/zr_money.php'><input type='button' value="额度转换" onclick=""></a><span></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="MMainData" style="margin-top: 20px;">
							<h2 class="MSubTitle">最近十笔记录</h2>
							<table class="MMain" border="1" style="margin-bottom: 8px;">
								<tbody>
									<tr>
										<th nowrap="">交易时间</th>
										<th nowrap="">金额</th>
										<th nowrap="">类型</th>
										<th nowrap="">交易类别</th>
										<th nowrap="">余额</th>
										<th nowrap="" style="width:244px;"> 备注</th>
								   </tr>
								   <?php if (!empty($data)) {?>
								   <?php foreach ($data as $k => $v) {?>
									<tr>
										<td style="text-align: center;" class=""><?php echo $v["cash_date"];?></td>
										<td style="text-align:right" class=""><?php echo number_format($v["cash_num"] + $v["discount_num"], 2);?></td>
										<td style="text-align: center;" class=""><?php echo cash_type_r($v['cash_type'])?></td>
										<td style="text-align: center;" class=""><?php echo cash_do_type_r($v['cash_do_type'])?></td>
										<td style="text-align: center;" class="MNumber"><?php echo $v["cash_balance"];?></td>
										<td style="text-align: center;Word-break: break-all;" class=""><?php echo str_cut($v["remark"]);?></td>
									</tr>
									<?php }?>
									<?php } else {?>
									<tr><td style="text-align: center;" colspan='6'>暂无交易记录</td></tr>
									<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="info" style="display:none">
			<span class="ag_money"><?=$userinfo["ag_money"]?></span>
			<span class="og_money"><?=$userinfo["og_money"]?></span>
			<span class="ct_money"><?=$userinfo["ct_money"]?></span>
			<span class="mg_money"><?=$userinfo["mg_money"]?></span>
			<span class="lebo_money"><?=$userinfo["lebo_money"]?></span>
			<span class="bbin_money"><?=$userinfo["bbin_money"]?></span>
		</div>
		<script type="text/javascript">
		$('#bet').click(function () {
		  var local_money=$(".MMain #local_money").html();
		  var og_money=$(".info .og_money").html();
		  var ct_money=$(".info .ct_money").html();
		  var ag_money=$(".info .ag_money").html();
		  var mg_money=$(".info .mg_money").html();
		  var lebo_money=$(".info .lebo_money").html();
		  var bbin_money=$(".info .bbin_money").html();
		//动态加载
		$.ajax({
			 type: 'GET',
			 url: '/video/games/getallbalance.php?action=save',
			 dataType: "json",
			 beforeSend: function(){
					var nr="<img src='../public/images/load_pk.gif'/>";
					$(".MMain #local_money").html(nr);
					$(".MMain #og_money").html(nr);
					$(".MMain #ct_money").html(nr);
					$(".MMain #ag_money").html(nr);
					$(".MMain #mg_money").html(nr);
					$(".MMain #lebo_money").html(nr);
					$(".MMain #bbin_money").html(nr);
					$(".MMain #allmoney").html(nr);
				},
			 success: function (rdata) {
			  if(rdata.error){
				alert(rdata.error);
				window.location.href = 'userinfo.php';
			  }else if(rdata.data.Code ==10017){
				  $(".MMain #local_money").html(parseFloat(local_money).toFixed(2));
				  if(rdata.data.ogstatus){
				  	$(".MMain #og_money").html(parseFloat(rdata.data.ogbalance).toFixed(2));
				  	$(".info .og_money").html(parseFloat(rdata.data.ogbalance).toFixed(2));
				  }else{
				  	$(".MMain #og_money").html(parseFloat(og_money).toFixed(2));
				  }
				  if(rdata.data.ctstatus){
				  	$(".MMain #ct_money").html(parseFloat(rdata.data.ctbalance).toFixed(2));
				  	$(".info .ct_money").html(parseFloat(rdata.data.ctbalance).toFixed(2));
				  }else{
				  	$(".MMain #ct_money").html(parseFloat(ct_money).toFixed(2));
				  }
				  if(rdata.data.agstatus){
				  	$(".MMain #ag_money").html(parseFloat(rdata.data.agbalance).toFixed(2));
				  	$(".info .ag_money").html(parseFloat(rdata.data.agbalance).toFixed(2));
				  }else{
				  	$(".MMain #ag_money").html(parseFloat(ag_money).toFixed(2));
				  }
				  if(rdata.data.mgstatus){
				  	$(".MMain #mg_money").html(parseFloat(rdata.data.mgbalance).toFixed(2));
				  	$(".info .mg_money").html(parseFloat(rdata.data.mgbalance).toFixed(2));
				  }else{
				  	$(".MMain #mg_money").html(parseFloat(mg_money).toFixed(2));
				  }
				  if(rdata.data.lebostatus){
				  	$(".MMain #lebo_money").html(parseFloat(rdata.data.lebobalance).toFixed(2));
				  	$(".info .lebo_money").html(parseFloat(rdata.data.lebobalance).toFixed(2));
				  }else{
				  	$(".MMain #lebo_money").html(parseFloat(lebo_money).toFixed(2));
				  }
				  if(rdata.data.bbinstatus){
				  	$(".MMain #bbin_money").html(parseFloat(rdata.data.bbinbalance).toFixed(2));
				  	$(".info .bbin_money").html(parseFloat(rdata.data.bbinbalance).toFixed(2));
				  }else{
				  	$(".MMain #bbin_money").html(parseFloat(bbin_money).toFixed(2));
				  }
				  if(rdata.data.aginfo == 9999){
				  	$(".MMain #ag_money").html('维护');
				  }
				  if(rdata.data.oginfo == 9999){
				  	$(".MMain #og_money").html('维护');
				  }
				  if(rdata.data.mginfo == 9999){
				  	$(".MMain #mg_money").html('维护');
				  }
				  if(rdata.data.ctinfo == 9999){
				  	$(".MMain #ct_money").html('维护');
				  }
				  if(rdata.data.bbininfo == 9999){
				  	$(".MMain #bbin_money").html('维护');
				  }
				  if(rdata.data.leboinfo == 9999){
				  	$(".MMain #lebo_money").html('维护');
				  }
				   countallmoney();
			  }
			}
		});

		function countallmoney(){

			allm = parseFloat($(".MMain #local_money").html()) + parseFloat($(".info .ag_money").html()) + parseFloat($(".info .og_money").html()) + parseFloat($(".info .ct_money").html()) + parseFloat($(".info .mg_money").html())+parseFloat($(".info .bbin_money").html())+parseFloat($(".info .lebo_money").html());
			$(".MMain #allmoney").html(parseFloat(allm).toFixed(2)+" RMB")
		}

		});
		</script>
	</body>
</html>