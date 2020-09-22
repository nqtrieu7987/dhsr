<?php
/**
 * Description of VtHelper
 * @author ducnm
 */
namespace App\Helper;

#use GuzzleHttp\Client;

class VtHelper {

    const MOBILE_SIMPLE = '09x';
    const MOBILE_GLOBAL = '849x';
    const FEE_DOWNLOAD = 'feedownload';
    const NO_DOWNLOAD = 'nodownload';
    const FREE_DOWNLOAD = 'freedownload';

    public static function upload_plugin($action_url, $name) {
        $form = '<div id="test_dialog"><form action="/"></form></div>
                <div id="upload_dialog_' . $name . '" title="Upload" style="display: none;">
                    <form action="' . $action_url . '/' . $name . '" name="upload_frm_' . $name . '" id="upload_frm_' . $name . '" method="POST" enctype="multipart/form-data">
                        <div id="dialog_mess_' . $name . '"></div>
                        <input type="file" name="file_' . $name . '" class="file_upload_' . $name . '" />
                        <input type="hidden" name="img_size_' . $name . '" id="img_size_' . $name . '" value="" />
                        <div class="progressbar_' . $name . '"></div>
                    </form>
                </div>';
        $form = str_replace("
        ", "", $form);
        $html = '<span>Nhập url hoặc <a class="upload" id="upload_' . $name . '" alt="x16" href="#">[Upload]</a></span>
                <script type="text/javascript">
                function setup_upload_' . $name . '() {
                    $("#modal_form").append(\'' . $form . '\');
                    $(".progressbar_' . $name . '").progressbar({
                        value: 0
                    });
                    $("#test_dialog").dialog({autoOpen: false});

                    // Dialog
                    $("#upload_dialog_' . $name . '").dialog({
                        autoOpen: false,
                        buttons: {
                            "Ok": function() {
                                $("#upload_frm_' . $name . '").submit();
                            },
                            "Cancel": function() {
                                $(this).dialog("close");
                            }
                        }
                    });

                    var upload_input;
                    // Dialog Link
                    $("#upload_' . $name . '").click(function(){
                        upload_input = "." + $(this).attr("alt");
                        $("#' . $name . '_size_' . $name . '").val(upload_input);
                        $("#dialog_mess_' . $name . '").html("");
                        $( ".progressbar_' . $name . '" ).progressbar( "option", "value", 0 );
                        $("#upload_dialog_' . $name . '").dialog("open");
                        return false;
                    });

                    $("#upload_frm_' . $name . '").ajaxForm({
                        dataType:  "json",
                        beforeSend: function() {
                        },
                        uploadProgress: function(event, position, total, percentComplete) {
                            $( ".progressbar_' . $name . '" ).progressbar( "option", "value", percentComplete );
                        },
                        success: function(data) {
                            if(data.code === 0)
                            {
                                $(".file_upload_' . $name . '").val("");
                                $("#' . $name . '").val(data.message);
                            }
                            $("#upload_dialog_' . $name . '").dialog("close");
                        }
                    });
                }
                </script>
                ';
        return $html;
    }

    //File will be rewritten if already exists
    public static function writeFile($filename, $newdata) {
        $f = fopen($filename, "w");
        fwrite($f, $newdata);
        fclose($f);
    }

    public static function appendFile($filename, $newdata) {
        $f = fopen($filename, "a");
        fwrite($f, $newdata);
        fclose($f);
    }

    public static function readFile($filename) {
        try {
            $f = fopen($filename, "r");
            $data = fread($f, filesize($filename));
            fclose($f);
            return $data;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function strBeginWith($needle, $haystack) {
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    public static function add84PhoneNumber($phone_no) {
        //if (self::isViettelPhoneNumber($phone_no)) {
        if (self::strBeginWith('0', $phone_no)) {
            return '84'.substr($phone_no, 1, strlen($phone_no) - 1);
        } else if (self::strBeginWith('84', $phone_no)) {
            return '84'.substr($phone_no, 2, strlen($phone_no) - 2);
        }
        return '84'.$phone_no;
        //}
        return '';
    }
    
    /**
     * Kiem tra co phai so dien thoai khong
     * @author NamDT5
     * @created on Jan 6, 2012
     * @param string $phone_no
     * @return boolean
     */
    public static function isPhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern') . '/';
        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function isViettelPhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern_viettel') . '/';

        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function isVinaPhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern_vinaphone') . '/';

        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function checkip() {
        $sf_user = sfContext::getInstance()->getUser();
        $checkip = $sf_user->getAttribute('checkip', 'new');
        if ($checkip === 'new') {
            if (self::isViettelIp()) {
                $checkip = 'viettel';
            } else if (self::isVinaPhoneChargingIp()) {
                $checkip = 'vina';
            } else {
                $checkip = 'unknown';
            }
            $sf_user->setAttribute('checkip', $checkip);
        }
        return $checkip;
    }

    public static function isViettelIp() {
        $ip = self::getDeviceIp();
        if (!$ip) {
            return false;
        }
        if (preg_match('/^27\./', $ip) || preg_match('/^10\./', $ip) || preg_match('/^171\./', $ip) || preg_match('/^125\./', $ip) || preg_match('/^100\./', $ip)) {
            return true;
        } else {
            return false;
        }
        $subnets = sfConfig::get('ips_subnets');
        $filter = new IpFilter($subnets);
        $result = $filter -> check($ip);
        return $result;
    }

    public static function isVinaPhoneChargingIp() {
        $ip = self::getDeviceIp();
        if (!$ip) {
            return false;
        }
        if (preg_match('/^113\.185\./', $ip) || preg_match('/^113\.185\.31\./', $ip)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isMobiFonePhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern_mobifone') . '/';

        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function isTypePhone($phone_no) {
        $type_msisdn = '';
        if (VtHelper::isViettelPhoneNumber($phone_no)) {
            $type_msisdn = 'VIETTEL';
        } else if (VtHelper::isVinaPhoneNumber($phone_no)) {
            $type_msisdn = 'Vinaphone';
        } else if (VtHelper::isMobiFonePhoneNumber($phone_no)) {
            $type_msisdn = 'MobiFone';
        } else if (VtHelper::isBeelinePhoneNumber($phone_no)) {
            $type_msisdn = 'Beeline';
        } else if (VtHelper::isVietnammobilePhoneNumber($phone_no)) {
            $type_msisdn = 'VietnamMobile';
        }
        return $type_msisdn;
    }

    public static function isBeelinePhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern_beeline') . '/';

        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function isVietnammobilePhoneNumber($phone_no) {
        $pattern = '/' . config('app.msisdn_regex_pattern_vietnammobile') . '/';

        preg_match($pattern, $phone_no, $matches);
        if (count($matches) > 0)
            return true;
        return false;
    }

    public static function getMobileNumber($msisdn, $type) {
        if (empty($type))
            $type = self::MOBILE_SIMPLE;
        switch ($type) {
            case self::MOBILE_GLOBAL:
                if ($msisdn[0] == '0')
                    return '84' . substr($msisdn, 1);
                else if ($msisdn[0] . $msisdn[1] != '84')
                    return '84' . $msisdn;
                else
                    return $msisdn;
                break;
            case self::MOBILE_SIMPLE:
                if ($msisdn[0] != '0')
                    return '0' . substr($msisdn, 2);
                else
                    return $msisdn;
                break;
        }
    }

    public static function getExtension($fileName) {
        $pos = strrpos($fileName, '.');
        if ($pos !== false) {
            $ext = substr($fileName, $pos + 1);
        } else {
            $ext = 'bin';
        }
        return $ext;
    }

    /**
     * Tao so ngau nhieu
     * @author cuonglv16
     * @created on 04 10, 2012
     * @param int $len Do dai mat khau
     * @return string
     */
    public static function generateRandom($len = 6) {
        $stringNumber = '';
        for ($i = 1; $i <= $len; $i++) {
            $stringNumber .= rand(0, 9);
        }
        return $stringNumber;
    }

    /**
     * Sinh mat khau ngau nhien
     * @author huypv5
     * @created on 03 15, 2011
     * @param int $len Do dai mat khau
     * @return string
     */
    public static function generatePassword($len = 6) {
        $len = max(6, $len);
        $chars = '1234567890adgjmptw';
        $nchar = strlen($chars);
        $randPass = '';
        for ($i = 0; $i < $len; $i++) {
            $randomIndex = rand(0, $nchar - 1);
            $randPass .= $chars[$randomIndex];
        }
        return $randPass;
    }

    /**
     * Ham loai bo tat ca cac the html
     * @author NamDT5
     * @created on May 19, 2011
     * @param string $str - Xau can loai bo tag
     * @param array $tags - Mang cac tag se strip, vi du: array('a', 'b')
     * @param boolean $stripContent
     * @return String
     */
    public static function strip_html_tags($str, $tags = array(), $stripContent = false) {
        if (empty($tags)) {
            $tags = array("!--", "!--...--", '!doctype', 'a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bb', 'bdo', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'command', 'datagrid', 'datalist', 'dd', 'del', 'details', 'dfn', 'div', 'dl', 'dt', 'em', 'embed', 'eventsource', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'mark', 'map', 'menu', 'meta', 'meter', 'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'q', 'ruby', 'rp', 'rt', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'ul', 'var', 'video', 'wbr');
        }

        $content = '';
        if (!is_array($tags)) {
            $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
            if (end($tags) == '')
                array_pop($tags);
        }
        foreach ($tags as $tag) {
            if ($stripContent)
                $content = '(.+</' . $tag . '(>|\s[^>]*>)|)';
            $str = preg_replace('#</?' . $tag . '(>|\s[^>]*>)' . $content . '#is', '', $str);
            $str = str_replace('<' . $tag, $tag, $str);
        }
        return $str;
    }

    public static function encodeOutput($string) {

        /* Thuc hien ma hoa va khong ma hoa cac ky tu dac biet thuong dc su dung */
        $string = strip_tags($string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        //$string = htmlspecialchars($string, ENT_NOQUOTES);
        $string = str_replace("&apos;s", "", $string);
        $string = str_replace("&#039;", "", $string);
        $string = str_replace("&lt;", "<", $string);
        $string = str_replace("&gt;", ">", $string);
        $string = str_replace("&quot;", '"', $string);
        $string = str_replace("&amp;", '&', $string);
        $string = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $string);


        return $string;
    }

    public static function encodeOutputUser($string) {

        /* Thuc hien ma hoa va khong ma hoa cac ky tu dac biet thuong dc su dung */
        $string = strip_tags($string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        //$string = htmlspecialchars($string, ENT_NOQUOTES);
        $string = str_replace("&apos;s", "", $string);
        $string = str_replace("&#039;", "", $string);
        $string = str_replace("&lt;", "<", $string);
        $string = str_replace("&gt;", ">", $string);
        $string = str_replace("&quot;", '"', $string);
        $string = str_replace("&amp;", '&', $string);
        $string = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $string);
        $string = str_replace(".", "", $string);
        if (strlen($string) > 20) {
            $string = substr($string, 0, 20);
            $string = $string . "***";
        }

        return $string;
    }

    public static function encodeOutputNew($string) {

        $string = htmlspecialchars($string, ENT_QUOTES);
        $string = str_replace("&apos;s", "", $string);
        $string = str_replace("&#039;", "", $string);
        $string = str_replace("&quot;", '"', $string);
        $string = str_replace("&amp;", '&', $string);
        $string = self::replaceStringError($string);
        return $string;
    }

    public static function replaceStringError($string) {
        $string = str_replace("&apos;s", "", $string);
        $string = str_replace("&#039;", "", $string);
        $string = str_replace("&quot;", '"', $string);
        $string = str_replace("&amp;", '&', $string);
        return $string;
    }

    function mysubstr($str, $length = 6) {
        return (strlen($str) > $length) ? substr($str, 0, $length) . '..' : $str;
    }

    function base64url_encode($plainText) {
        $base64 = base64_encode($plainText);
        $base64url = strtr($base64, '+/=', '');
        return $base64url;
    }

    function timeago($date) {
        if (empty($date)) {
            return _vi("No date provided");
        }

        #$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        $now = time();
        $unix_date = strtotime($date);

        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }

        if (abs($unix_date - $now) < 60)
            return _vi('vừa xong');

        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = _vi("trước");
        } else {
            $difference = $unix_date - $now;
            $tense = "sau";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);
        #if($difference != 1) {
        #    $periods[$j].= "s";
        #}

        return _vi("$difference $periods[$j] {$tense}");
    }

    function _vi($str) {
        return $str;
    }

    public static function formatDay($time, $format = "D, d/m/Y | h:i") {
        $str_search = array(
            "Mon",
            "Tue",
            "Wed",
            "Thu",
            "Fri",
            "Sat",
            "Sun",
        );

        $str_replace = array(
            "Thứ Hai",
            "Thứ Ba",
            "Thứ Tư",
            "Thứ Năm",
            "Thứ Sáu",
            "Thứ Bảy",
            "Chủ Nhật",
        );

        $timenow = date($format, $time);
        return str_replace($str_search, $str_replace, $timenow);
    }

    public static function genRandomString($length = 8) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function getWeekRange($date) {
        $ts = strtotime($date);

        $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
        return array('start' => date('Y-m-d', $start),
            'end' => date('Y-m-d', strtotime('next sunday', $start)));
    }

    public static function getWeekFromDay($str) {
        $iStr = strtotime($str);

        $i = date('N', strtotime($str));

        $d = $i - 1;
        $d2 = 7 - $i;

        $start = date('Y-m-d', strtotime("- $d days", $iStr));
        $end = date('Y-m-d', strtotime("+ $d2 days", $iStr));

        return array('start' => $start, 'end' => $end);
    }

    public static function getDayofWeekAgo($str) {
        $iStr = strtotime($str);

        $start = date('Y-m-d', strtotime("- 7 days", $iStr));

        return array('start' => $start);
    }

    public static function getDayofMonthAgo($str) {
        $iStr = strtotime($str);

        $start = date('Y-m-d', strtotime("- 30 days", $iStr));

        return array('start' => $start);
    }

    public static function getWeekNoByDay($year = 2007, $month = 5, $day = 5) {
        return ceil(($day + date("w", mktime(0, 0, 0, $month, 1, $year))) / 7);
    }


    /**
     * Cat bot ky tu
     * @author NamDT5
     * @created on Dec 26, 2011
     * @param integer $length
     * @return string
     */
    public static function substring($str, $leng = 22) {
        // Do some thing
        return htmlspecialchars(dmString::truncate($str, $leng, '...', true), ENT_COMPAT, 'UTF-8');
    }

    /**
     * Ham tra ve so dien thoai theo IP cua thiet bi
     * @author hanhvt
     * @modified by namdt
     * @created on Dec 26, 2011
     * @param string $id
     * @return string  - unknown neu ko nhan dien dc
     */
    public static function getMsisdnVina() {
        $ip = self::getDeviceIp();
        if (preg_match('/^113\.185\./', $ip) || preg_match('/^113\.185\.31\./', $ip)) {
            return 'vina';
        } else {
            return 'novina';
        }
    }

    /**
     * Tra ve IP cua thiet bi truy cap
     * @author NamDT5
     * @created on Mar 26, 2012
     * @return string
     */
    public static function getDeviceIp() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $mobileIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $mobileIp = $_SERVER['REMOTE_ADDR'];
        }

        if (!empty($mobileIp)) {
            $addr = explode(",", $mobileIp);
            return $addr[0];
        } else {
            return $mobileIp;
        }
    }

    /**
     * Ham chuan hoa xau, viet hoa ky tu dau tien cua tu trong xau
     * @author NamDT5
     * @created on Feb 3, 2012
     * @param unknown_type $str
     * @return string
     */
    public static function ucwords($str) {
        $str = trim($str);
        if (function_exists('mb_strtolower')) {
            $str = mb_strtolower($str);
        } else {
            $str = strtolower($str);
        }

        if (!function_exists('mb_ucwords')) {
            if (function_exists('mb_convert_case')) {
                $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
            } else {
                $str = ucwords($str);
            }
        } else {
            $str = mb_ucwords($str);
        }
        return $str;
    }

    public static function check_app() {
        $ip = config('app.ip_private');

        return $ip;
    }

    public static function strong_password($pwd, $test = false, $str = false) {
        $i18n = sfContext::getInstance()->getI18N();
        $error = $i18n->__("Chú ý: Mật khẩu mới phải chứa tối đa 20 ký tự, tối thiểu 8 ký tự") . ".<br />
              " . $i18n->__("Chứa tối thiểu 1 ký tự chữ hoa, 1 ký tự chữ thường, 1 ký tự số, 1 ký tự đặc biệt") . " <br />";
        $success = $i18n->__("Mật khẩu của bạn có mức độ bảo mật cao. Cảm ơn bạn");
        $check = false;
        if (strlen($pwd) < 8) {
            $check = false;
        } else if (strlen($pwd) > 20) {
            $check = false;
        } else if (!preg_match("#[0-9]+#", $pwd)) {
            $check = false;
        } else if (!preg_match("#[a-z]+#", $pwd)) {
            $check = false;
        } else if (!preg_match("#[A-Z]+#", $pwd)) {
            $check = false;
        } else if (!preg_match("#\W+#", $pwd)) {
            $check = false;
        } else {
            $check = true;
        }
        if ($test == true) {
            return $check;
        }
        if ($str == true) {
            if ($check == false) {
                return $error;
            }
        }
    }

    public static function filterXSS($str) {
        $match = array("<", ">", "\"", "'", "%", ";", ")", "(", "&", "+", "-");
        $replace = array("", "", "", "", "", "", "", "", "", "", "");
        $str = str_replace($match, $replace, $str);
        return $str;
    }


    public static function make_request($url, $data, $method) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function chuyenChuoi($str) {
// In thường
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
// In đậm
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str; // Trả về chuỗi đã chuyển
    }

    public static function changeToSlug($str) {
        $slug = self::chuyenChuoi($str);
        $slug = str_replace(' ', '-', $slug);
        return $slug;
    }


    public static function unsigned($str) {
        $hasSign = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ',
        );
        $noSign = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y', 'Y', 'Y', 'Y',
            'D');

        $str = str_replace($hasSign, $noSign, $str);
        return $str;
    }


    public static function checkIpViettel() {
        $ip = self::getDeviceIp();
        if (self::strBeginWith('10.', $ip) || self::strBeginWith('100.', $ip) || self::strBeginWith('125.234', $ip) || self::strBeginWith('125.235', $ip) || self::strBeginWith('1171.', $ip)) {
            return true;
        } else {
            return false;
        }
    }

    public static function formatPice($price){

    }


    public static function formatcurrency($floatcurr, $curr = "USD"){
        if(!is_numeric($floatcurr) || $floatcurr == 0){
            return 'Liên hệ';
        }
        $currencies['ARS'] = array(2,',','.');          //  Argentine Peso
        $currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
        $currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
        $currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
        $currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
        $currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
        $currencies['BRL'] = array(2,',','.');          //  Brazilian Real
        $currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
        $currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
        $currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,'','.');           //  Chilean Peso
        $currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2,',','.');          //  Colombian Peso
        $currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
        $currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
        $currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
        $currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
        $currencies['DKK'] = array(2,',','.');          //  Danish Krone
        $currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
        $currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
        $currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
        $currencies['ATS'] = array(2,',','.');          //  Euro
        $currencies['BEF'] = array(2,',','.');          //  Euro
        $currencies['DEM'] = array(2,',','.');          //  Euro
        $currencies['EEK'] = array(2,',','.');          //  Euro
        $currencies['ESP'] = array(2,',','.');          //  Euro
        $currencies['EUR'] = array(2,',','.');          //  Euro
        $currencies['FIM'] = array(2,',','.');          //  Euro
        $currencies['FRF'] = array(2,',','.');          //  Euro
        $currencies['GRD'] = array(2,',','.');          //  Euro
        $currencies['IEP'] = array(2,',','.');          //  Euro
        $currencies['ITL'] = array(2,',','.');          //  Euro
        $currencies['LUF'] = array(2,',','.');          //  Euro
        $currencies['NLG'] = array(2,',','.');          //  Euro
        $currencies['PTE'] = array(2,',','.');          //  Euro
        $currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
        $currencies['ISK'] = array(0,'','.');           //  Iceland Krona
        $currencies['INR'] = array(2,'.',',');          //  Indian Rupee
        $currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
        $currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,'',',');           //  Japan, Yen
        $currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
        $currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
        $currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
        $currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
        $currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
        $currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
        $currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
        $currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
        $currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
        $currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
        $currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
        $currencies['OMR'] = array(3,'.',',');          //  Rial Omani
        $currencies['RON'] = array(2,',','.');          //  Romania, New Leu
        $currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2,',','.');          //  Russian Ruble
        $currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
        $currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
        $currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,'',',');           //  South Korea, Won
        $currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2,',','.');          //  Swedish Krona
        $currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc 
        $currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
        $currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
        $currencies['AED'] = array(2,'.',',');          //  UAE Dirham
        $currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2,'.',',');          //  US Dollar
        $currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
        $currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,'',',');           //  Viet Nam, Dong
        $currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar

        

        if ($curr == "INR"){    
            return formatinr($floatcurr).' đ';
        } else {
            return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]).' đ';
        }
    }

    public static function formatinr($input){
            //CUSTOM FUNCTION TO GENERATE ##,##,###.##
            $dec = "";
            $pos = strpos($input, ".");
            if ($pos === false){
                //no decimals   
            } else {
                //decimals
                $dec = substr(round(substr($input,$pos),2),1);
                $input = substr($input,0,$pos);
            }
            $num = substr($input,-3); //get the last 3 digits
            $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
            while(strlen($input) > 0) //loop the process - further get digits 2 by 2
            {
                $num = substr($input,-2).",".$num;
                $input = substr($input,0,-2);
            }
            return $num . $dec;
        }

    public static function checkMobileNetwork($phone_no)
    {
        $first_phone = substr($phone_no, 0, 3);
        $vittel_arr = ["086", "096", "097", "098", "032", "033", "034", "035", "036", "037", "038", "039"];
        $mobi_arr = ["089", "090", "093", "070", "079", "077", "076", "078"];
        $vina_arr = ["088", "091", "094", "083", "084", "085", "081", "082"];
        $vina_mobi_arr = ["092", "056", "058"];
        $gtel_arr = ["099", "059"];

        $mobile_network = '';
        if (in_array($first_phone, $vittel_arr)) {
            $mobile_network = 'VIETTEL';
        }
        if (in_array($first_phone, $vina_arr)) {
            $mobile_network = 'VINA';
        }
        if (in_array($first_phone, $mobi_arr)) {
            $mobile_network = 'MOBI';
        }
        if (in_array($first_phone, $vina_mobi_arr)) {
            $mobile_network = 'VINAMOBI';
        }
        if (in_array($first_phone, $gtel_arr)) {
            $mobile_network = 'GTEL';
        }

        return $mobile_network;
    }
    public static function checkGenderIcon($type)
    {
        if($type == 1){
            return '<img class="status-icon" src="/images/female1.png">';
        }else{
            return '<img class="status-icon" src="/images/male1.png">';
        }
    }
    public static function checkStatusIcon($type)
    {
        if($type == 1){
            return '<img class="status-icon" src="/images/Active.png">';
        }else{
            return '<img class="status-icon" src="/images/Deactivated.png">';
        }
    }
    public static function checkStatusUser($type, $stt, $status)
    {
        if($status == 1){
            $statusName = 'active';
        }else{
            $statusName = 'deactive';
        }
        return '<a class="changeStatusUser '.$statusName.'" data-type="'.$type.'" data-stt="'.$status.'"><img id="img'.$stt.'" src="/uploads/images/view-type/'.$stt.'-'.$statusName.'.png"></a>';
    }

    public static function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp ); 

        return $output_file; 
    }
}