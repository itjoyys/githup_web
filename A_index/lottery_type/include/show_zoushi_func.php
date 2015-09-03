<?php
include_once ("../include/config.php");
include ("../include/public_config.php");


function zoushi($result, $ball_1, $daxiao)
{
    // 1,查询结果 3,第几球字段名 4,玩法
    $data = array();
    foreach ($result as $k => $v) {
        if ($daxiao == '大小') {
            if ($v[$ball_1] < 5) {
                $data[] = '2';
            } else {
                $data[] = '1';
            }
        } elseif ($daxiao == '单双') {
            if ($v[$ball_1] % 2 == 1) {
                $data[] = '1';
            } else {
                $data[] = '2';
            }
        } elseif ($daxiao == '总和单双') {
            if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5']) % 2 == 1) {
                $data[] = '1';
            } else {
                $data[] = '2';
            }
        } elseif ($daxiao == '总和大小') {
            $num_limit=$v['ball_4']?23:14;
            if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5']) < $num_limit) {
                $data[] = '2';
            } else {
                $data[] = '1';
            }
        }elseif($daxiao=="龙虎合"){
            if($ball_1=='ball_6'){
                //时时彩
                if ($v['ball_1']>$v['ball_5']) {
                    $data[] = '1';
                }elseif($v['ball_1']==$v['ball_5']){
                    $data[] = '3';
                }else {
                    $data[] = '2';
                }
            }else{
                //福彩，PL3
                if ($v['ball_1']>$v['ball_3']) {
                    $data[] = '1';
                }elseif($v['ball_1']==$v['ball_3']){
                    $data[] = '3';
                }else {
                    $data[] = '2';
                }
            }

        }
    }

    $big_arr_1 = array();
    if (! empty($data)) {
        $i = 0;
        foreach ($data as $k => $v) {
            if (! empty($big_arr_1)) {

                if (strstr($big_arr_1[($i - 1)], $v)) {

                    if (strlen($big_arr_1[($i - 1)]) == 7) {

                        $big_arr_1[$i] = $v;
                        $i ++;
                    } else {
                        $big_arr_1[($i - 1)] = $big_arr_1[$i - 1] . ',' . $v;
                    }
                } else {
                    $big_arr_1[$i] = $v;
                    $i ++;
                }
            } else {
                $big_arr_1[$i] = $v;
                $i ++;
            }
        }
    }
    return $big_arr_1;
}

function louzoushi($result, $daxiao)
{
    // 1,查询结果 3,第几球字段名 4,玩法
    $data = array();

    if ($daxiao == 'tmdx') {
        foreach ($result as $k => $v) {
            if ($v['na'] <= 24) {
                $data[] = '2';
            } elseif ($v['na'] > 24 && $v['na'] != 49) {
                $data[] = '1';
            } elseif ($v['na'] == 49) {
                    $data[] = '3';
            }
        }
    } elseif ($daxiao == 'tmds') {
        foreach ($result as $k => $v) {
            $v['na']%2!=0?$data[] = '1':$data[] = '2';
        }

    } elseif ($daxiao == 'tmt') {
        foreach ($result as $k => $v) {
        if ($v['na'] <= 24) {
            $v['na']%2!=0?$data[] = '1':$data[] = '2';

        } elseif ($v['na'] > 24 && $v['na'] != 49) {
             $v['na']%2!=0?$data[] = '3':$data[] = '4';
        }
        }
    }
    $big_arr_1 = array();

    if (! empty($data)) {
        $i = 0;
        foreach ($data as $k => $v) {
            if (! empty($big_arr_1)) {

                if (strstr($big_arr_1[($i - 1)], $v)) {

                    if (strlen($big_arr_1[($i - 1)]) == 7) {

                        $big_arr_1[$i] = $v;
                        $i ++;
                    } else {
                        $big_arr_1[($i - 1)] = $big_arr_1[$i - 1] . ',' . $v;
                    }
                } else {
                    $big_arr_1[$i] = $v;
                    $i ++;
                }
            } else {
                $big_arr_1[$i] = $v;
                $i ++;
            }
        }
    }

    // foreach ($big_arr_1 as $k=>$v){
    // $big_arr_1[$k] = explode(',',$v);
    // }

    $list[$daxiao] = $big_arr_1;
    return $list;
}








