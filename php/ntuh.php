<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://reg.ntuh.gov.tw/EmgInfoBoard/Y0NTUHEmgInfo.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/<t?di?v?.*>(.*)人?<\/t?di?v?>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = str_replace(
    array('：', '上', '下', '午'),
    array(' ', 'A', 'P', 'M'),
    strip_tags($match[1][11])
);
$time = explode(' ', $time);

$return = array(
    'hospital_sn'    => '0439010518',
    'update_time'    => strtotime($time[1] . ' ' . $time[3] . $time[2]),
    'full_reported'  => ('目前本院未通報119滿床' === strip_tags($match[1][12]))? false:true,
    'pending_doctor' => intval($match[1][4]),
    'pending_bed'    => intval($match[1][10]),
    'pending_ward'   => intval($match[1][6]),
    'pending_icu'    => intval($match[1][8])
);

echo json_encode($return);
