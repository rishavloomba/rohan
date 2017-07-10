import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

urls = ['http://www.szse.cn/szseWeb/FrontController.szse?ACTIONID=7&CATALOGID=1803&TABKEY=tab1&txtQueryDate=%s' % dt,
        'http://www.szse.cn/szseWeb/FrontController.szse?ACTIONID=7&CATALOGID=1803&TABKEY=tab2&txtQueryDate=%s' % dt,
        'http://www.szse.cn/szseWeb/FrontController.szse?ACTIONID=7&CATALOGID=1803&TABKEY=tab3&txtQueryDate=%s' % dt,
        'http://www.szse.cn/szseWeb/FrontController.szse?ACTIONID=7&CATALOGID=1803&TABKEY=tab4&txtQueryDate=%s' % dt]

sqls = ['INSERT INTO szsc (dt, name, today, delta, percent, hvalue, hdate) VALUES ("%s", %s)',
        'INSERT INTO szzb (dt, name, today, delta, hvalue, hdate) VALUES ("%s", %s)',
        'INSERT INTO zxb (dt, name, today, delta, hvalue, hdate) VALUES ("%s", %s)',
        'INSERT INTO cyb (dt, name, today, delta, hvalue, hdate) VALUES ("%s", %s)']

tbs = ['szsc', 'szzb', 'zxb', 'cyb']

sqlite_file = 'sqlite/szse.cn.db'
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
        soup = BeautifulSoup(content, 'lxml', from_encoding='gbk')
        for tr in soup.select('table .cls-data-table')[0].select('tr')[1:]:
            tds = tr.select('td')
            if len(tds) > 1:
                entries.append(map(lambda x: x.text.strip(), tds))
    return entries

def main():
    for m in range(len(urls)):
        if search_sqlite(m):
            insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
