<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://app.tzuchi.com.tw/tchw/ERInfo/ERInformation.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<(td|span).*>(.*)<\/(td|span)>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('上', '下', '午'),
    array('A', 'P', 'M'),
    strip_tags($match[2][4])
);
$time = explode(' ', $time);

$return = array(
    'hospital_sn'    => '1131050515',
    'update_time'    => strtotime($time[0] . ' ' . $time[2] . $time[1]),
    'full_reported'  => ('未向119通報滿床' === $match[2][5])? false:true,
    'pending_doctor' => intval($match[2][0]),
    'pending_bed'    => intval($match[2][1]),
    'pending_ward'   => intval($match[2][2]),
    'pending_icu'    => intval($match[2][3])
);

echo json_encode($return);
