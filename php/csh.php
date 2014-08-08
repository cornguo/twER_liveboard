<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.csh.org.tw/ER/index.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<span.*><font.*>(.*)<\/font>.*<\/span>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('：', '上', '下', '午'),
    array(' ', 'A', 'P', 'M'),
    strip_tags($match[1][11])
);
$time = explode(' ', $time);

$return = array(
    'hospital_sn'    => '1317040011',
    'update_time'    => strtotime($time[1] . ' ' . $time[3] . $time[2]),
    'full_reported'  => ('未通報' === $match[1][10])? false:true,
    'pending_doctor' => intval($match[1][0]),
    'pending_bed'    => intval($match[1][2]),
    'pending_ward'   => intval($match[1][3]),
    'pending_icu'    => intval($match[1][9])
);

echo json_encode($return);
