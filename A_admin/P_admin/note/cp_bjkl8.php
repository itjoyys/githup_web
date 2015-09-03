<?php
$Odd=M("c_odds_$cp_type",$db_config)->order('id asc')->limit("9")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($OddData);
for($ii=1;$ii<=5;$ii++){

    if($ii==1 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='选一';
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
                for($i=1;$i<=80;$i++){
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
                    <td class="cp_info"><div class=""><?=sprintf('%01d',$i);?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$Odd[$ii-1]['h1']?></span><br>
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
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="8">选二/选三/选四/选五</th>
            </tr>
            <tr>
                <? for($i=1;$i<=4;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?
                }?> </tr>
            <tr>
                <?

                $zdmoney=$zdnum=array();
                for($i=1;$i<=4;$i++){
                    if($i==1) {$mingxi_1='选二';$pl=$Odd[$i]['h1'];}
                    elseif($i==2) {$mingxi_1='选三';$pl=$Odd[$i]['h1'];}
                    elseif($i==3) {$mingxi_1='选四';$pl=$Odd[$i]['h1'];}
                    elseif($i==4) {$mingxi_1='选五'; $pl=$Odd[$i]['h1'];}
                    $mingxi_2=''.$i;
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($mingxi_1==$r['mingxi_1']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_1;?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$mingxi_1?>"><span class="cp_pl"><?=$pl?></span><br>
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

    elseif($ii==3 && 'ball_6'==$Odd[5]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10">和值</th>
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
                    if($i==1) {$mingxi_2='总和大';$pl=$Odd[5]['h1'];}
                    elseif($i==2) {$mingxi_2='总和小';$pl=$Odd[5]['h2'];}
                    elseif($i==3) {$mingxi_2='总和单';$pl=$Odd[5]['h3'];}
                    elseif($i==4) {$mingxi_2='总和双'; $pl=$Odd[5]['h4'];}
                    elseif($i==5) {$mingxi_2='总和810'; $pl=$Odd[5]['h5'];}
                    // $mingxi_2=''.$i;
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if('和值'==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_和值_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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
    elseif($ii==4 && 'ball_7'==$Odd[6]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="6">上中下</th>
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
                for($i=1;$i<=3;$i++){
                    if($i==1) {$mingxi_2='上盘';$pl=$Odd[6]['h1'];}
                    elseif($i==2) {$mingxi_2='中盘';$pl=$Odd[6]['h2'];}
                    elseif($i==3) {$mingxi_2='下盘';$pl=$Odd[6]['h3'];}
                    // $mingxi_2=''.$i;
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if('上中下'==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_上中下_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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
    elseif($ii==5 && 'ball_8'==$Odd[7]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="6">奇和偶</th>
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
                for($i=1;$i<=3;$i++){
                    if($i==1) {$mingxi_2='奇盘';$pl=$Odd[7]['h1'];}
                    elseif($i==2) {$mingxi_2='和盘';$pl=$Odd[7]['h2'];}
                    elseif($i==3) {$mingxi_2='偶盘';$pl=$Odd[7]['h3'];}
                    // $mingxi_2=''.$i;
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if('奇和偶'==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){;
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class=""><?=$mingxi_2;?></div> </td>
                    <td class="cp_info getlist" id="2_奇和偶_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
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