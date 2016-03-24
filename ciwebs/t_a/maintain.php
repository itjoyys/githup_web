<?php
$SiteStatus = @file_get_contents(dirname(__FILE__).'/../../_Site_Status_Json/site_status.log');
$d=json_decode($SiteStatus,true);
$type=1;
$cate_type='webhome';
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
            $url='http://'.$_SERVER['HTTP_HOST'].'/wh.php';
            //以下是定义输出类型
            if($type==1){//JS输出
            	//echo '<pre>';
            	foreach ($d['Siteinfo'] as $key => $value) {
            		if( $value['site_id'] === SITEID && $value['index_id'] === INDEX_ID){
	            		$siteinfo = $value;
	            	}
            	}
            	unset($siteinfo['site_id']);
            	unset($siteinfo['index_id']);
            	unset($siteinfo['video_module']);
            	unset($siteinfo['fc_module']);
            	$ps = base64_encode(json_encode($siteinfo));
                echo echo_frame($url,$siteinfo['copy_right'],$ps);exit;
            }
        }

    }


    	function echo_frame($url, $title,$ps) {
$str = <<<EOF
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>$title</title>
</head>
<FRAMESET ROWS="*" BORDER="0" FRAMEBORDER="0" FRAMESPACING="0">
<frame name="casinoFrame" src="$url?ps=$ps" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="NO" NORESIZE="NORESIZE" />
<noframes>
<BODY bgcolor="#000000" CLASS="bodyDocument" SCROLL="NO">Frame support is required.<BR></BODY>
</noframes>
</FRAMESET>
</html>
EOF;

	return $str;
}

?>