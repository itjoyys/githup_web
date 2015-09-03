<?php
function PrintJs(){
    $js='';
    foreach(GetGameType('',2) as $k=>$r){
        foreach($r as $a=>$b){
            $js .= '
        var '.$a.'["'.$k.'"] = new array(';
            $js_value='';
            foreach($b as $c=>$d){
                $js_value.="['$c':'$d'],";
            }
            $js.=rtrim($js_value,',').");";
        }

    }
    return json_encode(GetGameType('',2));
}
function porkeradd($v){

    if($v==11) $v='J';
    elseif($v==12) $v='Q';
    elseif($v==13) $v='K';
    return $v;
}

function poker($v){
    $a=$b=$c=$d=0;
    $r='';
    for($i=1;$i<=52;$i++){
        if($i<=13) {
              if($v==$i) $r='♠'.porkeradd($i);
        }
        elseif($i<=26) {
            $a++;
            if($v==$i) $r='<font color=red>♥</font>'.porkeradd($a);
        }
        elseif($i<=39) {
            $b++;
            if($v==$i) $r='♣'.porkeradd($b);
        }
        elseif($i<=52) {
            $c++;
            if($i==50)      $c='J';
            elseif($i==51)  $c='Q';
            elseif($i==52)  $c='K';
            if($v==$i) $r='<font color=red>♦</font>'.porkeradd($c);
        }
    }
    return $r;
}
function poker_lb($v){
    $a=$b=$c=$d=0;
    $r='';
    for($i=1;$i<=61;$i++){
        if($i<=13) {
            if($v==$i) $r='<font color=red>♦</font>'.porkeradd($i);
        }
        elseif($i<=29 && $i>=17) {
            $a++;
            if($v==$i) $r='♣'.porkeradd($a);
        }
        elseif($i<=45  && $i>=33) {
            $b++;
            if($v==$i) $r='<font color=red>♥</font>'.porkeradd($b);
        }
        elseif($i<=61  && $i>=49) {
            $c++;
            if($i==59)      $c='J';
            elseif($i==60)  $c='Q';
            elseif($i==61)  $c='K';
            if($v==$i) $r='♠'.porkeradd($c);
        }
    }
    return $r;
}
function GetGameType($v,$t=false,$CompanyType='og',$GameType='video'){
    //mg ag BBIN有电子
    $Type['video']['bbin'] = array(
    );
    $Type['game']['bbin'] = array(
    );
    $Type['video']['mg'] = array(
        'Diamond LG Baccarat'=>['Name'=>'Diamond LG Baccarat'],
    );
    $Type['game']['mg'] = array(
        '3 Reel Slot Games'=>['Name'=>'3 Reel Slot Games'],
        '5 Reel Slot Games'=>['Name'=>'5 Reel Slot Games'],
        'Soft Game'=>['Name'=>'Soft Game'],
    );
    $Type['video']['lebo'] = [
        1=>['Name'=>'百家乐'],
        2=>['Name'=>'轮盘'],
        3=>['Name'=>'骰宝'],
        4=>['Name'=>'龙虎'],
        5=>['Name'=>'番摊/骰宝翻摊'],
    ];
    $Type['game']['lebo'] = array(
    );
    $Type['video']['og'] = array(
        11=>['Name'=>'百家乐'],
        12=>['Name'=>'龙虎'],
        13=>['Name'=>'轮盘'],
        14=>['Name'=>'骰宝'],
        15=>['Name'=>'扑克'],
        16=>['Name'=>'番摊'],
    );
    $Type['game']['og'] = array(
    );
    $Type['video']['ct'] = [
        1=>['Name'=>'百家乐'],
        2=>['Name'=>'轮盘'],
        3=>['Name'=>'骰宝'],
        4=>['Name'=>'龙虎','Result'=>''],
        5=>['Name'=>'番摊/骰宝翻摊'],
        7=>['Name'=>'保险百家乐'],
        9=>['Name'=>'色碟'],
    ];
    $Type['game']['ct'] = array(
    );
    $Type['video']['ag'] = array(
        'BAC'=>['Name'=>'百家乐'],
        'CBAC'=>['Name'=>'包桌百家乐'],
        'LINK'=>['Name'=>'连环百家乐'],
        'DT'=>['Name'=>'龙虎'],
        'SHB'=>['Name'=>'骰宝'],
        'FT'=>['Name'=>'番攤'],
    );
    $Type['game']['ag'] = array(
        'SL1'=>['Name'=>'巴西世界杯'],
        'PK_J'=>['Name'=>'视频扑克(杰克高手)'],
        'SL2'=>['Name'=>'疯狂水果店'],
        'SL3'=>['Name'=>'3D 水族馆'],
        'SL4'=>['Name'=>'极速赛车'],
        'PKBJ'=>['Name'=>'新视频扑克(杰克高手)'],
        'FRU'=>['Name'=>'水果拉霸'],
    );
    if($t==1){
        return $Type[$GameType][$CompanyType];
    }elseif($t==2){
        return $Type;
    }else{
        $d=null;

        return $d;
    }
}
function ogwin($a){
    if($a==1) $b='<font color=blue>输</font>';
    elseif($a==2) $b='<font color=red>赢</font>';
    elseif($a==3) $b='和';
    else $b='';
    return $b;
}
function GetGameResult($v,$t=false){
    $Type=array(
        'a'=>'庄',
        'b'=>'庄,闲对',
        'c'=>'庄,庄对',
        'd'=>'庄,庄对,闲对',
        'e'=>'闲',
        'f'=>'闲,闲对',
        'g'=>'闲,庄对',
        'h'=>'闲,庄对,闲对',
        'i'=>'和',
        'j'=>'和,闲对',
        'k'=>'和,庄对',
        'l'=>'和,闲对,庄对',
    );
    if($t==true){
        return $Type;
    }else{
        $d=null;
        foreach($Type as $k=>$r){
            if($v==$k || $v==$r){
                $d['k']=$k;
                $d['v']=$r;
                break;
            }
        }
        return $d;
    }
}