<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://61.66.117.8/EmrCount/Default.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<td.*>.*<(font|span).*>(.*)<\/.*>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = $match[2][5];
$time = str_replace(
    array('上', '下', '午'),
    array('A', 'P', 'M'),
    $match[2][10]
);
$time = explode(' ', $time);

$return = array(
    'hospital_sn'    => '1317050017',
    'update_time'    => strtotime($time[0] . ' ' . $time[2] . $time[1]),
    'full_reported'  => ('是' === $match[2][1])? true:false,
    'pending_doctor' => intval($match[2][3]),
    'pending_bed'    => intval($match[2][5]),
    'pending_ward'   => intval($match[2][7]),
    'pending_icu'    => intval($match[2][9])
);

echo json_encode($return);
