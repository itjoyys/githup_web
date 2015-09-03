<?php
$Odd=M("c_odds_$cp_type",$db_config)->order('id asc')->limit("9")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($OddData);
for($ii=1;$ii<=11;$ii++){

    if($ii<=8 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='第'.retun_sort($ii).'球';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$title_menu?></th>
            </tr>
            <tr>
                <? for($i=1;$i<=5;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?
                }?> </tr>
            <tr>
                <?
                $zdmoney=$zdnum=array();
                for($i=1;$i<=35;$i++){
                    if($i<21)$mingxi_2=''.$i;
                    elseif($i==21) $mingxi_2='大';
                    elseif($i==22) $mingxi_2='小';
                    elseif($i==23) $mingxi_2='单';
                    elseif($i==24) $mingxi_2='双';
                    elseif($i==25) $mingxi_2='尾大';
                    elseif($i==26) $mingxi_2='尾小';
                    elseif($i==27) $mingxi_2='合数单';
                    elseif($i==28) $mingxi_2='合数双';
                    elseif($i==29) $mingxi_2='东';
                    elseif($i==30) $mingxi_2='南';
                    elseif($i==31) $mingxi_2='西';
                    elseif($i==32) $mingxi_2='北';
                    elseif($i==33) $mingxi_2='中';
                    elseif($i==34) $mingxi_2='发';
                    elseif($i==35) $mingxi_2='白';
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($title_menu==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$i>20?$mingxi_2:sprintf('%01d',$i);?></div> </td>
                    <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                <?
                    if($i==5) echo '</tr>';
                    elseif($i%5==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>
    <?
    }
    elseif($ii==9 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="16">总和,龙虎</th>
            </tr>
            <tr>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
            </tr>
            <tr>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和大'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">大</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和大">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h1']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和小'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">小</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和小">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h2']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和单'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">单</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和单">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h3']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和双'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">双</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和双">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h4']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '龙'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">龙</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_龙">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h5']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '虎'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">虎</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_虎">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h6']?></span><br>
                    <span class="cp_zd " ><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和尾大'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">总和尾大</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和尾大">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h7']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('总和、龙虎'==$r['mingxi_1'] && '总和尾小'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">总和尾小</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_总和尾小">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h7']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
            </tr>
        </table>
    <?
    }
}