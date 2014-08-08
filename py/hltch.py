#!/usr/bin/env ptyhon
#coding:UTF-8
import requests, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
html = requests.get('http://hlm.tzuchi.com.tw/html/HLinfo/Hlinfo.php')
pending = json.loads(html.text)[0]
# keys: ['m', 'WaitToPushBed', 'yy', 's', 'BedFull', 'WaitToAdm', 'WaitToDiagnosis', 'dd', 'mm', 'WaitToICU', 'h']

full_reported = True if u'是' in pending['BedFull'] else False
report ={}
report['full_reported'] = full_reported
report['pending_doctor'] = int( pending['WaitToDiagnosis'] )
report['pending_bed'] = int( pending['WaitToPushBed'] )
report['pending_ward'] = int( pending['WaitToAdm'] )
report['pending_icu'] = int( pending['WaitToICU'] )
update_time = '{0}/{1}/{2} {3}:{4}'.format(int(pending['yy'])+1911, pending['mm'], pending['dd'], pending['h'], pending['m'] )
report['update_time'] = datetime.strptime(update_time, '%Y/%m/%d %H:%M').strftime('%s')
report['Hosptial_sn'] = '1145010010'

print ( json.dumps(report, ensure_ascii=False) )

# [{"update_time": "1407232740", "pending_ICU": 1, "pending_bed": 0, "full_reported": true, "Hosptial_SN": "1145010010", "pending_doctor": 1, "pending_ward": 27}]
