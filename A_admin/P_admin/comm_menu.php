<?php
//账号管理
$menu = array(
	'a01' => array(
		'name' => '會員管理',
		'url' => './account/member_index.php',
	),
	'a02' => array(
		'name' => '股東管理',
		'url' => './account/shareholder_index.php',
	),
	'a03' => array(
		'name' => '總代理管理',
		'url' => './account/up_agent.php',
	),
	'a04' => array(
		'name' => '代理管理',
		'url' => './account/agent.php',
	),
	'a05' => array(
		'name' => '子账号',
		'url' => './account/sub_account.php',
	),
	'a06' => array(
		'name' => '會員注册設定',
		'url' => './account/member_reg_set.php',
	),
	'a07' => array(
		'name' => '体系查询',
		'url' => './account/account_search.php',
	),
// 	'a08' => array(
// 		'name' => '查看會員資料',
// 		'url' => './account/member_data.php',
//	),
	'b01' => array(
		'name' => '體育即時注單',
		'url' => './note/ft_danshi.php',
	),
	'b02' => array(
		'name' => '體育詳細注單',
		'url' => './note/list.php?status=0',
	),
	'b03' => array(
		'name' => '彩票即時注單',
		'url' => './note/cp_statis.php',
	),
	'b04' => array(
		'name' => '彩票詳細注單',
		'url' => './note/fc.php?type=福彩3D',
	),
	'b05' => array(
		'name' => '視訊詳細注單',
		'url' => './note/video.php?gtype=lebo',
	),
	'c01' => array(
		'name' => '报表明细',
		'url' => './report/report.php',
	),
	'c02' => array(
		'name' => '历史報表（不含当天，速度较快）',
		'url' => './report/report_history.php',
	),
	'd01' => array(
		'name' => '体育比赛结果',
		'url' => './results/football.php',
	),
	'd02' => array(
		'name' => '彩票开奖结果',
		'url' => './results/liuhecai.php?type=liuhecai',
	),
	'd03' => array(
		'name' => '赔率设置',
		'url' => './results/fc_odds_1.php',
	),
	'e01' => array(
		'name' => '存款與取款',
		'url' => './cash/catm.php',
	),
	'e02' => array(
		'name' => '出入账目汇总',
		'url' => './cash/account_count.php',
	),
	'e03' => array(
		'name' => '出款管理',
		'url' => './cash/out_record.php',
	),
	'e04' => array(
		'name' => '入款管理',
		'url' => './cash/bank_record.php',
	),
	'e05' => array(
		'name' => '即時稽核查詢',
		'url' => './cash/cash_check.php',
	),
	'e18' => array(
		'name' => '稽核日志',
		'url' => './cash/cash_audit_log.php',
	),
	'e06' => array(
		'name' => '层级管理',
		'url' => './cash/account_level.php',
	),
	'e07' => array(
		'name' => '支付平臺設定',
		'url' => './cash/pay_set.php',
	),

	'e08' => array(
		'name' => '在線支付参数設定',
		'url' => './cash/pay_detail_set.php',
	),
	'e09' => array(
		'name' => '廣告管理',
		'url' => './cash/agent_ad.php',
	),
	'e10' => array(
		'name' => '有效會員列表',
		'url' => './cash/active_member.php',
	),
	'e11' => array(
		'name' => '現金系統',
		'url' => './cash/cash_system.php',
	),
	'e12' => array(
		'name' => '退傭統計',
		'url' => './cash/agent_count.php',
	),
	'e13' => array(
		'name' => '會員餘額統計',
		'url' => './cash/blance_all.php',
	),
	'e14' => array(
		'name' => '優惠計算',
		'url' => './cash/discount_index.php',
	),
	'e15' => array(
		'name' => '代理申请管理',
		'url' => './cash/agent_examine.php?1=1',
	),
	'e16' => array(
		'name' => '视讯平台管理',
		'url' => './cash/mg_manage.php',
	),
	// 'e17' => array(
	// 	'name' => '会员分析系统',
	// 	'url' => './cash/user_account.php',
	// ),
	
	'f08' => array(
		'name' => '限額/退水',
		'url' => './other/sp_list.php',
	),
	'f09' => array(
		'name' => '网站资讯管理',
		'url' => '../site_info/index.php/site_info/index/index',
		'type' => '_',
	),
	'f02' => array(
		'name' => '個人資料',
		'url' => './other/person_info.php',
	),
	'f03' => array(
		'name' => '会员消息',
		'url' => './other/member_msg.php',
	),
	'f04' => array(
		'name' => '登陆日志',
		'url' => './other/member_login_log.php',
	),
	'f05' => array(
		'name' => '操作记录',
		'url' => './other/do_log.php',
	),
	'f06' => array(
		'name' => '最新消息',
		'url' => './other/new_msg.php',
	),
	'f07' => array(
		'name' => '上级公告',
		'url' => './other/notice.php',
	),
    'g01' => array(
        'name' => '姓名查看',
        'url' => './account/member_data.php',
    ),
    'g02' => array(
        'name' => '姓名修改',
        'url' => './account/member_data.php',
    ),
    'g03' => array(
        'name' => '银行帐号查看',
        'url' => './account/member_data.php',
    ),
    'g04' => array(
        'name' => '银行帐号修改',
        'url' => './account/member_data.php',
    ),
    'g05' => array(
        'name' => '取款密码查看',
        'url' => './account/member_data.php',
    ),
    'g06' => array(
        'name' => '取款密码修改',
        'url' => './account/member_data.php',
    ),
    'g07' => array(
        'name' => '手机查看',
        'url' => './account/member_data.php',
    ),
    'g08' => array(
        'name' => '手机修改',
        'url' => './account/member_data.php',
    ),
    'g09' => array(
        'name' => '邮箱查看',
        'url' => './account/member_data.php',
    ),
    'g10' => array(
        'name' => '邮箱修改',
        'url' => './account/member_data.php',
    ),
    'g11' => array(
        'name' => 'qq查看',
        'url' => './account/member_data.php',
    ),
    'g12' => array(
        'name' => 'qq修改',
        'url' => './account/member_data.php',
    ),
    'g13' => array(
        'name' => '出生日期查看',
        'url' => './account/member_data.php',
    ),
    'g14' => array(
        'name' => '出生日期修改',
        'url' => './account/member_data.php',
    ),
    'g15' => array(
        'name' => '身份证号查看',
        'url' => './account/member_data.php',
    ),
    'g16' => array(
        'name' => '身份证修改',
        'url' => './account/member_data.php',
    ),
);

/**
 * 权限检查函数
 * @param  string $menu_name 上面字段中子项的key字段(a01,a02..)
 * @return [type]            [description]
 */
function check_purview($key_str = "") {
	if (isset($_SESSION["adminid"])) {
		$quanxian = trim($_SESSION["quanxian"]);
		if ($quanxian == 'sadmin') {
			//
			return true;
		}
		//数组的情况
		$qx_arr = explode(",", $quanxian);
		if(is_array($key_str)){
		      if (array_intersect($key_str, $qx_arr)) {
		          //如果存在
		          return true;
		      }
		}else{
            if (in_array($key_str, $qx_arr)) {
		        //如果存在
		        return true;
		    } 
		}
	}
	//TODO 提示无权限
	return false;
}

?>