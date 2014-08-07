<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.vghks.gov.tw/ISCenter/data/VGHKERINFO.PDF');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = iconv('big5', 'utf-8//ignore', $data);

$pattern = '/Tm \(\s+?(.*)\)/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hosptial_sn'    => '0602030026',
    'update_time'    => strtotime(substr($match[1][1], 0, -8)),
    'full_reported'  => ('是' === $match[1][3])? true:false,
    'pending_doctor' => intval($match[1][5]),
    'pending_bed'    => intval($match[1][7]),
    'pending_ward'   => intval($match[1][9]),
    'pending_icu'    => intval($match[1][11])
);

echo json_encode($return);
