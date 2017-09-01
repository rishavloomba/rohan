import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

urls = ['http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zjh1&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zjh2&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zjh3&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zjh4&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zy1&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zy2&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zy3&date=%s' % dt,
        'http://www.csindex.com.cn/zh-CN/downloads/industry-price-earnings-ratio?type=zy4&date=%s' % dt]

sqls = ['INSERT INTO hy_jtsyl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_gdsyl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_sjl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_gxl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_jtsyl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_gdsyl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_sjl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_gxl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)']

tbs = ['hy_jtsyl', 'hy_gdsyl', 'hy_sjl', 'hy_gxl', 'bk_jtsyl', 'bk_gdsyl', 'bk_sjl', 'bk_gxl']

sqlite_file = 'sqlite/csindex.com.cn.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite(num):
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM %s WHERE dt=?' % tbs[num], (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        sql = sqls[num] % (dt, values)
        conn.execute(sql)
    conn.commit()
    conn.close()

def parse_web(num):
    print time.ctime() + ' -- ' + urls[num]
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(urls[num], headers=headers)
    if response['status'] == '200':
        soup = BeautifulSoup(content, 'lxml')
        if num < 4:
            trs = soup.select('tr .list-div-table-header')
        else:
            trs = soup.select('tbody tr')
        for tr in trs:
            entries.append(map(lambda x: x.text.strip(), tr.select('td')))
    return entries

def main():
    for m in range(len(urls)):
        if search_sqlite(m):
            insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
