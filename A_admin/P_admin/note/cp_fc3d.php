<?php
$Odd=M("c_odds_$cp_type",$db_config)->order('id asc')->limit("9")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($OddData);
for($ii=1;$ii<=11;$ii++){

    if($ii<=3 && 'ball_'.$ii==$Odd[$ii-1]['type']){
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
                for($i=0;$i<=9;$i++){
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
                    ?>
                    <td class="cp_info"><div class=""><?=sprintf('%01d',$i);?></div> </td>
                    <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i+1)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if(($i+1)%5==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>
    <?
    }
    elseif($ii==4 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="14">总和,龙虎</th>
            </tr>
            <tr>
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
                    if('总和、龙虎'==$r['mingxi_1'] && '和'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="">和</div> </td>
                <td class="cp_info getlist" id="2_总和、龙虎_和">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h7']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>

            </tr>
        </table>
    <?
    }elseif($ii==5  && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $mingxi_1='3连';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$mingxi_1?></th>
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
                    if($i==1) {$mingxi_2='豹子';$pl=$Odd[$ii-1]['h'.$i];}
                    elseif($i==2) {$mingxi_2='顺子';$pl=$Odd[$ii-1]['h'.$i];}
                    elseif($i==3) {$mingxi_2='对子';$pl=$Odd[$ii-1]['h'.$i];}
                    elseif($i==4) {$mingxi_2='半顺'; $pl=$Odd[$ii-1]['h'.$i];}
                    elseif($i==5) {$mingxi_2='杂六'; $pl=$Odd[$ii-1]['h'.$i];}
                    // $mingxi_2=''.$i;
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($mingxi_1==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$mingxi_1?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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
    }elseif($ii==6  && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $mingxi_1='跨度';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$mingxi_1?></th>
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
                for($i=1;$i<=10;$i++){

                     $mingxi_2=''.($i-1);
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($mingxi_1==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$mingxi_1?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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
    elseif($ii==7  && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $mingxi_1='独胆';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$mingxi_1?></th>
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
                for($i=1;$i<=10;$i++){

                    $mingxi_2=''.($i-1);
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($mingxi_1==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$mingxi_1?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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
}