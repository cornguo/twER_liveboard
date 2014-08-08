<?php
date_default_timezone_set('Asia/Taipei');
$ch = curl_init('http://www.ktgh.com.tw/BednoInfo_Show.asp?CatID=81&ModuleType=Y');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
$data = iconv('big5', 'utf-8//ignore', $data);
$data = strstr($data, '急診即時資訊公告');

$pattern = '/(<td style="color:#(.*);" >.*<\/td>|<td>(.*)<\/td>|.*Last Edited Time: (.*)\.)/Uum';

$match = array();
preg_match_all($pattern, $data, $match);
$time = $match[4][5];
$time = intval(substr($time, 0, 3) + 1911) . substr($time, 3);

$return = array(
    'hospital_sn'    => '1536030075',
    'update_time'    => strtotime($time),
    'full_reported'  => ('e7e7e7' === $match[2][0])? false:true,
    'pending_doctor' => intval($match[3][1]),
    'pending_bed'    => intval($match[3][2]),
    'pending_ward'   => intval($match[3][3]),
    'pending_icu'    => intval($match[3][4])
);

echo json_encode($return);
