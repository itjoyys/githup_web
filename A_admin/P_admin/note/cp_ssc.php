<?php

$Odd=M("c_odds_2",$db_config)->order('id asc')->limit("11")->select();
$OddData=M("c_bet",$_DBC['private'])->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and agent_id!='$testagent'")->select();
//print_r($OddData);
for($ii=1;$ii<=11;$ii++){

    if($ii<=5 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='第'.retun_sort($ii).'球';
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="20"><?=$title_menu?></th>
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
                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($title_menu==$r['mingxi_1'] && ''.$i==$r['mingxi_2']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class="num"><?=sprintf('%01d',$i);?></div> </td>
                    <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_<?=$i?>"><span class="cp_pl"><?=$Odd[$ii-1]['h'.($i+1)]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if(($i+1)==5) echo '</tr>';
                    elseif(($i+1)%5==0) echo '</tr><tr>';
                }
                ?>
            <tr>
                <?
                $zdmoney[$ii]['11']=$zdnum[$ii]['11']=0;
                foreach($OddData as $k =>$r){
                    if($title_menu==$r['mingxi_1'] && '大'==$r['mingxi_2']){
                        $zdnum[$ii]['11']++;
                        $zdmoney[$ii]['11']+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">大</div> </td>
                <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_大"><span class="cp_pl"><?=$Odd[$ii-1]['h11']?></span><br>
                    <span class="cp_zd "><?=$zdnum[$ii]['11']?>/<?=$zdmoney[$ii]['11']?></span>
                </td>
                <?
                $zdmoney[$ii]['12']=$zdnum[$ii]['12']=0;
                foreach($OddData as $k =>$r){
                    if($title_menu==$r['mingxi_1'] && '小'==$r['mingxi_2']){
                        $zdnum[$ii]['12']++;
                        $zdmoney[$ii]['12']+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">小</div> </td>
                <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_小"><span class="cp_pl"><?=$Odd[$ii-1]['h12']?></span><br>
                    <span class="cp_zd " ><?=$zdnum[$ii]['12']?>/<?=$zdmoney[$ii]['12']?></span>
                </td>
                <?
                $zdmoney[$ii]['13']=$zdnum[$ii]['13']=0;
                foreach($OddData as $k =>$r){
                    if($title_menu==$r['mingxi_1'] && '单'==$r['mingxi_2']){
                        $zdnum[$ii]['13']++;
                        $zdmoney[$ii]['13']+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">单</div> </td>
                <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_单"><span class="cp_pl"><?=$Odd[$ii-1]['h13']?></span><br>
                    <span class="cp_zd " ><?=$zdnum[$ii][13]?>/<?=$zdmoney[$ii][13]?></span>
                </td>
                <?
                $zdmoney[$ii]['14']=$zdnum[$ii]['14']=0;
                foreach($OddData as $k =>$r){
                    if($title_menu==$r['mingxi_1'] && '双'==$r['mingxi_2']){
                        $zdnum[$ii]['14']++;
                        $zdmoney[$ii]['14']+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">双</div> </td>
                <td class="cp_info getlist" id="2_第<?=retun_sort($ii)?>球_双"><span class="cp_pl"><?=$Odd[$ii-1]['h14']?></span><br>
                    <span class="cp_zd "><?=$zdnum[$ii][14]?>/<?=$zdmoney[$ii][14]?></span>
                </td>

            </tr>
        </table>
    <?
    }
    elseif($ii==6 && 'ball_'.$ii==$Odd[$ii-1]['type']){
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
                    if('總和,龍虎和'==$r['mingxi_1'] && '总和大'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">大</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_总和大">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h1']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '总和小'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">小</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_总和小">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h2']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '总和单'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">单</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_总和单">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h3']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '总和双'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">双</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_总和双">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h4']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '龙'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">龙</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_龙">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h5']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '虎'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">虎</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_虎">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h6']?></span><br>
                    <span class="cp_zd " ><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
                <?
                $zdmoney=$zdnum=0;
                foreach($OddData as $k =>$r){
                    if('總和,龍虎和'==$r['mingxi_1'] && '和'==$r['mingxi_2']){
                        $zdnum++;
                        $zdmoney+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="num">和</div> </td>
                <td class="cp_info getlist" id="2_總和,龍虎和_和">
                    <span class="cp_pl"><?=$Odd[$ii-1]['h7']?></span><br>
                    <span class="cp_zd "><?=$zdnum?>/<?=$zdmoney?></span>
                </td>
            </tr>
        </table>
    <?
    }
    elseif($ii>=7 && $ii<=9 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        $title_menu='';
        if($ii==7)$title_menu='前三';
        elseif($ii==8)$title_menu='中三';
        elseif($ii==9)$title_menu='后三';

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
                <? for($i=1;$i<=5;$i++){
                    if($i==1) $num_info='豹子';
                    elseif($i==2) $num_info='顺子';
                    elseif($i==3) $num_info='对子';
                    elseif($i==4) $num_info='半顺';
                    elseif($i==5) $num_info='杂六';

                    $zdmoney=$zdnum=array();

                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if($title_menu==$r['mingxi_1'] && $num_info==$r['mingxi_2']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }

                    ?>
                    <td class="cp_info"><div class="num"><?=$num_info?></div> </td>
                    <td class="cp_info getlist" id="2_<?=$title_menu.'_'.$num_info?>">
                        <span class="cp_pl"><?=$Odd[$ii-1]['h'.$i]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                <?
                }
                ?>
            </tr>
        </table>
    <?
    }
    elseif($ii==10 && 'ball_'.$ii==$Odd[$ii-1]['type']){
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10">斗牛</th>
            </tr>
            <tr>
                <? for($i=1;$i<=5;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?  }?> </tr>
            <tr>
                <? for($i=1;$i<=15;$i++){
                    if($i==10) $title_menu='没牛';
                    elseif($i>=1 and $i<=9) $title_menu='牛'.$i;
                    elseif($i==11) $title_menu='牛牛';
                    elseif ($i==12) $title_menu='牛大';
                    elseif ($i==13) $title_menu='牛小';
                    elseif ($i==14) $title_menu='牛单';
                    elseif ($i==15) $title_menu='牛双';

                    $zdmoney=$zdnum=array();

                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if('斗牛'==$r['mingxi_1'] && $title_menu==$r['mingxi_2']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    // if($zdmoney[$ii][$i]>0) $zdmoney[$ii][$i]="<span class='ball_red'>".$zdmoney[$ii][$i]."</span>";
                    //  if($zdnum[$ii][$i]>0) $zdnum[$ii][$i]="<span class='ball_red'>".$zdnum[$ii][$i]."</span>";
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class="num"><?=$title_menu;?></div> </td>
                    <td class="cp_info getlist" id="2_斗牛_<?=$title_menu?>">
                        <span class="cp_pl"><?=$Odd[$ii-1]['h'.$i]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    if($i==15) echo '</tr>';
                    elseif($i%5==0) echo '</tr><tr>';
                }?>
        </table>
    <?
    }
    elseif($ii==11  && 'ball_'.$ii==$Odd[$ii-1]['type'])
    {
        ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="16">梭哈</th>
            </tr>
            <tr>
                <? for($i=1;$i<=8;$i++){
                    ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <?  }?> </tr>
            <tr>
                <? for($i=1;$i<=8;$i++){
                    if($i==1) $title_menu='五条';
                    elseif($i==2) $title_menu='四条';
                    elseif($i==3) $title_menu='三条';
                    elseif ($i==4) $title_menu='散号';
                    elseif ($i==5) $title_menu='两对';
                    elseif ($i==6) $title_menu='一对';
                    elseif ($i==7) $title_menu='葫芦';
                    elseif ($i==8) $title_menu='顺子';

                    $zdmoney=$zdnum=array();

                    $zdmoney[$ii][$i]=$zdnum[$ii][$i]=0;
                    foreach($OddData as $k =>$r){
                        if('梭哈'==$r['mingxi_1'] && $title_menu==$r['mingxi_2']){
                            //echo $title_menu.'-'.$r['mingxi_2'].'-'. $i.'<br>';
                            $zdnum[$ii][$i]++;
                            $zdmoney[$ii][$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    // if($zdmoney[$ii][$i]>0) $zdmoney[$ii][$i]="<span class='ball_red'>".$zdmoney[$ii][$i]."</span>";
                    //  if($zdnum[$ii][$i]>0) $zdnum[$ii][$i]="<span class='ball_red'>".$zdnum[$ii][$i]."</span>";
                    //  print_r($zdmoney);
                    ?>
                    <td class="cp_info"><div class="num"><?=$title_menu;?></div> </td>
                    <td class="cp_info getlist" id="2_梭哈_<?=$title_menu?>">
                        <span class="cp_pl"><?=$Odd[$ii-1]['h'.$i]?></span><br>
                        <span class="cp_zd "><?=$zdnum[$ii][$i]?>/<?=$zdmoney[$ii][$i]?></span>
                    </td>
                    <?
                    //if($i==15) echo '</tr>';
                    //elseif($i%5==0) echo '</tr><tr>';
                }?>
            </tr>
        </table>
    <?
    }
}