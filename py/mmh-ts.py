#!/usr/bin/env python
#coding:UTF-8
import requests, re, json, os
from datetime import datetime
os.environ['TZ'] = 'ROC'
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36', 'Content-Type': 'application/x-www-form-urlencoded', 'Referer': 'https://trns.mmh.org.tw/WebEMR/Default.aspx', 'Accept-Encoding': 'gzip,deflate,sdch', 'Accept-Language': 'zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,ja;q=0.2', 'Cookie': 'ASPSESSIONIDQSRRSRQC=DNKDKBEAFLNDPCLLIMHDNMEG' }
data = {'__EVENTTARGET':'', '__EVENTARGUMENT':'', '__LASTFOCUS':'', '__VIEWSTATE':'/wEPDwUKLTg4MTI3NDI5MQ9kFgICAw9kFgQCBQ8QZGQWAQIBZAIHDzwrAA0BAA8WBB4LXyFEYXRhQm91bmRnHgtfIUl0ZW1Db3VudAIGZBYCZg9kFg4CAQ9kFgRmDw8WAh4EVGV4dAUP6KiK5oGv5pmC6ZaT77yaZGQCAQ8PFgIfAgUVMjAxNC84LzYg5LiK5Y2IIDAxOjM0ZGQCAg9kFgRmDw8WAh8CBSHlt7LlkJExMTnpgJrloLHmu7/luorvvIjovInvvInvvJpkZAIBDw8WAh8CBQPlkKZkZAIDD2QWBGYPDxYCHwIFFeetieW+heeci+iouuS6uuaVuO+8mmRkAgEPDxYCHwIFATZkZAIED2QWBGYPDxYCHwIFFeetieW+heaOqOW6iuS6uuaVuO+8mmRkAgEPDxYCHwIFATBkZAIFD2QWBGYPDxYCHwIFFeetieW+heS9j+mZouS6uuaVuO+8mmRkAgEPDxYCHwIFAjE4ZGQCBg9kFgRmDw8WAh8CBRvnrYnlvoXliqDorbfnl4XmiL/kurrmlbjvvJpkZAIBDw8WAh8CBQEwZGQCBw8PFgIeB1Zpc2libGVoZGQYAQUJR3JpZFZpZXcyDzwrAAkBCAIBZMHdxgUlbCwhEhGv7jcy/vdDPM4Q', 'RadioButtonList1':2, 'Button1':'查詢', '__EVENTVALIDATION':'/wEWBQKMpZfvBAL444i9AQL544i9AQL3jKLTDQKM54rGBga9OHdzNiFVAluD8E3DE64vEWO9' }
html = requests.post('https://trns.mmh.org.tw/WebEMR/Default.aspx', verify=False, headers=headers, data=data)

pending = re.findall(u'：</td><td>(.+?)</td>',html.text)
#parse like ['2014/8/6 上午 01:40', '否', '6', '0', '18', '0']

values = [ int(ele) for ele in pending[2:] ]
keys = ['pending_doctor','pending_bed', 'pending_ward', 'pending_icu']
report = { key:value for value, key in zip(values, keys) }

update_time = pending[0].replace(u'上午','am') if u'上午' in pending[0] else pending[0].replace(u'下午','pm')

report["Hosptial_sn"] = '1131100010'
report['full_reported'] = False if u'否' in pending[1] else True
report["update_time"] = datetime.strptime(update_time, '%Y/%m/%d %p %I:%M').strftime('%s')

print ( json.dumps(report, ensure_ascii=False) )
