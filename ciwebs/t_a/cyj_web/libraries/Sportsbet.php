<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sportsbet  {



/*生成登录验证ＴＯＫＥＮ　ＫＥＹ*/
	public function make_token_key($uid){
		return 'token_'.md5('token_'.CLUSTER_ID.'_'.SITEID.'_'.$uid);
	}
    public function write_bet_info($ball_sort,$column,$master_guest,$bet_point,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$tid=0){
    $dm         =   explode("VS",$master_guest); //队名
    $qcrq       =   array("Match_Ho","Match_Ao"); //全场让球盘口
    $qcdx       =   array("Match_DxDpl","Match_DxXpl"); //全场大小盘口
    $ds         =   array("Match_DsDpl","Match_DsSpl"); //单双
    $info       =   "";
    if(strrpos($ball_sort,"足球") === 0 || $ball_sort == 'FT' || $ball_sort == 'FTP'){
        $bcrq   =   array("Match_BHo","Match_BAo"); //半场让球盘口
        $bcdx   =   array("Match_Bdpl","Match_Bxpl"); //半场大小盘口
        $qcdy   =   array("Match_BzM","Match_BzG","Match_BzH"); //全场独赢
        $bcdy   =   array("Match_Bmdy","Match_Bgdy","Match_Bhdy"); //半场独赢
        $sbbdz  =   array("Match_Hr_Bd10","Match_Hr_Bd20","Match_Hr_Bd21","Match_Hr_Bd30","Match_Hr_Bd31","Match_Hr_Bd32","Match_Hr_Bd40","Match_Hr_Bd41","Match_Hr_Bd42","Match_Hr_Bd43"); //上半波胆主
        $sbbdk  =   array("Match_Hr_Bdg10","Match_Hr_Bdg20","Match_Hr_Bdg21","Match_Hr_Bdg30","Match_Hr_Bdg31","Match_Hr_Bdg32","Match_Hr_Bdg40","Match_Hr_Bdg41","Match_Hr_Bdg42","Match_Hr_Bdg43"); //上半波胆客
        $sbbdp  =   array("Match_Hr_Bd00","Match_Hr_Bd11","Match_Hr_Bd22","Match_Hr_Bd33","Match_Hr_Bd44","Match_Hr_Bdup5"); //上半波胆平
        $bdz    =   array("Match_Bd10","Match_Bd20","Match_Bd21","Match_Bd30","Match_Bd31","Match_Bd32","Match_Bd40","Match_Bd41","Match_Bd42","Match_Bd43"); //波胆主
        $bdk    =   array("Match_Bdg10","Match_Bdg20","Match_Bdg21","Match_Bdg30","Match_Bdg31","Match_Bdg32","Match_Bdg40","Match_Bdg41","Match_Bdg42","Match_Bdg43"); //波胆客
        $bdp    =   array("Match_Bd00","Match_Bd11","Match_Bd22","Match_Bd33","Match_Bd44","Match_Bdup5"); //波胆平
        $rqs    =   array("Match_Total01Pl","Match_Total23Pl","Match_Total46Pl","Match_Total7upPl"); //入球数
        $bqc    =   array("Match_BqMM","Match_BqMH","Match_BqMG","Match_BqHM","Match_BqHH","Match_BqHG","Match_BqGM","Match_BqGH","Match_BqGG"); //半全场
        
        if(in_array($column,$qcrq) || in_array($column,$bcrq)){ //让球
            if(in_array($column,$qcrq))     $info   .=  "让球-";
            else    $info   .=  "上半场让球-";
            
            if($match_showtype ==   "H")    $info   .=  "主让$match_rgg-";
            else    $info   .=  "客让$match_rgg-";
            
            if($column == "Match_Ho" || $column == "Match_BHo") $info .= $dm[0];
            else    $info   .=  $dm[1];
            
        }elseif(in_array($column,$qcdx) || in_array($column,$bcdx)){ //大小
            if(in_array($column,$qcdx)){
                $info       .=  "大小-";
                if($column  ==  "Match_DxDpl")  $info   .=  "O";
                else $info  .=  "U";
            }else{
                $info       .=  "上半场大小-";
                if($column  ==  "Match_Bdpl")   $info   .=  "O";
                else $info  .=  "U";
            }
            $info           .=  $match_dxgg;
        }elseif(in_array($column,$qcdy) || in_array($column,$bcdy)){ //独赢
            if(in_array($column,$qcdy))         $info   .=  "标准盘-";
            else    $info   .=  "上半场标准盘-";
            
            if(     $column == "Match_BzM" || $column == "Match_Bmdy") $info    .=  $dm[0]."-独赢";
            elseif( $column == "Match_BzG" || $column == "Match_Bgdy") $info    .=  $dm[1]."-独赢";
            else    $info   .=  "和局";
        }elseif(in_array($column,$ds)){ //单双
            $info           .=  "单双-";
            if($column      == "Match_DsDpl")   $info .= "单";
            else    $info   .=  "双";
        }elseif(in_array($column,$sbbdz) || in_array($column,$sbbdk) || in_array($column,$sbbdp) || in_array($column,$bdz) || in_array($column,$bdk) || in_array($column,$bdp)){ //波胆
            if(in_array($column,$sbbdz) || in_array($column,$sbbdk) || in_array($column,$sbbdp)) $info  .=  "上半波胆-";
            else    $info   .=  "波胆-";
            
            if(strrpos($column,"up5")){
                $info       .=  "UP5";
            }else{
                $z           =  substr($column,-2,1);
                $k           =  substr($column,-1,1);
                if(in_array($column,$sbbdz) || in_array($column,$bdz))  $info   .=  $z.":".$k;
                else $info  .=  $k.":".$z;
            }
        }elseif(in_array($column,$rqs)){ //入球数
            $info           .=  "入球数-";
            if(strrpos($column,"7up")){
                $info       .=  "7UP";
            }else{
                $info       .=  substr($column,-4,1)."~".substr($column,-3,1);
            }
        }elseif(in_array($column,$bqc)){ //半全场
            $info           .=  "半全场-";
            $n1              = "".substr($column,-2,1);
            $n2              = "".substr($column,-1,1);
            $n1              = ($n1 === "H" ? "和" : ($n1 === "M" ? "主" : "客"));
            $n2              = ($n2 === "H" ? "和" : ($n2 === "M" ? "主" : "客"));
            $info           .=  $n1."/".$n2;
        }
        if($ball_sort       ==  "足球滚球" || $ball_sort=='FTP'){
            $info           .=  "(".$match_nowscore.")";
        }
        $info               .=  "@".$bet_point;
    
    }elseif(strrpos($ball_sort,"篮球") === 0){
        if(in_array($column,$qcrq)){
            $info           .=  "让分-";
            if($match_showtype ==   "H") $info  .=  "主让$match_rgg-";
            else    $info   .=  "客让$match_rgg-";
            
            if($column      == "Match_Ho")$info .= $dm[0];
            else    $info   .=  $dm[1];
            
        }elseif(in_array($column,$qcdx)){
            $info           .=  "大小-";
            if($column      ==  "Match_DxDpl")$info .=  "O$match_dxgg";
            else $info      .=  "U$match_dxgg";
            
        }elseif(in_array($column,$ds)){ //单双
            $info           .=  "单双-";
            if($column      == "Match_DsDpl")   $info .= "单";
            else    $info   .=  "双";
        }
        $info               .=  "@".$bet_point;
    }elseif(strrpos($ball_sort,"棒球") === 0 || strrpos($ball_sort,"网球") === 0 || strrpos($ball_sort,"排球") === 0){
        $qcdy   =   array("Match_BzM","Match_BzG","Match_BzH"); //全场独赢
        if(in_array($column,$qcrq)){
            $info           .=  "让球-";
            if($match_showtype ==   "H") $info  .=  "主让$match_rgg-";
            else    $info   .=  "客让$match_rgg-";
            
            if($column      == "Match_Ho")$info .= $dm[0];
            else    $info   .=  $dm[1];
            
        }elseif(in_array($column,$qcdx)){
            $info           .=  "大小-";
            if($column      ==  "Match_DxDpl")$info .=  "O$match_dxgg";
            else $info      .=  "U$match_dxgg";
            
        }elseif(in_array($column,$ds)){ //单双
            $info           .=  "单双-";
            if($column      == "Match_DsDpl")   $info .= "单";
            else    $info   .=  "双";
        }elseif(in_array($column,$qcdy)){ //独赢
            $info           .=  "标准盘-";
            
            if(     $column == "Match_BzM") $info   .=  $dm[0]."-独赢";
            elseif( $column == "Match_BzG") $info   .=  $dm[1]."-独赢";
        }
        $info               .=  "@".$bet_point;
    }elseif(strrpos($ball_sort,"金融") === 0 || strrpos($ball_sort,"冠军") === 0){
        global $mysqli;
        $query  =   $mysqli->query("SELECT team_name FROM t_guanjun_team where tid=$tid limit 1");
        
        $row    =   $query->fetch_array();//print_r($row);exit;
        if(strrpos($ball_sort,"金融") === 0) $row['team_name']=strtolower(str_replace(" ",'',$row['team_name']));
        $info   =   $row['team_name'].'@'.$bet_point;
    }
    
    return $info;
}
 /**
 * 轉換賠率
 * @param odd_f
 * @param H_ratio
 * @param C_ratio
 * @param showior
 * @return
 */
 
	public function chg_ior($odd_f,$iorH,$iorC,$showior){
     
    $iorH = floor(($iorH*1000)+0.001) / 1000;
   
    $iorC = floor(($iorC*1000)+0.001) / 1000;
   
    $ior=Array();
    if($iorH < 11) $iorH *=1000;
    if($iorC < 11) $iorC *=1000;
    
    //iorH=parseFloat(iorH);
    //iorC=parseFloat(iorC);
    switch($odd_f){
	    case "H":   //香港變盤(輸水盤)
	        $ior = $this->get_HK_ior($iorH,$iorC);
	        break;
	    case "M":   //馬來盤
	        $ior = $this->get_MA_ior($iorH,$iorC);
	        break;
	    case "I" :  //印尼盤
	        $ior = $this->get_IND_ior($iorH,$iorC);
	        break;
	    case "E":   //歐洲盤
	        $ior = $this->get_EU_ior($iorH,$iorC);
	        break;
	    default:    //香港盤
	        $ior[0]=$iorH ;
	        $ior[1]=$iorC ;
    }
    $ior[0]/=1000;
    $ior[1]/=1000;
    
    $ior[0]=$this->double_format($ior[0]);
    $ior[1]=$this->double_format($ior[1]); 
    //alert("odd_f="+odd_f+",iorH="+iorH+",iorC="+iorC+",ouH="+ior[0]+",ouC="+ior[1]);
    return $ior;
}
/*
去正負號做小數第幾位捨去
進來的值是小數值
*/
	public function double_format($double_num){
		return $double_num>0 ? sprintf("%.2f",$double_num) : $double_num<0 ? sprintf("%.2f",$double_num) : 0;
}

	public function Decimal_point($tmpior,$show){
    $sign="";
    $sign =(($tmpior < 0)?"Y":"N");
    $tmpior = (floor(abs($tmpior) * $show + 1 / $show )) / $show;
    return ($tmpior * (($sign =="Y")? -1:1)) ;
}
    public function get_EU_ior($H_ratio, $C_ratio){
    $out_ior= Array();
    $out_ior=$this->get_HK_ior($H_ratio,$C_ratio);
    $H_ratio=$out_ior[0];
    $C_ratio=$out_ior[1];
    if($H_ratio==0 ) $out_ior[0]=0;
    else $out_ior[0]=$H_ratio+1000;
    if($C_ratio==0 ) $out_ior[1]=0;
    else $out_ior[1]=$C_ratio+1000;
    return $out_ior;
}
/**
 * 換算成印尼盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
public function get_IND_ior( $H_ratio, $C_ratio){
    $out_ior= Array();

    $out_ior = $this->get_HK_ior($H_ratio,$C_ratio);

    $H_ratio =$out_ior[0];
    $C_ratio =$out_ior[1];
    $H_ratio = $H_ratio/1000;
    $C_ratio = $C_ratio/1000;

    if($H_ratio < 1 && $H_ratio!=0){
        $H_ratio=(-1) / $H_ratio;
    
    }
    if($C_ratio < 1 && $C_ratio!=0){
        $C_ratio=(-1) / $C_ratio;
    }
    $out_ior[0]=$H_ratio*1000;
    $out_ior[1]=$C_ratio*1000;
    //echo $H_ratio.$C_ratio;exit;
    return $out_ior;
}
     
/**
 * 換算成輸水盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */

public function get_HK_ior( $H_ratio, $C_ratio){
     $out_ior= Array();
     $line=$lowRatio=$nowRatio=$highRatio=null;
     $nowType="";
    if ($H_ratio <= 1000 && $C_ratio <= 1000){
        $out_ior[0]=$H_ratio;
        $out_ior[1]=$C_ratio;
        return $out_ior;
    }
    $line=2000 - ( $H_ratio + $C_ratio );
    
    if ($H_ratio > $C_ratio){ 
        $lowRatio=$C_ratio;
        $nowType = "C";
    }else{
        $lowRatio = $H_ratio;
        $nowType = "H";
    }
    if (((2000 - $line) - $lowRatio) > 1000){
        //對盤馬來盤
        $nowRatio = ($lowRatio + $line) * (-1);
    }else{
        //對盤香港盤
        $nowRatio=(2000 - $line) - $lowRatio;  
    }
    
    if ($nowRatio < 0){
        $highRatio = floor(abs(1000 / $nowRatio) * 1000) ;
    }else{
        $highRatio = (2000 - $line - $nowRatio) ;
    }
    if ($nowType == "H"){
        $out_ior[0]=$lowRatio;
        $out_ior[1]=$highRatio;
    }else{
        $out_ior[0]=$highRatio;
        $out_ior[1]=$lowRatio;
    }
    return $out_ior;
}
	    /**
	 * 換算成馬來盤賠率
	 * @param $H_ratio
	 * @param $C_ratio
	 * @return
	 */
	public function get_MA_ior( $H_ratio, $C_ratio){
	    $out_ior= Array();
	    $line=$lowRatio=$highRatio=null;
	    $nowType="";
	    if (($H_ratio <= 1000 && $C_ratio <= 1000)){
	        $out_ior[0]=$H_ratio;
	        $out_ior[1]=$C_ratio;
	        return $out_ior;
	    }
	    $line=2000 - ( $H_ratio + $C_ratio );
	    if ($H_ratio > $C_ratio){ 
	        $lowRatio = $C_ratio;
	        $nowType = "C";
	    }else{
	        $lowRatio = $H_ratio;
	        $nowType = "H";
	    }
	    $highRatio = ($lowRatio + $line) * (-1);
	    if ($nowType == "H"){
	        $out_ior[0]=$lowRatio;
	        $out_ior[1]=$highRatio;
	    }else{
	        $out_ior[0]=$highRatio;
	        $out_ior[1]=$lowRatio;
	    }
	    return $out_ior;
	}
 
 	public function checkxe($set,$ball_sort,$tzx){
        if($ball_sort=='FTP') {
            $ball_sort='FT';
            $tzx='gq_'.$tzx;
        }elseif($ball_sort=='BKP') {
            $ball_sort='BK';
            $tzx='gq_'.$tzx;
        }
		 
		$dz_db=array();//单注
		$dc_db=array();//单场
		$dm_db=array();//最小值
		$data =array();
		$dz_db['足球单式']['让球']			=$set['ft']['rq']['single_note_max'] 			 =$set['ft']['rq']['single_note_max'];
		$dm_db['足球单式']['让球']			=$set['ft']['rq']['min']			   			 =$set['ft']['rq']['min'];
		$dc_db['足球单式']['让球']			=$set['ft']['rq']['single_field_max']			 =$set['ft']['rq']['single_field_max'];
		$dz_db['足球单式']['单双']			=$set['ft']['ds']['single_note_max'] 			 =$set['ft']['ds']['single_note_max'];
		$dc_db['足球单式']['单双']			=$set['ft']['ds']['single_field_max']			 =$set['ft']['ds']['single_field_max'];
		$dm_db['足球单式']['单双']			=$set['ft']['ds']['min']			   			 =$set['ft']['ds']['min'];
		$dz_db['足球单式']['大小']			=$set['ft']['dx']['single_note_max'] 			 =$set['ft']['dx']['single_note_max'];
		$dc_db['足球单式']['大小']			=$set['ft']['dx']['single_field_max']			 =$set['ft']['dx']['single_field_max'];
		$dm_db['足球单式']['大小']			=$set['ft']['dx']['min']			    		 =$set['ft']['dx']['min'];
		$dz_db['足球单式']['标准盘']		=$set['ft']['dy']['single_note_max']			 =$set['ft']['dy']['single_note_max'];
		$dc_db['足球单式']['标准盘']		=$set['ft']['dy']['single_field_max']			 =$set['ft']['dy']['single_field_max'];
		$dm_db['足球单式']['标准盘']		=$set['ft']['dy']['min']						 =$set['ft']['dy']['min'];
		$dz_db['足球上半场']['上半场让球']  =$set['ft']['sbrq']['single_note_max']	 		 =$set['ft']['rq']['single_note_max'];
		$dc_db['足球上半场']['上半场让球']  =$set['ft']['sbrq']['single_field_max']    		 =$set['ft']['rq']['single_field_max'];
		$dm_db['足球上半场']['上半场让球']  =$set['ft']['sbrq']['min']			     		 =$set['ft']['rq']['min'];
		$dz_db['足球上半场']['上半场大小']  =$set['ft']['sbdx']['single_note_max']   		 =$set['ft']['dx']['single_note_max'];
		$dc_db['足球上半场']['上半场大小']  =$set['ft']['sbdx']['single_field_max']  		 =$set['ft']['dx']['single_field_max'];
		$dm_db['足球上半场']['上半场大小']  =$set['ft']['sbdx']['min']			     		 =$set['ft']['dx']['min'];
		$dz_db['足球上半场']['上半场标准盘']=$set['ft']['sbdy']['single_note_max'] =$set['ft']['dy']['single_note_max'];
		$dc_db['足球上半场']['上半场标准盘']=$set['ft']['sbdy']['single_field_max']=$set['ft']['dy']['single_field_max'];
		$dm_db['足球上半场']['上半场标准盘']=$set['ft']['sbdy']['min']			 =$set['ft']['dy']['min'];
		$dz_db['足球单式']['波胆']=$set['ft']['bd']['single_note_max']			 =$set['ft']['bd']['single_note_max'];
		$dc_db['足球单式']['波胆']=$set['ft']['bd']['single_field_max']			 =$set['ft']['bd']['single_field_max'];
		$dm_db['足球单式']['波胆']=$set['ft']['bd']['min']						 =$set['ft']['bd']['min'];
		$dz_db['足球单式']['入球数']=$set['ft']['zrq']['single_note_max']		 =$set['ft']['zrq']['single_note_max'];
		$dc_db['足球单式']['入球数']=$set['ft']['zrq']['single_field_max']		 =$set['ft']['zrq']['single_field_max'];
		$dm_db['足球单式']['入球数']=$set['ft']['zrq']['min']					 =$set['ft']['zrq']['min'];
		$dz_db['足球单式']['半全场']=$set['ft']['bqc']['single_note_max']		 =$set['ft']['bqc']['single_note_max'];
		$dc_db['足球单式']['半全场']=$set['ft']['bqc']['single_field_max']		 =$set['ft']['bqc']['single_field_max'];
		$dm_db['足球单式']['半全场']=$set['ft']['bqc']['min']					 =$set['ft']['bqc']['min'];
		$dz_db['足球早餐']['让球']=$set['ft']['rq']['single_note_max']			 =$set['ft']['rq']['single_note_max'];
		$dc_db['足球早餐']['让球']=$set['ft']['rq']['single_field_max']			 =$set['ft']['rq']['single_field_max'];
		$dm_db['足球早餐']['让球']=$set['ft']['rq']['min']						 =$set['ft']['rq']['min'];
		$dz_db['足球早餐']['单双']=$set['ft']['ds']['single_note_max']			 =$set['ft']['ds']['single_note_max'];
		$dc_db['足球早餐']['单双']=$set['ft']['ds']['single_field_max']			 =$set['ft']['ds']['single_field_max'];
		$dm_db['足球早餐']['单双']=$set['ft']['ds']['min']						 =$set['ft']['ds']['min'];
		$dz_db['足球早餐']['大小']=$set['ft']['dx']['single_note_max']			 =$set['ft']['dx']['single_note_max'];
		$dc_db['足球早餐']['大小']=$set['ft']['dx']['single_field_max']			 =$set['ft']['dx']['single_field_max'];
		$dm_db['足球早餐']['大小']=$set['ft']['dx']['min']						 =$set['ft']['dx']['min'];
		$dz_db['足球早餐']['标准盘']=$set['ft']['dy']['single_note_max']		 =$set['ft']['dy']['single_note_max'];
		$dc_db['足球早餐']['标准盘']=$set['ft']['dy']['single_field_max']        =$set['ft']['dy']['single_field_max'];
		$dm_db['足球早餐']['标准盘']=$set['ft']['dy']['min']				     =$set['ft']['dy']['min'];
		$dz_db['足球早餐']['上半场让球']=$set['ft']['rq']['single_note_max']	 =$set['ft']['rq']['single_note_max'];
		$dc_db['足球早餐']['上半场让球']=$set['ft']['rq']['single_field_max']    =$set['ft']['rq']['single_field_max'];
		$dm_db['足球早餐']['上半场让球']=$set['ft']['rq']['min']				 =$set['ft']['rq']['min'];
		$dz_db['足球早餐']['上半场大小']=$set['ft']['rq']['single_note_max']	 =$set['ft']['rq']['single_note_max'];
		$dc_db['足球早餐']['上半场大小']=$set['ft']['rq']['single_field_max']	 =$set['ft']['rq']['single_field_max'];
		$dm_db['足球早餐']['上半场大小']=$set['ft']['rq']['min']				 =$set['ft']['rq']['min'];
		$dz_db['足球早餐']['上半场标准盘']=$set['ft']['dy']['single_note_max']	 =$set['ft']['dy']['single_note_max'];
		$dc_db['足球早餐']['上半场标准盘']=$set['ft']['dy']['single_field_max']  =$set['ft']['dy']['single_field_max'];
		$dm_db['足球早餐']['上半场标准盘']=$set['ft']['dy']['min']				 =$set['ft']['dy']['min'];
		$dz_db['足球早餐']['波胆']=$set['ft']['bd']['single_note_max']			 =$set['ft']['bd']['single_note_max'];
		$dc_db['足球早餐']['波胆']=$set['ft']['bd']['single_field_max']			 =$set['ft']['bd']['single_field_max'];
		$dm_db['足球早餐']['波胆']=$set['ft']['bd']['min']						 =$set['ft']['bd']['min'];
		$dz_db['足球早餐']['入球数']=$set['ft']['zrq']['single_note_max']		 =$set['ft']['zrq']['single_note_max'];
		$dc_db['足球早餐']['入球数']=$set['ft']['zrq']['single_field_max']		 =$set['ft']['zrq']['single_field_max'];
		$dm_db['足球早餐']['入球数']=$set['ft']['zrq']['min']					 =$set['ft']['zrq']['min'];
		$dz_db['足球早餐']['半全场']=$set['ft']['bqc']['single_note_max']		 =$set['ft']['bqc']['single_note_max'];
		$dc_db['足球早餐']['半全场']=$set['ft']['bqc']['single_field_max']       =$set['ft']['bqc']['single_field_max'];
		$dm_db['足球早餐']['半全场']=$set['ft']['bqc']['min']					 =$set['ft']['bqc']['min'];
		$dz_db['足球滚球']['让球']=$set['ft']['gq_rq']['single_note_max']		 =$set['ft']['gq_rq']['single_note_max'];
		$dc_db['足球滚球']['让球']=$set['ft']['gq_rq']['single_field_max']		 =$set['ft']['gq_rq']['single_field_max'];
		$dm_db['足球滚球']['让球']=$set['ft']['gq_rq']['min']					 =$set['ft']['gq_rq']['min'];
		$dz_db['足球滚球']['大小']=$set['ft']['gq_dx']['single_note_max']		 =$set['ft']['gq_dx']['single_note_max'];
		$dc_db['足球滚球']['大小']=$set['ft']['gq_dx']['single_field_max']		 =$set['ft']['gq_dx']['single_field_max'];
		$dm_db['足球滚球']['大小']=$set['ft']['gq_dx']['min']					 =$set['ft']['gq_dx']['min'];
		$dz_db['足球滚球']['上半场让球']=$set['ft']['gq_rq']['single_note_max']  =$set['ft']['gq_rq']['single_note_max'];
		$dc_db['足球滚球']['上半场让球']=$set['ft']['gq_rq']['single_field_max'] =$set['ft']['gq_rq']['single_field_max'];
		$dm_db['足球滚球']['上半场让球']=$set['ft']['gq_rq']['min']				 =$set['ft']['gq_rq']['min'];
		$dz_db['足球滚球']['上半场大小']=$set['ft']['gq_dx']['single_note_max']  =$set['ft']['gq_dx']['single_note_max'];
		$dc_db['足球滚球']['上半场大小']=$set['ft']['gq_dx']['single_field_max'] =$set['ft']['gq_dx']['single_field_max'];
		$dm_db['足球滚球']['上半场大小']=$set['ft']['gq_dx']['min']				 =$set['ft']['gq_dx']['min'];
		$dz_db['足球滚球']['标准盘']=$set['ft']['gq_dy']['single_note_max']		 =$set['ft']['gq_dy']['single_note_max'];
		$dc_db['足球滚球']['标准盘']=$set['ft']['gq_dy']['single_field_max']	 =$set['ft']['gq_dy']['single_field_max'];
		$dm_db['足球滚球']['标准盘']=$set['ft']['gq_dy']['min']					 =$set['ft']['gq_dy']['min'];
		$dz_db['足球滚球']['上半场标准盘']=$set['ft']['gq_dy']['single_note_max']=$set['ft']['gq_dy']['single_note_max'];
		$dc_db['足球滚球']['上半场标准盘']=$set['ft']['gq_dy']['single_field_max']=$set['ft']['gq_dy']['single_field_max'];
		$dm_db['足球滚球']['上半场标准盘']=$set['ft']['gq_dy']['min']			 =$set['ft']['gq_dy']['min'];
		$dz_db['篮球单式']['单双']=$set['bk']['ds']['single_note_max']			 =$set['bk']['ds']['single_note_max'];
		$dc_db['篮球单式']['单双']=$set['bk']['ds']['single_field_max'] 		 =$set['bk']['ds']['single_field_max'];
		$dm_db['篮球单式']['单双']=$set['bk']['ds']['min']						 =$set['bk']['ds']['min'];
		$dz_db['篮球单式']['让球']=$set['bk']['rq']['single_note_max']			 =$set['bk']['rq']['single_note_max'];
		$dc_db['篮球单式']['让球']=$set['bk']['rq']['single_field_max']			 =$set['bk']['rq']['single_field_max'];
		$dm_db['篮球单式']['让球']=$set['bk']['rq']['min']						 =$set['bk']['rq']['min'];
		$dz_db['篮球单式']['大小']=$set['bk']['dx']['single_note_max']			 =$set['bk']['dx']['single_note_max'];
		$dc_db['篮球单式']['大小']=$set['bk']['dx']['single_field_max']			 =$set['bk']['dx']['single_field_max'];
		$dm_db['篮球单式']['大小']=$set['bk']['dx']['min']						 =$set['bk']['dx']['min'];
		$dz_db['篮球滚球']['让球']=$set['bk']['rq']['single_note_max']			 =$set['bk']['rq']['single_note_max'];
		$dc_db['篮球滚球']['让球']=$set['bk']['rq']['single_field_max']			 =$set['bk']['rq']['single_field_max'];
		$dm_db['篮球滚球']['让球']=$set['bk']['rq']['min']						 =$set['bk']['rq']['min'];
		$dz_db['篮球滚球']['大小']=$set['bk']['dx']['single_note_max']			 =$set['bk']['dx']['single_note_max'];
		$dc_db['篮球滚球']['大小']=$set['bk']['dx']['single_field_max']			 =$set['bk']['dx']['single_field_max'];
		$dm_db['篮球滚球']['大小']=$set['bk']['dx']['min']						 =$set['bk']['dx']['min'];
		$dz_db['网球单式']['标准盘']=$set['tn']['dy']['single_note_max']		 =$set['tn']['dy']['single_note_max'];
		$dc_db['网球单式']['标准盘']=$set['tn']['dy']['single_field_max']		 =$set['tn']['dy']['single_field_max'];
		$dm_db['网球单式']['标准盘']=$set['tn']['dy']['min']				 	 =$set['tn']['dy']['min'];
		$dz_db['网球单式']['让球']=$set['tn']['rq']['single_note_max']			 =$set['tn']['rq']['single_note_max'];
		$dc_db['网球单式']['让球']=$set['tn']['rq']['single_field_max']			 =$set['tn']['rq']['single_field_max'];
		$dm_db['网球单式']['让球']=$set['tn']['rq']['min']						 =$set['tn']['rq']['min'];
		$dz_db['网球单式']['单双']=$set['tn']['ds']['single_note_max']			 =$set['tn']['ds']['single_note_max'];
		$dc_db['网球单式']['单双']=$set['tn']['ds']['single_field_max']			 =$set['tn']['ds']['single_field_max'];
		$dm_db['网球单式']['单双']=$set['tn']['ds']['min']						 =$set['tn']['ds']['min'];
		$dz_db['网球单式']['大小']=$set['tn']['dx']['single_note_max']			 =$set['tn']['dx']['single_note_max'];
		$dc_db['网球单式']['大小']=$set['tn']['dx']['single_field_max']			 =$set['tn']['dx']['single_field_max'];
		$dm_db['网球单式']['大小']=$set['tn']['dx']['min']						 =$set['tn']['dx']['min'];
		$dz_db['排球单式']['标准盘']=$set['vb']['dy']['single_note_max']		 =$set['vb']['dy']['single_note_max'];
		$dc_db['排球单式']['标准盘']=$set['vb']['dy']['single_field_max']		 =$set['vb']['dy']['single_field_max'];
		$dm_db['排球单式']['标准盘']=$set['vb']['dy']['min']					 =$set['vb']['dy']['min'];
		$dz_db['排球单式']['让球']=$set['vb']['rq']['single_note_max']			 =$set['vb']['rq']['single_note_max'];
		$dc_db['排球单式']['让球']=$set['vb']['rq']['single_field_max']			 =$set['vb']['rq']['single_field_max'];
		$dm_db['排球单式']['让球']=$set['vb']['rq']['min']						 =$set['vb']['rq']['min'];
		$dz_db['排球单式']['大小']=$set['vb']['dx']['single_note_max']			 =$set['vb']['dx']['single_note_max'];
		$dc_db['排球单式']['大小']=$set['vb']['dx']['single_field_max']			 =$set['vb']['dx']['single_field_max'];
		$dm_db['排球单式']['大小']=$set['vb']['dx']['min']						 =$set['vb']['dx']['min'];
		$dz_db['排球单式']['单双']=$set['vb']['ds']['single_note_max']			 =$set['vb']['ds']['single_note_max'];
		$dc_db['排球单式']['单双']=$set['vb']['ds']['single_field_max']			 =$set['vb']['ds']['single_field_max'];
		$dm_db['排球单式']['单双']=$set['vb']['ds']['min']						 =$set['vb']['ds']['min'];
		$dz_db['棒球单式']['让球']=$set['bs']['rq']['single_note_max']			 =$set['bs']['rq']['single_note_max'];
		$dc_db['棒球单式']['让球']=$set['bs']['rq']['single_field_max']			 =$set['bs']['rq']['single_field_max'];
		$dm_db['棒球单式']['让球']=$set['bs']['rq']['min']						 =$set['bs']['rq']['min'];
		$dz_db['棒球单式']['大小']=$set['bs']['dx']['single_note_max']			 =$set['bs']['dx']['single_note_max'];
		$dc_db['棒球单式']['大小']=$set['bs']['dx']['single_field_max']			 =$set['bs']['dx']['single_field_max'];
		$dm_db['棒球单式']['大小']=$set['bs']['dx']['min']						 =$set['bs']['dx']['min'];
		$dz_db['棒球单式']['单双']=$set['bs']['ds']['single_note_max']			 =$set['bs']['ds']['single_note_max'];
		$dc_db['棒球单式']['单双']=$set['bs']['ds']['single_field_max']			 =$set['bs']['ds']['single_field_max'];
		$dm_db['棒球单式']['单双']=$set['bs']['ds']['min']						 =$set['bs']['ds']['min'];
		$dz_db['冠军']=100000;
		$dc_db['冠军']=100000;
		$dm_db['冠军']=10;
		$dz_db['金融']=100000;
		$dc_db['金融']=100000;
		$dm_db['金融']=10;
		$dz_db['串关']=$set['ft']['zhgg']['single_note_max']					 =$set['ft']['zhgg']['single_note_max'];
		$dc_db['串关']=$set['ft']['zhgg']['single_field_max']					 =$set['ft']['zhgg']['single_field_max'];
		$dm_db['串关']=$set['ft']['zhgg']['min']								 =$set['ft']['zhgg']['min'];
		$dz_db['未定义']=100000;
		$dc_db['未定义']=100000;
		$dm_db['未定义']=10;
		if(!$set){
			$data[0]=$set[strtolower($ball_sort)][$tzx]['single_field_max']=0; //单场
			$data[1]=$set[strtolower($ball_sort)][$tzx]['single_note_max']=0;//单注
			$data[2]=$set[strtolower($ball_sort)][$tzx]['min']=0;// 最小
		}else{
			$data[0]=$set[strtolower($ball_sort)][$tzx]['single_field_max']; //单场
			$data[1]=$set[strtolower($ball_sort)][$tzx]['single_note_max'];//单注
			$data[2]=$set[strtolower($ball_sort)][$tzx]['min'];// 最小
		}
		
		return ($data);
	}
}
