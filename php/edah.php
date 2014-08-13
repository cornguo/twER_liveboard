<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www3.edah.org.tw/E-DA/WebRegister/ProcessEmeInf.jsp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = iconv('big5', 'utf-8//ignore', $data);

$pattern = '/<td>.*：(.*)<\/font>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hospital_sn'    => '1142120001',
    'update_time'    => time(),
    'full_reported'  => ('是' === $match[1][0])? true:false,
    'pending_doctor' => intval($match[1][1]),
    'pending_bed'    => intval($match[1][2]),
    'pending_ward'   => intval($match[1][3]),
    'pending_icu'    => intval($match[1][4])
);

echo json_encode($return);
