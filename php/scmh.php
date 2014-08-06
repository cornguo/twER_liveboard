<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.scmh.org.tw/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = strstr($data, '重度級急救責任醫院急診即時訊息');

$pattern = '/<td.*>(.*<span.*>(.*)<\/span>|.*119.*)<\/td>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hosptial_sn'    => '0937010019',
    'update_time'    => strtotime($match[2][6]),
    'full_reported'  => ('未向119通報滿床(載)' === $match[1][1])? false:true,
    'pending_doctor' => intval($match[2][2]),
    'pending_bed'    => intval($match[2][4]),
    'pending_ward'   => intval($match[2][3]),
    'pending_icu'    => intval($match[2][5])
);

echo json_encode($return);
