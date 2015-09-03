<?php
$Odd=M("c_odds_$cp_type",$db_config)->order('id asc')->limit("11")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($Odd);
for($ii=1;$ii<=12;$ii++){

    if($ii<=10 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        if($ii==1) $title_menu='冠军';
        elseif($ii==2) $title_menu='亚军';
        elseif($ii>2 || $ii<11) $title_menu='第'.retun_sort($ii).'名';
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
                for($i=1;$i<=14;$i++){

                    if($i<11)$mingxi_2=''.$i;
                    elseif($i==11) $mingxi_2='大';
                    elseif($i==12) $mingxi_2='小';
                    elseif($i==13) $mingxi_2='单';
                    elseif($i==14) $mingxi_2='双';
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
                    <td class="cp_info"><div class=""><?=$i>10?$mingxi_2:sprintf('%01d',$i);?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
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
    elseif($ii==11 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="16">龍虎</th>
            </tr>
            <tr>
                <th colspan="2">1V10 龍虎</th>
                <th colspan="2">2V9 龍虎</th>
                <th colspan="2">3V8 龍虎</th>
                <th colspan="2">4V7 龍虎</th>
                <th colspan="2">5V6 龍虎</th>
            </tr>
            <tr>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
            </tr>
            <tr>
            <tr>
                <?
                $title_menu='龍虎';
                $zdmoney=$zdnum=array();
                for($i=1;$i<=10;$i++){

                    if($i==1)      {$mingxi_2='龙';$mingxi_3='1V10 龍虎';$pl=$Odd[0]['h15']; }
                    elseif($i==2) {$mingxi_2='龙';$mingxi_3='2V9 龍虎';$pl=$Odd[1]['h15'];}
                    elseif($i==3) {$mingxi_2='龙';$mingxi_3='3V8 龍虎';$pl=$Odd[2]['h15'];}
                    elseif($i==4) {$mingxi_2='龙';$mingxi_3='4V7 龍虎';$pl=$Odd[3]['h15'];}
                    elseif($i==5) {$mingxi_2='龙';$mingxi_3='5V6 龍虎';$pl=$Odd[4]['h15'];}
                    elseif($i==6) {$mingxi_2='虎';$mingxi_3='1V10 龍虎';$pl=$Odd[0]['h16'];}
                    elseif($i==7) {$mingxi_2='虎';$mingxi_3='2V9 龍虎';$pl=$Odd[1]['h16'];}
                    elseif($i==8) {$mingxi_2='虎';$mingxi_3='3V8 龍虎';$pl=$Odd[2]['h16'];}
                    elseif($i==9) {$mingxi_2='虎';$mingxi_3='4V7 龍虎';$pl=$Odd[3]['h16'];}
                    elseif($i==10) {$mingxi_2='虎';$mingxi_3='5V6 龍虎';$pl=$Odd[4]['h16'];}
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($title_menu==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2'] && $mingxi_3==$r['mingxi_3']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_龍虎_<?=$mingxi_2?>_<?=$mingxi_3?>"><span class="cp_pl"><?=$pl?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if($i==5) echo '</tr>';
                    elseif($i%5==0) echo '</tr><tr>';
                }
                ?>
            </tr>
            </tr>
        </table>
    <?
    }
    else print_r($Odd[$ii-1]['type']);
}