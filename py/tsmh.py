#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www.tsmh.org.tw/~webapp/b/web_dg/er_status/er_show_db.php')
html.encoding='big5'

pending = re.findall(u': (.+?) 人',html.text)
# parse like ['1', '0', '4', '0']
full_reported = re.findall(u'>(.+)119',html.text)[0]
update_time = re.findall(u'時間: (.+) ]</',html.text)[0]

values = [ int(ele) for ele in pending ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']

report = { key:value for value, key in zip(values, keys) }
report["Hosptial_sn"] = '943030019'
report['full_reported'] = False if u'未' in full_reported else True
report["update_time"] = datetime.strptime(update_time, '%Y/%m/%d %H:%M').strftime('%s')

report = [report]
print ( json.dumps(report, ensure_ascii=False) )
