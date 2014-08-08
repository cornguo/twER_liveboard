#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://eng.shh.org.tw/ER_WEB/ER_WEB/Default.aspx')
html.encoding='utf8'

pending = re.findall(u'">(.*)</span>',html.text)
# prase like ['<b><font color="Red">未滿載</font></b>', '1', '0', '22', '0', '06:33:10']
update_time = datetime.today().strftime('%Y/%m/%d ') + pending.pop()
values = [ int(ele) for ele in pending[1:] ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']

report = { key:value for value, key in zip(values, keys) }

report["Hosptial_sn"] = '1331040513'
report['full_reported'] = False if u'未滿載' in pending[0] else True
report["update_time"] = datetime.strptime(update_time, '%Y/%m/%d %H:%M:%S').strftime('%s')

print ( json.dumps(report, ensure_ascii=False) )
# [{"update_time": "1407227991", "pending_ICU": 2, "pending_bed": 5, "full_reported": false, "Hosptial_SN": "1331040513", "pending_doctor": 5, "pending_ward": 19}]
