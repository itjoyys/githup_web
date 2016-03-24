define([], function() {
	var e = ry_lottery_config,
		t = e.locale,
		n = {
			dialog: {
				ok: "确定",
				cancel: "取消",
				close: "关闭"
			},
			msg: {
				empty: "请选择一个玩法",
				limit: "只能选择{0}个号码",
				maxGroup: "最多只能选择{0}组",
				minGroup: "最少选择{0}组",
				emptyAmount: "请输入下注金额",
				minNumbers: "最少选择{0}个号码",
				issue: "期",
				closedGate: "已封盘",
				errorNumber: "号码信息有误",
				sameNumbers: "不能包含相同的号码",
				notice: "公告"
			},
			date: {
				days: "天",
				hours: "小时",
				minutes: "分钟",
				seconds: "秒"
			},
			bet: {
				success: "下注成功",
				unknownError: "未知错误",
				linesUpdated: "赔率发生变化",
				loading: "正在保存中...",
				orders: "下注清单"
			}
		},
		r = {
			dialog: {
				ok: "確定",
				cancel: "取消",
				close: "關閉"
			},
			msg: {
				empty: "請選擇壹個玩法",
				limit: "只能選擇{0}個號碼",
				maxGroup: "最多只能選擇{0}組",
				minGroup: "最少選擇{0}組",
				emptyAmount: "請輸入下註金額",
				minNumbers: "最少選擇{0}個號碼",
				issue: "期",
				closedGate: "已封盤",
				notice: "公告",
				errorNumber: "號碼信息有誤",
				sameNumbers: "不能包含相同的號碼"
			},
			date: {
				days: "天",
				hours: "小時",
				minutes: "分鐘",
				seconds: "秒"
			},
			bet: {
				success: "下註成功",
				unknownError: "未知錯誤",
				linesUpdated: "賠率發生變化",
				loading: "正在保存中...",
				orders: "下註清單"
			}
		},
		i = {
			dialog: {
				ok: "Ok",
				cancel: "Cancel",
				close: "Close"
			},
			msg: {
				empty: "Please choose for betting",
				limit: "Can only choose {0} numbers",
				maxGroup: "Can only choose {0} groups",
				minGroup: "Select at least {0} groups",
				emptyAmount: "Please enter betting amount",
				notice: "Notice",
				minNumbers: "Select at least {0} numbers",
				issue: "Issue",
				closedGate: "Closed",
				errorNumber: "Number info error",
				sameNumbers: "Can not include same numbers"
			},
			date: {
				days: "Days",
				hours: "Hours",
				minutes: "Minutes",
				seconds: "Seconds"
			},
			bet: {
				success: "Success !",
				unknownError: "Unknown error !",
				linesUpdated: "Lines updated ",
				loading: "Loading...",
				orders: "Order list"
			}
		},
		s = {
			zh_cn: n,
			zh_tw: r,
			en: i
		};
	return s[t]
});