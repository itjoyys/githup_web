<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 报告
 */
class Account_search_model extends MY_Model
{

    function __construct()
    {
        // $this->init_db();
    }

    // 组合多维数组
    static function arrayLevel($meau, $pid = 0)
    {
        $arr = array();
        foreach ($meau as $v) {
            if ($v['pid'] == $pid) {
                $v['child'] = $this->arrayLevel($meau, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }

    // 传递子分类ID返回所有的父级分类
    Public function getParents($agent, $id)
    {
        $arr = array();
        foreach ($agent as $v) {
            if ($v['id'] == $id) {
                $arr[$v['agent_type']] = $v['agent_user'];
                $arr['intr'] = $v['intr'];
                $arr = array_merge($this->getParents($agent, $v['pid']), $arr);
            }
        }
        return $arr;
    }
    // 返回下级数量
    Public function getChildsCount($cate, $pid, $cate_type1, $cate_type2)
    {
        $arr = array();
        foreach ($cate as $k=> $v) {
            if ($v['pid'] == $pid) {
                if ($v['agent_type'] == $cate_type1) {
                    $arr[$cate_type1] += 1;
                } elseif ($v['agent_type'] == $cate_type2) {
                    $arr[$cate_type2] += 1;
                }
                $arr = array_add($arr, $this->getChildsCount($cate, $v['id'], $cate_type1, $cate_type2));
            }
        }
        return $arr;
    }

    // 传递股东id，返回所有代理商id $cate数组 $pid父id $cate_type 子类 比如u_a a_t
    Public function getChildsId($cate, $pid, $cate_type)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                if ($v['agent_type'] == $cate_type) {
                    $arr[] = $v['id'];
                }
                $arr = array_merge($arr, $this->getChildsId($cate, $v['id'], $cate_type));
            }
        }
        return $arr;
    }
    // 传递父级ID返回所有子分类
    Public function getChilds($cate, $pid)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, $this->getChilds($cate, $v['id']));
            }
        }
        return $arr;
    }

    // 传递父级ID返回下级子分类
    Public function getChild($cate, $pid)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
            }
        }
        return $arr;
    }
}