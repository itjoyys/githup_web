<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Odds_set_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取彩票结果
	public function get_fc_odds($type,$map){
		$db_model['tab'] = 'c_odds_'.$type;
		$db_model['type'] = 2;

        return $this->M($db_model)->where($map)->select();
	}

	//通用赔率设置
	public function odds_set_do($type,$odds,$h_s,$h_e,$b_s,$b_e){
		$arr = array();

		//判断是否开启或者
		if(strstr($h_s,'-')){
            $hs_tmp = array();
		    $hs_tmp = explode('-',$h_s);
		    foreach ($hs_tmp as $hk => $hv) {
		        $arr['h'.$hv] = $odds;
		    }
		}else{
			//按照区间处理数据
            for ($i=$h_s; $i <= $h_e; $i++) {
			    $arr['h'.$i] = $odds;
			}
		}
        $map['site_id'] = $_SESSION['site_id'];

		//条件拼接
		$str_ball = array();
		for ($j=$b_s; $j<= $b_e; $j++) {
		    $str_ball[] = 'ball_'.$j;
		}

		$map['type'] = array('in',"('".implode("','", $str_ball)."')");
        $log = $this->Odds_set_model->M(array('tab'=>'c_odds_'.$type,'type'=>2))->where($map)->update($arr);
        //删除缓存key
        $this->del_redis_odds($type);
        return $log;
	}

		//删除缓存的赔率
	public function del_redis_odds($type){
		  //删除缓存key
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $tmp_arr = array('特码'=>'k_tm','正码'=>'k_zm','正特'=>'k_zt','正1-6'=>'k_zm6','生肖'=>'k_sx6','全不中'=>'k_wbz');
        if (is_numeric($type)) {
            if ($type == 7) {
                foreach ($tmp_arr as $key => $val) {
                    $redis_key = $_SESSION['site_id'].$val.'_c_odds_7';
		            $redis->delete($redis_key); //清除redis里面的键值
                }
            }else{
                $redis_key = $_SESSION['site_id'].'_c_odds_'.$type;
        	    $redis->delete($redis_key); //清除redis里面的键值
            }
        }else{
		    $redis_key = $_SESSION['site_id'].$tmp_arr[$type].'_c_odds_7';
		    $redis->delete($redis_key); //清除redis里面的键值
        }
	}

	public function odds_set7_do($type,$odds,$b_s,$b_e){
        $map['site_id'] = $_SESSION['site_id'];
        if ($type[1] == '正码1') {
            $map['class2'] = array('in',"('正码1','正码2','正码3','正码4','正码5','正码6')");
        }elseif($type[1] == '正1特'){
            $map['class2'] = array('in',"('正1特','正2特','正3特','正4特','正5特','正6特')");
        }else{
        	$map['class2'] = $type[1];
        }
        $map['class1'] = $type[0];

		//条件拼接
		if(strstr($b_s,'-')){
            $hs_tmp = array();
		    $hs_tmp = explode('-',$b_s);
		    foreach ($hs_tmp as $hk => $hv) {
		        $str_ball[] = $hv;
		    }
		}else{
			//为空单项数据 非空为区间数据
			if (empty($b_e)) {
				$str_ball[] = $b_s;
			}else{
			    for ($j=$b_s; $j<= $b_e; $j++) {
				    $str_ball[] = $j;
				}
			}

		}

		$map['class3'] = array('in',"('".implode("','", $str_ball)."')");
		//return $map;
        $log = $this->Odds_set_model->M(array('tab'=>'c_odds_7','type'=>2))->where($map)->update(array('rate'=>$odds));

        $this->del_redis_odds($type[0]);
        return $log;
	}



	//六合彩
	function result_7($arr = array()){
        $num['tmis_even'] = $this->single_double($arr['na']);//特码单双
        $num['tmis_big'] = $this->big_small($arr['na'],25); //特码大小
        $num['tmis_color'] = $this->tm_sebo($arr['na']);//特码色波
        $num['sum'] = $this->lhc_sum($arr);//总和
        $num['sumis_big']=$this ->big_small($num['sum'],49*6/2); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['sum_hsdx']=$this ->liuhecai_hsdx($num['sum']); //合数大小
        $num['sum_hsds'] = $this->liuhecai_hsds($num['sum']);//合数单双
        $num['sum_shengxiao'] = $this->shengxiao($arr);//生肖
        return $num;
	}

	//福彩3D
	function title_5(){
        return array('ball_1'=>'第一球','ball_2'=>'第二球',
        	              'ball_3'=>'第三球','ball_4'=>'总和/龙虎',
        	              'ball_5'=>'三连','ball_6'=>'跨度','ball_7'=>'独胆');

	}
	//排列3
	function result_6($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,6);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,6);//三连
        return $num;
	}

	//重庆时时彩
	function result_2($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,2);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//天津时时彩
	function result_10($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,10);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//江西时时彩
	function result_11($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,11);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//新疆时时彩
	function result_12($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,12);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}

	//北京赛车PK
	function result_3($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],11); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        return $num;
	}

	//北京快乐8
	function result_8($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],11); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        return $num;
	}

	//广东快乐10分
	function result_1($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->gd_dx($num['sum']); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['subws'] = $this->gd_wdx($num['sum']);//尾数大小
         $num['sumis_tiger'] = $this->gd_tiger($arr);//龙虎
        return $num;
	}

	//重庆快乐10分
	function result_4($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->gd_dx($num['sum']); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['subws'] = $this->gd_wdx($num['sum']);//尾数大小
        $num['sumis_tiger'] = $this->gd_tiger($arr);//龙虎
        return $num;
	}

	//江苏快3
	function result_13($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,13);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,13);//三连
        return $num;
	}

	//吉林快3
	function result_14($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,14);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,14);//三连
        return $num;
	}

}