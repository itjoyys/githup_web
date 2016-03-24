<?
$config['sprotsorderpktype']=[
		    'QCDY'=>[					//benadd 0 带本金 1不带PKINFO限额需要匹配此处判断*/
				'Match_BzM' =>['全场独赢主队','ben_add'=>1,'Z','pkinfo'/*独赢,2=全场1=上半场*/=>['dy',2]],
				'Match_BzG' =>['全场独赢客队','ben_add'=>1,'K','pkinfo'=>['dy',2]],
				'Match_BzH' =>['全场独赢和局','ben_add'=>1,'和局','pkinfo'=>['dy',2]]
			    ],
			'SBDY'=>[ 
				'Match_Bmdy'=>['半场独赢主队','ben_add'=>1,'Z','pkinfo'=>['dy',1]],
				'Match_Bgdy'=>['半场独赢客队','ben_add'=>1,'K','pkinfo'=>['dy',1]],
				'Match_Bhdy'=>['半场独赢和局','ben_add'=>1,'和局','pkinfo'=>['dy',1]]
			    ],
			'QCRQ'=>[
			    'Match_Ho'  =>['全场让球主队','ben_add'=>0,'Z','pkinfo'=>['rq',2],'Match_RGG'],
			    'Match_Ao'  =>['全场让球客队','ben_add'=>0,'K','pkinfo'=>['rq',2],'Match_RGG']
			     ],
			'SBRQ'=>[
			    'Match_BHo'  =>['半场让球主队','ben_add'=>0,'Z','pkinfo'=>['rq',1],'Match_BRpk'],
			    'Match_BAo'  =>['半场让球客队','ben_add'=>0,'K','pkinfo'=>['rq',1],'Match_BRpk']
			     ],
			'QCDS'=>[
			    'Match_DsDpl'  =>['全场单双-单','ben_add'=>1,'单','pkinfo'=>['ds',2]],
			    'Match_DsSpl'  =>['全场单双-双','ben_add'=>1,'双','pkinfo'=>['ds',2]]
			     ],
			'QCDX'=>[
			    'Match_DxDpl'  =>['全场大小-大','ben_add'=>0,'大','pkinfo'=>['dx',2],'Match_DxGG'],
		  		'Match_DxXpl'  =>['全场大小-小','ben_add'=>0,'小','pkinfo'=>['dx',2],'Match_DxGG']
			     ],
		    'SBDX'=>[
			    'Match_Bdpl'  =>['半场大小-大','ben_add'=>0,'大','pkinfo'=>['dx',1],'Match_Bdxpk'],
		  		'Match_Bxpl'  =>['半场大小-小','ben_add'=>0,'小','pkinfo'=>['dx',1],'Match_Bdxpk']
			     ],
		  	'ZRQ'=>[
			    'Match_Total01Pl'  =>['总入球0-1'  ,'ben_add'=>1,'总入球0-1', 'pkinfo'=>['zrq',2]],
		  		'Match_Total23Pl'  =>['总入球2-3'  ,'ben_add'=>1,'总入球2-3',  'pkinfo'=>['zrq',2]],
		  		'Match_Total46Pl'  =>['总入球4-6'  ,'ben_add'=>1,'总入球4-6',  'pkinfo'=>['zrq',2]],
		  		'Match_Total7upPl' =>['总入球大于或等于7','ben_add'=>1,'总入球UP7',  'pkinfo'=>['zrq',2]]
			     ],
		  	'BD'=>[
			    'Match_Bd10'   =>['波胆1:0','ben_add'=>1,'波胆1:0','pkinfo'=>['bd',2]],
		  		'Match_Bd20'   =>['波胆2:0','ben_add'=>1,'波胆2:0','pkinfo'=>['bd',2]],
		  		'Match_Bd21'   =>['波胆2:1','ben_add'=>1,'波胆2:1','pkinfo'=>['bd',2]],
		  		'Match_Bd30'   =>['波胆3:0','ben_add'=>1,'波胆3:0','pkinfo'=>['bd',2]],
		  		'Match_Bd31'   =>['波胆3:1','ben_add'=>1,'波胆3:1','pkinfo'=>['bd',2]],
		  		'Match_Bd32'   =>['波胆3:2','ben_add'=>1,'波胆3:2','pkinfo'=>['bd',2]],
		  		'Match_Bd40'   =>['波胆4:0','ben_add'=>1,'波胆4:0','pkinfo'=>['bd',2]],
		  		'Match_Bd41'   =>['波胆4:1','ben_add'=>1,'波胆4:1','pkinfo'=>['bd',2]],
		  		'Match_Bd42'   =>['波胆4:2','ben_add'=>1,'波胆4:2','pkinfo'=>['bd',2]],
		  		'Match_Bd43'   =>['波胆4:3','ben_add'=>1,'波胆4:3','pkinfo'=>['bd',2]],
		  		'Match_Bdg10'  =>['波胆0:1','ben_add'=>1,'波胆0:1','pkinfo'=>['bd',2]],
		  		'Match_Bdg20'  =>['波胆0:2','ben_add'=>1,'波胆0:2','pkinfo'=>['bd',2]],
		  		'Match_Bdg21'  =>['波胆1:2','ben_add'=>1,'波胆1:2','pkinfo'=>['bd',2]],
		  		'Match_Bdg30'  =>['波胆0:3','ben_add'=>1,'波胆0:3','pkinfo'=>['bd',2]],
		  		'Match_Bdg31'  =>['波胆1:3','ben_add'=>1,'波胆1:3','pkinfo'=>['bd',2]],
		  		'Match_Bdg32'  =>['波胆2:3','ben_add'=>1,'波胆2:3','pkinfo'=>['bd',2]],
		  		'Match_Bdg40'  =>['波胆0:4','ben_add'=>1,'波胆0:4','pkinfo'=>['bd',2]],
		  		'Match_Bdg41'  =>['波胆1:4','ben_add'=>1,'波胆1:4','pkinfo'=>['bd',2]],
		  		'Match_Bdg42'  =>['波胆2:4','ben_add'=>1,'波胆2:4','pkinfo'=>['bd',2]],
		  		'Match_Bdg43'  =>['波胆3:4','ben_add'=>1,'波胆3:4','pkinfo'=>['bd',2]],
		  		'Match_Bd00'   =>['波胆0:0','ben_add'=>1,'波胆0:0','pkinfo'=>['bd',2]],
		  		'Match_Bd11'   =>['波胆1:1','ben_add'=>1,'波胆1:1','pkinfo'=>['bd',2]],
		  		'Match_Bd22'   =>['波胆2:2','ben_add'=>1,'波胆2:2','pkinfo'=>['bd',2]],
		  		'Match_Bd33'   =>['波胆3:3','ben_add'=>1,'波胆3:3','pkinfo'=>['bd',2]],
		  		'Match_Bd44'   =>['波胆4:4','ben_add'=>1,'波胆4:4','pkinfo'=>['bd',2]],
		  		'Match_Bdup5'  =>['波胆UP5','ben_add'=>1,'波胆UP5','pkinfo'=>['bd',2]],
			     ],
		  
		  ];
$config['sportsarea']=[
			'H'=>['name'=>'香港盘','open'=>['']],
			'M'=>'足球单式'
			];

$config['sportsstatus']=[
		'0'=>'未结算',
		'1'=>'<span style="color:#FF0000;">赢</span>',
		'2'=>'<span style="color:#00CC00;">输</span>',
		'8'=>'和局',
		'3'=>'注单无效',
		'4'=>'<span style="color:#FF0000;">赢一半</span>',
		'5'=>'<span style="color:#00CC00;">输一半</span>',
		'6'=>'进球无效',
		'7'=>'红卡取消',
		];