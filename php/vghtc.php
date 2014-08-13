<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.vghtc.gov.tw/GipOpenWeb/wSite/sp?xdUrl=/wSite/query/Doctor/GetEmgBedInform.jsp&ctNode=55658&mp=1&idPath=213_55658');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = strstr($data, '<span>急診即時資訊</span>');

$pattern = '/<td.*>.*([^>]*)人?<\/.*<\/td>/Uum';

$match = array();
preg_match_all($pattern, $data, $match);

$return = array(
    'hospital_sn'    => '0617060018',
    'update_time'    => time(),
    'full_reported'  => ('否' === $match[1][1])? false:true,
    'pending_doctor' => intval($match[1][3]) + intval($match[1][5]) + intval($match[1][7]),
    'pending_bed'    => intval($match[1][9]),
    'pending_ward'   => intval($match[1][11]),
    'pending_icu'    => intval($match[1][13])
);

echo json_encode($return);
