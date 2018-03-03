<?php
/**
 * Created by PhpStorm.
 * User: Anh Nguyen
 * Date: 3/1/18
 * Time: 10:47 AM
 */
function facebook_curl($url)
{
    $timeout = 900;
    $file_name = md5('AA' . $url . 'A3Code');
    if (file_exists('cache/' . $file_name . '.cache')) {
        $f_open = file_get_contents('cache/' . $file_name . '.cache');
        $data = explode('@@', $f_open);
        $now = gmdate('Y-m-d H:i:s', time() + 3600 * (+7 + date('I')));
        $times = strtotime($now) - $data[0];
        if ($times >= $timeout) {
            $html = trim(curl($url));
            $create_cache = cache_init($url, $html);
            $arrays = explode('|', $create_cache);
            $cache = $arrays[0];
        } else {
            $cache = $data[1];
        }
    } else {
        $html = trim(curl($url));
        $create_cache = cache_init($url, $html);
        $arrays = explode('|', $create_cache);
        $cache = $arrays[0];
    }
    $sd = explode_by('sd_src_no_ratelimit:"', '"', $cache);
    $hd = explode_by('hd_src_no_ratelimit:"', '"', $cache);
    $jw[0]["file"] = '';
    $jw[0]["src"] = '';
    $jw[0]["label"] = "HD";
    $jw[0]["default"] = "true";
    $jw[0]["type"] = "video/mp4";
    $i = 0;
    if ($hd) {
        $jw[$i]["file"] = $hd;
        $jw[$i]["src"] = $hd;
        $jw[$i]["label"] = "HD";
        $jw[$i]["type"] = "video/mp4";
        $i++;

    }
    if ($sd && !$hd) {
        $jw[$i]["file"] = $sd;
        $jw[$i]["src"] = $sd;
        $jw[$i]["label"] = "SD";
        $jw[$i]["type"] = "video/mp4";
    }
    return $jw;
}

function curl($url)
{
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}

function explode_by($begin, $end, $data)
{
    $data = explode($begin, $data);
    $data = explode($end, $data[1]);
    return $data[0];
}

function cache_init($link, $source)
{
    $time = gmdate('Y-m-d H:i:s', time() + 3600 * (+7 + date('I')));
    $file_name = md5('AA' . $link . 'A3Code');
    $string = strtotime($time) . '@@' . $source;
    $file = fopen("cache/" . $file_name . ".cache", 'w');
    fwrite($file, $string);
    fclose($file);

    if (file_exists('cache/' . $file_name . '.cache')) {
        $msn = $source;
    } else {
        $msn = $source;
    }
    return $msn;
}

?>