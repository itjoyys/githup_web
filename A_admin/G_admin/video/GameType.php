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
function GetGameType($v,$t=false,$CompanyType='og',$GameType='video'){
    //mg ag BBIN有电子
    $Type['video']['bbin'] = array(
    );
    $Type['game']['bbin'] = array(
    );
    $Type['video']['mg'] = array(
        'Diamond LG Baccarat'=>'Diamond LG Baccarat'
    );
    $Type['game']['mg'] = array(
        '3 Reel Slot Games'=>'3 Reel Slot Games',
        '5 Reel Slot Games'=>'5 Reel Slot Games',
        'Soft Game'=>'Soft Game'
    );
    $Type['video']['lebo'] = array(
    );
    $Type['game']['lebo'] = array(
    );
    $Type['video']['og'] = array(
        11=>'百家乐',
        12=>'龙虎',
        13=>'轮盘',
        14=>'骰宝',
        15=>'扑克',
        16=>'番摊',
    );
    $Type['game']['og'] = array(
    );
    $Type['video']['ct'] = array(
        1=>'百家乐',
        2=>'轮盘',
        3=>'骰宝',
        4=>'龙虎',
        5=>'番摊/骰宝翻摊',
        7=>'保险百家乐',
        9=>'色碟',
    );
    $Type['game']['ct'] = array(
    );
    $Type['video']['ag'] = array(
        'BAC'=>'百家乐',
        'CBAC'=>'包桌百家乐',
        'LINK'=>'连环百家乐',
        'DT'=>'龙虎',
        'SHB'=>'骰宝',
        'FT'=>'番攤',
    );
    $Type['game']['ag'] = array(
        'SL1'=>'巴西世界杯',
        'PK_J'=>'视频扑克(杰克高手)',
        'SL2'=>'疯狂水果店',
        'SL3'=>'3D 水族馆',
        'SL4'=>'极速赛车',
        'PKBJ'=>'新视频扑克(杰克高手)',
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