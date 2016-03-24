<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Index_model extends MY_Model {

	function __construct() {
		$this->init_db();
	}
	public function qrcode(){
		$query=$this->private_db->query('select * from web_config where site_id=\''.SITEID.'\' AND  index_id=\''.INDEX_ID.'\'');
		$row=$query->row_array();
		return $row;
	}
	//介绍人是否合法判断
	public function is_intr($intr){
        $mapS = array();
		$mapS['table'] = 'k_user_agent';
	    $mapS['select'] = 'id,intr';
		$mapS['where']['intr'] = $intr;
		$mapS['where']['site_id'] = SITEID;
		$mapS['where']['index_id'] = INDEX_ID;
		$mapS['where']['is_demo'] = 0;//屏蔽测试账号
		$mapS['where']['agent_type'] = 'a_t';
		return $this->rfind($mapS);
	}

		//判断会员id是否存在
	public function is_uuno_true($uuno){
	    $mapS = array();
		$mapS['table'] = 'k_user';
	    $mapS['select'] = 'uid,agent_id';
		$mapS['where']['site_id'] = SITEID;
		$maps['where']['index_id'] = INDEX_ID;
		$maps['where']['uid'] = $uuno;
		$log = $this->rfind($mapS);
		if ($log) {
			//存在则写入数据
		    $_SESSION['uuno_uid'] = $uuno;
		    $_SESSION['uuno_agent_id'] = $log['agent_id'];
		}
	}

	//获取logo信息
	public function get_logo(){
	    if($_SESSION['ty'] == 11){
	    	$this->db->from('info_logo_edit');
	    }else{
	    	$this->db->from('info_logo_use');
	    }
		$this->db->where('site_id',SITEID);
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('type',11);
		$this->db->where('state',1);
		$this->db->select('logo_url');
		return $this->db->get()->row_array();
	}

	//读取首页轮播图
	public function get_flash($value='') {
		//首页幻灯片
		if($_SESSION['ty'] == 13){
			$this->db->from('info_flash_edit');
		}else{
			$this->db->from('info_flash_use');
			$this->db->where('case_state',0);
		}
		$this->db->where('type',13);
		$this->db->where('site_id',SITEID);
		$this->db->where('index_id',INDEX_ID);
		$flash = $this->db->get()->row_array();
		$result = array();
		if(!empty($flash)){
			$arr = array('A','B','C','D','E');
			foreach ($arr as $k => $v) {
				 $img_field = 'img_'.$v;
				 $title_field = 'title_'.$v;
				 $url_field = 'url_'.$v;
	    	 	if(!empty($flash[$img_field]) && $flash[$img_field] != 1){
	    	 		$result[$v]['img'] = $flash[$img_field];
	    	 		$result[$v]['title'] = $flash[$title_field];
	    	 		$result[$v]['url'] = $flash[$url_field];
	    	 	}
			}
		}else{
			$result[0]['img'] = '';
	    	$result[0]['title'] = '您未上传轮播图，请后台添加！！！';
	    	$result[0]['url'] = '#';
		}
		return $result;
	}

	/*
	 *视讯
	 */
	function get_livetop() {
	    //视讯配置
	    $map = array();
	    $map['site_id'] = SITEID;
	    $map['index_id'] = INDEX_ID;

	    $video_config = $this->db->from('web_config')->where($map)->select('video_module')->get()->row_array();
	    $video_config = explode(',',$video_config['video_module']);
	    return $video_config;
    }

        //获取下拉菜单数组
    function get_xl_top() {
	    //获取电子配置
	    $map = array();
	    $map['site_id'] = SITEID;
	    $map['index_id'] = INDEX_ID;

	    $video_config = $this->db->from('web_config')->where($map)->select('video_module')->get()->row_array();
	    $video_config = explode(',',$video_config['video_module']);
        $video_arr =array('bbin','ag','og','mg','ct','lebo');
        $egame_arr =array('mg','ag','bbin','pt','eg');
        $this_video_arr = array();
        $this_egame_arr = array();
        foreach ($video_config as $k => $v) {
            if(in_array($v, $video_arr)){
                $this_video_arr[$k]['up'] =  strtoupper($v);
                $this_video_arr[$k]['low'] =  $v;
            }
            // if(in_array($v,$egame_arr)){
            //     $this_egame_arr[$k]['up'] =  strtoupper($v);
            //     $this_egame_arr[$k]['low'] =  $v;
            // }
        }

        //电子顺序匹配定位
        foreach ($egame_arr as $key => $val) {
            if(in_array($val,$video_config)){
                $this_egame_arr[$key]['up'] =  strtoupper($val);
                $this_egame_arr[$key]['low'] =  $val;
            }
        }

	    return array($this_video_arr,$this_egame_arr);
	}

	//获取视讯自定义图片
    public function get_video_imgs(){
    	//获取视讯自定义图片
    	$video_imgs = $tmp_video = array();
        $tmp_video = $this->db->from('info_video')->where(array('site_id'=>SITEID,'index_id'=>INDEX_ID))->select('type,img_url')->get()->result_array();

        foreach ($tmp_video as $key => $val) {
            $video_imgs[$val['type']] = $val;
        }
        return $video_imgs;
    }

    	/**
	 * 获取优惠分类
	 * @param  [array] $map [查询条件]
	 * @return [array]      [优惠]
	 * PK 黄
	 */
	public function get_promot_cate($map) {
		if($_SESSION['ty'] == 14){
			$data = $this->db->from("info_activity_edit")->where($map)->order_by('sort ASC')->get()->result_array();
		}else{
			$data = $this->db->from("info_activity_use")->where($map)->order_by('sort ASC')->get()->result_array();
		}
		return $data;
	}

	/**
	 * 优惠活动内页
	 * @return [array] [优惠活动分类和内容]
	 * PK 黄
	 */
	public function get_promotions(){
		$info = array();
		$info['index_id'] = INDEX_ID;
		$info['site_id'] = SITEID;
		$info['state'] = 1;
		$info['ctype'] = 2;
		$data = $this->get_promot_cate($info);
		if(!empty($data) && is_array($data)){
			foreach ($data as $key => $value) {
				$data[$key]['status'] = ','.$value['pid'];
			}
			$promotion['data'] = $data;
		}
		$info['ctype'] = 1;
		$cate = $this->get_promot_cate($info);
		if(!empty($cate) && is_array($cate)){
			$promotion['cate'] = $cate;
		}
		return $promotion;
	}

	//获取弹窗信息
	public function get_site_pop($type){
        $pop_config = array();

	    //是否开启多前台
	    $map['where']['index_id'] = INDEX_ID;
	    $map['table'] = 'site_ad';
	    $map['where']['site_id'] = SITEID;
	    $map['where']['is_delete'] = 1;
            $map['order'] = 'add_date desc';
	    $map['where']['ad_type'] = $type;
	    $pop = $this->rfind($map);

        if (!empty($pop)) {
        	if ($type == 1) {
        		$map_s['where']['site_id'] = SITEID;
				$map_s['where']['index_id'] = INDEX_ID;
				$map_s['table'] = 'site_pop_config';
        	    $pop_config = $this->rfind($map_s);
		        $pop['pop_config'] = $pop_config;
        	}
            $pop['pop_state'] = 1;
        }else{
        	$pop['pop_state'] = 0;
        }
        return $pop;
	}

	//获取网址底部导航
	public function get_meau_footer(){
        $map_mf = array();
		if(empty($_SESSION['ty']) || $_SESSION['ty'] > 8 || $_SESSION['ty'] < 3){
	    	$map_mf['table'] = 'info_iword_use';
	  	}elseif($_SESSION['ty'] >= 3 && $_SESSION['ty'] <= 8){      //预览文案
	  		$map_mf['table'] = 'info_iword_edit';
	  		$map_mf['where']['case_state'] = 0;
	  	}
		$map_mf['where']['site_id'] = SITEID;
		$map_mf['where']['index_id'] = INDEX_ID;
		$map_mf['where']['state'] = 1;
		$map_mf['order']= "sort desc";
		$meau_foot = $this->rget($map_mf);
		if ($meau_foot) {
		    $i = count($meau_foot);
			foreach ($meau_foot as $k => $v) {
				if ($i > ($k+1)) {
					$meau_foot[$k]['str_m'] = '|';
				}
			}
		}
		return $meau_foot;
	}

}