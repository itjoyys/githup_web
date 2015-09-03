<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../class/user.php");
include_once("common.php");
include_once("../../include/public_config.php");
include_once("../../include/public_sport_user.php");
include_once("../common/pager.class.php");
if($_GET['type']){
	$type=$_GET['type'];
}else{
	$type='danshi';
}


$sqlwhere='';
if(@$_GET["lsm"]<>""){
	$leaguename	 =	explode("$",urldecode($_GET["lsm"]));
	$v			 =	(count($leaguename)>1 ? 'and (' : 'and' );
	$sqlwhere	.=	" $v match_name='".$leaguename[0]."'";
	for($is=1; $is<count($leaguename)-1; $is++){
		$sqlwhere.=	" or match_name='".$leaguename[$is]."'";
	}
	$sqlwhere	.=	(count($leaguename)>1 ? ')' : '' );
}
$date		=	date("Y-m-d",time());

if($_GET['date']){
	$date	=	$_GET['date'];	
}
//单式
if($type=='danshi'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match WHERE Match_Type=1 and Match_Date='".date("m-d",strtotime($date))."' and Match_HalfId is not null order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}



	$sql		=	"select id from bet_match WHERE Match_Type=1 and Match_Date='".date("m-d",strtotime($date))."' and Match_HalfId is not null ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest, Match_RGG, Match_Name, Match_IsLose, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];  
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];  
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];   
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];    
			$mdate	=	$rows["Match_Date"]."<br/>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;   
			$rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
			$json["db"][$i]["Match_BzM"]		=	$a;  
			double_format($rows["Match_Ho"])<>""?$b=double_format($rows["Match_Ho"]):$b=0;
			$json["db"][$i]["Match_Ho"]			=	$b;    
			$rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
			$json["db"][$i]["Match_DxDpl"]		=	$c;   
			$rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
			$json["db"][$i]["Match_DsDpl"]		=	$d;    
			$rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
			$json["db"][$i]["Match_BzG"]		=	$e;   
			$rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
			$json["db"][$i]["Match_Ao"]			=	$f;   
			$rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
			$json["db"][$i]["Match_DxXpl"]		=	$g;   
			$rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
			$json["db"][$i]["Match_DsSpl"]		=	$h;    
			$rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
			$json["db"][$i]["Match_BzH"]		=	$k;     
			$rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
			$json["db"][$i]["Match_RGG"]		=	$j;   
			$rows["Match_DxGG"]<>""?$m="O".$rows["Match_DxGG"]:$m=0;
			$json["db"][$i]["Match_DxGG1"]		=	$m;    
			$json["db"][$i]["Match_ShowType"]	=	$rows["Match_ShowType"];
			$rows["Match_DxGG"]<>""?$n="U".$rows["Match_DxGG"]:$n=0;
			$json["db"][$i]["Match_DxGG2"]		=	$n;
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"]; 			
			 $i++;
            $mid.=$rows["Match_ID"].',';
		}
	}

    if($mid){
        $mid	=	rtrim($mid,",");
        $sql	=	"select match_id,point_column,bet_money from `k_bet` where match_type<2 and match_id in($mid) and `status`!=3";
        $query	=	$mysqlit->query($sql);
        while($rows = $query->fetch_array()){
            $arr[$rows["match_id"]][$rows["point_column"]]['num']	=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
            $arr_m[$rows["match_id"]]								+=	$rows["bet_money"];
        }
    }
}
//上半场
if($type=='shangbanchang'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match where Match_Type=1 and match_date='".date("m-d",strtotime($date))."' and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}


	$sql		=	"select id from bet_match where Match_Type=1 and match_date='".date("m-d",strtotime($date))."' and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}

	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	$totalPage=ceil($sum/$pagenum);
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

	$myPage=new Pager($sum,intval($CurrentPage),$pagenum);

	$pageStr= $myPage->GetPagerContent();

	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}

	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bmdy, Match_BHo,  Match_Bdpl, Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_Bdxpk,Match_MasterID,Match_GuestID FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
		
		$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
		$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"]; 
		$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
		$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
		$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
		$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
		$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
		if($rows["Match_IsLose"]==1){
			$mdate.= "<br><font color=red>滾球</font>";
		}
		$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
		$json["db"][$i]["Match_Bmdy"]		=	$rows["Match_Bmdy"];     ///////////     5
		$json["db"][$i]["Match_BHo"]		=	$rows["Match_BHo"];     ///////////     6
		$json["db"][$i]["Match_Bdpl"]		=	$rows["Match_Bdpl"];     ///////////     7
		$json["db"][$i]["Match_Bgdy1"]		=	$rows["Match_Bgdy"];     ///////////     8
		$json["db"][$i]["Match_Bgdy2"]		=	$rows["Match_Bgdy"];     ///////////     9
		$json["db"][$i]["Match_BAo"]		=	$rows["Match_BAo"];     ///////////     10
		$json["db"][$i]["Match_Bxpl"]		=	$rows["Match_Bxpl"];     ///////////     11
		$json["db"][$i]["Match_Bhdy1"]		=	$rows["Match_Bhdy"];     ///////////     12
		$json["db"][$i]["Match_Bhdy2"]		=	$rows["Match_Bhdy"];     ///////////     13
		$json["db"][$i]["Match_BRpk"]		=	$rows["Match_BRpk"];     ///////////     14
		$json["db"][$i]["Match_Bdxpk1"]		=	"O".$rows["Match_Bdxpk"];     ///////////     15
		$json["db"][$i]["Match_Hr_ShowType"]=	$rows["Match_Hr_ShowType"];     ///////////     16
		$json["db"][$i]["Match_Bdxpk2"]		=	"U".$rows["Match_Bdxpk"];     ///////////     17
		$i++;
        $mid.=$rows["Match_ID"].',';
		}
	}
    if($mid){
        $mid	=	rtrim($mid,",");
        $sql	=	"select match_id,point_column,bet_money from `k_bet` where match_type<2 and match_id in($mid) and `status`!=3";
        $query	=	$mysqlit->query($sql);
        while($rows = $query->fetch_array()){
            $arr[$rows["match_id"]][$rows["point_column"]]['num']	=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
            $arr_m[$rows["match_id"]]								+=	$rows["bet_money"];
        }
    }
}
//全场波胆
if($type=='bodan'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match where Match_Type=1 and Match_IsShowbd=1 and match_date='".date("m-d",strtotime($date))."' and Match_Bd21>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}



	$sql		=	"select id from bet_match where Match_Type=1 and Match_IsShowbd=1 and match_date='".date("m-d",strtotime($date))."' and Match_Bd21>0 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bd10, Match_Bd20, Match_Bd21, Match_Bd30, Match_Bd31, Match_Bd32, Match_Bd40, Match_Bd41, Match_Bd42, Match_Bd43, Match_Bd00, Match_Bd11, Match_Bd22, Match_Bd33, Match_Bd44, Match_Bdup5, Match_Bdg10, Match_Bdg20, Match_Bdg21, Match_Bdg30, Match_Bdg31, Match_Bdg32, Match_Bdg40, Match_Bdg41, Match_Bdg42, Match_Bdg43 FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
		
			$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
			$json["db"][$i]["Match_Bd10"]		=	$rows["Match_Bd10"];     ///////////     5
			$json["db"][$i]["Match_Bd20"]		=	$rows["Match_Bd20"];     ///////////     6
			$json["db"][$i]["Match_Bd21"]		=	$rows["Match_Bd21"];     ///////////     7
			$json["db"][$i]["Match_Bd30"]		=	$rows["Match_Bd30"];     ///////////     8
			$json["db"][$i]["Match_Bd31"]		=	$rows["Match_Bd31"];     ///////////     9
			$json["db"][$i]["Match_Bd32"]		=	$rows["Match_Bd32"];     ///////////     10
			$json["db"][$i]["Match_Bd40"]		=	$rows["Match_Bd40"];     ///////////     11
			$json["db"][$i]["Match_Bd41"]		=	$rows["Match_Bd41"];     ///////////     12
			$json["db"][$i]["Match_Bd42"]		=	$rows["Match_Bd42"];     ///////////     13
			$json["db"][$i]["Match_Bd43"]		=	$rows["Match_Bd43"];     ///////////     14
			$json["db"][$i]["Match_Bd00"]		=	$rows["Match_Bd00"];     ///////////     15
			$json["db"][$i]["Match_Bd11"]		=	$rows["Match_Bd11"];     ///////////     16
			$json["db"][$i]["Match_Bd22"]		=	$rows["Match_Bd22"];     ///////////     17
			$json["db"][$i]["Match_Bd33"]		=	$rows["Match_Bd33"];     ///////////     18
			$json["db"][$i]["Match_Bd44"]		=	$rows["Match_Bd44"];     ///////////     19
			$json["db"][$i]["Match_Bdup5"]		=	$rows["Match_Bdup5"];     ///////////     20
			$json["db"][$i]["Match_Bdg10"]		=	$rows["Match_Bdg10"];     ///////////     21
			$json["db"][$i]["Match_Bdg20"]		=	$rows["Match_Bdg20"];     ///////////     22
			$json["db"][$i]["Match_Bdg21"]		=	$rows["Match_Bdg21"];     ///////////     23
			$json["db"][$i]["Match_Bdg30"]		=	$rows["Match_Bdg30"];     ///////////     24
			$json["db"][$i]["Match_Bdg31"]		=	$rows["Match_Bdg31"];     ///////////     25
			$json["db"][$i]["Match_Bdg32"]		=	$rows["Match_Bdg32"];     ///////////     26
			$json["db"][$i]["Match_Bdg40"]		=	$rows["Match_Bdg40"];     ///////////     27
			$json["db"][$i]["Match_Bdg41"]		=	$rows["Match_Bdg41"];     ///////////     28
			$json["db"][$i]["Match_Bdg42"]		=	$rows["Match_Bdg42"];     ///////////     29
			$json["db"][$i]["Match_Bdg43"]		=	$rows["Match_Bdg43"];     ///////////     30
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"]; 			
			$i++;
            $mid.=$rows["Match_ID"].',';
        }
    }
    if($mid){
        $mid	=	rtrim($mid,",");
        $sql	=	"select match_id,point_column,bet_money from `k_bet` where match_type<2 and match_id in($mid) and `status`!=3";
        $query	=	$mysqlit->query($sql);
        while($rows = $query->fetch_array()){
            $arr[$rows["match_id"]][$rows["point_column"]]['num']	=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
            $arr_m[$rows["match_id"]]								+=	$rows["bet_money"];
        }
    }
}

//上半场波胆
if($type=='shangbanbodan'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match where match_date='".date("m-d",strtotime($date))."' and Match_Hr_Bd10>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}


	$sql		=	"select id from bet_match where match_date='".date("m-d",strtotime($date))."' and Match_Hr_Bd10>0 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn ';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Hr_Bd10, Match_Hr_Bd20, Match_Hr_Bd21, Match_Hr_Bd30, Match_Hr_Bd31, Match_Hr_Bd32, Match_Hr_Bd40, Match_Hr_Bd41, Match_Hr_Bd42, Match_Hr_Bd43, Match_Hr_Bd00, Match_Hr_Bd11, Match_Hr_Bd22, Match_Hr_Bd33, Match_Hr_Bd44, Match_Hr_Bdup5, Match_Hr_Bdg10, Match_Hr_Bdg20, Match_Hr_Bdg21, Match_Hr_Bdg30, Match_Hr_Bdg31, Match_Hr_Bdg32, Match_Hr_Bdg40, Match_Hr_Bdg41, Match_Hr_Bdg42, Match_Hr_Bdg43 FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
			$json["db"][$i]["Match_Bd10"]		=	$rows["Match_Hr_Bd10"];     ///////////     5
			$json["db"][$i]["Match_Bd20"]		=	$rows["Match_Hr_Bd20"];     ///////////     6
			$json["db"][$i]["Match_Bd21"]		=	$rows["Match_Hr_Bd21"];     ///////////     7
			$json["db"][$i]["Match_Bd30"]		=	$rows["Match_Hr_Bd30"];     ///////////     8
			$json["db"][$i]["Match_Bd31"]		=	$rows["Match_Hr_Bd31"];     ///////////     9
			$json["db"][$i]["Match_Bd32"]		=	$rows["Match_Hr_Bd32"];     ///////////     10
			$json["db"][$i]["Match_Bd40"]		=	$rows["Match_Hr_Bd40"];     ///////////     11
			$json["db"][$i]["Match_Bd41"]		=	$rows["Match_Hr_Bd41"];     ///////////     12
			$json["db"][$i]["Match_Bd42"]		=	$rows["Match_Hr_Bd42"];     ///////////     13
			$json["db"][$i]["Match_Bd43"]		=	$rows["Match_Hr_Bd43"];     ///////////     14
			$json["db"][$i]["Match_Bd00"]		=	$rows["Match_Hr_Bd00"];     ///////////     15
			$json["db"][$i]["Match_Bd11"]		=	$rows["Match_Hr_Bd11"];     ///////////     16
			$json["db"][$i]["Match_Bd22"]		=	$rows["Match_Hr_Bd22"];     ///////////     17
			$json["db"][$i]["Match_Bd33"]		=	$rows["Match_Hr_Bd33"];     ///////////     18
			$json["db"][$i]["Match_Bd44"]		=	$rows["Match_Hr_Bd44"];     ///////////     19
			$json["db"][$i]["Match_Bdup5"]		=	$rows["Match_Hr_Bdup5"];     ///////////     20
			$json["db"][$i]["Match_Bdg10"]		=	$rows["Match_Hr_Bdg10"];     ///////////     21
			$json["db"][$i]["Match_Bdg20"]		=	$rows["Match_Hr_Bdg20"];     ///////////     22
			$json["db"][$i]["Match_Bdg21"]		=	$rows["Match_Hr_Bdg21"];     ///////////     23
			$json["db"][$i]["Match_Bdg30"]		=	$rows["Match_Hr_Bdg30"];     ///////////     24
			$json["db"][$i]["Match_Bdg31"]		=	$rows["Match_Hr_Bdg31"];     ///////////     25
			$json["db"][$i]["Match_Bdg32"]		=	$rows["Match_Hr_Bdg32"];     ///////////     26
			$json["db"][$i]["Match_Bdg40"]		=	$rows["Match_Hr_Bdg40"];     ///////////     27
			$json["db"][$i]["Match_Bdg41"]		=	$rows["Match_Hr_Bdg41"];     ///////////     28
			$json["db"][$i]["Match_Bdg42"]		=	$rows["Match_Hr_Bdg42"];     ///////////     29
			$json["db"][$i]["Match_Bdg43"]		=	$rows["Match_Hr_Bdg43"];     ///////////     30
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"]; 			
			$i++;
		}
	}
}

//总入球
if($type=='ruqiushu'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match where Match_Type=1 and Match_IsShowt=1 and Match_Total01Pl>0 and match_date='".date("m-d",strtotime($date))."' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}


	$sql		=	"select id from bet_match where Match_Type=1 and Match_IsShowt=1 and Match_Total01Pl>0 and match_date='".date("m-d",strtotime($date))."'".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BzM, Match_Total01Pl, Match_Total23Pl, Match_Total46Pl, Match_Total7upPl, Match_BzG, Match_BzH FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
			$json["db"][$i]["Match_BzM"]		=	$rows["Match_BzM"];     ///////////  5
			$json["db"][$i]["Match_Total01Pl"]	=	$rows["Match_Total01Pl"];     ///////////   6
			$json["db"][$i]["Match_Total23Pl"]	=	$rows["Match_Total23Pl"];     ///////////    7
			$json["db"][$i]["Match_Total46Pl"]	=	$rows["Match_Total46Pl"];     ///////////     8
			$json["db"][$i]["Match_Total7upPl"]	=	$rows["Match_Total7upPl"];     ///////////   9
			$json["db"][$i]["Match_BzG"]		=	$rows["Match_BzG"];     ///////////    10
			$json["db"][$i]["Match_BzH"]		=	$rows["Match_BzH"];     ///////////     11			
			$i++;
		}
	}
}
//半全场
function NOnull($str){
	return $str>0?sprintf("%.2f",$str):0;
}
if($type=='banquanchang'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_BqMM>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}


	$sql		=	"select id from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_BqMM>0 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BqMM, Match_BqMH, Match_BqMG, Match_BqHM, Match_BqHH, Match_BqHG, Match_BqGM, Match_BqGH, Match_BqGG  FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];       //  0
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];   //  1
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];    //  2
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     //  3
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;     //               4
			$json["db"][$i]["Match_BqMM"]		=	NOnull($rows["Match_BqMM"]);  //  5
			$json["db"][$i]["Match_BqMH"]		=	NOnull($rows["Match_BqMH"]);      //  6
			$json["db"][$i]["Match_BqMG"]		=	NOnull($rows["Match_BqMG"]);      //  7
			$json["db"][$i]["Match_BqHM"]		=	NOnull($rows["Match_BqHM"]);      //  8
			$json["db"][$i]["Match_BqHH"]		=	NOnull($rows["Match_BqHH"]);      //  9
			$json["db"][$i]["Match_BqHG"]		=	NOnull($rows["Match_BqHG"]);      //  10
			$json["db"][$i]["Match_BqGM"]		=	NOnull($rows["Match_BqGM"]);      //  11
			$json["db"][$i]["Match_BqGH"]		=	NOnull($rows["Match_BqGH"]);      //  12
			$json["db"][$i]["Match_BqGG"]		=	NOnull($rows["Match_BqGG"]);      //  13

			$i++;
		}
	}
}
//滚球
function NOkong($str2){
	return $str2<>""?$str2:0;
}
if($type=='gunqiu'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_IsLose='1' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}

	$sql		=	"select id from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_IsLose='1' ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BqMM, Match_BqMH, Match_BqMG, Match_BqHM, Match_BqHH, Match_BqHG, Match_BqGM, Match_BqGH, Match_BqGG,Match_Ho,Match_DxDpl,Match_BHo,Match_Bdpl,Match_Ao,Match_DxXpl,Match_BAo,Match_Bxpl,Match_RGG,Match_BRpk,Match_ShowType,Match_Hr_ShowType,Match_DxGG,Match_Bdxpk,Match_HRedCard,Match_GRedCard,Match_NowScore,Match_BzM,Match_BzG,Match_BzH,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_MasterID,Match_GuestID  FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     		   //  0
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];         //  1
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];          //  2
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     	   //  3
			$json["db"][$i]["Match_Time"]		=	$rows["Match_Time"];           //  4
			$json["db"][$i]["Match_Ho"]			=	NOnull($rows["Match_Ho"]);     //  5
			$json["db"][$i]["Match_DxDpl"]		=	NOnull($rows["Match_DxDpl"]);  //  6
			$json["db"][$i]["Match_BHo"]		=	NOnull($rows["Match_BHo"]);    //  7
			$json["db"][$i]["Match_Bdpl"]		=	NOnull($rows["Match_Bdpl"]);   //  8
			$json["db"][$i]["Match_Ao"]			=	NOnull($rows["Match_Ao"]);     //  9
			$json["db"][$i]["Match_DxXpl"]		=	NOnull($rows["Match_DxXpl"]);  //  10
			$json["db"][$i]["Match_BAo"]		=	NOnull($rows["Match_BAo"]);    //  11
			$json["db"][$i]["Match_Bxpl"]		=	NOnull($rows["Match_Bxpl"]);   //  12
			$json["db"][$i]["Match_RGG"]		=	$rows["Match_RGG"];    //  13
			$json["db"][$i]["Match_BRpk"]		=	$rows["Match_BRpk"];    // 14
			$json["db"][$i]["Match_ShowType"]	=	$rows["Match_ShowType"]; // 15   
			$json["db"][$i]["Match_Hr_ShowType"]=	$rows["Match_Hr_ShowType"]; // 16
			$json["db"][$i]["Match_DxGG"]		=	"O".$rows["Match_DxGG"];     	// 17
			$json["db"][$i]["Match_Bdxpk"]		=	"O".$rows["Match_Bdxpk"];      //   18
			$json["db"][$i]["Match_DxGG1"]		=	"U".$rows["Match_DxGG"];        //  19
			$json["db"][$i]["Match_Bdxpk2"]		=	"U".$rows["Match_Bdxpk"];     	//  20
			$json["db"][$i]["Match_HRedCard"]	=	NOkong($rows["Match_HRedCard"]); // 21
			$json["db"][$i]["Match_GRedCard"]	=	NOkong($rows["Match_GRedCard"]); // 22
			$json["db"][$i]["Match_NowScore"]	=	NOkong($rows["Match_NowScore"]); // 23
			$json["db"][$i]["Match_BzM"]		=	NOkong($rows["Match_BzM"]);     //  24
			$json["db"][$i]["Match_BzG"]		=	NOkong($rows["Match_BzG"]);     //  25
			$json["db"][$i]["Match_BzH"]		=	NOkong($rows["Match_BzH"]);     //  26
			$json["db"][$i]["Match_Bmdy"]		=	NOkong($rows["Match_Bmdy"]);    //  27
			$json["db"][$i]["Match_Bgdy"]		=	NOkong($rows["Match_Bgdy"]);    //  28
			$json["db"][$i]["Match_Bhdy"]		=	NOkong($rows["Match_Bhdy"]);    //  29
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"]; 
			$i++;
		}
	}
}
//开赛

if($type=='kaisai'){
	$match_name=array();
	$sqlname		=	"select match_name from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_CoverDate<'".date("Y-m-d H:i:s")."' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}

	$sql		=	"select id from bet_match WHERE match_date='".date("m-d",strtotime($date))."' and Match_CoverDate<'".date("Y-m-d H:i:s")."' ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$thisPage = 1;
	if(@$_GET['page']){
	  $thisPage = $_GET['page'];
	}
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; 
	
	$totalPage=ceil($sum/$pagenum);
	
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	$myPage=new pager($sum,intval($CurrentPage),$pagenum);
	$pageStr= $myPage->GetPagerContent();
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"SELECT Match_ID,Match_Date, Match_Time, Match_Master, Match_Guest, Match_RGG, Match_Name, Match_IsLose, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_DsDpl, Match_DsSpl,match_js  FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		$arr	=	array();
		$arr_m		=	array();
		$mid		=	'';
		while($rows = $query->fetch_array()){ 
			$arr[$rows["Match_ID"]]["Match_ID"]			=	$rows["Match_ID"]; //赛事id
			$arr[$rows["Match_ID"]]["Match_Date"]		=	$rows["Match_Date"];
			$arr[$rows["Match_ID"]]["Match_Time"]		=	$rows["Match_Time"];
			$arr[$rows["Match_ID"]]["Match_Master"]		=	$rows["Match_Master"];
			$arr[$rows["Match_ID"]]["Match_Guest"]		=	$rows["Match_Guest"];
			$arr[$rows["Match_ID"]]["Match_RGG"]		=	$rows["Match_RGG"];
			$arr[$rows["Match_ID"]]["Match_Name"]		=	$rows["Match_Name"];
			$arr[$rows["Match_ID"]]["Match_IsLose"]		=	$rows["Match_IsLose"];
			$arr[$rows["Match_ID"]]["Match_BzM"]		=	$rows["Match_BzM"];
			$arr[$rows["Match_ID"]]["Match_BzG"]		=	$rows["Match_BzG"];
			$arr[$rows["Match_ID"]]["Match_BzH"]		=	$rows["Match_BzH"];
			$arr[$rows["Match_ID"]]["Match_DxDpl"]		=	$rows["Match_DxDpl"];
			$arr[$rows["Match_ID"]]["Match_DxXpl"]		=	$rows["Match_DxXpl"];
			$arr[$rows["Match_ID"]]["Match_DxGG"]		=	$rows["Match_DxGG"];
			$arr[$rows["Match_ID"]]["Match_Ho"]			=	$rows["Match_Ho"];
			$arr[$rows["Match_ID"]]["Match_Ao"]			=	$rows["Match_Ao"];
			$arr[$rows["Match_ID"]]["Match_MasterID"]	=	$rows["Match_MasterID"];
			$arr[$rows["Match_ID"]]["Match_GuestID"]	=	$rows["Match_GuestID"];
			$arr[$rows["Match_ID"]]["Match_ShowType"]	=	$rows["Match_ShowType"];
			$arr[$rows["Match_ID"]]["Match_DsDpl"]		=	$rows["Match_DsDpl"];
			$arr[$rows["Match_ID"]]["Match_DsSpl"]		=	$rows["Match_DsSpl"];
			$arr[$rows["Match_ID"]]["match_js"]			=	$rows["match_js"];
			$arr_m[$rows["Match_ID"]]					=	0;
			$mid.=$rows["Match_ID"].',';
		}
		
		if($mid){
	
			$mid	=	rtrim($mid,",");
			$sql	=	"select match_id,point_column,bet_money,match_type from `k_bet` where match_id in($mid) and `status`!=3";
			$query	=	$mysqlt->query($sql);
			while($rows = $query->fetch_array()){
				if($rows["match_type"] == 2){
					$arr[$rows["match_id"]][$rows["point_column"]]['gq']['num']		=	$arr[$rows["match_id"]][$rows["point_column"]]['gq']['num']+1;
					$arr[$rows["match_id"]][$rows["point_column"]]['gq']['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['gq']['money']+$rows["bet_money"];
				}else{
					$arr[$rows["match_id"]][$rows["point_column"]]['num']			=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
					$arr[$rows["match_id"]][$rows["point_column"]]['money']			=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
				}
				$arr_m[$rows["match_id"]]											+=	$rows["bet_money"];
			}
		}

		arsort($arr_m);
		
		
	}
}


?>

<?php require("../common_html/header.php");?>

<script>
function go(value){//alert(value);
	window.location.href=value;
}
//下拉框选中
$(document).ready(function(){
   $("#lsm").val('<?=$_GET['lsm']?>');
    $("#retime").val('<?=$_GET['time']?>');
	$("#type").val('sports_note.php?type=danshi');
	$("#date").val('<?=$_GET['date']?>');
})
</script>
<?
if($type=='danshi'){
	$type_name='單式';
}elseif($type=='shangbanchang'){
	$type_name='上半場';
}elseif($type=='gunqiu'){
	$type_name='滾球';
}elseif($type=='bodan'){
	$type_name='波膽';
}elseif($type=='shangbanbodan'){
	$type_name='上半波膽';
}elseif($type=='ruqiushu'){
	$type_name='總入球';
}elseif($type=='banquanchang'){
	$type_name='半全場';
}elseif($type=='kaisai'){
	$type_name='已开赛';
}
?>
<div  id="con_wrap">

<div  class="con_menu"  style="width:99%;">
<div  class="input_002">足球-<?=$type_name?></div>

<div>
&nbsp;赛事选择:
<select  name="here" id="type" class="za_select" onchange="go($(this).val());" > 
	<option  value="sports_note.php?type=danshi">足球賽事</option> 
	<option  value="ftz.php?type=danshi">足球早餐賽事</option>
	<option  value="bk.php?type=danshi">籃球賽事</option> 
	<option  value="bkz.php">籃球早餐賽事</option> 
	<option  value="tennis.php">網球賽事</option>
	<option  value="volleyball.php">排球賽事</option>
	<option  value="baseball.php">棒球賽事</option> 
	<option  value="guanjun.php">冠軍賽事</option> 
</select>
<script>
var times=180;
//刷新
function shuaxin(){
	var time=$("#retime").val();//alert(time);
	if(time=='180'){
		times=times-1;
		if(times<1){
			var type='<?=$type?>';
			var lsm=$("#lsm").val();
			location.href='sports_note.php?type='+type+'&lsm='+lsm+'&time=180';
		}else{
			$("#time").html(times);
		}
		setTimeout("shuaxin()",1000);
	}
}
$(document).ready(function(){

	var time='<?=$_GET['time']?>';
	if(time){
		setTimeout("shuaxin()",1000);
	}
});
</script>
重新整理:
<select  id="retime"  name="retime"   class="za_select"  onchange="shuaxin();">
	<option  value="-1"  selected="">不更新</option>
	<option  value="180">180 sec</option>
</select>&nbsp;&nbsp;<span id="time"></span>


</div>
<div  id="dt_now">--美東時間:2014-11-29 04:36:31</div>
 &nbsp;&nbsp;
<a  href="sports_note.php?type=danshi" <?if($type=='danshi'){echo 'style="color:#f00"';}?>>單式</a>
<a  href="sports_note.php?type=shangbanchang" <?if($type=='shangbanchang'){echo 'style="color:#f00"';}?>>上半場</a>
<a  href="sports_note.php?type=gunqiu" <?if($type=='gunqiu'){echo 'style="color:#f00"';}?>>滾球</a>
<a  href="sports_note.php?type=bodan" <?if($type=='bodan'){echo 'style="color:#f00"';}?>>波膽</a>
<a  href="sports_note.php?type=shangbanbodan" <?if($type=='shangbanbodan'){echo 'style="color:#f00"';}?>>上半波膽</a>
<a  href="sports_note.php?type=ruqiushu" <?if($type=='ruqiushu'){echo 'style="color:#f00"';}?>>總入球</a>
<a  href="sports_note.php?type=banquanchang" <?if($type=='banquanchang'){echo 'style="color:#f00"';}?>>半全場</a>
<a  href="sports_note.php?type=kaisai" <?if($type=='kaisai'){echo 'style="color:#f00"';}?>>已开赛</a>
</div>

<div  class="con_menu"  style="width: 80%;">
<form  name="REFORM" id="myform" action="#"  method="get">
<input type="hidden" name="type" value="<?=$type?>">
<span  id="show_h">選擇聯盟:
	<select  id="lsm"  name="lsm"  onchange="document.getElementById('myform').submit()"  class="za_select">
	<option  value="">全部</option>
	<?
	foreach($match_name as $v){?>		
	
	<option  value="<?=$v?>"><?=$v?></option>
	<?}?>
	</select>&nbsp;&nbsp;日期：
	<input type="text"  id="date"  name="date" value="" style="width:100px;height:15px;" onClick="new Calendar(2008,2020).show(this);">
	<input type="submit" value="查询" class="za_button">
</span>
每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myform').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
  &nbsp;頁數：
 <select id="page" name="page" class="za_select" onchange="document.getElementById('myform').submit()"> 
  <?php  

    for($i=1;$i<=$totalPage;$i++){
      if($i==$CurrentPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }  
    } 
   ?>
  </select> <?php echo  $totalPage ;?> 頁&nbsp;




</form>

</div>
</div>
<div  class="content">
<!--单式-->
<?if($type=='danshi'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="20">場次</td>
			<td  width="200">隊伍</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
			<td  width="130">獨贏</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td><?=$json['db'][$i]["Match_MasterID"]?><br><?=$json['db'][$i]["Match_GuestID"]?></td>
		<td  align="left">
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"><font  color="#009900">和局</font></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_ShowType"]=="H"&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ho"]!=null&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_Ho"];}?>
					</td>
					<td>
                        <!--让球主队-->

                        <a href="#" style="color:#CF2878">
                            <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_ho"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_ho"]['num'])?>
                        </a>

					</td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_ShowType"]=="C"&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ao"]!=null&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_Ao"];}?>
					
					</td>
					<td> <a href="#" style="color:#CF2878">
                            <!--让球客队-->
                            <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_ao"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_ao"]['num'])?>
                        </a></td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_DxGG1"]=="O" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxDpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxDpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">
                                <!--大盘-->
                                <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_dxdpl"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_dxdpl"]['num'])?>
                            </a></td>
					</tr>
					<tr  align="right">
						<td>

							<?if($json['db'][$i]["Match_DxGG2"]=="U" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxXpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxXpl"];}?>
						</td>
						<td>
                            <!--小盘-->
                            <a href="#" style="color:#CF2878">
                                <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_dxxpl"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_dxxpl"]['num'])?>
                            </a>

                        </td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr align="right">
					  <td align="right" width="33%"><?if($json['db'][$i]["Match_BzM"]!="0"){echo $json['db'][$i]["Match_BzM"];}?></td>
					  <td width="67%"><a href="#" style="color:#CF2878">
                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzm"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzm"]['num'])?>
                          </a></td>
					</tr>
					<tr align="right">
					  <td align="right"><?if($json['db'][$i]["Match_BzG"]!="0"){echo $json['db'][$i]["Match_BzG"];}?></td>
					  <td><a href="#" style="color:#CF2878">
                              <!--让球客队-->

                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzg"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzg"]['num'])?>

                          </a></td>
					</tr>
					<tr align="right">
					  <td align="right"> <?if($json['db'][$i]["Match_BzH"]!="0"){echo $json['db'][$i]["Match_BzH"];}?></td>
					  <td><a href="#" style="color:#CF2878">
                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzh"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bzh"]['num'])?>

                          </a></td>
					</tr>
				</tbody>
			</table>
		</td>
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	<!--上半场-->
	<?if($type=='shangbanchang'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="20">場次</td>
			<td  width="200">隊伍</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
			<td  width="130">獨贏</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td><?=$json['db'][$i]["Match_MasterID"]?><br><?=$json['db'][$i]["Match_GuestID"]?></td>
		<td  align="left">
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"><font  color="#009900">和局</font></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_Hr_ShowType"]=="H"&&$json['db'][$i]["Match_BHo"]!="0"){echo $json['db'][$i]["Match_BRpk"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_BHo"]!=null&&$json['db'][$i]["Match_BHo"]!="0"){echo $json['db'][$i]["Match_BHo"];}?>
					</td>
					<td>

                        <a href="#" style="color:#CF2878">
                            <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bho"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bho"]['num'])?>
                        </a>

					</td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_Hr_ShowType"]=="C"&&$json['db'][$i]["Match_BAo"]!="0"){echo $json['db'][$i]["Match_BRpk"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_BAo"]!=null&&$json['db'][$i]["Match_BAo"]!="0"){echo $json['db'][$i]["Match_BAo"];}?>
					
					</td>
					<td>
                        <a href="#" style="color:#CF2878">
                            <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bao"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bao"]['num'])?>
                        </a>
					</td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Bdxpk1"]=="O" || $json['db'][$i]["Match_Bdxpk1"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdxpk1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_Bdpl"]==null || $json['db'][$i]["Match_Bdpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdpl"];}?>
						</td>
						<td>
                            <a href="#" style="color:#CF2878">

                                <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bdpl"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bdpl"]['num'])?>
                            </a>

						</td>
					</tr>
					<tr  align="right">
						<td>
							<?if($json['db'][$i]["Match_Bdxpk2"]=="U" || $json['db'][$i]["Match_Bdxpk2"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdxpk2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_Bxpl"]==null || $json['db'][$i]["Match_Bxpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bxpl"];}?>
						</td>
						<td>
                            <a href="#" style="color:#CF2878">
                            <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bxpl"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bxpl"]['num'])?>
                            </a>
						</td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr align="right">
					  <td align="right" width="33%"><?if($json['db'][$i]["Match_Bmdy"]!="0"){echo $json['db'][$i]["Match_Bmdy"];}?></td>
					  <td width="67%">
                          <a href="#" style="color:#CF2878">
                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bmdy"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bmdy"]['num'])?>
                          </a>
					  </td>
					</tr>
					<tr align="right">
					  <td align="right"><?if($json['db'][$i]["Match_Bgdy2"]!="0"){echo $json['db'][$i]["Match_Bgdy2"];}?></td>
					  <td> <a href="#" style="color:#CF2878">
                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bgdy"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bgdy"]['num'])?>
                          </a></td>
					</tr>
					<tr align="right">
					  <td align="right"> <?if($json['db'][$i]["Match_Bhdy2"]!="0"){echo $json['db'][$i]["Match_Bhdy2"];}?></td>
					  <td><a href="#" style="color:#CF2878">
                              <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bhdy"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bhdy"]['num'])?>
                          </a></td>
					</tr>
				</tbody>
			</table>
		</td>
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	<!--波胆-->
	<?if($type=='bodan'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="200">主客隊伍</td>
			<td  width="130">波膽</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td>
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Bdup5"]!=null&&$json['db'][$i]["Match_Bdup5"]!='0'){echo $json['db'][$i]["Match_Bdup5"];}?>
						</td>
						<td> <a href="#" style="color:#CF2878">
                                <?
                               // print_r($arr);
                                ?>
                                <?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bd10"]['money'])?>/<?=getString($arr[$json['db'][$i]["Match_ID"]]["match_bdup"]['num'])?>
                            </a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	<!--上半场波胆-->
	<?if($type=='shangbanbodan'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="200">主客隊伍</td>
			<td  width="130">波膽</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td>
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Bdup5"]!=null&&$json['db'][$i]["Match_Bdup5"]!='0'){echo $json['db'][$i]["Match_Bdup5"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	<!--入球数-->
	<?if($type=='ruqiushu'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="200">主客隊伍</td>
			<td  width="130">0~1</td>
			<td  width="130">2~3</td>
			<td  width="130">4~6</td>
			<td  width="130">7UP</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td>
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"></div>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Total01Pl"]!=null&&$json['db'][$i]["Match_Total01Pl"]!='0'){echo $json['db'][$i]["Match_Total01Pl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Total23Pl"]!=null&&$json['db'][$i]["Match_Total23Pl"]!='0'){echo $json['db'][$i]["Match_Total23Pl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Total46Pl"]!=null&&$json['db'][$i]["Match_Total46Pl"]!='0'){echo $json['db'][$i]["Match_Total46Pl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Total7upPl"]!=null&&$json['db'][$i]["Match_Total7upPl"]!='0'){echo $json['db'][$i]["Match_Total7upPl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	
	<!--半全场-->
	<?if($type=='banquanchang'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="200">主客隊伍</td>
			<td  width="130">主/主</td>
			<td  width="130">主/和</td>
			<td  width="130">主/客</td>
			<td  width="130">和/主</td>
			<td  width="130">和/和</td>
			<td  width="130">和/客</td>
			<td  width="130">客/主</td>
			<td  width="130">客/和</td>
			<td  width="130">客/客</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?> </td>
		<td>
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"></div>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqMM"]!=null&&$json['db'][$i]["Match_BqMM"]!='0'){echo $json['db'][$i]["Match_BqMM"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqMH"]!=null&&$json['db'][$i]["Match_BqMH"]!='0'){echo $json['db'][$i]["Match_BqMH"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqMG"]!=null&&$json['db'][$i]["Match_BqMG"]!='0'){echo $json['db'][$i]["Match_BqMG"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqHM"]!=null&&$json['db'][$i]["Match_BqHM"]!='0'){echo $json['db'][$i]["Match_BqHM"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqHH"]!=null&&$json['db'][$i]["Match_BqHH"]!='0'){echo $json['db'][$i]["Match_BqHH"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqHG"]!=null&&$json['db'][$i]["Match_BqHG"]!='0'){echo $json['db'][$i]["Match_BqHG"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqGM"]!=null&&$json['db'][$i]["Match_BqGM"]!='0'){echo $json['db'][$i]["Match_BqGM"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqGH"]!=null&&$json['db'][$i]["Match_BqGH"]!='0'){echo $json['db'][$i]["Match_BqGH"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_BqGG"]!=null&&$json['db'][$i]["Match_BqGG"]!='0'){echo $json['db'][$i]["Match_BqGG"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	<!--滚球-->
<?if($type=='gunqiu'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="20">場次</td>
			<td  width="200">隊伍</td>
			<td  width="130">獨贏</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
			<td  width="130">上半獨贏</td>
			<td  width="195">上半讓球 / 注單</td>
			<td  width="195">上半大小盤 / 注單</td>
			
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td><?=$json['db'][$i]["Match_MasterID"]?><br><?=$json['db'][$i]["Match_GuestID"]?></td>
		<td  align="left">
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"><font  color="#009900">和局</font></div>
		</td>
		
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_DxGG1"]=="O" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxDpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxDpl"];}?>
						</td>
						<td>

                            <a href="#" style="color:#CF2878"><?=getString($arr[$rows["Match_ID"]]["match_ho"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ho"]['money'])?></a></td>
					</tr>
					<tr  align="right">
						<td>
							<?if($json['db'][$i]["Match_DxGG2"]=="U" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxXpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxXpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr align="right">
					  <td align="right" width="33%"><?if($json['db'][$i]["Match_BzM"]!="0"){echo $json['db'][$i]["Match_BzM"];}?></td>
					  <td width="67%"><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr align="right">
					  <td align="right"><?if($json['db'][$i]["Match_BzG"]!="0"){echo $json['db'][$i]["Match_BzG"];}?></td>
					  <td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr align="right">
					  <td align="right"> <?if($json['db'][$i]["Match_BzH"]!="0"){echo $json['db'][$i]["Match_BzH"];}?></td>
					  <td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_ShowType"]=="H"&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ho"]!=null&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_Ho"];}?>
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_ShowType"]=="C"&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ao"]!=null&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_Ao"];}?>
					
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_Hr_ShowType"]=="H"&&$json['db'][$i]["Match_BHo"]!="0"){echo $json['db'][$i]["Match_BRpk"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_BHo"]!=null&&$json['db'][$i]["Match_BHo"]!="0"){echo $json['db'][$i]["Match_BHo"];}?>
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_Hr_ShowType"]=="C"&&$json['db'][$i]["Match_BAo"]!="0"){echo $json['db'][$i]["Match_BRpk"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_BAo"]!=null&&$json['db'][$i]["Match_BAo"]!="0"){echo $json['db'][$i]["Match_BAo"];}?>
					
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_Bdxpk1"]=="O" || $json['db'][$i]["Match_Bdxpk1"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdxpk1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_Bdpl"]==null || $json['db'][$i]["Match_Bdpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr  align="right">
						<td>
							<?if($json['db'][$i]["Match_Bdxpk2"]=="U" || $json['db'][$i]["Match_Bdxpk2"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bdxpk2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_Bxpl"]==null || $json['db'][$i]["Match_Bxpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_Bxpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr align="right">
					  <td align="right" width="33%"><?if($json['db'][$i]["Match_Bmdy"]!="0"){echo $json['db'][$i]["Match_Bmdy"];}?></td>
					  <td width="67%"><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr align="right">
					  <td align="right"><?if($json['db'][$i]["Match_Bgdy2"]!="0"){echo $json['db'][$i]["Match_Bgdy2"];}?></td>
					  <td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr align="right">
					  <td align="right"> <?if($json['db'][$i]["Match_Bhdy2"]!="0"){echo $json['db'][$i]["Match_Bhdy2"];}?></td>
					  <td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
				</tbody>
			</table>
		</td>
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="10"><?php echo $pageStr;?></td>
		</tr>

	</tbody>
	</table>
	
	<?}?>
		<!--开赛-->
<?if($type=='kaisai'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="20">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="20">場次</td>
			<td  width="200">隊伍</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
			<td  width="195">滾球 / 注單</td>
			<td  width="195">滾球大小 / 注單</td>
			<td  width="195">功能</td>
			
		</tr>
		
	 
	<?php
		if(is_array($arr_m)){
		foreach($arr_m as $k=>$v){
			$rows	=	$arr[$k];
		?>
		<tr  class="m_cen_top">
		<td><?=$rows["Match_Date"]?><br><?=$rows["Match_Time"]?><br><? if(@$rows["Match_IsLose"]==1){?><span style="color:#FF0000">滾球</span><? } ?></td>
		<td><?=$rows["Match_ID"]?><br /><?=$rows["Match_Name"]?></td>
		<td><?=$rows["Match_MasterID"]?><br><?=$rows["Match_GuestID"]?></td>
		<td  align="left">
			<?=$rows["Match_Master"]?><br><?=$rows["Match_Guest"]?>
			<div style=" color:#009933">和局</div></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<? if($rows["Match_ShowType"]=="H"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
							<? if($rows["Match_Ho"]>0) echo double_format($rows["Match_Ho"]);?></font>
						</td>
						<td>
							<?=getString($arr[$rows["Match_ID"]]["match_ho"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ho"]['money'])?>
						</td>
					</tr>
					<tr  align="right">
						<td>
							<? if($rows["Match_ShowType"]=="C"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
							<? if($rows["Match_Ao"]>0) echo double_format($rows["Match_Ao"]);?>
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_ao"]['num'])?>/<?=getString(	$arr[$rows["Match_ID"]]["match_ao"]['money'])?></td>
					</tr>
					<tr>
						<td  colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<? if ($rows["Match_DxGG"]>0){?><font color="#0033FF"><?="O".$rows["Match_DxGG"]?> </font>
              <?}?>                <? if($rows["Match_DxDpl"]>0) echo double_format($rows["Match_DxDpl"]);?>
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['money'])?></td>
					</tr>
					<tr  align="right">
						<td>
							<? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="U".$rows["Match_DxGG"]?> </font><?}?>  <? if($rows["Match_DxXpl"]>0) echo double_format($rows["Match_DxXpl"]);?>     <br />
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['money'])?></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<? if($rows["Match_ShowType"]=="H"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
               <? if($rows["Match_Ho"]>0) echo double_format($rows["Match_Ho"]);?></font>
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_ho"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ho"]['gq']['money'])?></td>
					</tr>
					<tr  align="right">
						<td>
							<? if($rows["Match_ShowType"]=="C"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
              <? if($rows["Match_Ao"]>0) echo double_format($rows["Match_Ao"]);?>
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_ao"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ao"]['gq']['money'])?></td>
					</tr>
					<tr>
						<td  colspan="2">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="O".$rows["Match_DxGG"]?> </font>
              <?}?>                <? if($rows["Match_DxDpl"]>0) echo double_format($rows["Match_DxDpl"]);?>
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['gq']['money'])?></td>
					</tr>
					<tr  align="right">
						<td>
							<? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="U".$rows["Match_DxGG"]?> </font><?}?>  <? if($rows["Match_DxXpl"]>0) echo double_format($rows["Match_DxXpl"]);?>     <br /> 
						</td>
						<td><?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['gq']['money'])?></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		<td align="center"><!--<a href="javascript:winopen('yks_gn.php?mid=<?=$rows["Match_ID"]?>')">-->more..</td>
		
		</tr>
	<?}?>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="10"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	
	<?}?>
</div>	




<?php require("../common_html/footer.php");?>