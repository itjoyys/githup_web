<?php
header("Content-type: text/html; charset=utf-8");
include(dirname(__FILE__).'/../include/filter.php');
include(dirname(__FILE__).'/class/medoo.php');
include_once(dirname(__FILE__)."/../include/private_config.php");
$_DBC['private']['dbname']='manage_cyj';
$_DBCPRI=$_DBC['private'];
$_DBCPRI=MDOO($_DBCPRI);
$SiteCatteRel_['status']=1;
$SiteCatteModule = $_DBCPRI->select("site_cate_module", ["id",'cate_type','name','state'] );
$SiteCatteRel = $_DBCPRI->select("site_cate_relation",[
    "[><]site_cate_module(mo)"=>["mid"=>"id"]
], [
    "mo.name","site_cate_relation.mid",'site_cate_relation.site_id','site_cate_relation.message','site_cate_relation.status'
] ,[
    "AND"=>['site_cate_relation.site_id[=]'=>SITEID,'site_cate_relation.mid[=]'=>21],
    "LIMIT"=>1
]);
//状态 1表示正常 0表示关闭  2表示维护 3表示暂停
//echo $_DBCPRI->last_query();

if($SiteCatteRel[0]['status']!=1){
    echo json_encode($SiteCatteRel);
}else{
    echo json_encode($SiteCatteRel_);
}
