<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('https://www.cgmh.org.tw/bed/erd/index.asp?loc=8');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = iconv('big5', 'utf-8//ignore', $data);

$pattern = '/(<img.*<FONT.*>|更新時間：)(.*)<\/td>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hosptial_sn'    => '1142100017',
    'update_time'    => strtotime($match[2][0]),
    'full_reported'  => ('是' === $match[2][1])? true:false,
    'pending_doctor' => intval($match[2][2]),
    'pending_bed'    => intval($match[2][3]),
    'pending_ward'   => intval($match[2][4]),
    'pending_icu'    => intval($match[2][5])
);

echo json_encode($return);
