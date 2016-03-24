<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Maintenance_model extends MY_Model {

	private $module = null;
	private $moduleall = null;
	private $relation = null;
	private $siteinfo = null;
	private $SiteStatus = null;

	function __construct() {
		parent::__construct();

		//解析维护文件
		$SiteStatus=@file_get_contents(dirname(__FILE__).'/../../../../_Site_Status_Json/site_status.log');
		//$SiteStatus=@file_get_contents(dirname(__FILE__).'/../../../site_status.log');
		
		$this->SiteStatus=json_decode($SiteStatus,true);

		$this->module = $status['Module'];
		$this->moduleall = $status['ModuleAll'];
		$this->relation = $status['Relation'];
		$this->siteinfo = $status['Siteinfo'];

		

		/*
	    [Module] => Module
	    [ModuleAll] => ModuleAll
	    [Relation] => Relation
	    [Siteinfo] => Siteinfo
		*/
		/*
		$keys =array();
		foreach ($status as $key => $value) {
			$keys[$key] = $key;
		}
		[ModuleAll] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [cate_type] => ag
                    [name] => AG视讯
                    [state] => 1
                    [message] => 维护原因：临时维护
◆ 维护完成时间：待定
                )

                $Gamestats = array();
		$this->Gamestats["lot"]['fc_3d'] = 0 // 0是维护 1是正常
		$this->Gamestats["sport"] = 0 // 0是维护 1是正常

		echo "<pre>";
		*/
		//print_r($status);
		//exit();
		
	}

	/*
0: {LotteryName: "福彩3D", type: "fc_3d", LotteryID: 2, OpenCount: null}
1: {LotteryName: "排列三", type: "pl_3", LotteryID: 3, OpenCount: 37416}
2: {LotteryName: "六合彩", type: "liuhecai", LotteryID: 4, OpenCount: 1856016}
3: {LotteryName: "北京快乐8", type: "bj_8", LotteryID: 5, OpenCount: 216}
4: {LotteryName: "北京赛车pk拾", type: "bj_10", LotteryID: 6, OpenCount: 36}
5: {LotteryName: "重庆时时彩", type: "cq_ssc", LotteryID: 7, OpenCount: 216}
6: {LotteryName: "天津时时彩", type: "tj_ssc", LotteryID: 8, OpenCount: 216}
7: {LotteryName: "新疆时时彩", type: "xj_ssc", LotteryID: 9, OpenCount: 216}
8: {LotteryName: "重庆快乐十分", type: "cq_ten", LotteryID: 11, OpenCount: 396}
9: {LotteryName: "广东快乐十分", type: "gd_ten", LotteryID: 12, OpenCount: 216}
10: {LotteryName: "江苏快3", type: "js_k3", LotteryID: 13, OpenCount: 216}
11: {LotteryName: "吉林快3", type: "jl_k3", LotteryID: 14, OpenCount: 216}
*/

	public function getweihu($type,$islot = false){
		if($islot){
			$whuri_1 = "lottery";
			$payload = $type;
		}else{
			$whuri_1 = $type;
		}
		switch ($whuri_1) {
			case 'index':
			    $wgtype=1;
			    $cate_type='webhome'; //首页
				return $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				break;
            case 'lottery':
				$wgtype=1;
                if($payload =='liuhecai'){//其他彩种维护以此类推
                  $wgtype=4;
                  $cate_type='liuhecai';//六合彩
                }elseif($payload=='fc_3d'){
                  $wgtype=4;
                  $cate_type='fc_3d';
                }elseif($payload=='pl_3'){
                  $wgtype=4;
                  $cate_type='pl_3';
                }elseif($payload=='cq_ssc'){
                  $wgtype=4;
                  $cate_type='cq_ssc';
                }elseif($payload=='xj_ssc'){
                  $wgtype=4;
                  $cate_type='xj_ssc';
                }elseif($payload=='tj_ssc'){
                  $wgtype=4;
                  $cate_type='tj_ssc';
                }elseif( $payload=='bj_8'){
                  $wgtype=4;
                  $cate_type='bj_8';
                }elseif( $payload=='bj_10'){
                  $wgtype=4;
                  $cate_type='bj_10';
                }elseif($payload=='cq_ten'){
                  $wgtype=4;
                  $cate_type='cq_ten';
                }elseif( $payload=='gd_ten'){
                  $wgtype=4;
                  $cate_type='gd_ten';
                }elseif( $payload=='js_k3'){
                  $wgtype=4;
                  $cate_type='js_k3';
                }elseif( $payload=='jl_k3'){
                  $wgtype=4;
                  $cate_type='jl_k3';
                }else{
                  $wgtype=1;
                  $cate_type='lottery';
                }
				return $this->GetSiteStatus($this->SiteStatus,$wgtype,$cate_type,1);
				default:
				$wgtype=1;
				return $this->GetSiteStatus($this->SiteStatus,$wgtype,$whuri_1,1);
				break;
			}

		return false;
	}

	public function getkefu($site_id,$index_id){
		if (count($this->siteinfo))
		foreach ($this->siteinfo as  $value) {
			if($value['site_id'] == $site_id && $value['index_id'] == $index_id){
				return $value;
			}
		}
		return array();
	}

	/*
 *
 * 跳转方式 $type=0,
 * 监控类别$cate_type,此处匹配来源在数据库manage_cyj/site_cate_module/cate_type字段
 * 前台 $site_type=1 后台$site_type=2
 *
 * */
	public function GetSiteStatus($d,$type=0,$cate_type,$site_type=1){

	    if(!isset($cate_type)) return false;
	    $echo['status']=0;
	    $echo['url']=null;
	    $wh = false;
	    if($d['ModuleWap'] || $d['RelationWap']){
	        $data=array_merge($d['ModuleWap'],$d['RelationWap']);
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
	    }
	    if($d['ModuleWap']){
	    	
	    }

	    return $wh;

	}

}
?>