<?php

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function nay($v, $na = 'N.A') {
    return $v ? $v : $na;
}

function sexy_snake($str) {
    return ucwords(str_replace('_', ' ', $str));
}

// Checks if a variable is set and has some truthy
// Checks if a variable is set and value equals $val if provided
// Does not work if $var is NULL
//
// set, non-empty, true [and equals $val]
// Remember: isset() returns false if $var exists but is NULL.
// So this won't work if $var is null
//
// var_dump(ixxet($x02));       // False, not set
// $v0;
// var_dump(ixxet($v0));        // False, set but no value
// $v1 = '';
// var_dump(ixxet($v1));        // False, set but falsy
// $v2 = 'abc';
// var_dump(ixxet($v2));        // True, set and non-empty
// $v3 = 'abc';
// var_dump(ixxet($v3, 'xyz')); // False, set, non-emtpy but doesn't match
// $v4 = 'abc';
// var_dump(ixxet($v4, 'abc')); // True, set, non-empty and matches
// die;
//
function ixxet($var, $val = NULL) {
    if(!is_null($val)) return isset($var) && $var == $val;
    return isset($var) && $var;
}

// Checks if all given indexes are ixxet in given array
function ixxets($indexes, $array) {
    foreach($indexes as $index) {
        if(ixxet($array[$index])){} else return false;
    }
    return true;
}

function is_email_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function random_text($count, $remove_similar = false, $numbers_only = false)
{
    $chars = array_flip(array_merge(range(0, 9), range('A', 'Z'), range('a', 'z')));
    if($numbers_only) $chars = array_flip(array_merge(range(0, 9)));

    if ($remove_similar) {
        unset($chars[0], $chars[1], $chars[2], $chars[5], $chars[8], $chars['B'], $chars['I'], $chars['O'], $chars['Q'], $chars['S'], $chars['U'], $chars['V'], $chars['Z']);
    }

    for ($i = 0, $text = ''; $i < $count; $i++) {
        $text .= array_rand($chars);
    }
    return $text;
}

function current_domain() {

    $domain = 'http';
    if (@$_SERVER["HTTPS"] == "on") {
        $domain .= "s";
    }
    $domain .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $domain .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    } else {
        $domain .= $_SERVER["SERVER_NAME"];
    }

    return $domain;

}

function current_page_url()
{
    return current_domain() . $_SERVER["REQUEST_URI"];
}
function current_page_name()
{
    return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

function value_or_dash($value, $dash = '-')
{
    if (!isempty($value))
        return $value;
    else
        return $dash;
}
function js_redirect($url)
{
    if (isset($_GET["debug"])) {
        if (strpos($url, '?'))
            $url .= '&debug';
        else
            $url .= '?debug';
    }
    echo "<script>window.location = '" . $url . "';</script>";
    die();
}
function message($url)
{
    echo "<script>alert('" . $url . "');</script>";
}
function bounce_back()
{
    echo "<script>history.go(-1)</script>";
    die();
}
function delayed_redirect($url, $time)
{
    $a = "<script type='text/javascript'>
    <!--
    function delayer(){
        window.location = '" . $url . "';
    }
    setTimeout('delayer()', " . $time . ");
    //-->
</script>

";
    return $a;
}

function assemble_params($params, $bypass = [])
{
    if($bypass) {
        $bypass = is_array($bypass) ? $bypass : explode(',', $bypass);
        foreach($bypass as $param) {
            unset($params[$param]);
        }
    }

    return http_build_query($params);

    // $p = '';
    // $b = array();
    // if ($bypass)
    //     $b = explode(",", $bypass);
    // foreach ($params as $k => $v) {
    //     if (!in_array($k, $b)) {
    //         if (trim($v)) {
    //             $p .= $k . '=' . $v . '&';
    //         }
    //     }
    // }
    // if ($p) {
    //     $p = removeLastChars($p, 1);
    //     if ($lead_question_mark)
    //         return '?' . $p;
    //     else
    //         return $p;
    // }
}

function human_readable_filesize($size)
{
    $i   = 0;
    $iec = array(
        "b",
        "kb",
        "mb",
        "gb",
        "tb",
        "pb",
        "eb",
        "zb",
        "yb"
    );
    while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;
    }
    return substr($size, 0, strpos($size, '.') + 4) . $iec[$i];
}

// Also declared by WP in wp-includes/formatting.php. So use that
// ---
// if (!function_exists('stripslashes_deep')) {
//     function stripslashes_deep($value)
//     {
//         $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
//         return $value;
//     }
// }
//*

function time_remaining($end, $exceeds = 'Time exceeded by: ', $start = '', $limit = 5)
{
    /* Documentation: [
    $limit:
    it indicates the maximum level of time we want to display. It ranges from 0 to 5.

    values:
    0 for YEARS
    1 for MONTHS
    2 for DAYS
    3 for HOURS
    4 for MINUTES
    5 for SECONDS

    ex:
    if we set limit =3, then time returned will be like: 1 year, 2 months, 3 days, 1 hour
    (or)
    if we set limit =1, then time returned will be like: 1 year, 2 months

    ] */
    if (!$start)
        $start = time();
    if ($end > $start) {
        $diff = $end - $start;
    } else {
        $diff = $start - $end;
    }
    $secs    = $diff;
    $years   = floor($secs / (12 * 60 * 60 * 24 * 30));
    $rem     = $secs % (12 * 60 * 60 * 24 * 30);
    $months  = floor($rem / (60 * 60 * 24 * 30));
    $rem     = $secs % (60 * 60 * 24 * 30);
    $days    = floor($rem / (60 * 60 * 24));
    $rem     = $secs % (60 * 60 * 24);
    $hours   = floor($rem / (60 * 60));
    $rem     = $secs % (60 * 60);
    $minutes = floor($rem / 60);
    $rem     = $secs % (60);
    $secs    = floor($rem / 60);
    $cd      = array();
    if ($years)
        $cd[] = $years . ' years';
    // $limit = 0
    if ($months)
        $cd[] = $months . ' months';
    // $limit = 1
    if ($days)
        $cd[] = $days . ' days';
    // $limit = 2
    if ($hours)
        $cd[] = $hours . ' hours';
    // $limit = 3
    if ($minutes)
        $cd[] = $minutes . ' minutes';
    // $limit = 4
    if ($secs)
        $cd[] = $secs . ' seconds';
    // $limit = 5
    //remove elements above the limit
    for ($i = 5; $i >= 0; $i--) {
        if ($i > $limit)
            unset($cd[$i]);
    }
    //join all the times
    if (count($cd)) {
        $timeRemaining = ($start > $end) ? $exceeds : '';
        $timeRemaining .= implode(', ', $cd);
        return $timeRemaining;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function coalesce()
{
    $args = func_get_args();
    foreach ($args as $arg) {
        if (!isempty($arg)) {
            return $arg;
        }
    }
    return NULL;
}
function date_context($date, $today = '')
{
    if (!$today)
        $today = time();
    //echo date('l/d/m/Y',$date).'<br />';
    //today's day number in week. ex; tuesday: 2, wed: 3 ...
    $day_number       = date('N', $today);
    $next_sunday      = 7 - $day_number;
    //get time stamps
    $next_month       = strtotime('+30 days', $today);
    $next_weekend     = strtotime("+$next_sunday days", $today);
    //echo date('l/d/m/Y',$next_weekend).'<br />';
    $tomorrow         = strtotime('+1 day', $today);
    $yesterday        = strtotime('-1 day', $today);
    $previous_weekend = strtotime("-$day_number days", $today);
    //echo date('l/d/m/Y',$previous_weekend).'<br />';
    $last_month       = strtotime('-30 days', $today);
    //echo " - ".date('l/d/m/Y',$last_month).'<br />';
    //echo $last_month.'-'.$date.'<br /><br />';
    //convert timestamps to date format
    $date_to_day      = date('dmY', $date);
    $tomorrow_to_day  = date('dmY', $tomorrow);
    $today_to_day     = date('dmY', $today);
    $yesterday_to_day = date('dmY', $yesterday);
    if ($date > $next_month)
        return 'after next month';
    if ($date <= $next_month && $date > $next_weekend)
        return 'this month';
    if ($date <= $next_weekend && $date > $tomorrow)
        return 'next weekend';
    if ($date_to_day == $tomorrow_to_day)
        return 'tomorrow';
    if ($date_to_day == $today_to_day)
        return 'today';
    if ($date_to_day == $yesterday_to_day)
        return 'yesterday';
    if ($date >= $previous_weekend && $date < $yesterday)
        return 'previous weekend';
    if ($date >= $last_month && $date < $previous_weekend)
        return 'last month';
    if ($date < $last_month) { //echo 'here'.date('l/d/m/Y',$date).'<br />';
        return 'older than last month';
    }
}
function wrap_tag($content, $tag = 'b', $attr = '')
{
    return '<' . $tag . ' ' . $attr . '>' . $content . '</' . $tag . '>';
}
function is_form_posted($name, $array = false, $return_value = false, $placeholder = '')
{
    if ($array) {
        if (isset($_POST[$name]) && count($_POST[$name]))
            if ($return_value)
                return $_POST[$name];
            else
                return true;
    } else {
        if (isset($_POST[$name]) && $_POST[$name])
            if ($return_value)
                if ($placeholder)
                    return ($_POST[$name] == $placeholder) ? false : $_POST[$name];
                else {
                    return $_POST[$name];
                } else
                return true;
    }
    return false;
}
function got_GET($name, $return_value = false)
{
    if (isset($_GET[$name])) {
        if ($return_value)
            return $_GET[$name];
        else
            return true;
    }
    return false;
}
function file_name($fullFileName)
{
    $dotPosition = strrpos($fullFileName, '.');
    //echo "File Name: ".strtolower(substr($fullFileName, 0,$dotPosition));
    return strtolower(substr($fullFileName, 0, $dotPosition));
}
function file_extension($fullFileName)
{
    //echo "File Extension: ".strtolower(substr($fullFileName, strrpos($fullFileName, '.')+1));
    return strtolower(substr($fullFileName, strrpos($fullFileName, '.') + 1));
}
function mres($txt)
{
    return mysql_real_escape_string($txt);
}
function currency_unformat($str_number)
{
    $tmp = str_replace('$', '', $str_number);
    $tmp = str_replace(',', '', $tmp);
    return $tmp;
}

function array_to_ul($array, $ul_params = '', $li_params = '')
{
    return '<ul ' . $ul_params . '><li ' . $li_params . '>' . implode('</li><li ' . $li_params . '>', $array) . '</li></ul>';
}
function dob_to_age($dob) //yyyy-mm-dd
{
    if ($dob == '0000-00-00' || $dob == '') {
        return false;
    }
    list($year, $month, $day) = explode('-', $dob);
    $this_year = date('Y');
    return ($this_year - $year);
}
function google_doc($url, $width = 975, $height = 500)
{
    return '<iframe src="http://docs.google.com/gview?url=' . $url . '&embedded=true" style="width:' . $width . 'px; height:' . $height . 'px;" frameborder="0"></iframe>';
}
function clean_ck_editor_text($submitted_value)
{
    $remove          = array(
        '\n',
        '\r'
    );
    $submitted_value = str_replace($remove, '', $submitted_value);
    $submitted_value = stripslashes($submitted_value);
    return $submitted_value;
}
function date_us_to_iso($date)
{
    $date = date('Y-m-d', strtotime($date));
    return $date;
}
function date_iso_to_us($date, $time = true)
{
    if($time) return date('m/d/Y h:i a', strtotime($date));
    return date('m/d/Y', strtotime($date));
}
function date_iso_to_pk($date)
{
    $date = date('d M, Y', strtotime($date));
    return $date;
}
function browser_name()
{
    $u_agent  = $_SERVER['HTTP_USER_AGENT'];
    $bname    = 'Unknown';
    $platform = 'Unknown';
    $version  = "";
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub    = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub    = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub    = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub    = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub    = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub    = "Netscape";
    }
    // finally get the correct version number
    $known   = array(
        'Version',
        $ub,
        'other'
    );
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}
function user_ip_address()
{
    return $_SERVER['REMOTE_ADDR'];
}
function bang($pin, $die = 0)
{
    echo "\n\n";
    echo '<pre class="bangpre">';
    echo "\n";
    if (is_array($pin) || is_object($pin)) {
        print_r($pin);
    } else {
        echo $pin;
    }
    echo "\n";
    echo '</pre>';
    echo "\n\n";
    if ($die)
        die();
}
function number_to_currency($int_number, $dec = 2)
{
    return '$' . number_format($int_number, $dec);
}
function date_range_to_number_of_days($date1, $date2)
{
    $start        = strtotime($date1);
    $end          = strtotime($date2);
    $days_between = ceil(abs($end - $start) / 86400);
    return ($days_between);
}
if (!function_exists('cc_crypt')) {
    function cc_crypt($key, $string, $action = 'encrypt')
    {
        $res = '';
        if ($action !== 'encrypt') {
            $string = base64_decode($string);
        }
        for ($i = 0; $i < strlen($string); $i++) {
            $c = ord(substr($string, $i));
            if ($action == 'encrypt') {
                $c += ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr($c & 0xFF);
            } else {
                $c -= ord(substr($key, (($i + 1) % strlen($key))));
                $res .= chr(abs($c) & 0xFF);
            }
        }
        if ($action == 'encrypt') {
            $res = base64_encode($res);
        }
        return $res;
    }
}
function extract_numbers($string)
{
    //$string = "this is 1.0 and this is 2 and this is $3.3";
    preg_match_all('/\d+(\.\d+)?/', $string, $matches);
    return ($matches[0]);
}
function wp_mysql_extract_numbers($wpdb)
{
    $sql     = "SHOW FUNCTION STATUS WHERE `name` = 'digits'";
    $results = $wpdb->get_results($sql, ARRAY_A);
    if (!$results) {
        $sql = "
                #SET GLOBAL log_bin_trust_function_creators=1;
        DROP FUNCTION IF EXISTS digits;
        DELIMITER |
        CREATE FUNCTION digits( str CHAR(32) ) RETURNS CHAR(32)
        BEGIN
        DECLARE i, len SMALLINT DEFAULT 1;
        DECLARE ret CHAR(32) DEFAULT '';
        DECLARE c CHAR(1);

        IF str IS NULL
        THEN
        RETURN '';
        END IF;

        SET len = CHAR_LENGTH( str );
        REPEAT
        BEGIN
        SET c = MID( str, i, 1 );
        IF c BETWEEN '0' AND '9' THEN
        SET ret=CONCAT(ret,c);
        END IF;
        IF c = '.' THEN
        SET ret=CONCAT(ret,c);
        END IF;
        SET i = i + 1;
        END;
        UNTIL i > len END REPEAT;
        RETURN ret;
        END |
        DELIMITER ;";
        //if(!mysql_query($sql)) bang(mysql_error(),1);
        bang('Functions: Dependability Issue. MySQL digits() does not exist.', 1);
    }
}
function make_get($params, $exclude = array())
{
    $tmp = array();
    foreach ($params as $param => $value) {
        if (in_array($param, $exclude))
            continue;
        if (is_array($value)) {
            $tmp2 = array();
            foreach ($value as $value2) {
                $tmp2[] = $param . '[]=' . $value2;
            }
            $tmp[] = implode('&', $tmp2);
        } else {
            $tmp[] = $param . '=' . $value;
        }
    }
    return implode('&', $tmp);
}
function clean_slashes($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            if (is_string($v)) {
                $d[$k] = stripslashes($v);
            }
        }
        return $d;
    } else {
        return stripslashes($d);
    }
}
function has_url($string)
{
    if (preg_match('/[\w\d\.]+\.(com|org|ca|net|uk|co)/', $string, $matches)) {
        if ($matches)
            return true;
    }
    return false;
}
function commas_to_backtick($str)
{
    $str = str_replace("'", '`', $str);
    $str = str_replace('"', '``', $str);
    return $str;
}

function us_states() {
    return [
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District Of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
    ];
}

function iso_to_country_name($iso_code, $echo = true)
{
    $countries = array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'Ivoire",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "TL" => "East Timor",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France, Metropolitan",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard and Mc Donald Islands",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran (Islamic Republic of)",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "North Korea",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau",
        "MK" => "FYROM",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States of",
        "MD" => "Moldova, Republic of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome and Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovak Republic",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia &amp; South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SH" => "St. Helena",
        "PM" => "St. Pierre and Miquelon",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen Islands",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic of",
        "TH" => "Thailand",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VA" => "Vatican City State (Holy See)",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands (British)",
        "VI" => "Virgin Islands (U.S.)",
        "WF" => "Wallis and Futuna Islands",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "CD" => "Democratic Republic of Congo",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
        "ME" => "Montenegro",
        "RS" => "Serbia",
        "AX" => "Aaland Islands",
        "BQ" => "Bonaire, Sint Eustatius and Saba",
        "CW" => "Curacao",
        "PS" => "Palestinian Territory, Occupied",
        "SS" => "South Sudan",
        "BL" => "St. Barthelemy",
        "MF" => "St. Martin (French part)",
        "IC" => "Canary Islands"
    );
    $country   = (isset($countries[strtoupper($iso_code)])) ? $countries[strtoupper($iso_code)] : '';
    if (!$echo)
        return $country;
    echo $country;
}
function embed_code_from_url($video_url, $width = '723', $height = '350')
{
    $embed   = '';
    // Youtube
    $matches = array();
    if (strpos($video_url, 'youtube.com')) {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches);
        $video_id = $matches[1];
        if ($video_id) {
            $embed = '<iframe class="dt-youtube" width="' . $width . '" height="' . $height . '" src="//www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
        }
    }
    // Vimeo
    $matches = array();
    if (strpos($video_url, 'vimeo.com')) {
        preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $video_url, $matches);
        $video_id = $matches[2];
        if ($video_id) {
            $embed = '<iframe src="http://player.vimeo.com/video/' . $video_id . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        }
    }
    return $embed;
}
function hour_to_day($hhiiss) //240:45:30
{
    $eta = '';
    list($hour, $minute, $second) = explode(':', $hhiiss);
    if ($hour > 24) {
        $day  = floor($hour / 24);
        $hour = $hour % 24;
        $eta  = $day . ' Day' . (($day > 1) ? 's' : '');
        $eta .= ($hour) ? ', ' . $hour . ' Hour' . (($hour > 1) ? 's' : '') : '';
    } else {
        $eta = $hour . ' Hour' . (($hour > 1) ? 's' : '');
    }
    return $eta;
}
function in_array_recursive($needle, $haystack)
{
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
    foreach ($it AS $element) {
        if ($element == $needle) {
            return true;
        }
    }
    return false;
}
function create_slug($string)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return $slug;
}
/**
 * Insert $needle in $haystack right after $number of words.
 */
function insert_after_x_words($needle, $haystack, $number)
{
    $word_count      = 0;
    $haystack        = trim($haystack);
    $haystack_array  = str_split($haystack);
    $pause           = false;
    $target_position = 0;
    // If 0, prepend $needle at beginning
    if (!$number)
        return $needle . $haystack;
    foreach ($haystack_array as $i => $letter) {
        // Don't count space within HTML tags or comments
        if ($letter == '<')
            $pause = true;
        if ($letter == '>')
            $pause = false;
        // Increase word count if found a space
        if (!$pause) {
            if ($letter == ' ') {
                $word_count++;
            }
        }
        // Stop if $number of words have reached. This is our target
        // position
        if ($word_count == $number) {
            $target_position = $i;
            break;
        }
    } // foreach
    // If $number was greater than the total words $haystack has,
    // then just append $needle at end of $haystack.
    if (!$target_position) {
        $finished = $haystack . $needle;
    } else {
        // Insert needle at target position
        $finished = substr_replace($haystack, $needle, $target_position, 0);
    }
    // Finished Haystack
    return $finished;
}

function params($params) {
    if(!$params) return '';
    if(!is_array($params)) return $params;
    return (http_build_query($params));
}

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius 6371000 meters, or 3959 miles. Use
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversine_great_circle_distance($lat_from, $lng_from, $lat_to, $lng_to, $unit = ['m', 'mi'][0]) {

    $earth_radius = $unit === 'm' ? 6371000 : 3959;

    // convert from degrees to radians
    $lat_from = deg2rad($lat_from);
    $lng_from = deg2rad($lng_from);
    $lat_to = deg2rad($lat_to);
    $lng_to = deg2rad($lng_to);

    $lat_delta = $lat_to - $lat_from;
    $lng_delta = $lng_to - $lng_from;

    $angle = 2 * asin(sqrt(pow(sin($lat_delta / 2), 2) + cos($lat_from) * cos($lat_to) * pow(sin($lng_delta / 2), 2)));
    return $angle * $earth_radius;
}

function get_server_protocol() {
    return $_SERVER['REQUEST_SCHEME'] . "://";
}
