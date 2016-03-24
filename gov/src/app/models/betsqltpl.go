package models

import (

)


var (

ag_bet_record_create = `
(
	bill_no BIGINT(20) NOT NULL,
	data_type CHAR(5) NOT NULL,
	player_name CHAR(20) NOT NULL,
	agent_code CHAR(20) NOT NULL,
	game_code CHAR(20) NOT NULL,
	netamount DECIMAL(10,2) NOT NULL DEFAULT '0.00',
	bet_time DATETIME NOT NULL,
	game_type CHAR(10) NOT NULL,
	bet_amount DECIMAL(20,2) NOT NULL DEFAULT '0.00',
	valid_betamount DECIMAL(20,2) NOT NULL DEFAULT '0.00',
	flag TINYINT(1) NOT NULL DEFAULT '0',
	play_type CHAR(10) NOT NULL,
	currency CHAR(10) NOT NULL,
	table_code CHAR(10) NOT NULL,
	login_ip CHAR(16) NOT NULL,
	recalcu_time DATETIME NOT NULL,
	platform_id CHAR(10) NOT NULL,
	platform_type CHAR(10) NOT NULL,
	stringex TEXT NOT NULL,
	remark TEXT NOT NULL,
	round CHAR(10) NOT NULL,
	result TEXT NOT NULL,
	before_credit DECIMAL(20,2) NOT NULL,
	device_type CHAR(10) NOT NULL DEFAULT '',
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	site_id CHAR(20) NOT NULL DEFAULT '',
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	PRIMARY KEY (bill_no),
	UNIQUE INDEX bill_no (bill_no) USING BTREE
) COMMENT='ag注单记录表' COLLATE='utf8_general_ci' ENGINE=MyISAM;`

bbin_bet_record_create = `
(
	wagers_id BIGINT(20) NOT NULL,
	username CHAR(20) NOT NULL,
	wagers_date DATETIME NOT NULL,
	gametype CHAR(10) NOT NULL,
	result TEXT NOT NULL,
	betamount DECIMAL(20,6) NOT NULL,
	payoff DECIMAL(20,6) NOT NULL,
	currency CHAR(10) NOT NULL,
	commissionable DECIMAL(20,6) NOT NULL,
	serial_id CHAR(20) NOT NULL,
	round_no CHAR(20) NOT NULL,
	game_code CHAR(20) NOT NULL,
	result_type CHAR(20) NOT NULL,
	card CHAR(200) NOT NULL DEFAULT '',
	exchange_rate DECIMAL(10,6) NOT NULL,
	commission CHAR(50) NOT NULL,
	is_paid CHAR(50) NOT NULL,
	uptime DATETIME NOT NULL,
	order_date DATETIME NOT NULL,
	modified_date DATETIME NOT NULL,
	gamekind TINYINT(2) NOT NULL,
	site_id CHAR(10) NOT NULL,
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	update_time DATETIME NOT NULL,
	PRIMARY KEY (wagers_id, gamekind)
)COMMENT='BBIN注单记录表' COLLATE='utf8_general_ci' ENGINE=MyISAM;`


ct_bet_record_create = `
(
	transaction_id BIGINT(20) NOT NULL DEFAULT '0',
	transaction_date_time DATETIME NULL DEFAULT NULL,
	closed_time DATETIME NULL DEFAULT NULL,
	member_id CHAR(20) NULL DEFAULT NULL,
	member_type CHAR(10) NULL DEFAULT NULL,
	currency CHAR(10) NULL DEFAULT NULL,
	balance_start DECIMAL(20,2) NULL DEFAULT NULL,
	balance_end DECIMAL(20,2) NULL DEFAULT NULL,
	is_revocation TINYINT(1) NULL DEFAULT NULL,
	game_type INT(10) NULL DEFAULT NULL,
	table_id INT(10) NULL DEFAULT NULL,
	shoe_id BIGINT(20) NULL DEFAULT NULL,
	play_id BIGINT(20) NULL DEFAULT NULL,
	betpoint DECIMAL(20,2) NULL DEFAULT NULL,
	betpoint_detail TEXT NULL,
	betresult TEXT NULL,
	betresult_detail TEXT NULL,
	win_or_loss DECIMAL(20,2) NULL DEFAULT NULL,
	betip CHAR(16) NULL DEFAULT NULL,
	paramid CHAR(20) NULL DEFAULT '1',
	site_id CHAR(20) NULL DEFAULT '',
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	availablebet DECIMAL(20,2) NULL DEFAULT NULL,
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (transaction_id)
)COMMENT='ct注单记录' COLLATE='utf8_general_ci' ENGINE=MyISAM;`


lebo_bet_record_create = `
(
	game_id CHAR(20) NOT NULL DEFAULT '0',
	betstart_time DATETIME NULL DEFAULT NULL,
	member CHAR(20) NULL DEFAULT NULL,
	is_revocation TINYINT(1) NULL DEFAULT NULL,
	game_type INT(10) NULL DEFAULT NULL,
	table_id INT(10) NULL DEFAULT NULL,
	betamount DECIMAL(20,2) NULL DEFAULT NULL,
	payout DECIMAL(20,2) NULL DEFAULT NULL,
	valid_betamount DECIMAL(20,2) NULL DEFAULT NULL,
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	bet_detail TEXT NULL,
	game_result TEXT NULL,
	site_id CHAR(20) NULL DEFAULT '',
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	PRIMARY KEY (game_id),
	UNIQUE INDEX game_id (game_id) USING BTREE
)COMMENT='lebo注单记录' COLLATE='utf8_general_ci' ENGINE=MyISAM;`

mg_bet_record_create = `
(
	bet_no CHAR(32) NOT NULL DEFAULT '',
	account_number CHAR(20) NOT NULL,
	income DECIMAL(20,4) NULL DEFAULT NULL,
	payout DECIMAL(20,4) NULL DEFAULT NULL,
	win_amount DECIMAL(20,4) NULL DEFAULT NULL,
	lose_amount DECIMAL(20,4) NULL DEFAULT NULL,
	all_income DECIMAL(20,4) NULL DEFAULT '0.0000',
	all_payout DECIMAL(20,4) NULL DEFAULT '0.0000',
	all_win_amount DECIMAL(20,4) NULL DEFAULT '0.0000',
	all_lose_amount DECIMAL(20,4) NULL DEFAULT '0.0000',
	all_net_cash DECIMAL(20,4) NULL DEFAULT '0.0000',
	all_net_win DECIMAL(20,4) NULL DEFAULT '0.0000',
	date DATETIME NOT NULL,
	game_type CHAR(50) NULL DEFAULT NULL,
	net_cash DECIMAL(20,4) NULL DEFAULT NULL,
	net_win DECIMAL(20,4) NULL DEFAULT NULL,
	module_id INT(11) NOT NULL,
	client_id CHAR(20) NOT NULL,
	site_id CHAR(20) NULL DEFAULT '',
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (bet_no),
	UNIQUE INDEX bet_no (bet_no) USING BTREE
)COMMENT='mg注单表' COLLATE='utf8_general_ci' ENGINE=MyISAM;`

og_bet_record_create = `
(
	product_id INT(11) NOT NULL COMMENT '默认id',
	user_name CHAR(16) NOT NULL,
	game_record_id INT(10) NOT NULL,
	order_number BIGINT(20) NOT NULL,
	table_id CHAR(5) NOT NULL,
	stage CHAR(5) NOT NULL,
	inning CHAR(5) NOT NULL,
	game_name_id CHAR(5) NOT NULL,
	game_betting_kind INT(11) NOT NULL,
	game_betting_content TEXT NOT NULL,
	result_type TINYINT(4) NOT NULL,
	betting_amount DECIMAL(20,2) NOT NULL,
	compensate_rate DECIMAL(5,2) NOT NULL,
	win_lose_amount DECIMAL(20,2) NOT NULL,
	balance DECIMAL(20,2) NOT NULL,
	add_time DATETIME NOT NULL,
	platform_id TINYINT(4) NOT NULL,
	vendor_id BIGINT(16) NOT NULL,
	valid_amount DECIMAL(20,2) NOT NULL,
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	site_id CHAR(20) NOT NULL DEFAULT '',
	agent_id INT(10) NOT NULL DEFAULT '0',
	index_id CHAR(1) NOT NULL DEFAULT 'a',
	pkusername CHAR(20) NOT NULL DEFAULT '',
	PRIMARY KEY (product_id, site_id),
	UNIQUE INDEX vendor_id (vendor_id) USING BTREE
)COMMENT='og订单记录表' COLLATE='utf8_general_ci' ENGINE=MyISAM ;`
)




