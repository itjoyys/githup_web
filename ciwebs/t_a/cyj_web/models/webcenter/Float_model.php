<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Float_model extends MY_Model {
	function __construct() {
		$this->init_db();
	}

	//获取浮动配置
	public function get_float_conf(){
		$this->db->from('info_float_c_edit');
		$this->db->where('site_id', SITEID);
		$this->db->where('index_id', INDEX_ID);
		$this->db->where('state', 1);
		return $this->db->get()->result_array();
	}

	//处理左右浮动数据
	public function get_allfloat(){

		$float = $this->get_float_conf();

        if(!empty($float) && is_array($float)){
        	foreach ($float as $key => $value) {
        		$id[] = $value['id'];
        		if($value['type'] == 16){
        			$typel = $value['id'];
        		}elseif($value['type'] == 17){
        			$typer = $value['id'];
        		}
        		
        	}
        	$map['where']['fid'] = '(' . implode(',', $id) . ')';
        	$map['order'] = 'sort ASC';
	        $floatall = $this->get_float($map);

	        foreach ($floatall as $k => $val) {
	        	$sort = $val['sort'];
	        	if($val['fid'] == $typel){

	        		$data['floatl'][$sort] = $val;

	        		if($val['eff'] == 0){
						$data['floatl'][$sort]['img_B'] = $val['img_A'];
					}
					if($val['is_blank'] == 1){
						$data['floatl'][$sort]['b_str'] = 'target="_blank"';
					}
					if($val['is_inter'] == 0){
						$data['floatl'][$sort]['click'] = $this->geturl($val['urltype']);
						$data['floatl'][$sort]['url'] = "javascript:void(0);";
					}else{
						$data['floatl'][$sort]['click'] = '';
					}
	        	}elseif($val['fid'] == $typer){
	        		
        			$data['floatr'][$sort] = $val;

        			if($val['eff'] == 0){
						$data['floatr'][$sort]['img_B'] = $val['img_A'];
					}
					if($val['is_blank'] == 1){
						$data['floatr'][$sort]['b_str'] = 'target="_blank"';
					}
					if($val['is_inter'] == 0){
						$data['floatr'][$sort]['click'] = $this->geturl($val['urltype']);
						$data['floatr'][$sort]['url'] = "javascript:void(0);";
					}else{
						$data['floatr'][$sort]['click'] = '';
					}
        		}
	        }
	        
	        $data['floatl'] = array_values($data['floatl']);
	        $data['floatr'] = array_values($data['floatr']);
	        $closel = count($data['floatl']);
	        $closer = count($data['floatr']);
			if($closel > 0){
				$data['floatl'][$closel-1]['click'] = 'FloatClose(this);';
				$data['floatl'][$closel-1]['url'] = "javascript:void(0);";
				$data['floatl'][$closel-1]['b_str'] = '';
			}else{
				$data['floatl'] = "";
			}
	        if($closer > 0){
			$data['floatr'][$closer-1]['click'] = 'FloatClose(this);';
			$data['floatr'][$closer-1]['url'] = "javascript:void(0);";
			$data['floatr'][$closer-1]['b_str'] = '';
			}else{
				$data['floatr'] = "";
			}
        }
        return $data;
	}

	/**
	 * [get_float 获取浮动数据]
	 * @param  array   $map   [查询条件]
	 * @return [array]        [description]
	 * PK 黄
	 */
	public function get_float($map){
		$map['where']['state'] = 1;
		foreach ($map['where'] as $k => $v) {
			
			if($k == 'fid'){
				$sql .= "$k in $v";
			}else{
				if(empty($sql)){
					$sql .= "$k = '$v' and ";
				}else{
					$sql .= "and $k = '$v' ";
				}
			}
		}
		$this->db->from('info_float_list_edit')->where($sql);
		if(!empty($map['order'])){
			$this->db->order_by($map['order']);
		}

		$dataf = $this->db->get()->result_array();
		return $dataf;
	}

	//获取URL
	public function geturl($type){
		switch ($type) {
			case '1':
		        return "getPager('-','sports','m');";
		        break;
		      case '2':
		        return "getPager('-','livetop','m');";
		        break;
		      case '3':
		        return "getPager('-','egame','m');";
		        break;
		      case '4':
		        return "getPager('-','lottery');";
		        break;
		      case '5':
		        return "getPager('-','youhui');";
		        break;
		      case '6':
		        return "getPager('-','iword','5');";
		        break;
		      case '7':
		        return "getPager('-','shiwan_reg');";
		        break;
		      case '8':
		        return "getPager('-','iword','8');";
		        break;
		      case '9':
		        return "getPager('-','iword','3');";
		        break;
		      case '10':
		        return "getPager('-','iword','4');";
		        break;
		      case '11':
		        return "getPager('-','zhuce');";
		        break;
		      case '12':
		        return "getPager('-','iword','6');";
		        break;
		      case '13':
		        return "getPager('-','iword','7');";
		        break;
			  case '14':
		        return "getPager('-','detect');";
		        break;
    	}
	}
}


?>