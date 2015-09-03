<?php

Class CommonModel extends Model{

    function is_empty($tab){
    	if (empty($tab)) {
    		exit('table empty');
    	}
    }
    public function rselect($tab,$field,$map,$order){
    	self::is_empty($tab);
    	$obj_m = M($tab);
    	if (!empty($field)) {
    		$obj_m->field($field);
    	}
    	if (!empty($map)) {
    		$obj_m->where($map);
    	}
        if (!empty($order)) {
        	$obj_m->order($order);
        }
    	return $obj_m->select();
    }

    public function rfind($tab,$field,$map){
    	self::is_empty($tab);
    	$obj_m = M($tab);
    	if (!empty($field)) {
    		$obj_m->field($field);
    	}
    	if (!empty($map)) {
    		$obj_m->where($map);
    	}
    	return $obj_m->find();
    }

    public function rupdate($tab,$map,$arr){
    	self::is_empty($tab);
    	$obj_m = M($tab);
    	if (!empty($map)) {
    		$obj_m->where($map);
    	}
    	return $obj_m->update($arr);
    }

    public function radd($tab,$arr){
    	self::is_empty($tab);
    	$obj_m = M($tab);
    	return $obj_m->add($arr);
    }
}
?>