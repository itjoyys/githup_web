<?php
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
//include(dirname(__FILE__).'/../class/medoo.php');
//$_DBCMAN=MDOO($_DBCMAN);


//$SiteCatteModule = $_DBCPRI->select("site_module", ["type",'state','content','sids'],['OR'=>['sids'=>SITEID,'sids'=>0]] );*
/*$SiteStatus['Relation']=$_DBCMAN->select("site_cate_relation",
    [
        "[><]site_cate_module"=>[
            "mid"=>"id"
        ]
    ],
    [

        "site_cate_relation.message",
        "site_cate_module.cate_type",
    ],
    [
        "site_cate_relation.site_id"=>SITEID,

    ]);
//print_r($DataTheOne);
$SiteStatus['Module']=$_DBCMAN->select("site_cate_module",["message","cate_type"],["state[!]"=>1]);*/
//echo $_DBCPRI->last_query();
//print_r($DataModule);

//状态 1表示正常 0表示关闭  2表示维护 3表示暂停
//echo $_DBCPRI->last_query();
/*
 * $d 数据提取
 * 跳转方式 $type=0,
 * 监控类别$cate_type,
 * 前台 $site_type=1 后台$site_type=2
 *
 *
 *
 * */
$SiteStatus = @file_get_contents(dirname(__FILE__).'/../../_Site_Status_Json/site_status.log');
$SiteStatus=json_decode($SiteStatus,true);
function GetSiteStatus($d,$type=0,$cate_type,$site_type=1){

    $echo['status']=0;
    $echo['url']=null;
    if($d['Module'] || $d['Relation']){
        $data=array_merge($d['Module'],$d['Relation']);

        $wh=false;
        $r=array();
        if($cate_type){
            foreach($data as $v){
                $msg=($v['message']);
                    if($v['site_id']==SITEID && $v['cate_type']==$cate_type){
                        $wh=true;
                        $url_[$v['cate_type']]=1;
                        $url_[$v['cate_type'].'_msg']=$msg;
                    }
            }
            foreach($data as $v){
                $msg=($v['message']);

                if(!$v['site_id'] && $v['cate_type']==$cate_type){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
        }else{
            foreach($data as $v){
                $msg=($v['message']);
                if($v['site_id']==SITEID){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
            foreach($data as $v){
                $msg=($v['message']);

                if(!$v['site_id'] ){
                    $wh=true;
                    $url_[$v['cate_type']]=1;
                    $url_[$v['cate_type'].'_msg']=$msg;
                }
            }
        }

        if($wh==true){
            $echo['status']=1;
            $url='http://'.$_SERVER['HTTP_HOST'].'/wh/';
            if($type==1){
                $url="<script>window.top.frames.location.href='$url'</script>";
                $echo['url']=$url;
            }elseif($type==2){//会员中心额度转换以及刷新额度使用
                return $url_;
            }elseif($type==100){
                return $url_;
            }else{
                $echo['url']=$url;
            }
        }
    }

    if ($echo['status']==1){
        echo $echo['url'];
        exit;
    }


}
/*
include_once(dirname(__FILE__)."/../include/private_config.php");
include_once(dirname(__FILE__)."/../wh/site_state.php");
$SiteStatus= GetSiteStatus($SiteStatus,1,'sport');
if ($SiteStatus['status']==1){
    echo $SiteStatus['url'];
    exit;
}
*/

?>
