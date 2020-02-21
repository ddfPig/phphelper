<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/21
 * Time: 15:43
 */

namespace tools\tools;
/** 无限分类
 * Class Infinite
 * @package tools\tools
 */

class Infinite
{
    /**
     * @param $cate
     * @param string $html
     * @param int $pid
     * @param int $level
     * @return array
     */
     public static function catesort($cate, $html = '&nbsp;&nbsp;&nbsp;--', $pid = 0, $level = 0){
        $arr = array();
        foreach($cate as $v){
            if ($v['pid'] == $pid){
                $v['level'] = $level +1;
                $v['html'] = str_repeat($html, $level);
                $arr[] = $v;
                $arr = array_merge($arr, self::catesort($cate, $html, $v['id'], $level + 1));

            }
        }
        return $arr;
    }

    /**
     * @param $cate
     * @param string $name
     * @param int $pid
     * @return array
     */
     public static function catesortforlayer($cate, $name = 'child', $pid = 0){
        $arr = array();
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $v[$name] = self::catesortforlayer($cate, $name, $v['id']);
                $arr[] = $v;

            }
        }
        return $arr;
    }

    /**
     * @param $cate
     * @param $id
     * @return array
     */
     public static function getParents ($cate, $id){
        $arr = array();
        foreach($cate as $v){
            if($v['id'] == $id){
                $arr = array_merge($arr, self::getParents($cate, $v['pid']));
                $arr[] = $v;
            }
        }
        return $arr;
    }

    /**
     * @param $cate
     * @param $pid
     * @return array
     */
     public static function getChildsId($cate, $pid){
        $arr = array();
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $arr[] = $v['id'];
                $arr = array_merge($arr, self::getChildsId($cate, $v['id']));
            }
        }
        return $arr;
    }

    /**
     * @param $cate
     * @param $pid
     * @return array
     */
     public static function getChilds($cate, $pid){
        $arr = array();
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $arr[] = $v;
                $arr = array_merge($arr, self::getChildsId($cate, $v['id']));
            }
        }
        return $arr;
    }
}