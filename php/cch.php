<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.cch.org.tw/news/er_news.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'MSIE');

$data = curl_exec($ch);
$data = strstr($data, 'UpdatePanel1');

$pattern = '/(<img.*Image2.*alt="(.*)".* \/>|<span.*(Txt_Label|Bed_Time).*><font.*>(.*)<\/font><\/span>)/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('：', '上', '下', '午'),
    array(' ', 'A', 'P', 'M'),
    strip_tags($match[4][5])
);
$time = explode(' ', $time);

$return = array(
    'hosptial_sn'    => '1137010024',
    'update_time'    => strtotime($time[0] . ' ' . $time[2] . $time[1]),
    'full_reported'  => ('尚未通報' === $match[2][0])? false:true,
    'pending_doctor' => intval($match[4][1]),
    'pending_bed'    => intval($match[4][2]),
    'pending_ward'   => intval($match[4][3]),
    'pending_icu'    => intval($match[4][4])
);

echo json_encode($return);
