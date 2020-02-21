<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/21
 * Time: 16:03
 */

namespace tools\tools;
/** 常规函数
 * Class Functions
 * @package tools\tools
 */

class Functions
{
    /**
     * 保留小数
     * @param $number 数字
     * @param int $num 几位小数
     * @return float|int
     */
    public static function decimal($number, $num = 2)
    {
        $number = round($number, $num);
        $number1 = intval($number);
        $number2 = $number > $number1 ? $number : $number1;
        return $number2;

    }

    /**
     * 截取字符串
     * @param $str
     * @param $start
     * @param $lenth
     * @return string
     */
    public static function subStr($str, $start, $lenth)
    {
        $str1 = mb_substr($str, $start, $lenth, 'UTF8');
        return $str == $str1 ? $str : $str1 . '...';
    }

    /**
     * 格式化数字
     * @param $number 要格式化的数值
     * @param $type 类型 normal,electric
     * @param $type 单位后缀
     */
    public static function numberFormat($number, $type = 'normal', $extUnit = '')
    {
        /**
         * @var array 数据格式化类型
         */
        $FORMAT_TYPE = array(
            'normal' => array(
                array(
                    'base' => 100000000,
                    'unit' => '亿'
                ),
                array(
                    'base' => 10000,
                    'unit' => '万'
                ),
                array(
                    'base' => 1000,
                    'unit' => '千'
                ),
                array(
                    'base' => 1,
                    'unit' => ''
                )
            ),
            'electric' => array(
                array(
                    'base' => 1000000000000,
                    'unit' => 'TW'
                ),
                array(
                    'base' => 1000000000,
                    'unit' => 'GW'
                ),
                array(
                    'base' => 1000000,
                    'unit' => 'MW'
                ),
                array(
                    'base' => 1000,
                    'unit' => 'KW'
                ),
                array(
                    'base' => 1,
                    'unit' => 'W'
                )
            )
        );

        $FORMAT_TYPE_ITEM = $FORMAT_TYPE[$type];
        //$keys=array_keys($FORMAT_TYPE_ITEM);
        //var_dump($FORMAT_TYPE_ITEM[$keys[0]]);exit();
        if (empty($number)) {
            $arr['value'] = 0;
            $arr['unit'] = '';
            $arr['base'] = 1;
            return $arr;
        }
        return self::getMaxUnit($number, $FORMAT_TYPE_ITEM, $extUnit);

    }

    /**
     * 获取最大单位数据
     * @param $number 数字
     * @param $AllArr 数据类型配置
     * @param string $extUnit 单位
     * @param array $currArr 当前数据
     * @return array
     */
    public static function getMaxUnit($number, $AllArr, $extUnit = '', $currArr = array())
    {
        //if($index>count($desArr)) return $returnArr;
        if (empty($currArr)) {
            $currArr = $AllArr[0];
        }
        $key = $currArr['base'];
        $value = $currArr['unit'];

        $leaveNum = $number / $key;
        $resdata = array();
        //arrayStep::getInstance($AllArr)->setCurrent($key);
        if ($leaveNum > 0 && $leaveNum < 1) {
            //$nextArr=arrayStep::getInstance($AllArr)->getNext();
            $nextArr = next($AllArr);//var_dump($nextArr);
            $data = self::getMaxUnit($number, $AllArr, $extUnit, $nextArr);
            if (!empty($data)) return $data;
        } else {
            $resdata = array(
                'value' => number_format(round($leaveNum)),
                'unit' => $value . $extUnit,
                'base' => $key
            );
            //var_dump($resdata);
            return $resdata;
            exit();
        }
    }

    /**
     * 简单加密处理函数
     * @param $data
     * @return string
     */
    public static function encodeData($data)
    {
        return urlencode(json_encode($data));
    }

    /**
     * 简单解密处理函数
     * @param $data
     * @return mixed
     */
    public static function decodeData($data)
    {
        return json_decode(urldecode($data), true);
    }

    /**
     * @desc 不足时几位数时，前面补零
     * @param $len
     * @param $number
     * @return string
     */
    public static function fillZero($len = 0, $number = 0)
    {

        return sprintf("%0" . $len . "d", $number);//生成4位数，不足前面补0

    }


    /**
     * @desc 转换字节数为其他单位
     * @param    string $file_size 字节大小
     * @return    string    返回大小
     */
    public static function sizeCount($file_size = 0)
    {

        if ($file_size >= 1073741824) {
            $file_size = round($file_size / 1073741824 * 100) / 100 . ' GB';
        } elseif ($file_size >= 1048576) {
            $file_size = round($file_size / 1048576 * 100) / 100 . ' MB';
        } elseif ($file_size >= 1024) {
            $file_size = round($file_size / 1024 * 100) / 100 . ' KB';
        } else {
            $file_size = $file_size . ' Bytes';
        }
        return $file_size;

    }

    /**
     * @desc 字符串截取，支持中文和其他编码, 字符串截取是一个开发者都要面对的基本技能，毕竟你要处理数据，支持中文和其他编码
     * @desc 这里的关键，$charset="utf-8"，对中文支持是很重要的！不然出现一些？号，就要挨批了！
     * @param [string] $str  [字符串]
     * @param integer $start [起始位置]
     * @param integer $length [截取长度]
     * @param string $charset [字符串编码]
     * @param boolean $suffix [是否有省略号]
     * @return [type] [description]
     */
    public static function mSubStr($str, $start = 0, $length = 15, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            return mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix) {
            return $slice . "…";
        }
        return $slice;
    }

    /**
     * @desc PHP利用正则表达式实现手机号码中间4位用星号替换显示
     * @param $phone
     * @return null|string|string[]
     */
    public static function hideTel($phone)
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i', $phone); //固定电话
        if ($IsWhat == 1) {
            return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i', '$1****$2', $phone);
        } else {
            return preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
        }
    }

    /**
     * @desc 判断字符串是utf-8 还是gb2312
     * @param $str
     * @param string $default
     * @return string
     */
    public static function is_utf8_gb2312($str, $default = 'gb2312')
    {
        $str = preg_replace("/[\x01-\x7F]+/", "", $str);
        if (empty($str)) return $default;
        $preg = array(
            "gb2312" => "/^([\xA1-\xF7][\xA0-\xFE])+$/", //正则判断是否是gb2312
            "utf-8" => "/^[\x{4E00}-\x{9FA5}]+$/u",      //正则判断是否是汉字(utf8编码的条件了)，这个范围实际上已经包含了繁体中文字了
        );
        if ($default == 'gb2312') {
            $option = 'utf-8';
        } else {
            $option = 'gb2312';
        }
        if (!preg_match($preg[$default], $str)) {
            return $option;
        }
        $str = @iconv($default, $option, $str);
        //不能转成 $option, 说明原来的不是 $default
        if (empty($str)) {
            return $option;
        }
        return $default;
    }

    /**
     * @desc utf-8和gb2312自动转化
     * @param $string
     * @param string $outEncoding
     * @return string
     */
    public static function safeEncoding($string, $outEncoding = 'UTF-8')
    {
        $encoding = "UTF-8";
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string{$i}) < 128)
                continue;
            if ((ord($string{$i}) & 224) == 224) {
                // 第一个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    // 第二个字节判断通过
                    $char = $string{++$i};
                    if ((ord($char) & 128) == 128) {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if ((ord($string{$i}) & 192) == 192) {
                // 第一个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }
        if (strtoupper($encoding) == strtoupper($outEncoding))
            return $string;
        else
            return @iconv($encoding, $outEncoding, $string);
    }


    /**
     * @desc 将用户名进行处理，中间用星号表示，可用于中文
     * @param $user_name
     * @return string
     */
    public static function subStrCut($user_name)
    {

        //获取字符串长度
        $strlen = mb_strlen($user_name, 'utf-8');
        //如果字符创长度小于2，不做任何处理
        if ($strlen < 2) {
            return $user_name;
        } else {
            //mb_substr — 获取字符串的部分
            $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
            $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
            //str_repeat — 重复一个字符串
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
        }

    }


    /**
     * @desc 随机字符串生成
     * @param int $len 生成的字符串长度
     * @return string
     */
    public static function randomString($len = 6)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }


    /**
     * @desc 随机字符
     * @param number $length 长度
     * @param string $type 类型
     * @param number $convert 转换大小写
     * @return string
     */
    public static function random($length = 6, $type = 'string', $convert = 0)
    {

        $config = array(
            'number' => '1234567890',
            'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
            'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );

        if (!isset($config[$type])) $type = 'string';
        $string = $config[$type];

        $code = '';
        $strlen = strlen($string) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $string{mt_rand(0, $strlen)};
        }
        if (!empty($convert)) {
            $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
        }
        return $code;
    }


    /**
     * @desc 查询字符是否存在于某字符串
     * @param $haystack 字符串
     * @param $needle 要查找的字符
     * @return bool
     */
    public static function strExists($haystack, $needle)
    {
        return !(strpos($haystack, $needle) === FALSE);
    }


    /**
     * @desc 实现中文字串截取无乱码的方法
     * @param $string
     * @param $start
     * @param $length
     * @return string
     */
    public static function getSubstr($string, $start, $length)
    {
        if (mb_strlen($string, 'utf-8') > $length) {
            $str = mb_substr($string, $start, $length, 'utf-8');
            return $str . '...';
        } else {
            return $string;
        }
    }


    /**
     * @desc 手机号隐藏中间
     * @param $mobile
     * @return mixed
     */
    public static function mobileHide($mobile)
    {
        return substr_replace($mobile, '****', 3, 4);

    }

    //用户数据加密 加密数据  会员显示信息加星，身份证，手机号，卡号等
    public static function hideInfo($data,$num,$numb){
        $length = mb_strlen($data,'utf8')-$num-$numb;
        $str = str_repeat("*",$length);//替换字符数量
        $re = substr_replace($data,$str,$num,$length);
        return $re;
    }


    /**
     * @desc 自动转换字符集 支持数组转换
     * @param $string 需要转换的字符串或数组
     * @param string $from 以什么字符集编码开始转换
     * @param string $to 转换成什么字符集编码
     * @return array|string
     */
    public static function autoCharset($string, $from = 'gbk', $to = 'utf-8')
    {

        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
            //如果编码相同或者非字符串标量则不转换
            return $string;
        }
        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } elseif (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);
                if ($key != $_key) {
                    unset($string[$key]);
                }

            }
            return $string;
        } else {
            return $string;
        }

    }

    /**
     * @desc 获取当前页面的URL
     * @return string
     */
    public static function getCurrentPageURL()
    {

        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;

    }


    /**
     * @desc 获取当前页域名的URL
     * @return string
     */
    public static function getDomainName()
    {

        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":";
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL;

    }


    /**
     * @desc 解析url并得到url中的参数
     * @param string $url
     * @return array
     */
    public static function convert_url_query($url = '')
    {
        $arr = parse_url($url);
        $query_arr = explode('&', $arr['query']);
        $params = array();
        if ($query_arr) {
            foreach ($query_arr as $param) {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }
        }
        return $params;
    }

    /**
     * @desc Base64生成图片文件,自动解析格式
     * @param $base64 可以转成图片的base64字符串
     * @param $path 绝对路径
     * @param $filename 生成的文件名
     * @return array 返回的数据，当返回status==1时，代表base64生成图片成功，其他则表示失败
     */
    public static function base64ToImage($base64, $path, $filename)
    {

        $res = array();
        //匹配base64字符串格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
            //保存最终的图片格式
            $postfix = $result[2];
            $base64 = base64_decode(substr(strstr($base64, ','), 1));
            $filename .= '.' . $postfix;
            $path .= $filename;
            //创建图片
            if (file_put_contents($path, $base64)) {
                $res['status'] = 1;
                $res['filename'] = $filename;
            } else {
                $res['status'] = 2;
                $res['err'] = 'Create img failed!';
            }
        } else {
            $res['status'] = 2;
            $res['err'] = 'Not base64 char!';
        }

        return $res;

    }


    /**
     * @desc 将图片转成base64字符串
     * @param string $filename 图片地址
     * @return string
     */
    public static function imageToBase64($filename = '')
    {

        $base64 = '';
        if (file_exists($filename)) {
            if ($fp = fopen($filename, "rb", 0)) {
                $img = fread($fp, filesize($filename));
                fclose($fp);
                $base64 = 'data:image/jpg/png/gif;base64,' . chunk_split(base64_encode($img));
            }
        }
        return $base64;

    }

    /**
     *
     * 判断是否微信浏览器内打开
     * @param  string  $userAget 可选参数 用户浏览器useAgent头
     * @return boolean
     */
    public static function is_weixin_browser($userAget = '')
    {
        if(!$userAget)
        {
            $userAget = $_SERVER['HTTP_USER_AGENT'];
        }
        if ( strpos($userAget, 'MicroMessenger') !== false )
        {
            return true;
        }
        return false;
    }

    /**
     * 产生随机字串
     * 默认长度6位 字母和数字混合 支持中文
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 2大写字母 3小写字母 4中文
     * 默认：大小写字母和数字混合并且去除了容易混淆的字母oOLl和数字01
     * @param string $addChars 额外添加进去的字符
     * @return string
     */
    public static function get_rand_string($len = 6, $type = '', $addChars = '')
    {
        $str ='';
        switch($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789',3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
                break;
        }
        if($len>10 ) {//位数过长重复字符串一定次数
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        if($type!=4) {
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len);
        }else{
            // 中文随机字
            for($i=0;$i<$len;$i++)
            {
                $str.= self::mbsubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1,'utf-8');
            }
        }
        return $str;
    }

    /**
     * 判断是否移动端浏览器
     * @return boolean
     */
    public static function is_mobile_browser()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if(isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if(isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap"))
        {
            return  true;
        }
        // userAgent匹配
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if(isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    /**判断是否为空
     * @param $str
     * @return bool
     */
    public static function isEmpty($str) {
        $str = trim($str);
        return !empty($str) ? true : false;
    }

    /**
     * 将路径转换加密
     * @param  string $file_path 路径
     * @return string            转换后的路径
     */
    public static function path_encode($file_path){
        return rawurlencode(base64_encode($file_path));
    }

    /**
     * 将路径解密
     * @param  string $file_path 加密后的字符串
     * @return string            解密后的路径
     */
    public static function path_decode($file_path){
        return base64_decode(rawurldecode($file_path));
    }

    /**
     * 根据文件后缀的不同返回不同的结果
     * @param  string $str 需要判断的文件名或者文件的id
     * @return integer     1:图片  2：视频  3：压缩文件  4：文档  5：其他
     */
    public static  function file_category($str){
        // 取文件后缀名
        $str=strtolower(pathinfo($str, PATHINFO_EXTENSION));
        // 图片格式
        $images=array('webp','jpg','png','ico','bmp','gif','tif','pcx','tga','bmp','pxc','tiff','jpeg','exif','fpx','svg','psd','cdr','pcd','dxf','ufo','eps','ai','hdri');
        // 视频格式
        $video=array('mp4','avi','3gp','rmvb','gif','wmv','mkv','mpg','vob','mov','flv','swf','mp3','ape','wma','aac','mmf','amr','m4a','m4r','ogg','wav','wavpack');
        // 压缩格式
        $zip=array('rar','zip','tar','cab','uue','jar','iso','z','7-zip','ace','lzh','arj','gzip','bz2','tz');
        // 文档格式
        $document=array('exe','doc','ppt','xls','wps','txt','lrc','wfs','torrent','html','htm','java','js','css','less','php','pdf','pps','host','box','docx','word','perfect','dot','dsf','efe','ini','json','lnk','log','msi','ost','pcs','tmp','xlsb');
        // 匹配不同的结果
        switch ($str) {
            case in_array($str, $images):
                return 1;
                break;
            case in_array($str, $video):
                return 2;
                break;
            case in_array($str, $zip):
                return 3;
                break;
            case in_array($str, $document):
                return 4;
                break;
            default:
                return 5;
                break;
        }
    }

}