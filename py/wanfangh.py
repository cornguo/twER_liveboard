#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www.wanfang.gov.tw/W402008web_new/epd_query.asp')
html.encoding='big5'

update_time = re.findall(u'日期:(.*) ，系統',html.text)
pending = re.findall(u'：(.*)?</li>',html.text)
# prase like ['否', '0 人', '0 人', '24 人 ', '0 人']
pending =[ ele.replace(u' 人','').strip() for ele in pending ]
values = [int(ele) for ele in pending[1:]]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']
report = { key:value for value, key in zip(values, keys) }
update_time = update_time[0].replace(u'：',':').replace(u'\u3000',' ')
report["Hosptial_sn"] = '1301200010'
report['full_reported'] = False if pending[0]==u'否' else True
report["update_time"] = datetime.strptime(update_time, '%Y/%m/%d %H:%M').strftime('%s')
#using .timestamp() if py3 else .strftime('%s')

print ( json.dumps(report, ensure_ascii=False) )
#[{"update_time": "1407225600", "pending_ICU": 2, "pending_bed": 0, "full_reported": false, "Hosptial_SN": "1301200010", "pending_doctor": 2, "pending_ward": 19}]
