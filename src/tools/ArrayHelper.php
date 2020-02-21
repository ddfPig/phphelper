<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/21
 * Time: 14:50
 */

namespace tools\tools;
/** 数组常用操作函数
 * Class ArrayHelper
 * @package tools\tools
 */

class ArrayHelper
{
    //将数组转化成以逗号分隔的字符串，并且去掉最后的逗号
    public static function arrToStr($object,$field){
        $str = '';
        if ($object){
            foreach ($object as $value){
                $str .= $value[$field].',';
            }
        }
        return substr($str,0,-1);
    }

    /**
     * @desc 过滤数组元素前后空格 (支持多维数组)
     * @param $array 要过滤的数组
     * @return array|string
     */
    public static function trimArrayElement($array)
    {
        if (!is_array($array))
            return trim($array);
        return array_map('trim_array_element', $array);

    }

    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     * @param array $arr
     * @param boolean $trim
     */
    public static function array_remove_empty(& $arr, $trim = true)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                self::array_remove_empty($arr[$key]);
            } else {
                $value = trim($value);
                if ($value == '') {
                    unset($arr[$key]);
                } elseif ($trim) {
                    $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * @desc 将二维数组以元素的某个值作为键 并归类数组
     * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
     * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
     * @param $arr 数组
     * @param $key 分组值的key
     * @return array
     */
    public static function groupSameKey($arr, $key)
    {

        $new_arr = array();
        foreach ($arr as $k => $v) {
            $new_arr[$v[$key]][] = $v;
        }
        return $new_arr;

    }

    /**
     * @desc 多维数组转化为一维数组
     * @param $array 多维数组
     * @return array 一维数组
     */
    public static function arrayMulti2single($array)
    {
        static $result_array = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                self::arrayMulti2single($value);
            } else
                $result_array [] = $value;
        }
        return $result_array;
    }

    /**将二维数组转为mysql 带引号的字符串
     * @param $data
     * @return string
     */
    public static function mysql_in_str($data)
    {
        return join( ', ',array_map(function( $v ){  return "'".$v."'";},$data) );
    }

    /**
     * @desc 替换数组中的某个value值
     * @param string $find 要替换的字符串
     * @param string $replace 要被替换成什么的字符串
     * @param array $list 要替换的数组
     * @return array|mixed
     */
    public static function arrayValueReplace($find = '', $replace = '', $list = [])
    {

        if (empty($find)) {
            return $list;
        }
        $find = ":" . json_encode($find);
        $replace = ":" . json_encode($replace);
        $str = json_encode($list);
        $replace_str = str_replace($find, $replace, $str);
        $list = json_decode($replace_str, true);

        return $list;

    }

    /**
     * @desc 对象转数组，PHP stdClass Object转array
     * @param $array
     * @return array
     */
    public static function object_array($array)
    {

        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::object_array($value);
            }
        }
        return $array;

    }

    /**
     * @desc 去除二维数组中的重复项
     * @param $array
     * @return array
     */
    public static function removeArrayDuplicate($array)
    {
        $result = array();
        for ($i = 0; $i < count($array); $i++) {
            $source = $array[$i];
            if (array_search($source, $array) == $i && $source <> "") {
                $result[] = $source;
            }
        }
        return $result;
    }

    /**
     * @desc 二维数组根据字段进行排序
     * @params array $array 需要排序的二维数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }
}