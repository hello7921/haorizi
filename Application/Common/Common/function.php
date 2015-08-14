<?php
/**
 * 公共方法
 * @author handsome
 * @email hello_21@qq.com
 */
function msubstr($str, $length, $start = 0, $charset = "utf-8", $suffix = true) {
    $str = trim(strip_tags($str));
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return strlen($str) > $length ? $slice . '...' : $slice;
}

function addslashes_deep($value) {
    $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    return $value;
}

function stripslashes_deep($value) {
    if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = stripslashes_deep($data);
        }
    } else {
        $value = stripslashes($value);
    }
    return $value;
}

function todaytime() {
    return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
}

function fdate($time) {
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y'));
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y'));
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'));
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y'));
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}

function ftime($time) {
    if ($time < 0)
        return '0天';
    $date = intval($time / (3600 * 24));
    $hour = intval(($time - $date * 3600 * 24) / 3600) > 0 ? intval(($time - $date * 3600 * 24) / 3600) : 0;
    $minute = intval(($time - $date * 3600 * 24 - $hour * 3600) / 60) > 0 ? intval(($time - $date * 3600 * 24 - $hour * 3600) / 60) : 0;
    return $date . '天' . $hour . '小时' . $minute . '分';
}

function avatar_dir($uid) {
    $uid = abs(intval($uid));
    $suid = sprintf("%09d", $uid);
    $dir1 = substr($suid, 0, 3);
    $dir2 = substr($suid, 3, 2);
    $dir3 = substr($suid, 5, 2);
    return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
}

function attach($attach, $type, $full_url = false) {
    $attach = trim($attach);
    if (is_url($attach)) {
        return $attach;
    }
    $attach = ltrim($attach, '/');
    $is_local = strstr($attach, 'static/') != false;
    $url_preix = __ROOT__;
    if ($full_url) {
        $url_preix = __SITEROOT__;
    }
    $attach_path = $is_local ? $attach : "data/upload/" . $type . '/' . $attach;
    if (!file_exists('./' . $attach_path) || empty($attach)) {
        return $url_preix . "/data/upload/no_picture.gif";
    } else {
        return $url_preix . '/' . $attach_path;
    }
}

function object_to_array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function get_child_ids($mod, $id) {
    $ids = '';
    $list = $mod->where("pid=$id")->select();
    if ($list) {
        foreach ($list as $key => $val) {
            $ids .= $val['id'] . ',';
            $ids .= get_child_ids($mod, $val['id']);
        }
    } else {
        return '';
    }
    return trim($ids, ',');
}

function get_cate_tree($mod, $id = 0) {
    $where = array();
    if ($id > 0) {
        $where['id'] = $id;
    } else {
        $where['pid'] = 0;
    }
    $list = $mod->where($where)->select();
    foreach ($list as $key => $val) {
        $list[$key]['depth'] = 0;
        $list[$key]['child'] = get_child_tree($mod, $val['id'], 0);
    }
    return $list;
}


/* * 根据 汉语关键字 返回 拼音
 * @param $keyword
 * @return array
 */

function word_to_pinyin($keyword) {
    import("@.Extend.pin");
    $pin = new pin();
    if (is_string($keyword)) {
        return $pin->Pinyin($keyword, 'UTF8');
    } elseif (is_array($keyword)) {
        $r = array();
        foreach ($keyword as $key) {
            $r[] = $pin->Pinyin($key, 'UTF8');
        }
        return $r;
    }
}

function html_select($name, $list, $id = -1) {
    if ($id == null) {
        $id = -1;
    }
    $html = "<select name='$name' id='$name'>";
    $html .= "<option value='-1'>请选择...</option>";
    foreach ($list as $key => $val) {
        $html .= "<option value='$key'";
        if ($key == $id) {
            $html .= " selected='selected'";
        }
        $html .= ">$val</option>";
    }
    $html .= "</select>";
    return $html;
}

function html_radio($name, $list, $id = -1) {
    $html = "";
    if (is_array($list)) {
        foreach ($list as $key => $val) {
            $html .= "<span class='radio_item'><input type='radio' name='$name' value='$key'";
            if ($key == $id) {
                $html .= " checked='checked'";
            }
            $html .= "/>$val</span>";
        }
    } else {
        $html .= "<script type='text/javascript'>\$(function(){\$(\"input[name='$name'][value='$list']\").attr('checked','checked');});</script>";
    }
    return $html;
}

function topinyin($title) {
    include(APP_PATH . 'Lib/Inslib/pinyin.class.php');
    $py = new cls_pinyin();
    return $py->tourl($title);
}

function get_index() {
    $list = array();
    $list[9] = '0~9';
    for ($i = 65; $i < 91; $i++) {
        $list[chr($i)] = chr($i);
    }
    return $list;
}

function table($table) {
    return C('DB_PREFIX') . $table;
}


function filter_data($data) {
    foreach ($data as $key => $val) {
        $data[$key] = strip_tags($val);
    }
    return $data;
}



function get_ret_url() {
    $res = parse_url($_SERVER['REQUEST_URI']);
    $query_list = explode('&', $res['query']);
    foreach ($query_list as $key => $val) {
        $param = explode('=', $val);
        if ($param[0] == 'ret_url') {
            unset($query_list[$key]);
        }
    }
    $url = 'http://' . $_SERVER["HTTP_HOST"] . ($_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"]) . $res['path'] . '?' . implode('&', $query_list);
    return urlencode(rtrim($url, '?'));
}

function check_url($str) {
    if (empty($str)) {
        return "#";
    }
    $info = parse_url(ltrim($str, '.'));
    empty($info['scheme']) && $info['scheme'] = "http";
    if (empty($info['host'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = $info['host'];
    }
    if (isset($info['port'])) {
        $port = ':' . $info['port'];
    }
    $url = $info['scheme'] . "://" . $host . $port;
    if (empty($info['host'])) {
        $url .= rtrim(__ROOT__, '/');
    }
    $url .= '/' . ltrim($info['path'], '/');
    if (!empty($info['query'])) {
        $url .= '?' . $info['query'];
    }
    $url . $info['fragment'];
    return $url;
}

function get_site_logo($theme) {
    $site_logo = C('pin_site_logo');
    return $site_logo[$theme];
}

function check_entry_permission($path) {
    if (is_file($path)) {
        $path = dirname($path);
    }
    $test_file = rtrim($path, DIR_SEP) . "/__test__" . time() . '.txt';
    file_put_contents($test_file, "test");
    if (trim(file_get_contents($test_file) != "test")) {
        return array('status' => false, 'msg' => "$path 无法读写");
    }
    if (!unlink($test_file)) {
        return array('status' => false, 'msg' => "$path 无法删除");
    }
    return array('status' => true, 'msg' => "$path 可读可写");
}

function is_url($str) {
    $res = parse_url(trim($str));
    return !empty($res['scheme']);
}



function get_float_digit($val) {
    return str_pad(round($val - intval($val), 2) * 100, 2, '0', STR_PAD_LEFT);
}

function yesno_where($field) {
    if (!isset($_REQUEST[$field])) {
        return "";
    }
    $val = intval($_REQUEST[$field]);
    if (in_array($val, array(0, 1))) {
        return " and $field=$val ";
    }
    return "";
}


/**
 * 复制目录和文件
 * @param $src
 * @param $dst
 */
function copy_file($src, $dst) {  // 原目录，复制到的目录
    if (is_file($src)) {
        $file = basename($src);
        $r = copy($src, $dst . '/' . $file);
        // echo $r;
    }

    //echo $src;
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                copy_file($src . '/' . $file, $dst . '/' . $file);
            } else {
                // dump($src . '/' . $file) ;
                //dump($dst . '/' . $file) ;
                $r = copy($src . '/' . $file, $dst . '/' . $file);
                echo $r;
            }
        }
    }
    closedir($dir);
}

/*
 *  随机产生时间
 */
function time_sort($n=0,$d=0){
    for($i=0;$i<=$n;$i++){
        $_hour=mt_rand(1,24);
        $_minute=mt_rand(0,60);
        $fomart='+'.$d.' day +'.$_hour.' hour +'.$_minute.' minute';
        $getdate=date('Y-m-d H:i:s',strtotime($fomart));
        $date_a[]=$getdate;
    }
    sort($date_a);
    return $date_a;
}
function parse_editor_img($info) {
    $list = array();
    $c1 = preg_match_all('/<img\s.*?>/', $info, $m1);
    for ($i = 0; $i < $c1; $i++) {
        $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);
        for ($j = 0; $j < $c2; $j++) {
            $list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
        }
    }
    $res = array();
    foreach ($list as $val) {
        if (is_url($val['src']))
            continue;
        $res[] = ltrim($val['src'], '/');
    }
    return $res;
}

function parse_editor_info($str) {
    return;
    include_once(APP_PATH . "Lib/Inslib/simple_html_dom.php");
    $html = str_get_html($str);
    if ($html) {
        $img_list = $html->find('img');
        foreach ($img_list as $k => $v) {
            $src = $html->find('img', $k)->src;
            if (strstr($src, 'data/upload/')) {
                $html->find('img', $k)->src = __SITEROOT__ . '/' . substr($src, strpos($src, "data/upload/"));
            }
        }
        return $html->innertext;
    } else {
        return $str;
    }
}
