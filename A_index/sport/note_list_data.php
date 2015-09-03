<?php
include_once("../include/config.php");
include_once("../include/public_config.php");
include_once ("../include/private_config.php");
include_once("../lib/class/model.class.php");
include_once("common/login_check.php");
include_once("common/function.php");
$p=intval($_REQUEST['p']);
$action=intval($_REQUEST['action']);

if($p<1)$p=1;
$limit=($p-1)*10;
if($action==0){
    $note_list['d'] = M('k_bet',$db_config)->join('join k_user on k_user.uid = k_bet.uid')->where("k_user.uid = '".$_SESSION['uid']."' and k_bet.status=0")->
        field("k_bet.master_guest,bet_money,bet_win,number,bet_time,ball_sort,match_name,bet_info,status,lose_ok")->order('k_bet.bid DESC')->limit("$limit,10")->select();
    $note_list['nums'] = M('k_bet',$db_config)->join('join k_user on k_user.uid = k_bet.uid')->where("k_user.uid = '".$_SESSION['uid']."' and k_bet.status=0")->
        field("count(*) as total_nums")->select();
    $note_list['nums']=$note_list['nums'][0]['total_nums'];
}
elseif($action==1){
    $note_list['d'] = M('k_bet',$db_config)->join('join k_user on k_user.uid = k_bet.uid')->where("k_user.uid = '".$_SESSION['uid']."' and k_bet.status>0")->
        field("k_bet.master_guest,bet_money,bet_win,number,bet_time,ball_sort,match_name,bet_info,status,MB_Inball,TG_Inball,win")->order('k_bet.bid DESC')->limit("$limit,10")->select();
    $note_list['nums'] = M('k_bet',$db_config)->join('join k_user on k_user.uid = k_bet.uid')->where(" k_bet.status >0  and k_user.uid = '".$_SESSION['uid']."'")->
        field("count(*) as total_nums")->select();
    $note_list['nums']=$note_list['nums'][0]['total_nums'];
}
elseif($action==2 || $action==3){
$bet_money	=	0;
$ky			=	0;
$i			=	0;
$uid		=	$_SESSION["uid"];
$arr		=	array();
$gid		=	'';
if($action==2){
    $sql		=	"select g.gid,g.bet_time,g.cg_count,g.bet_money,g.win,g.bet_win,g.number,g.status from k_bet_cg_group g where g.status in(0,2) and g.is_jiesuan=0 and uid=$uid order by g.bet_time  desc limit $limit,10";

    $sql_nums	=	"select count(*) from k_bet_cg_group g where g.status in(0,2) and g.is_jiesuan=0 and uid=$uid ";
}
else {
    $sql		=	"select g.gid,g.bet_time,g.cg_count,g.bet_money,g.win,g.bet_win,g.number,g.status from k_bet_cg_group g where  g.is_jiesuan=1 and uid=$uid order by g.bet_time desc limit $limit,10";
    $sql_nums	=	"select count(*) from k_bet_cg_group g where  g.is_jiesuan=1 and uid=$uid ";
}
    $query_nums		=	$mysqlt->query($sql_nums);
    while($count_rows	=	$query_nums->fetch_array()){
            $page_nums[]=$count_rows;
    }
    $note_list['nums']=$page_nums[0][0];
    $query		=	$mysqlt->query($sql);
while($rows	    =	$query->fetch_array()){
    $arr[$rows['gid']]['number']	=	$rows['number'];
    $arr[$rows['gid']]['bet_time']	=	$rows['bet_time'];
    $arr[$rows['gid']]['cg_count']	=	$rows['cg_count'];
    $arr[$rows['gid']]['bet_money']	=	$rows['bet_money'];
    $arr[$rows['gid']]['win']		=	$rows['win'];
    $arr[$rows['gid']]['bet_win']	=	$rows['bet_win'];
    $arr[$rows['gid']]['status']	=	$rows['status'];
    $gid	.=	$rows['gid'].',';
}

    $note_list['d']='';
 if($gid == '')
    {   }
 else{
                    $gid	=	rtrim($gid,',');

                    $arr_cg	=	array();
                    $sql	=	"select gid,bid,bet_info,match_name,master_guest,bet_time,MB_Inball,TG_Inball,status from k_bet_cg where gid in ($gid) order by bid desc";
                    $query	=	$mysqlt->query($sql);
                    while($rows	=	$query->fetch_array()){
                        $arr_cg[$rows['gid']][$rows['bid']]['bet_info']		=	$rows['bet_info'];
                        $arr_cg[$rows['gid']][$rows['bid']]['match_name']	=	$rows['match_name'];
                        $arr_cg[$rows['gid']][$rows['bid']]['master_guest']	=	$rows['master_guest'];
                        $arr_cg[$rows['gid']][$rows['bid']]['bet_time']		=	$rows['bet_time'];
                        $arr_cg[$rows['gid']][$rows['bid']]['MB_Inball']	=	$rows['MB_Inball'];
                        $arr_cg[$rows['gid']][$rows['bid']]['TG_Inball']	=	$rows['TG_Inball'];
                        $arr_cg[$rows['gid']][$rows['bid']]['status']		=	$rows['status'];
                    }
                    foreach($arr as $gid=>$rows){
                        $html='';
                        $bet_money	+=	$rows["bet_money"];
                        $ky			+=	$rows["bet_win"];
                        if(($i%2)==0) $bgcolor="#FFFFFF";
                        else $bgcolor="#F5F5F5";
                        $i++;
                        $note_list['d'][$gid]['bet_time']=$rows["bet_time"];
                        $note_list['d'][$gid]['ball_sort']=$rows["cg_count"].'串1';
                        $note_list['d'][$gid]['bet_money']=$rows["bet_money"];
                        $note_list['d'][$gid]['number']=$rows["number"];


                        $x		=	0;
                        $nums	=	count($arr_cg[$gid]);
                        foreach($arr_cg[$gid] as $k=>$myrows){
                            $html.="<div style=\"height:40px; width:99%; padding:10px;\">";
                            $m=explode('-',$myrows["bet_info"]);
                            $html.= $m[0];
                            if(mb_strpos($myrows["bet_info"]," - ")){
                                //篮球上半之内的,这里换成正则表达替换
                                $m[2]=$m[2].preg_replace('[\[(.*)\]]', '',$m[3]);
                                //  $m[2]=$m[2].str_replace('[上半]','',$m[3]);
                            }
                            $m[2]=@preg_replace('[\[(.*)\]]','',$m[2].$m[3]);
                            unset($m[3]);
                            //如果是波胆
                            if(mb_strpos($m[0],"胆")){
                                $bodan_score=explode("@",$m[1],2);
                                $score=$bodan_score[0];
                                $m[1]="波胆@".$bodan_score[1];
                            }
                            $html.='<span style="color:#005481"><b>'.$myrows["match_name"].'</b></span><br />';


                            $m_count=count($m);
                            preg_match('[\((.*)\)]', $m[$m_count-1], $matches);
                            if(strpos($myrows["master_guest"],'VS.')) $team=explode('VS.',$myrows["master_guest"]);
                            else $team=explode('VS',$myrows["master_guest"]);

                            if(count(@$matches)>0) $html.="@". $myrows['bet_time'].@$matches[0]."<br/>";

                            if(mb_strpos($m[1],"让")>0) { //让球
                                   if(mb_strpos($m[1],"主")===false) { //客让
                                    $html.='<font style="color:#000000">'.$team[1].'</font>'.str_replace(array("主让","客让"),array("",""),$m[1]).'<font style="color:#890209">'.$team[0].'</font>(主)';
                                   }else{
                                    $html.='<font style="color:#000000">'.$team[0].'</font>'.str_replace(array("主让","客让"),array("",""),$m[1]).' <font style="color:#890209">'.$team[1].'</font>';
                                   }
                                $m[1]="";
                            }else{
                                $html.='<font style="color:#000000">'.$team[0].'</font>';
                                if(isset($score)) { $html.=$score;  }else{$html.='VS.';  }
                                $html.='<font style="color:#890209">'.$team[1].'</font>';
                             }

                            $html.='<br /> <font style="color:#000000">';
                            if($m_count==3){
                                if(strpos($m[1],'@')){
                                    $ss = explode('@',$m[1]);
                                    $html.= $ss[0]." @ <font color=\"red\">".$ss[1]."</font>";
                                }else{
                                    $html.= $m[1].' ';//半全场替换显示
                                    $arraynew=array($team[0]," / ",$team[1],"和局");
                                    $arrayold=array("主","/","客","和");
                                    $ss = str_replace($arrayold,$arraynew,preg_replace('[\((.*)\)]', '',$m[$m_count-1]));
                                    $ss = explode('@',$ss);
                                    $html.= $ss[0]." @ <font color=\"red\">".$ss[1]."</font>";
                                }
                            }
                            $html.='</font>';
                            if($myrows["status"]==3 || $myrows["MB_Inball"]<0){
                                $html.='[取消]';
                              }else if($myrows["status"]>0){
                                $html.='['.$myrows["MB_Inball"].':'.$myrows["TG_Inball"].']';
                              }
                                $html.= "</div>";
                            if($x<$nums-1){
                                $html.='<div style="height:1px; width:99%; background:#ccc; overflow:hidden;"></div>';
                            }
                            $x++;


                        }
                        $note_list['d'][$gid]['match_name']=$html;
                        $abc = double_format($rows["bet_win"]);
                        @$win+=$abc;

                        $note_list['d'][$gid]['bet_win']=$abc;
                        $note_list['d'][$gid]['master_guest']='';
                        $note_list['d'][$gid]['bet_info']='';



                        $note_list['d'][$gid]['status']=$rows['status'];

                    }
                }


}

echo json_encode($note_list);
?>