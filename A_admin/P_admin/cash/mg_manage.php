<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";
include_once "../../lib/video/Games.class.php";

$active = trim($_GET["fun"]);
$active = "Index";
if ($active == "Index") {
	//
	include_once dirname(__FILE__) . "/../../lib/video/Games.class.php";
	$games = new Games();
	$data1 = $games->GetMgAgentInfo();
	try {
		$data = json_decode($data1);
	} catch (Exception $e) {
		message('MG网络错误，请重试-！');exit();
	}
	if (empty($data) || !$data->result) {
		message('MG网络错误，请重试！');exit();
	}
} else if ($active == "APIGetBetInfoDetailsByAccount") {

} else if ($active == "CreditTransferByAccount") {

} else if ($active == "APICasinoProfitByGameTypeReport") {

} else if ($active == "GetAccountDetails") {

}

$title = "视讯平台管理";
require "../common_html/header.php";
if ($active == "Index") {
	?>
<div class="content">
<table width="99%" class="m_tab">
        <tr class="m_title">
            <td >视讯类型</td>
            <td >登陆账号</td>
            <td >登陆密码(谨慎修改此密码)</td>
            <td > 管理链接</a></td>
        </tr>
        <tr>
            <td align="middle">MG</td>
            <td align="middle" ><?=$data->data->loginname?></td>
            <td align="middle" id="mg"  class="addinput"><?=$data->data->password?></td>
            <td align="middle"><a href="<?=$data->data->url?>" target="_blank"><?=$data->data->url?></a></td>
        </tr>
        <tr>
            <td align="middle">LEBO</td>
            <td align="middle"><?=$data->data->lebo_loginname?></td>
            <td align="middle" id="lebo"  class="addinput"><?=$data->data->lebo_password?></td>
            <td align="middle"><a href="<?=$data->data->lebo_url?>" target="_blank"><?=$data->data->lebo_url?></a></td>
        </tr>
    </table>
</div>
<?php

} else {
	?><body>
        <div id="con_wrap">
            <div class="input_002">
                MG报表查询
            </div>
            <div class="con_menu"></div>
        </div>
        <div class="content">
            <form name="FrmData" method="get" id="FrmData">
                <input type="hidden" name="action" id="action" value="GetReportByName"><input type="hidden" name="fun" id="fun" value="GetBetInfoDetailsByAccount"><input type="hidden" name="sub" id="sub" value="1">
                <table border="0" cellspacing="0" cellpadding="0" class="m_tab" style="float:left;">
                    <tbody>
                        <tr class="m_cen_003">
                            <td width="100" class="m_title_re">
                                日期區間：
                            </td>
                            <td style="text-align:left">
                                <input type="text" name="date_start" readonly="readonly" id="date_start" style="min-width:80px;width:80px" value="2015-06-01" size="10" maxlength="11" class="za_text Wdate" onclick="WdatePicker()"> -- <input type="text" name="date_end" id="date_end" value="2015-06-01" readonly="readonly" style="min-width:80px;width:80px" size="10" maxlength="10" class="za_text Wdate" onclick="WdatePicker()">
                            </td>
                            <td>
                                <input name="account" id="account" value="">
                            </td>
                        </tr><!--tr class="m_cen_003">
        <td width="100" class="m_title_re">游戏選擇：</td>
        <td colspan="3" style="text-align:left">
        <input type="checkbox" name="zq" class="game" checked="checked" id="zq" value="1">&nbsp;體育&nbsp;
        <input type="checkbox" name="cp" class="game" checked="checked" id="cp" value="2">&nbsp;彩票&nbsp;
        <input type="checkbox" name="zr" class="game" checked="checked" id="zr" value="3">&nbsp;Lebo&nbsp;
        <input type="checkbox" name="bkn" class="game" checked="checked" id="hg" value="4">&nbsp;Bikini&nbsp;
        <input type="checkbox" name="ge" class="game" checked="checked" id="ge" value="5">&nbsp;GE&nbsp;
        <input type="checkbox" name="hg" class="game" checked="checked" id="hg" value="6">&nbsp;HG&nbsp;
        <input type="checkbox" name="mg" class="game" checked="checked" id="mg" value="7">&nbsp;MG&nbsp;
        </td>
      </tr-->
                        <tr class="m_cen_003">
                            <td width="100" class="m_title_re"></td>
                            <td colspan="3" style="text-align:left">
                                <input type="submit" name="searchbtn" id="searchbtn" onclick="document.FrmData.submit();this.disabled=true" class="za_button" value="查詢">&nbsp;&nbsp; <input type="reset" name="resbtn" id="resbtn" class="za_button" value="重設">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
                <tr>
                    <td align="center" height="50"></td>
                </tr>
            </table>
        </div>

<!-- 公共尾部 -->
<?php }
?>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
<script language="javascript">
$(function() {
    //获取class为caname的元素
    $(".addinput").dblclick(function() {
        if (!confirm("请保证这里修改的密码与视讯代理后台的密码一样,否则前台视讯无法使用,确认要修改密码？")) {
            return false;
        }
        var td = $(this);
        var txt = jQuery.trim(td.text());
        var input = $("<input style='height:20px;margin:5px 0px;' size='12' type='text'value='" + txt + "'/>");
        td.html(input);
        input.click(function() {
            return false;
        });
        //获取焦点
        input.trigger("focus");
        //文本框失去焦点后提交内容，重新变为文本
        input.blur(function() {
            var newtxt = $(this).val();
            //判断文本有没有修改
            if (newtxt != txt) {
                var type = $(this).parent().attr('id');
                var url ="";
                if(type == "mg"){
                    url = "mg_changepwd.php?mgpwd=" + newtxt;
                }else if(type == "lebo"){
                    url = "mg_changepwd.php?lebopwd=" + newtxt;
                }
                if (url == ""){
                    alert("未知错误");
                    return false;
                }
                $.get(url, function(data) {
                    if (data == 'yes') {
                        td.html(newtxt);
                    } else {
                        td.html(txt);
                    }
                });
            } else {
                td.html(newtxt);
            }
        });
    });
});
</script>
</div>
<?php require "../common_html/footer.php";?>