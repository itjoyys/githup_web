<?php
if (!defined('BASEPATH')) {
	EXIT('No direct script asscess allowed');
}

/**
 * 体彩算法类
 * /sport/help/2.html
 */
class Sports_Algorithm {

	/**
	 * 体彩判断输赢
	 * @param  [type] $column         [description]
	 * @param  [type] $mb_inball      [description]  全场 主场的进球数
	 * @param  [type] $tg_inball      [description]  全场 客场的进球数
	 * @param  [type] $mb_inball_hr   [description]  上半场 主场的进球数
	 * @param  [type] $tg_inball_hr   [description]  上半场 客场的进球数
	 * @param  [type] $match_type     [description]  $row["match_type"] 下注球赛类型  2滚球
	 * @param  [type] $match_showtype [description]  $row["match_showtype"]  让球类型 h主场让球,c客场让球
	 * @param  [type] $rgg            [description]  $row["match_rgg"] 让球数
	 * @param  [type] $dxgg           [description]  $row["match_dxgg"]  大小盘口
	 * @param  [type] $match_nowscore [description]  $row["match_nowscore"] 当前比分
	 * @return [Array]  status 输赢的状态 1为赢,2输,3无效,4赢一半,5输一半,6其他无效 进球无效,7红卡取消,8和局
	 */
	public function make_point($column, $mb_inball, $tg_inball, $mb_inball_hr, $tg_inball_hr,
		$match_type, $match_showtype, $rgg, $dxgg, $match_nowscore) {

		if ($mb_inball < 0) {
			//全场取消
			return array("column" => $column, "ben_add" => 0, "status" => 3, "mb_inball" => $mb_inball, "tg_inball" => $tg_inball);
		} elseif ($mb_inball == "" && $mb_inball_hr < 0) {
			//半场取消
			return array("column" => $column, "ben_add" => 0, "status" => 3, "mb_inball" => $mb_inball_hr, "tg_inball" => $tg_inball_hr);
		}
		$ben_add = 0;
		$status = 2; //默认为输
		switch ($column) {
			case 'match_bzm':	//标准盘独赢 --标配的主场赢
				if ($mb_inball > $tg_inball) {
					$status = 1;
				}

				break;
			case 'match_bzg':	//--标配的客场赢
				if ($mb_inball < $tg_inball) {
					$status = 1;
				}

				break;
			case 'match_bzh':	////--标配的和局
				if ($mb_inball == $tg_inball) {
					$status = 1;
				}

				break;
			case 'match_ho':	//主让球盘
				$m = explode('/', $rgg); 	//让球
				$ben_add = 1;
				if (count($m) == 2) {
					foreach ($m as $k) {
						if (strtolower($match_showtype) == 'h') {
							//??
							$mb_temp = $mb_inball;
							$tg_temp = $tg_inball + $k;
						} else {
							$mb_temp = $mb_inball + $k;
							$tg_temp = $tg_inball;
						}
						if ($match_type == 2) {
							// 如果是滚球,减去下注比分
							$n = explode(':', $match_nowscore);
							$mb_temp -= $n[0];
							$tg_temp -= $n[1];
						}
						if ($mb_temp > $tg_temp) {
							$temp += 1;
						} elseif ($mb_temp == $tg_temp) {
							$temp += 0.5;
						} else {
							$temp += 0;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if (strtolower($match_showtype) == 'h') {
						$mb_temp = $mb_inball;
						$tg_temp = $tg_inball + $rgg;
					} else {
						$mb_temp = $mb_inball + $rgg;
						$tg_temp = $tg_inball;
					}
					if ($match_type == 2) {
						// 如果是滚球,减去下注比分
						$n = explode(':', $match_nowscore);
						$mb_temp -= $n[0];
						$tg_temp -= $n[1];
					}
					if ($mb_temp > $tg_temp) {
						$status = 1;
					} elseif ($mb_temp == $tg_temp) {
						$status = 8;
					} else {
						$status = 2;
					}

				}break;
			case 'match_ao':	//让球盘
				$m = explode('/', $rgg);
				$ben_add = 1;
				if (count($m) == 2) {
					foreach ($m as $k) {
						if (strtolower($match_showtype) == 'h') {
							$mb_temp = $mb_inball;
							$tg_temp = $tg_inball + $k;
						} else {
							$mb_temp = $mb_inball + $k;
							$tg_temp = $tg_inball;
						}
						if ($match_type == 2) {
							// 如果是滚球,减去下注比分
							$n = explode(':', $match_nowscore);
							$mb_temp -= $n[0];
							$tg_temp -= $n[1];
						}
						if ($mb_temp < $tg_temp) {
							$temp += 1;
						} elseif ($mb_temp == $tg_temp) {
							$temp += 0.5;
						} else {
							$temp += 0;
						}
					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}
				} else {
					if (strtolower($match_showtype) == 'h') {
						$mb_temp = $mb_inball;
						$tg_temp = $tg_inball + $rgg;
					} else {
						$mb_temp = $mb_inball + $rgg;
						$tg_temp = $tg_inball;
					}
					if ($match_type == 2) {
						// 如果是滚球,减去下注比分
						$n = explode(':', $match_nowscore);
						$mb_temp -= $n[0];
						$tg_temp -= $n[1];
					}
					if ($mb_temp < $tg_temp) {
						$status = 1;
					} elseif ($mb_temp == $tg_temp) {
						$status = 8;
					} else {
						$status = 2;
					}

				}break;
//---------------------------大小,单双竞彩
			case 'match_dxdpl':	//大小盘
				$m = explode('/', $dxgg);
				$ben_add = 1;
				$total = $mb_inball + $tg_inball;
				if (count($m) == 2) {
					foreach ($m as $t) {
						if ($total > $t) {
							$temp += 1;
						} elseif ($total == $t) {
							$temp += 0.5;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if ($total > $dxgg) {
						$status = 1;
					} elseif ($total == $dxgg) {
						$status = 8;
					} else {
						$status = 2;
					}

				}break;
			case 'match_dxxpl':	//大小盘
				$m = explode('/', $dxgg);
				$ben_add = 1;
				$total = $mb_inball + $tg_inball;
				if (count($m) == 2) {
					foreach ($m as $t) {
						if ($total < $t) {
							$temp += 1;
						} elseif ($total == $t) {
							$temp += 0.5;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if ($total < $dxgg) {
						$status = 1;
					} elseif ($total == $dxgg) {
						$status = 8;
					} else {
						$status = 2;
					}

				}break;
			case 'match_dsdpl':
				if (($mb_inball + $tg_inball) % 2 == 1) {
					$status = 1;
				}

				break;
			case 'match_dsspl':
				if (($mb_inball + $tg_inball) % 2 == 0) {
					$status = 1;
				}

				break;
//--------------------------------------主场赢
			case 'match_bmdy':	//上半场独赢
				if ($mb_inball_hr > $tg_inball_hr) {
					$status = 1;
				}

				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
			case 'match_bgdy':
				if ($mb_inball_hr < $tg_inball_hr) {
					$status = 1;
				}

				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
			case 'match_bhdy':
				if ($mb_inball_hr == $tg_inball_hr) {
					$status = 1;
				}

				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
//-------------------------------------
			case 'match_bho':
				$m = explode('/', $rgg);
				$ben_add = 1;
				if (count($m) == 2) {
					foreach ($m as $k) {
						if (strtolower($match_showtype) == 'h') {
							$mb_temp = $mb_inball_hr;
							$tg_temp = $tg_inball_hr + $k;
						} else {
							$mb_temp = $mb_inball_hr + $k;
							$tg_temp = $tg_inball_hr;
						}
						if ($match_type == 2) {
							// 如果是滚球,减去下注比分
							$n = explode(':', $match_nowscore);
							$mb_temp -= $n[0];
							$tg_temp -= $n[1];
						}
						if ($mb_temp > $tg_temp) {
							$temp += 1;
						} elseif ($mb_temp == $tg_temp) {
							$temp += 0.5;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}
				} else {
					if (strtolower($match_showtype) == 'h') {
						$mb_temp = $mb_inball_hr;
						$tg_temp = $tg_inball_hr + $rgg;
					} else {
						$mb_temp = $mb_inball_hr + $rgg;
						$tg_temp = $tg_inball_hr;
					}
					if ($match_type == 2) {
						// 如果是滚球,减去下注比分
						$n = explode(':', $match_nowscore);
						$mb_temp -= $n[0];
						$tg_temp -= $n[1];
					}
					if ($mb_temp > $tg_temp) {
						$status = 1;
					} elseif ($mb_temp == $tg_temp) {
						$status = 8;
					} else {
						$status = 2;
					}

				}
				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
			case 'match_bao':
				$m = explode('/', $rgg);
				$ben_add = 1;
				if (count($m) == 2) {
					foreach ($m as $k) {
						if (strtolower($match_showtype) == 'h') {
							$mb_temp = $mb_inball_hr;
							$tg_temp = $tg_inball_hr + $k;
						} else {
							$mb_temp = $mb_inball_hr + $k;
							$tg_temp = $tg_inball_hr;
						}
						if ($match_type == 2) {
							// 如果是滚球,减去下注比分
							$n = explode(':', $match_nowscore);
							$mb_temp -= intval($n[0]);
							$tg_temp -= intval($n[1]);
						}
						if ($mb_temp < $tg_temp) {
							$temp += 1;
						} elseif ($mb_temp == $tg_temp) {
							$temp += 0.5;
						} else {
							$temp += 0;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if (strtolower($match_showtype) == 'h') {
						$mb_temp = $mb_inball_hr;
						$tg_temp = $tg_inball_hr + $rgg;
					} else {
						$mb_temp = $mb_inball_hr + $rgg;
						$tg_temp = $tg_inball_hr;
					}
					if ($match_type == 2) {
						// 如果是滚球,减去下注比分
						$n = explode(':', $match_nowscore);
						$mb_temp -= $n[0];
						$tg_temp -= $n[1];
					}
					if ($mb_temp < $tg_temp) {
						$status = 1;
					} elseif ($mb_temp == $tg_temp) {
						$status = 8;
					} else {
						$status = 2;
					}

				}
				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
//------------------------------------------------
			case 'match_bdpl':
				$m = explode('/', $dxgg);
				$ben_add = 1;
				$total = $mb_inball_hr + $tg_inball_hr;
				if (count($m) == 2) {
					foreach ($m as $t) {
						if ($total > $t) {
							$temp += 1;
						} elseif ($total == $t) {
							$temp += 0.5;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if ($total > $dxgg) {
						$status = 1;
					} elseif ($total == $dxgg) {
						$status = 8;
					} else {
						$status = 2;
					}

				}
				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
			case 'match_bxpl':
				$m = explode('/', $dxgg);
				$ben_add = 1;
				$total = $mb_inball_hr + $tg_inball_hr;
				if (count($m) == 2) {
					foreach ($m as $t) {
						if ($total < $t) {
							$temp += 1;
						} elseif ($total == $t) {
							$temp += 0.5;
						} else {
							$temp += 0;
						}

					}
					if ($temp == 0.5) {
						$status = 5;
					} elseif ($temp == 1.5) {
						$status = 4;
					} elseif ($temp == 2) {
						$status = 1;
					} elseif ($temp == 0) {
						$status = 2;
					}

				} else {
					if ($total < $dxgg) {
						$status = 1;
					} elseif ($total == $dxgg) {
						$status = 8;
					} else {
						$status = 2;
					}

				}
				$mb_inball = $mb_inball_hr;
				$tg_inball = $tg_inball_hr;
				break;
//---------------------以下是波胆盘 波胆竞猜
			case 'match_bd10':	//波胆
				if (($mb_inball == 1) && ($tg_inball == 0)) {
					$status = 1;
				}

				break;
			case 'match_bd20':	//波胆
				if (($mb_inball == 2) && ($tg_inball == 0)) {
					$status = 1;
				}

				break;
			case 'match_bd21':	//波胆
				if (($mb_inball == 2) && ($tg_inball == 1)) {
					$status = 1;
				}

				break;
			case 'match_bd30':	//波胆
				if (($mb_inball == 3) && ($tg_inball == 0)) {
					$status = 1;
				}

				break;
			case 'match_bd31':	//波胆
				if (($mb_inball == 3) && ($tg_inball == 1)) {
					$status = 1;
				}

				break;
			case 'match_bd32':	//波胆
				if (($mb_inball == 3) && ($tg_inball == 2)) {
					$status = 1;
				}

				break;
			case 'match_bd40':	//波胆
				if (($mb_inball == 4) && ($tg_inball == 0)) {
					$status = 1;
				}

				break;
			case 'match_bd41':	//波胆
				if (($mb_inball == 4) && ($tg_inball == 1)) {
					$status = 1;
				}

				break;
			case 'match_bd42':	//波胆
				if (($mb_inball == 4) && ($tg_inball == 2)) {
					$status = 1;
				}

				break;
			case 'match_bd43':	//波胆
				if (($mb_inball == 4) && ($tg_inball == 3)) {
					$status = 1;
				}

				break;
			case 'match_bd00':	//波胆
				if (($mb_inball == 0) && ($tg_inball == 0)) {
					$status = 1;
				}

				break;
			case 'match_bd11':	//波胆
				if (($mb_inball == 1) && ($tg_inball == 1)) {
					$status = 1;
				}

				break;
			case 'match_bd22':	//波胆
				if (($mb_inball == 2) && ($tg_inball == 2)) {
					$status = 1;
				}

				break;
			case 'match_bd33':	//波胆
				if (($mb_inball == 3) && ($tg_inball == 3)) {
					$status = 1;
				}

				break;
			case 'match_bd44':	//波胆
				if (($mb_inball == 4) && ($tg_inball == 4)) {
					$status = 1;
				}

				break;
			case 'match_bdup5':
				if (($mb_inball >= 5) || ($tg_inball >= 5)) {
					$status = 1;
				}

				break;
			case 'match_bdg10':
				if (($mb_inball == 0) && ($tg_inball == 1)) {
					$status = 1;
				}

				break;
			case 'match_bdg20':
				if (($mb_inball == 0) && ($tg_inball == 2)) {
					$status = 1;
				}

				break;
			case 'match_bdg21':
				if (($mb_inball == 1) && ($tg_inball == 2)) {
					$status = 1;
				}

				break;
			case 'match_bdg30':
				if (($mb_inball == 0) && ($tg_inball == 3)) {
					$status = 1;
				}

				break;
			case 'match_bdg31':
				if (($mb_inball == 1) && ($tg_inball == 3)) {
					$status = 1;
				}

				break;
			case 'match_bdg32':
				if (($mb_inball == 2) && ($tg_inball == 3)) {
					$status = 1;
				}

				break;
			case 'match_bdg40':
				if (($mb_inball == 0) && ($tg_inball == 4)) {
					$status = 1;
				}

				break;
			case 'match_bdg41':
				if (($mb_inball == 1) && ($tg_inball == 4)) {
					$status = 1;
				}

				break;
			case 'match_bdg42':
				if (($mb_inball == 2) && ($tg_inball == 4)) {
					$status = 1;
				}

				break;
			case 'match_bdg43':
				if (($mb_inball == 3) && ($tg_inball == 4)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd10':
				if (($mb_inball_hr == 1) && ($tg_inball_hr == 0)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd20':
				if (($mb_inball_hr == 2) && ($tg_inball_hr == 0)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd21':
				if (($mb_inball_hr == 2) && ($tg_inball_hr == 1)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd30':
				if (($mb_inball_hr == 3) && ($tg_inball_hr == 0)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd31':
				if (($mb_inball_hr == 3) && ($tg_inball_hr == 1)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd32':
				if (($mb_inball_hr == 3) && ($tg_inball_hr == 2)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd40':
				if (($mb_inball_hr == 4) && ($tg_inball_hr == 0)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd41':
				if (($mb_inball_hr == 4) && ($tg_inball_hr == 1)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd42':
				if (($mb_inball_hr == 4) && ($tg_inball_hr == 2)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd43':
				if (($mb_inball_hr == 4) && ($tg_inball_hr == 3)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd00':
				if (($mb_inball_hr == 0) && ($tg_inball_hr == 0)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd11':
				if (($mb_inball_hr == 1) && ($tg_inball_hr == 1)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd22':
				if (($mb_inball_hr == 2) && ($tg_inball_hr == 2)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd33':
				if (($mb_inball_hr == 3) && ($tg_inball_hr == 3)) {
					$status = 1;
				}

				break;
			case 'match_hr_bd44':
				if (($mb_inball_hr == 4) && ($tg_inball_hr == 4)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdup5':
				if (($mb_inball_hr >= 5) || ($tg_inball_hr >= 5)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg10':
				if (($mb_inball_hr == 0) && ($tg_inball_hr == 1)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg20':
				if (($mb_inball_hr == 0) && ($tg_inball_hr == 2)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg21':
				if (($mb_inball_hr == 1) && ($tg_inball_hr == 2)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg30':
				if (($mb_inball_hr == 0) && ($tg_inball_hr == 3)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg31':
				if (($mb_inball_hr == 1) && ($tg_inball_hr == 3)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg32':
				if (($mb_inball_hr == 2) && ($tg_inball_hr == 3)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg40':
				if (($mb_inball_hr == 0) && ($tg_inball_hr == 4)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg41':
				if (($mb_inball_hr == 1) && ($tg_inball_hr == 4)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg42':
				if (($mb_inball_hr == 2) && ($tg_inball_hr == 4)) {
					$status = 1;
				}

				break;
			case 'match_hr_bdg43':
				if (($mb_inball_hr == 3) && ($tg_inball_hr == 4)) {
					$status = 1;
				}

				break;
//------------------入球数竞猜
			case 'match_total01pl':
				$total = $mb_inball + $tg_inball;
				if (($total >= 0) && ($total <= 1)) {
					$status = 1;
				}

				break;
			case 'match_total23pl':
				$total = $mb_inball + $tg_inball;
				if (($total >= 2) && ($total <= 3)) {
					$status = 1;
				}

				break;
			case 'match_total46pl':
				$total = $mb_inball + $tg_inball;
				if (($total >= 4) && ($total <= 6)) {
					$status = 1;
				}

				break;
			case 'match_total7uppl':
				$total = $mb_inball + $tg_inball;
				if (($total >= 7)) {
					$status = 1;
				}

				break;
//------------------半全场竞猜和派彩
			case 'match_bqmm':	//主/主
				if (($mb_inball > $tg_inball) && ($mb_inball_hr > $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqmh':	//主/和
				if (($mb_inball == $tg_inball) && ($mb_inball_hr > $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqmg':	//主/客
				if (($mb_inball < $tg_inball) && ($mb_inball_hr > $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqhm':	//和/主
				if (($mb_inball > $tg_inball) && ($mb_inball_hr == $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqhh':	//和/和
				if (($mb_inball == $tg_inball) && ($mb_inball_hr == $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqhg':	//和/客
				if (($mb_inball < $tg_inball) && ($mb_inball_hr == $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqgm':	//客/主
				if (($mb_inball > $tg_inball) && ($mb_inball_hr < $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqgh':	//客/和
				if (($mb_inball == $tg_inball) && ($mb_inball_hr < $tg_inball_hr)) {
					$status = 1;
				}

				break;
			case 'match_bqgg':	//客/客
				if (($mb_inball < $tg_inball) && ($mb_inball_hr < $tg_inball_hr)) {
					$status = 1;
				}

				break;
			default:break;
		}

		return array("column" => $column, "ben_add" => $ben_add, "status" => $status, "mb_inball" => $mb_inball, "tg_inball" => $tg_inball);
	}

}