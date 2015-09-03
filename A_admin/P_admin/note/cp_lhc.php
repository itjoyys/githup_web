<?php

$Odd=M("c_odds_$cp_type",$db_config)->field("class1,class2,class3,rate")->select();
//print_r($Odd);
$OddData=M("c_bet",$_DBC['private'])->field('`type`,mingxi_1,mingxi_2,mingxi_3,money')->where(" `site_id`='".SITEID."' and `type`='$cp_name' and qishu='$qs' and (mingxi_1='特码' or mingxi_1='正码' or mingxi_1='正特')  and agent_id!='$testagent'")->select();

function ColorBall($Num){
   $ballarray=array(
       1=>array(1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46),//red
       2=>array(3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48),  //blue
       3=>array(5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49)  //green
   );
    foreach($ballarray as $k =>$r){
        if(in_array($Num,$r)) {
            return $k;
        }
    }
    return false;
}
?>

<? $title_menu='特码'; ?>
        <table cellspacing="1" cellpadding="1" class="table_cp">
            <tr>
                <th colspan="10"><?=$title_menu?></th>
            </tr>
            <tr>
                <? for($i=1;$i<=5;$i++){ ?>
                    <th class="cp_info">号码</th><th class="cp_info">注单</th>
                <? }?> </tr>
            <tr>
                <?
                $zdmoney=$zdnum=array();
                for($i=1;$i<=58;$i++){
                    $pl='';

                    if($i<=49) $mingxi_2=''.$i;
                    elseif($i==50)$mingxi_2='单';
                    elseif($i==51)$mingxi_2='双';
                    elseif($i==52)$mingxi_2='大';
                    elseif($i==53)$mingxi_2='小';
                    elseif($i==54)$mingxi_2='合单';
                    elseif($i==55)$mingxi_2='合双';
                    elseif($i==56)$mingxi_2='红波';
                    elseif($i==57)$mingxi_2='绿波';
                    elseif($i==58)$mingxi_2='蓝波';
                    foreach($Odd as $k =>$r){
                        if($mingxi_2==$r['class3'] && $title_menu==$r['class1']) {
                            $pl=$r['rate'];
                            break;
                        }
                    }
                    $zdmoney[$i]=$zdnum[$i]=0;

                    foreach($OddData as $k =>$r){
                        if($title_menu==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){
                            $zdnum[$i]++;
                            $zdmoney[$i]+=$r['money'];
                            unset($OddData[$k]);
                        }
                    }
                    ?>
                    <td class="cp_info"><div class="ColorBall_<?=ColorBall($i)?>"><?=$mingxi_2?></div> </td>
                    <td class="cp_info getlist" id="_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
                        <span class="cp_zd "><?=$zdnum[$i]?>/<?=$zdmoney[$i]?></span>
                    </td>
                    <?
                    if($i==5) echo '</tr>';
                    elseif($i%5==0) echo '</tr><tr>';
                }
                ?>
            </tr>

        </table>

<? $title_menu='正码'; ?>
<table cellspacing="1" cellpadding="1" class="table_cp">
    <tr>
        <th colspan="10"><?=$title_menu?></th>
    </tr>
    <tr>
        <? for($i=1;$i<=5;$i++){ ?>
            <th class="cp_info">号码</th><th class="cp_info">注单</th>
        <? }?> </tr>
    <tr>
        <?
        $zdmoney=$zdnum=array();
        for($i=1;$i<=53;$i++){
            $pl='';

            if($i<=49) $mingxi_2=''.$i;
            elseif($i==50)$mingxi_2='总单';
            elseif($i==51)$mingxi_2='总双';
            elseif($i==52)$mingxi_2='总大';
            elseif($i==53)$mingxi_2='总小';
            foreach($Odd as $k =>$r){
                if($mingxi_2==$r['class3'] && $title_menu==$r['class1']) {
                    $pl=$r['rate'];
                    break;
                }
            }
            $zdmoney[$i]=$zdnum[$i]=0;

            foreach($OddData as $k =>$r){
                if($title_menu==$r['mingxi_1'] && $mingxi_2==$r['mingxi_2']){
                    $zdnum[$i]++;
                    $zdmoney[$i]+=$r['money'];
                    unset($OddData[$k]);
                }
            }
            ?>
            <td class="cp_info"><div class="ColorBall_<?=ColorBall($i)?>"><?=$mingxi_2?></div> </td>
            <td class="cp_info getlist" id="_<?=$title_menu?>_<?=$mingxi_2?>"><span class="cp_pl"><?=$pl?></span><br>
                <span class="cp_zd "><?=$zdnum[$i]?>/<?=$zdmoney[$i]?></span>
            </td>
            <?
            if($i==5) echo '</tr>';
            elseif($i%5==0) echo '</tr><tr>';
        }
        ?>
    </tr>

</table>



<table cellspacing="1" cellpadding="1" class="table_cp">
    <tr>
        <th class="cp_info lhccat change" data="1">正特一</th><th class="cp_info lhccat" data="2">正特二</th>
        <th class="cp_info lhccat" data="3">正特三</th><th class="cp_info lhccat" data="4">正特四</th>
        <th class="cp_info lhccat" data="5">正特五</th><th class="cp_info lhccat" data="6">正特六</th>
    </tr>
</table>
<script>
    $('.lhccat').mouseover(function(){
        var id=$(this).attr('data')
        $('.lhccat').removeClass('change');
        $(this).addClass('change');
        $('.lhctable').hide();
        $('#zt_'+id).show();
    })


</script>
<? for($ii=1;$ii<=6;$ii++){?>

    <? $title_menu='正特'.retun_sort($ii); ?>
    <table cellspacing="1" cellpadding="1" id="zt_<?=$ii?>" class="table_cp  lhctable <?=$ii==1?'':'none';?>">
        <tr>
            <th colspan="10"><?=$title_menu?></th>
        </tr>
        <tr>
            <? for($i=1;$i<=5;$i++){ ?>
                <th class="cp_info">号码</th><th class="cp_info">注单</th>
            <? }?> </tr>
        <tr>
            <?
            $zdmoney=$zdnum=array();
            for($i=1;$i<=49;$i++){
                $pl='';

                $mingxi_2=''.$i;
                $mingxi_3='正'.$ii.'特';
                foreach($Odd as $k =>$r){
                    if($mingxi_3==$r['class2'] && '正特'==$r['class1'] && $i==$r['class3']) {
                        $pl=$r['rate'];
                        break;
                    }
                }
                $zdmoney[$i]=$zdnum[$i]=0;

                foreach($OddData as $k =>$r){
                    if('正特'==$r['mingxi_1'] && $i==$r['mingxi_2'] && $mingxi_3==$r['mingxi_3']){

                        $zdnum[$i]++;
                        $zdmoney[$i]+=$r['money'];
                        unset($OddData[$k]);
                    }
                }
                ?>
                <td class="cp_info"><div class="ColorBall_<?=ColorBall($i)?>"><?=$mingxi_2?></div> </td>
                <td class="cp_info getlist" id="_正特_<?=$mingxi_2?>_<?=$mingxi_3?>"><span class="cp_pl"><?=$pl?></span><br>
                    <span class="cp_zd "><?=$zdnum[$i]?>/<?=$zdmoney[$i]?></span>
                </td>
                <?
                if($i==5) echo '</tr>';
                elseif($i%5==0) echo '</tr><tr>';
            }
            ?>
        </tr>
    </table>

<? }?>
