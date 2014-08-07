#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://med.cgh.org.tw/unit/branch/Pharmacy/ebl/RealTimeInfoHQ.html')
html.encoding='big5'

update_time = re.findall(u'時間：(.*)</p>',html.text)
pending = re.findall(u'：</td *>(.+?)</td>',html.text)
# prase like ["<td align='right'>否", "<td align='right'>0", "<td align='right'>0", "<td align='right'>9", "<td align='right'>4"]

pending = [ele.replace("<td align='right'>",'') for ele in pending ]
values = [int(ele) for ele in pending[1:]]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']

report = { key:value for value, key in zip(values, keys) }
update_time = update_time[0].replace(u'：',':')
update_time = str( 1911 + int(update_time[0:3]) ) + update_time[3:]
report["Hosptial_sn"] = '1101020018'
report['full_reported'] = False if pending[0] ==u'否' else True
report["update_time"] = datetime.strptime(update_time, '%Y/%m/%d %H:%M').strftime('%s')
#using .timestamp() if py3 else .strftime('%s')

report = [report]

print ( json.dumps(report, ensure_ascii=False) )
# [{'update_time': '1407227400', 'pending_ICU': 4, 'pending_bed': 0, 'full_reported': False, 'Hosptial_SN': '1101020018', 'pending_doctor': 0, 'pending_ward': 8}]
