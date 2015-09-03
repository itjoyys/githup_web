<?php
//账号管理
$menu = array(
       'a01'=>array(
         'name'=>'會員管理',
         'url'=>'../../account/member_index.php'
    	),
       'a02'=>array(
         'name'=>'體系查詢',
         'url'=>'../../account/account_search.php'
    	),
       'a03'=>array(
         'name'=>'子帳號',
         'url'=>'../../account/sub_account.php'
    	),
       // 'b04'=>array(
       //   'name'=>'體育即时注單',
       //   'url'=>'../../note/ft_danshi.php'
       //  ),
       'b01'=>array(
         'name'=>'體育詳細注單',
         'url'=>'../../note/list.php'
     	),
       // 'b05'=>array(
       //   'name'=>'彩票即时注單',
       //   'url'=>'../../note/fc.php?type=福彩3D'
       //  ),
       'b02'=>array(
         'name'=>'彩票詳細注單',
         'url'=>'../../note/fc.php?type=福彩3D'
    	),
       'b03'=>array(
         'name'=>'視訊詳細注單',
         'url'=>'../../note/video.php'
    	),
        'c01'=>array(
         'name'=>'總報表',
         'url'=>'../../report/report.php'
    	),
       'd01'=>array(
         'name'=>'體育比賽結果',
         'url'=>'../../results/football.php'
    	),
        'd02'=>array(
         'name'=>'彩票開獎結果',
         'url'=>'../../results/fucai_3D.php'
    	),
        'd03'=>array(
           'name'=>'赔率设置',
           'url'=>'../../results/fc_odds_1.php'
        ),
    	'e01'=>array(
         'name'=>'現金系統',
         'url'=>'../../cash/cash_system.php'
    	),
    	 'e02'=>array(
         'name'=>'退傭統計',
         'url'=>'../../cash/agent_search.php'
    	),
    	 'f01'=>array(
         'name'=>'限額/退水',
         'url'=>'../../other/sp_list.php'
    	),
    	 'f02'=>array(
         'name'=>'個人資料',
         'url'=>'../../other/person_info.php'
    	),
    	 'f07'=>array(
         'name'=>'上级公告',
         'url'=>'../../other/notice.php'
    	),
	);

?>