<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('https://regis.skh.org.tw/ERONLINE/INDEX.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<span.*>([^>]*)<\/span>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hospital_sn'    => '1101150011',
    'update_time'    => strtotime($match[1][7] . ' ' . $match[1][8]),
    'full_reported'  => ('已向119通報滿床(載)' === $match[1][0])? true:false,
    'pending_doctor' => intval($match[1][1]) + intval($match[1][5]) + intval($match[1][6]),
    'pending_bed'    => intval($match[1][2]),
    'pending_ward'   => intval($match[1][3]),
    'pending_icu'    => intval($match[1][4])
);

echo json_encode($return);
