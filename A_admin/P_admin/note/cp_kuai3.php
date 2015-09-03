<?php
$Odd=M("c_odds_$cp_type",$db_config)->order('id asc')->limit("9")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($OddData);
for($ii=1;$ii<=11;$ii++){
    if($ii==1 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='和值';
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
                for($i=1;$i<=20;$i++){
                    if($i<=16) $mingxi_2=''.($i+2);
                    elseif($i==17) $mingxi_2='大';
                    elseif($i==18) $mingxi_2='小';
                    elseif($i==19) $mingxi_2='单';
                    elseif($i==20) $mingxi_2='双';
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
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="13_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
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
    elseif($ii==2 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='独胆';
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
                for($i=1;$i<=5;$i++){
                    $mingxi_2=''.$i;

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
    elseif($ii==3 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='豹子';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$title_menu?></th>
            </tr>
            <tr>
                <? for($i=1;$i<=3;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?
                }?> </tr>
            <tr>
                <?

                $zdmoney=$zdnum=array();
                for($i=1;$i<=7;$i++){
                    if($i==1)$mingxi_2='1,1,1';
                    elseif($i==2)$mingxi_2='2,2,2';
                    elseif($i==3)$mingxi_2='3,3,3';
                    elseif($i==4)$mingxi_2='4,4,4';
                    elseif($i==5)$mingxi_2='5,5,5';
                    elseif($i==6)$mingxi_2='6,6,6';
                    elseif($i==7)$mingxi_2='任意豹子';
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
                    <td class="cp_info"><div class=""><?=$i?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if($i==3) echo '</tr>';
                    elseif($i%3==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>
    <?
    }
    elseif($ii==4 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='两连';
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
                for($i=1;$i<=15;$i++){
                    if($i==1) $mingxi_2='1,2';
                    elseif($i==2) $mingxi_2='1,3';
                    elseif($i==3) $mingxi_2='1,4';
                    elseif($i==4) $mingxi_2='1,5';
                    elseif($i==5) $mingxi_2='1,6';
                    elseif($i==6) $mingxi_2='2,3';
                    elseif($i==7) $mingxi_2='2,4';
                    elseif($i==8) $mingxi_2='2,5';
                    elseif($i==9) $mingxi_2='2,6';
                    elseif($i==10) $mingxi_2='3,4';
                    elseif($i==11) $mingxi_2='3,5';
                    elseif($i==12) $mingxi_2='3,6';
                    elseif($i==13) $mingxi_2='4,5';
                    elseif($i==14) $mingxi_2='4,6';
                    elseif($i==15) $mingxi_2='5,6';
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
                    <td class="cp_info"><div class=""><?=$mingxi_2?></div> </td>
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
    elseif($ii==5 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='对子';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$title_menu?></th>
            </tr>
            <tr>
                <? for($i=1;$i<=3;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?
                }?> </tr>
            <tr>
                <?

                $zdmoney=$zdnum=array();
                for($i=1;$i<=6;$i++){
                    $mingxi_2=$i.','.$i;

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
                    <td class="cp_info"><div class=""><?=$i?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if($i==3) echo '</tr>';
                    elseif($i%3==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>
    <?
    }
    elseif($ii==6 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='短牌';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$title_menu?></th>
            </tr>
            <tr>
                <? for($i=1;$i<=3;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?
                }?> </tr>
            <tr>
                <?

                $zdmoney=$zdnum=array();
                for($i=1;$i<=6;$i++){
                    if($i==1) $mingxi_2='1,1';
                    elseif($i==2) $mingxi_2='2,2';
                    elseif($i==3) $mingxi_2='3,3';
                    elseif($i==4) $mingxi_2='4,4';
                    elseif($i==5) $mingxi_2='5,5';
                    elseif($i==6) $mingxi_2='6,6';

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
                    <td class="cp_info"><div class=""><?=$mingxi_2?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if($i==3) echo '</tr>';
                    elseif($i%3==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>
    <?
    }

}