<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/21
 * Time: 15:59
 */

namespace tools\tools;
/** 时间处理函数
 * Class DateHelper
 * @package tools\tools
 */

class DateHelper
{
    /**
     * @desc 得到某天凌晨零点的时间戳
     * @param string $str
     * @return int
     */
    public static function getSomeZeroTimeStamp($str = 'today')
    {

        switch ($str) {
            case 'today':   // 今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"), time());
                break;
            case 'yesterday':   // 昨天 即 今天凌晨零点的时间戳 减去 一天的秒数
                return strtotime(date("Y-m-d"), time()) - 3600 * 24;
                break;
            case 'tomorrow':    // 明天 即 今天凌晨零点的时间戳 加上 一天的秒数
                return strtotime(date("Y-m-d"), time()) + 3600 * 24;
                break;
            case 'month_first': // 这个月第一天凌晨零点的时间戳
                return strtotime(date("Y-m"), time());
                break;
            case 'year_first':  // 这一年第一天凌晨零点的时间戳
                return strtotime(date("Y-01"), time());
                break;
            default:   // 默认为今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"), time());
                break;
        }

    }

    /**
     * @desc 友好时间显示
     * @param $time 时间戳
     * @param string $lang $lang 语言, cn 中文, en 英文
     * @return bool|string
     */
    public static function get_friend_date($time, $lang = 'cn')
    {
        if (!$time) {
            return '';
        }
        $f_date = '';
        $d = time() - intval($time);
        $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
        $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
        $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
        $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
        $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
        $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
        $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
        if ($lang == 'cn') {
            if ($d <= 10) {
                $f_date = '刚刚';
            } else {
                switch ($d) {
                    case $d < $atd:
                        $f_date = date('Y年m月d日', $time);
                        break;
                    case $d < $td:
                        $f_date = '后天' . date('H:i', $time);
                        break;
                    case $d < 0:
                        $f_date = '明天' . date('H:i', $time);
                        break;
                    case $d < 60:
                        $f_date = $d . '秒前';
                        break;
                    case $d < 3600:
                        $f_date = floor($d / 60) . '分钟前';
                        break;
                    case $d < $dd:
                        $f_date = floor($d / 3600) . '小时前';
                        break;
                    case $d < $yd:
                        $f_date = '昨天' . date('H:i', $time);
                        break;
                    case $d < $byd:
                        $f_date = '前天' . date('H:i', $time);
                        break;
                    case $d < $md:
                        $f_date = date('m月d日 H:i', $time);
                        break;
                    case $d < $ld:
                        $f_date = date('m月d日', $time);
                        break;
                    default:
                        $f_date = date('Y年m月d日', $time);
                        break;
                }
            }
        } else {
            if ($d <= 10) {
                $f_date = 'just';
            } else {
                switch ($d) {
                    case $d < $atd:
                        $f_date = date('Y-m-d', $time);
                        break;
                    case $d < $td:
                        $f_date = 'the day after tomorrow' . date('H:i', $time);
                        break;
                    case $d < 0:
                        $f_date = 'tomorrow' . date('H:i', $time);
                        break;
                    case $d < 60:
                        $f_date = $d . 'seconds ago';
                        break;
                    case $d < 3600:
                        $f_date = floor($d / 60) . 'minutes ago';
                        break;
                    case $d < $dd:
                        $f_date = floor($d / 3600) . 'hour ago';
                        break;
                    case $d < $yd:
                        $f_date = 'yesterday' . date('H:i', $time);
                        break;
                    case $d < $byd:
                        $f_date = 'the day before yesterday' . date('H:i', $time);
                        break;
                    case $d < $md:
                        $f_date = date('m-d H:i', $time);
                        break;
                    case $d < $ld:
                        $f_date = date('m-d', $time);
                        break;
                    default:
                        $f_date = date('Y-m-d', $time);
                        break;
                }
            }
        }
        return $f_date;

    }

    /**
     * @desc 获取当前时间的前7天
     * @return array
     */
    public static function getLast7Days()
    {

        $begin = strtotime(date('Y-m-d', strtotime('-6 days')));  // ? 7天前
        $today_time = strtotime(date('Y-m-d'));  // ? 7天前
        $now_time = time();
        $weeks_arr = array();
        $weeks_arr['date'] = array();
        $weeks_arr['weeks'] = array();
        $weeks_arr['day'] = array();
        $weeks_array = array("日", "一", "二", "三", "四", "五", "六"); // 先定义一个数组
        $day_second = 3600 * 24;
        for ($i = $begin; $i < $now_time; $i = $i + $day_second) {
            if ($i != $today_time) {
                array_push($weeks_arr['date'], $i);
            } else {
                array_push($weeks_arr['date'], $now_time);
            }
            array_push($weeks_arr['weeks'], '星期' . $weeks_array[date('w', $i)]);
            array_push($weeks_arr['day'], date('Y-m-d', $i));
        }
        return $weeks_arr;

    }

    /**
     * @desc 获取星期几的信息
     * @param $timestamp 时间戳
     * @param string $lang 语言, cn 中文, en 英文
     * @return mixed
     */
    public static function get_week_day($timestamp, $lang = 'cn')
    {

        if ($lang == 'cn') {
            $week_array = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            return $week_array[date("w", $timestamp)];
        } else {
            return date("l"); // date("l") 可以获取英文的星期比如Sunday
        }

    }


    /**
     * @desc 获取月份
     * @param $timestamp 时间戳
     * @param string $lang cn 中文, en 英语
     * @return string
     */
    public static function get_month($timestamp, $lang = 'cn')
    {

        if ($lang == 'cn') {
            $month_arr = array(
                '1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'
            );
        } else {
            $month_arr = array(
                'Jan.', 'Feb.', 'Mar.', 'Apr.', 'May.', 'Jun.', 'Jul.', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.'
            );
        }
        $month = date('n', $timestamp);
        return $month_arr[$month - 1];

    }

    /**
     * 获取时间间隔
     * @param $startTime 开始时间
     * @param $endTime 结束时间
     * @return String
     */
    public static function getDurationTime($startTime, $endTime)
    {
        if (empty($startTime)) {
            return "";
        } else {
            $endTime = empty($endTime) ? time() : strtotime($endTime);
            $durationTime = $endTime - strtotime($startTime);
            return self::time2Units($durationTime);
        }
    }

    /**
     * 时间差计算
     *
     * @param Timestamp $time
     * @return String Time Elapsed
     * @author Shelley Shyan
     * @copyright http://phparch.cn (Professional PHP Architecture)
     */
    private static function time2Units($time)
    {
        $year = floor($time / 60 / 60 / 24 / 365);
        $time -= $year * 60 * 60 * 24 * 365;
        $month = floor($time / 60 / 60 / 24 / 30);
        $time -= $month * 60 * 60 * 24 * 30;
        $week = floor($time / 60 / 60 / 24 / 7);
        $time -= $week * 60 * 60 * 24 * 7;
        $day = floor($time / 60 / 60 / 24);
        $time -= $day * 60 * 60 * 24;
        $hour = floor($time / 60 / 60);
        $time -= $hour * 60 * 60;
        $minute = floor($time / 60);
        $time -= $minute * 60;
        $second = $time;
        $elapse = '';

        $unitArr = array('年' => 'year', '个月' => 'month', '周' => 'week', '天' => 'day',
            '小时' => 'hour', '分钟' => 'minute', '秒' => 'second'
        );

        foreach ($unitArr as $cn => $u) {
            if ($$u > 0) {
                $elapse = $$u . $cn;
                break;
            }
        }

        return $elapse;
    }

    /**  两个时间相差的天数，不满1为1，注意ceil函数
     * @param int $ntime 当前时间
     * @param int $ctime 减少的时间
     * @return int
     */
    public static function SubTime($ntime, $ctime)
    {
        $dayst = 3600 * 24;
        $cday = ceil(($ntime - $ctime) / $dayst);
        return $cday;
    }

    /**
     *  增加减少天数
     * @param     int $ntime 当前时间
     * @param     int $aday 增加天数用正数，减少天数用负数
     * @return    int
     */
    public static function AddSubDay($ntime, $aday)
    {
        $dayst = 3600 * 24;
        $oktime = $ntime + ($aday * $dayst);
        return $oktime;
    }

    /**
     *  返回格式化(Y-m-d H:i:s)格式的时间
     *
     * @param     int $mktime 时间戳
     * @return    string
     */
    public static function GetDateTimeMk($mktime)
    {
        return self::MyDate('Y-m-d H:i:s', $mktime);
    }

    /**
     *  返回格式化(Y-m-d)的日期
     * @param     int $mktime 时间戳
     * @return    string
     */
    public static function GetDateMk($mktime)
    {
        if ($mktime == "0") return "暂无";
        else return self::MyDate("Y-m-d", $mktime);
    }

    /**
     *  将时间转换为距离现在的精确时间
     *  10小时前
     * @param     int $seconds 秒数
     * @return    string
     */
    public static function FloorTime($seconds)
    {
        $times = '';
        $days = floor(($seconds / 86400) % 30);
        $hours = floor(($seconds / 3600) % 24);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = floor($seconds % 60);
        if ($seconds >= 1)
            $times .= $seconds . '秒';
        if ($minutes >= 1)
            $times = $minutes . '分钟 ' . $times;
        if ($hours >= 1)
            $times = $hours . '小时 ' . $times;
        if ($days >= 1)
            $times = $days . '天';
        if ($days > 30)
            return false;
        $times .= '前';
        return str_replace(" ", '', $times);
    }


    /**
     *  返回格林威治标准时间
     *
     * @param     string $format 字符串格式
     * @param     string $timest 时间基准
     * @return    string
     */
    public static function MyDate($format = 'Y-m-d H:i:s', $timest = 0)
    {
        $cfg_cli_time = 8;
        $addtime = $cfg_cli_time * 3600;
        if (empty($format)) {
            $format = 'Y-m-d H:i:s';
        }
        return gmdate($format, $timest + $addtime);
    }

}