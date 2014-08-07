#!/usr/bin/env python
#coding:UTF-8

import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www.pohai.org.tw/pohai/bedqty_er/bedqty_er.php')
html.encoding='utf8'

update_time = re.findall(u'日期：(.*)</td>',html.text)
pending = re.findall(u'center">(.+?)人</td>',html.text)
# prase like ['否</td>\t\t\t\t\t\t<td align="center">0  ', '0  ', '8  ', '0  ']
# [ report+doctor, bed, ward, ICU ]
p = pending[0].split('</td>\t\t\t\t\t\t<td align="center">')
# parse [report+doctor] -> ['否', '0  ']

full_reported = False if p[0]==u'否' else True
pending_doctor = int( p[1] )
pending_bed = int( pending[1] )
pending_ward = int( pending[2] )
pending_ICU = int( pending[3] )
update_time = datetime.strptime(update_time[0].strip(), '%Y/%m/%d %H:%M:%S').strftime('%s')
# using .timestamp() if python3 else .strftime('%s')
report = [{ "Hosptial_sn":'1434020015', "update_time":update_time, "full_reported":full_reported, "pending_doctor":pending_doctor, "pending_bed":pending_bed, "pending_ward":pending_ward, "pending_icu":pending_ICU }];
print ( json.dumps(report, ensure_ascii=False) )
#[{"full_reported": false, "pending_ward": 3, "update_time": 1407218898.0, "pending_doctor": 0, "pending_bed": 0, "Hosptial_SN": 1434020015, "pending_ICU": 0}]
