#!/usr/bin/python2
# -*- coding: utf-8 -*-
import re
import httplib2
import sqlite3
import time
from bs4 import BeautifulSoup

base = ['http://sme.szse.cn', 'http://chinext.szse.cn']
uri1 = ['/main/sme/xqsj/sctjyb/', '/main/chinext/scsj/sctjyb/']
tbs = ['sme', 'chinext']

sqlite_file = 'sqlite/sme_chinext.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite(num, dt):
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM %s WHERE dt=?' % tbs[num], (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(num, results):
    conn = sqlite3.connect(sqlite_file)
    dt = results[0]
    entries = results[1]
    if dt and entries and search_sqlite(num, dt):
        for entry in entries:
            sql = 'INSERT INTO %s (dt,code,turnover,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12) VALUES ("%s", "%s")' % (tbs[num], dt, '", "'.join(entry))
            conn.execute(sql)
    conn.commit()
    conn.close()

def parse_web(num):
    print time.ctime() + ' -- ' + base[num] + uri1[num]
    entries = []
    dt = ''
    http = httplib2.Http(timeout=60)
    response1, content1 = http.request(base[num] + uri1[num], headers=headers)
    if response1['status'] == '200':
        soup1 = BeautifulSoup(content1, 'lxml', from_encoding='gbk')
        uri2 = soup1.find('a', string='上市证券成交金额').get('href')
        print time.ctime() + ' -- ' + base[num] + uri2
        response2, content2 = http.request(base[num] + uri2, headers=headers)
        if response2['status'] == '200':
            soup2 = BeautifulSoup(content2, 'lxml', from_encoding='gbk')
            title = soup2.find('div',class_='title_en').text
            date1 = re.findall(r'[\d\.]+', title)[0]
            dt = date1.replace('.','-') + '-01'
            for tr in soup2.select('tr')[2:]:
                tds = tr.select('td')
                val = map(lambda x: x.text.strip(), tds)
                val.pop(1)
                entries.append(val)
    return [dt, entries]

def main():
    for m in range(len(base)):
        insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
