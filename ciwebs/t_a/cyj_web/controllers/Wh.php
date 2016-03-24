<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wh extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $SiteStatus=$this->SiteStatus;
        if($SiteStatus['Siteinfo']){
            foreach ($SiteStatus['Siteinfo'] as $k => $v) {
                if($v['site_id']==SITEID && $v['index_id']==INDEX_ID){
                    $kefu=$v['online_service'];
                    $sitename=$v['copy_right'];
                    $video=explode(',',$v['video_module']);
                    foreach($video as $kk=>$vv){
                        $games[]=[$vv.'_game'];
                    }
                    $siteopen=['sport'=>['sport'],'lottery'=>['lottery'],'video'=>$video,'games'=>$games,'cp'=>explode(',',$v['fc_module'])];
                    break;
                }
            }
        } 
        $domain=$_SERVER['HTTP_HOST']; 
        $playurl['ag']=['type'=>1,      'url'=>"http://$domain/index.php/video/login?g_type=ag"];
        $playurl['mg']=['type'=>1,      'url'=>"http://$domain/index.php/video/login?g_type=mg"];
        $playurl['ct']=['type'=>1,      'url'=>"http://$domain/index.php/video/login?g_type=ct"];
        $playurl['lebo']=['type'=>1,    'url'=>"http://$domain/index.php/video/login?g_type=lebo"];
        $playurl['bbin']=['type'=>1,    'url'=>"http://$domain/index.php/video/login?g_type=bbin"];
        $playurl['sport']=['type'=>2,   'url'=>"http://$domain/index.php/index/sports?type=m"];
        $playurl['lottery']=['type'=>2, 'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['mg_game']=['type'=>2, 'url'=>"http://$domain/index.php/index/egame?type=m"];
        $playurl['pt']=['type'=>2, 'url'=>"http://$domain/index.php/index/egame?type=m"];
        $playurl['liuhecai']=['type'=>2,   'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['cq_ssc']=['type'=>2, 'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['xj_ssc']=['type'=>2, 'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['tj_ssc']=['type'=>2, 'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['fc_3d']=['type'=>2,  'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['pl_3']=['type'=>2,   'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['bj_8']=['type'=>2, 'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['bj_10']=['type'=>2,  'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['cq_ten']=['type'=>2,'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['gd_ten']=['type'=>2,'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['js_k3']=['type'=>2,  'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['jl_k3']=['type'=>2,  'url'=>"http://$domain/index.php/index/lottery"];
        $playurl['ag_game']=['type'=>2, 'url'=>"http://$domain/index.php/index/egame?type=m"];
        $playurl['bbin_game']=['type'=>2, 'url'=>"http://$domain/index.php/index/egame?type=m"];
        $playurl['eg_game']=['type'=>2, 'url'=>"http://$domain/index.php/index/egame?type=m"];
        $status=$this->GetSiteStatus($SiteStatus,100,'',1);
       // print_r($status);
        if($status['webhome']==1){
            $this->add('webhome',1);
        }else $this->add('webhome',0);
        foreach($SiteStatus['ModuleAll'] as $k=>$v){
            $modules_[$k]['cate_type']=$v['cate_type'];
        }

        $this->add('siteopen',json_encode($siteopen));
        $this->add('kefu',$kefu);
        $this->add('playurl',json_encode($playurl));
        $this->add('domain',$domain);
        $this->add('status_json',json_encode($status));
        $this->add('status',($status));
        $this->add('sitename',$sitename);
        $this->add('modules',$SiteStatus['ModuleAll']);
        $this->add('modules_',json_encode($modules_));
        $this->display('wh.html');
    }
    

    
}
