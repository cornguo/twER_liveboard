<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.sltung.com.tw/tw/BED/bed.html');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = iconv('big5', 'utf-8//ignore', $data);

$pattern = '/>(\d\..*：|更新時間 : )([^<]*)<?\s*</Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = $match[2][5];
$time = intval(substr($time, 0, 3) + 1911) . substr($time, 3);

$return = array(
    'hospital_sn'    => '0936060016',
    'update_time'    => strtotime($time),
    'full_reported'  => ('否' === $match[2][0])? false:true,
    'pending_doctor' => intval($match[2][1]),
    'pending_bed'    => intval($match[2][2]),
    'pending_ward'   => intval($match[2][3]),
    'pending_icu'    => intval($match[2][4])
);

echo json_encode($return);
