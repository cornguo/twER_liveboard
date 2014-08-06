<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.kmuh.org.tw/KMUHWeb/Pages/P04MedService/ERStatus.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<span.*>(.*)<\/span>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('上', '下', '午'),
    array('A', 'P', 'M'),
    $match[1][0]
);
$time = explode(' ', $time);

$return = array(
    'hosptial_sn'    => '1302050014',
    'update_time'    => strtotime($time[0] . ' ' . $time[2] . $time[1]),
    'full_reported'  => ('【未滿載】' === $match[1][1])? false:true,
    'pending_doctor' => intval($match[1][2]),
    'pending_bed'    => intval($match[1][3]),
    'pending_ward'   => intval($match[1][4]),
    'pending_icu'    => intval($match[1][5])
);

echo json_encode($return);
