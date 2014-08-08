<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.hosp.ncku.edu.tw/nckm/ER/default.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<span.*>(.*)<\/span>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('：', '上', '下', '午', '）'),
    array(' ', 'A', 'P', 'M', ''),
    strip_tags($match[1][0])
);
$time = explode(' ', $time);

$return = array(
    'hospital_sn'    => '0421040011',
    'update_time'    => strtotime($time[1] . ' ' . $time[3] . $time[2]),
    'full_reported'  => ('未通報' === strip_tags($match[1][5]))? false:true,
    'pending_doctor' => intval($match[1][1]),
    'pending_bed'    => intval($match[1][4]),
    'pending_ward'   => intval($match[1][2]),
    'pending_icu'    => intval($match[1][3])
);

echo json_encode($return);
