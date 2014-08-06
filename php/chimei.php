<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.chimei.org.tw/%E6%80%A5%E8%A8%BA%E5%8D%B3%E6%99%82%E8%A8%8A%E6%81%AF/main.aspx?ihosp=10');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

$pattern = '/(<span.*>(.*)<\/span>|<font.*>(.*)<\/font>)/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hosptial_sn'    => '1141310019',
    'update_time'    => strtotime($match[2][1]),
    'full_reported'  => ('未通報' === strip_tags($match[3][11]))? false:true,
    'pending_doctor' => intval($match[3][12]),
    'pending_bed'    => intval($match[3][17]),
    'pending_ward'   => intval($match[3][15]),
    'pending_icu'    => intval($match[3][16])
);

echo json_encode($return);
