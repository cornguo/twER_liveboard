#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://www.femh.org.tw/research/news_op.aspx')

update_time =re.findall(u'日期：(.*)</span>',html.text)
update_time.append( datetime.now().strftime('%p') )
update_time = ' '.join(update_time)

full_reported = re.findall(u'滿床：.*(.+?)</span>',html.text)
pending = re.findall(u'人數：.*(.[0-9])</span>',html.text)
# parse [doctor, bed, ward, ICU] like ['>1', '38', '70', '>0']
values = [ int( ele.replace('>','') ) for ele in pending ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']

report = { key:value for value, key in zip(values, keys) }
report['Hosptial_sn'] = '1131010011'
report['full_reported'] = True if full_reported[0] == u'是' else False
report['update_time'] = datetime.strptime(update_time, '%Y-%m-%d %I:%M %p').strftime('%s')

print ( json.dumps(report, ensure_ascii=False) )
# [{"update_time": "1407185520", "pending_ICU": 0, "pending_bed": 37, "full_reported": true, "Hosptial_SN": "1131010011", "pending_doctor": 4, "pending_ward": 56}]
