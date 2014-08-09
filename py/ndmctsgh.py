#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os

os.environ['TZ'] = 'ROC'
html = requests.get('http://www1.ndmctsgh.edu.tw/ErOnlineNews/ErOnLineData.aspx')

pending = re.findall(u'd">(.+)</fon',html.text)
# prase like ['否', '3', '0', '9', '0']
values = [ int(ele) for ele in pending[1:] ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']

report = { key:value for value, key in zip(values, keys) }

report["Hosptial_sn"] = '1331040513'
print pending[0]
report['full_reported'] = False if u'否' in pending[0] else True
report["update_time"] = 'null'

print ( json.dumps(report, ensure_ascii=False) )
