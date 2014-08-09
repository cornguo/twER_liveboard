#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www6.vghtpe.gov.tw/ERREALIFO/ERREALIFO.jsp')
html.encoding='big5'

pending = re.findall(u'">?(\w+)</font>',html.text)
full_reported = re.findall(u'體">?(.?)</font>',html.text)[0]
# prase like ['2', '0', '20', '0']

keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']
report = { key:value for value, key in zip(pending, keys) }

report["Hosptial_sn"] = '0501110514'
report['full_reported'] = False if full_reported==u'否' else True
report["update_time"] = 'null'

print ( json.dumps(report, ensure_ascii=False) )
# [{"update_time": "1407226207", "full_reported": true, "pending_bed": 16, "Hosptial_SN": "0501110514", "pending_doctor": 0, "pending_ward": 0}]
