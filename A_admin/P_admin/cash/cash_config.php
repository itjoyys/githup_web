<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../common/user_set.php");
//设定时候读取数据
  if (!empty($_GET['cash_id']) && $_GET['type'] =='set') {
  	    $cashid = $_GET['cash_id'];
		if (!empty($cashid)) {
			$cash_config = M('k_cash_config_view',$db_config)->where("site_id = '".SITEID."' and id = '".$cashid."'")->find();			
	    }
  }

  if(!empty($_GET['type']) && $_GET['type'] == 'del'){
  	//删除
  	$id=$_GET['cash_id'];
  	$data['is_delete']='1';
  	M("k_cash_config",$db_config)->where("id='".$id."'")->update($data);
  	message("删除成功！","pay_detail_set.php");
  }

//提交更新
		if (!empty($_POST['cash_id'])) {
			 //p($_POST);
			$payment_val;
			foreach ($_POST as $k => $v) {
				$key = explode("_", $k);
				if($key[0] == 'charge'){
					$payment_val=$payment_val.','.$key[1].'_'.$v.'_'.$key[2];
					//1_2_3  1=>id,2=>charge,3=>pay_type(取名字用：支付宝)
					
					
				}
			}
		
			//公告配置
			$data_p['payment_val'] = trim($payment_val,',');//线上充值手续费(支付平台)
			$data_p['is_fee_free'] = $_POST['is_fee_free'];//有效投注满是否免手续费
			$data_p['repeat_hour_num'] = $_POST['repeat_hour_num'];//重复出款时数
			$data_p['fee_free_num'] = $_POST['fee_free_num'];//免收手续费次数
			$data_p['out_fee'] = $_POST['out_fee'];//出款一次手续费
			M('k_cash_config',$db_config)->where("id = '".$_POST['cash_id']."'")->update($data_p);

			//线上配置
			$data_o['ol_deposit'] = $_POST['ol_deposit'];//存款优惠
			$data_o['ol_discount_num'] = $_POST['ol_discount_num'];//线上优惠标准
			$data_o['ol_discount_per'] = $_POST['ol_discount_per'];//优惠百分比
			$data_o['ol_discount_coe'] = $_POST['ol_discount_coe'];//优惠系数
			$data_o['ol_catm_max'] = $_POST['ol_catm_max'];//线上存款最大
			$data_o['ol_catm_min'] = $_POST['ol_catm_min'];//线上存款最低
			$data_o['ol_discount_max'] = $_POST['ol_discount_max'];//優惠上限金額
			$data_o['ol_atm_max'] = $_POST['WithdrawalMax'];//出款上限
			$data_o['ol_atm_min'] = $_POST['WithdrawalMin'];//出款下限

			//其它优惠配置
			$data_o['ol_other_discount_num'] = $_POST['ol_other_discount_num'];		
			$data_o['ol_other_discount_per'] = $_POST['ol_other_discount_per'];
			$data_o['ol_other_discount_max'] = $_POST['ol_other_discount_max'];
			$data_o['ol_o_discount_max_24'] = $_POST['ol_o_discount_max_24'];
			
			
            $data_o['ol_is_game_audit'] = $_POST['ol_is_game_audit'];
            $data_o['ol_game_audit'] = $_POST['ol_game_audit'];//游戏額度稽核

            $data_o['ol_is_sport_audit'] = $_POST['ol_is_sport_audit'];
            $data_o['ol_sport_audit'] = $_POST['ol_sport_audit'];//体育額度稽核

            $data_o['ol_is_fc_audit'] = $_POST['ol_is_fc_audit'];
            $data_o['ol_fc_audit'] = $_POST['ol_fc_audit'];//福彩額度稽核

            $data_o['ol_is_zh_audit'] = $_POST['ol_is_zh_audit'];
            $data_o['ol_zh_audit'] = $_POST['ol_zh_audit'];//综合額度稽核

            $data_o['ol_is_ct_audit'] = $_POST['ol_is_ct_audit'];
            $data_o['ol_ct_audit'] = $_POST['ol_ct_audit'];//常態性稽核

            $data_o['ol_discount_audit'] = $_POST['ol_discount_audit'];//優惠餘額稽核
            $data_o['ol_ct_fk_audit'] = $_POST['ol_ct_fk_audit'];//常態性稽核放寬額度
            $data_o['ol_ct_xz_audit'] = $_POST['ol_ct_xz_audit'];//常態性稽核行政費率
           
            $data_o['ol_is_give_up'] = $_POST['ol_is_give_up'];//是否可放弃优惠
           
            M('k_cash_config_ol',$db_config)->where("cash_id = '".$_POST['cash_id']."'")->update($data_o);

             // p($data_o);
            //线下设置
            $data_l['line_deposit'] = $_POST['line_deposit'];//存款优惠
			$data_l['line_discount_num'] = $_POST['line_discount_num'];//优惠标准
			$data_l['line_discount_per'] = $_POST['line_discount_per'];//优惠百分比
			$data_l['line_discount_coe'] = $_POST['line_discount_coe'];//优惠系数
			$data_l['line_catm_max'] = $_POST['line_catm_max'];//存款最大	

			$data_l['line_catm_min'] = $_POST['line_catm_min'];//线上存款最低
			$data_l['line_discount_max'] = $_POST['line_discount_max'];//優惠上限金額
			$data_l['line_other_discount_num'] = $_POST['line_other_discount_num'];//其他優惠標準(元)
			$data_l['line_other_discount_per'] = $_POST['line_other_discount_per'];//其他優惠百分比
			$data_l['line_other_discount_max'] = $_POST['line_other_discount_max'];//其他優惠上限
			$data_l['line_o_discount_max_24'] = $_POST['line_o_discount_max_24'];//其他優惠24小時內最高上限
 
            $data_l['line_is_game_audit'] = $_POST['line_is_game_audit'];
            $data_l['line_game_audit'] = $_POST['line_game_audit'];//游戏額度稽核

            $data_l['line_is_sport_audit'] = $_POST['line_is_sport_audit'];
            $data_l['line_sport_audit'] = $_POST['line_sport_audit'];//体育額度稽核

            $data_l['line_is_fc_audit'] = $_POST['line_is_fc_audit'];
            $data_l['line_fc_audit'] = $_POST['line_fc_audit'];//福彩額度稽核

            $data_l['line_is_zh_audit'] = $_POST['line_is_zh_audit'];
            $data_l['line_zh_audit'] = $_POST['line_zh_audit'];//综合額度稽核

            $data_l['line_is_ct_audit'] = $_POST['line_is_ct_audit'];
            $data_l['line_ct_audit'] = $_POST['line_ct_audit'];//常態性稽核

            $data_l['line_discount_audit'] = $_POST['line_discount_audit'];//優惠餘額稽核
            $data_l['line_ct_fk_audit'] = $_POST['line_ct_fk_audit'];//常態性稽核放寬額度
            $data_l['line_ct_xz_audit'] = $_POST['line_ct_xz_audit'];//常態性稽核行政費率
           
            $data_l['line_is_give_up'] = $_POST['line_is_give_up'];//是否可放弃优惠
            M('k_cash_config_line',$db_config)->where("cash_id = '".$_POST['cash_id']."'")->update($data_l);
            // p( $data_l);


             message("修改成功！","pay_detail_set.php");
		}

//查询支付平台
		$zhifu=M("pay_set",$db_config)->field("*")->where("site_id = '".SITEID."' and is_delete = '0'")->select();

?>

<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">在線支付設定</div>
  <div class="con_menu"></div>
</div>

<div class="content">
<form action="" method="post">
	<input type="hidden" name="cash_id" value="<?=$cash_config['id']?>">
	<input type="hidden" name="type" value="<?=$_GET['type']?>">
	<table width="99%" class="m_tab">
		<tbody>
		<tr class="m_title">
			<td height="27" class="table_bg" colspan="25" align="middle">支付平台手續費</td>
		</tr>

	</tbody></table>
	<table width="99%" class="m_tab">
		<tbody><tr class="m_title">
			<td height="27" class="table_bg" colspan="12" align="middle">出款手續費</td>
		</tr>
		<tr>
		<td class="table_bg1" align="center">
			达到有效投注额是否免手续费
				<input value="1" <?php radio_check($cash_config['is_fee_free'],1);?> type="radio" name="is_fee_free">是
				<input value="0" <?php radio_check($cash_config['is_fee_free'],0);?> type="radio" name="is_fee_free">否
				</td>
			<td class="table_bg1" align="center">重复出款时数</td>
			<td><input value="<?=$cash_config['repeat_hour_num']?>" maxlength="10" class="za_text" onkeydown="return Yh_Text.CheckNumber();" size="8" name="repeat_hour_num"></td>
			<td class="table_bg1" align="center">免收手续费次数</td>
			<td><input value="<?=$cash_config['fee_free_num']?>" maxlength="10" class="za_text" onkeydown="return Yh_Text.CheckNumber();" size="8" name="fee_free_num"></td>
			<td class="table_bg1" align="center">出款手续费金额</td>
			<td><input value="<?=$cash_config['out_fee']?>" maxlength="10" class="za_text" onkeydown="return Yh_Text.CheckNumber();" size="8" name="out_fee"></td>
		</tr>
	</tbody></table>
	<table width="99%" class="m_tab">

		<tbody><tr class="m_title">
			<td class="table_bg" align="middle">出入款設定</td>
			<td class="table_bg" align="middle">線上存款</td>
			<td class="table_bg" align="middle">公司入款</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">存款優惠</td>
			<td>
				<input value="2" <?php radio_check($cash_config['ol_deposit'],2);?>  type="radio" name="ol_deposit">首次&nbsp;&nbsp;
				<input value="1" <?php radio_check($cash_config['ol_deposit'],1);?>  type="radio" name="ol_deposit">每次&nbsp;&nbsp;
				<input value="1"  <?php check_box($cash_config['ol_is_give_up'],1);?> type="checkbox" name="ol_is_give_up">可放棄存款優惠
			</td>
			<td><input value="2" <?php radio_check($cash_config['line_deposit'],2);?> type="radio" name="line_deposit">首次&nbsp;&nbsp;
				<input value="1" <?php radio_check($cash_config['line_deposit'],1);?> type="radio" name="line_deposit">每次&nbsp;&nbsp;
				<input value="1" <?php check_box($cash_config['line_is_give_up'],1);?> type="checkbox" name="line_is_give_up">可放棄存款優惠
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">優惠標准(元)</td>
			<td><input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['ol_discount_num']?>" maxlength="10" size="8" name="ol_discount_num"></td>
			<td><input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['line_discount_num']?>" maxlength="10" size="8" name="line_discount_num"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">優惠百分比(%)</td>
			<td><input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['ol_discount_per']?>" maxlength="4" size="4" name="ol_discount_per">%</td>
			<td><input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['line_discount_per']?>" maxlength="4" size="4" name="line_discount_per">%</td>
		</tr>
		<!--<tr>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="center" class="form_font"><font color='red'>單場</font>限額上限(元)</td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SCMax" type="text" value="30000"  onkeypress='return CheckKey();'></td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SCMax" type="text" value="30000"  onkeypress='return CheckKey();'></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="center" class="form_font"><font color='red'>單場</font>限額下限(元)</td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SCMin" type="text" value="100"  onkeypress='return CheckKey();'></td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SCMin" type="text" value="100"  onkeypress='return CheckKey();'></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="center" class="form_font"><font color='blue'>單注</font>限額上限(元)</td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SOMax" type="text" value="10000"  onkeypress='return CheckKey();'></td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SOMax" type="text" value="10000"  onkeypress='return CheckKey();'></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="center" class="form_font"><font color='blue'>單注</font>限額下限(元)</td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SOMin" type="text" value="100"  onkeypress='return CheckKey();'></td>
          <td bgcolor="#FFFFFF" class="form_font" scope="col"><div align="left"><input  size="8" maxlength="10" name="SOMin" type="text" value="100"  onkeypress='return CheckKey();'></td>
        </tr>-->
		<tr>
			<td class="table_bg1" align="center">优惠系數</td>
			<td><input value="<?=$cash_config['ol_discount_coe']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_discount_coe"></td>
			<td><input value="<?=$cash_config['line_discount_coe']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_discount_coe"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">单次最高存款金額</td>
			<td><input value="<?=$cash_config['ol_catm_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_catm_max"></td>
			<td><input value="<?=$cash_config['line_catm_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_catm_max"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">单次最低存款金額</td>
			<td><input value="<?=$cash_config['ol_catm_min']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_catm_min"></td>
			<td><input value="<?=$cash_config['line_catm_min']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_catm_min"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">優惠上限金額</td>
			<td><input value="<?=$cash_config['ol_discount_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_discount_max"></td>
			<td><input value="<?=$cash_config['line_discount_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_discount_max"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">其他優惠標準(元)</td>
			<td><input value="<?=$cash_config['ol_other_discount_num']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_other_discount_num"></td>
			<td><input value="<?=$cash_config['line_other_discount_num']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_other_discount_num"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">其他優惠百分比</td>
			<td><input value="<?=$cash_config['ol_other_discount_per']?>" maxlength="4" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="4" name="ol_other_discount_per">%</td>
			<td><input value="<?=$cash_config['line_other_discount_per']?>" maxlength="4" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="4" name="line_other_discount_per">%</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">其他優惠上限</td>
			<td><input value="<?=$cash_config['ol_other_discount_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="ol_other_discount_max"></td>
			<td><input value="<?=$cash_config['line_other_discount_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="line_other_discount_max"></td>
		</tr>
        <tr>
			<td class="table_bg1" align="center">其他優惠24小時內最高上限</td>
			<td><input value="<?=$cash_config['ol_o_discount_max_24']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" id="AbsorbMax24_co" size="8" name="ol_o_discount_max_24"><font color="red">0為不設上限</font></td>
			<td><input value="<?=$cash_config['line_o_discount_max_24']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" id="AbsorbMax24_co" size="8" name="line_o_discount_max_24"><font color="red">0為不設上限</font></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">单次出款上限</td>
			<td colspan="2"><input value="<?=$cash_config['ol_atm_max']?>" maxlength="10" onkeydown="return Yh_Text.CheckNumber();" class="za_text" size="8" name="WithdrawalMax"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">单次出款下限</td>
			<td colspan="2"><input class="za_text" onkeydown="return Yh_Text.CheckNumber();" value="<?=$cash_config['ol_atm_min']?>" maxlength="10" size="8" name="WithdrawalMin"></td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">游戏額度稽核</td>
			<td>
				<label><input value="1" <?php check_box($cash_config['ol_is_game_audit'],1);?> type="checkbox" name="ol_is_game_audit">開啟</label>
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['ol_game_audit']?>" maxlength="10" size="8" name="ol_game_audit" type="text">倍
			</td>
			<td>
				<label><input  value="1" <?php check_box($cash_config['line_is_game_audit'],1);?> type="checkbox" name="line_is_game_audit">開啟</label>
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['line_game_audit']?>" maxlength="10" size="8" name="line_game_audit" type="text">倍
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">体育額度稽核</td>
			<td>
				<label><input value="1" <?php check_box($cash_config['ol_is_sport_audit'],1);?> type="checkbox" name="ol_is_sport_audit">開啟</label> 
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['ol_sport_audit']?>" maxlength="10" size="8" name="ol_sport_audit">倍
			</td>
			<td>
				<label><input  value="1" <?php check_box($cash_config['line_is_sport_audit'],1);?> type="checkbox" name="line_is_sport_audit">開啟</label>
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['line_sport_audit']?>" maxlength="10" size="8" name="line_sport_audit">倍
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">彩票額度稽核</td>
			<td>
				<label><input value="1" <?php check_box($cash_config['ol_is_fc_audit'],1);?> type="checkbox" name="ol_is_fc_audit">開啟</label> 
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['ol_fc_audit']?>" maxlength="10" size="8" name="ol_fc_audit">倍
			</td>
			<td>
				<label><input value="1" <?php check_box($cash_config['line_is_fc_audit'],1);?> type="checkbox" name="line_is_fc_audit">開啟</label>
				<input onkeydown="return Yh_Text.CheckNumber();" class="za_text" value="<?=$cash_config['line_fc_audit']?>" maxlength="10" size="8" name="line_fc_audit">倍
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">綜合額度稽核</td>
			<td>
				<label><input  value="1" <?php check_box($cash_config['ol_is_zh_audit'],1);?> type="checkbox" name="ol_is_zh_audit">開啟</label>
				<input class="za_text" onkeydown="return Yh_Text.CheckNumber();" value="<?=$cash_config['ol_zh_audit']?>" maxlength="10" size="8" name="ol_zh_audit">倍
			</td>
			<td>
				<label><input value="1"  <?php check_box($cash_config['line_is_zh_audit'],1);?> type="checkbox" name="line_is_zh_audit">開啟</label>
				<input class="za_text" onkeydown="return Yh_Text.CheckNumber();" value="<?=$cash_config['line_zh_audit']?>" maxlength="10" size="8" name="line_zh_audit">倍
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">常態性稽核</td>
			<td>
				<label><input id="NORMALITY_OPEN" value="1"  <?php check_box($cash_config['ol_is_ct_audit'],1);?> type="checkbox" name="ol_is_ct_audit">開啟</label>
				<input id="Audit_NORMALITY" value="<?=$cash_config['ol_ct_audit']?>" maxlength="10" size="2" class="za_text" name="ol_ct_audit">%
			</td>
			<td>
				<label><input id="NORMALITY_OPEN_Co" value="1" <?php check_box($cash_config['line_is_ct_audit'],1);?>  type="checkbox" name="line_is_ct_audit">開啟</label>
				<input id="Audit_NORMALITY_Co" value="<?=$cash_config['line_ct_audit']?>" maxlength="10" class="za_text" size="2" name="line_ct_audit">%
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">優惠餘額稽核</td>
			<td><input value="<?=$cash_config['ol_discount_audit']?>" maxlength="10" class="za_text" size="2" name="ol_discount_audit">%</td>
			<td><input value="<?=$cash_config['line_discount_audit']?>" maxlength="10" class="za_text" size="2" name="line_discount_audit">%</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">常態性稽核放寬額度</td>
			<td><input value="<?=$cash_config['ol_ct_fk_audit']?>" maxlength="10" class="za_text" size="8" name="ol_ct_fk_audit">元</td>
			<td><input value="<?=$cash_config['line_ct_fk_audit']?>" maxlength="10" class="za_text" size="8" name="line_ct_fk_audit">元</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">常態性稽核行政費率</td>
			<td><input id="CHARGE_RATE" value="<?=$cash_config['ol_ct_xz_audit']?>" class="za_text" maxlength="10" size="2" name="ol_ct_xz_audit" type="text">%</td>
			<td><input id="CHARGE_RATE_Co" value="<?=$cash_config['line_ct_xz_audit']?>" class="za_text" maxlength="10" size="2" name="line_ct_xz_audit" type="text">%</td>
		</tr>
		<tr>
			<td colspan="3" height="30px" align="center">
			<input class="button_d" value="確定" type="submit"> &nbsp;&nbsp;
            <input class="button_d" value="重置" type="reset"> 
            </td>
		</tr>
		<tr><td colspan="3">優惠標准：會員單筆存款金額達到優惠標准，始可享有存款優惠。 <br>
優惠百分比：會員達到優惠標准後，依所設定的百分比給與存款優惠。  <br>
金額系數：單場(注)上限 &gt; (目前總額度 x 金額系數) &gt; 單場(注)下限。 <br>
最高存款金額：會員存款不可超過所設定的金額，公司存入時僅爲柔性勸導，並無實質限制。  <br>
最低存款金額：會員存款不可低于所設定的金額，公司存入時僅爲柔性勸導，並無實質限制。  <br>
優惠上限金額：1.若會員存款金額超過所設定的金額，則以所設定的金額給予優惠。  <br>
2.若設定 0 則爲不限制。  <br>
   例：假設 優惠上限金額 設定爲 10000,優惠百分比爲 15%,當會員的優惠金額超過10000元時,  <br>
        存款優惠金額為10000 <br>
		</td></tr>
	</tbody></table>
</form>
</div>
<?php  ?>