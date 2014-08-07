#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www6.vghtpe.gov.tw/ERREALIFO/ERREALIFO.jsp')
html.encoding='big5'

update_time = datetime.now()
pending = re.findall(u'">?(.\w)</font>',html.text)
# prase like ['>否', '>2', '>0', '20', '>0']

pending = [ ele.replace('>','').strip() for ele in pending ]
values = [ int(ele) for ele in pending[1:] ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_ICU']
report = { key:value for value, key in zip(values, keys) }

report["Hosptial_SN"] = '0501110514'
report['full_reported'] = False if pending[0]==u'否' else True
report["update_time"] = update_time.strftime('%s')
# no updatetime, so using datetime.now().
# using .timestamp() if py3 else .strftime('%s')

report = [report]
print ( json.dumps(report, ensure_ascii=False) )
# [{"update_time": "1407226207", "full_reported": true, "pending_bed": 16, "Hosptial_SN": "0501110514", "pending_doctor": 0, "pending_ward": 0}]
