import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

urls = ['http://www.cnindex.com.cn/syl/%s/csrc_szzt.html' % dt,
        'http://www.cnindex.com.cn/syl/%s/csrc_szzb.html' % dt,
        'http://www.cnindex.com.cn/syl/%s/csrc_zxb.html'  % dt,
        'http://www.cnindex.com.cn/syl/%s/csrc_cyb.html'  % dt,
        'http://www.cnindex.com.cn/syl/%s/csrc_hsls.html' % dt]

sqls = ['INSERT INTO szsc (dt, hyid, name, total, jtsyl_jqpj, jtsyl_zws, gdsyl_jqpj, gdsyl_zws) VALUES ("%s", %s)',
        'INSERT INTO szzb (dt, hyid, name, total, jtsyl_jqpj, jtsyl_zws, gdsyl_jqpj, gdsyl_zws) VALUES ("%s", %s)',
        'INSERT INTO zxb  (dt, hyid, name, total, jtsyl_jqpj, jtsyl_zws, gdsyl_jqpj, gdsyl_zws) VALUES ("%s", %s)',
        'INSERT INTO cyb  (dt, hyid, name, total, jtsyl_jqpj, jtsyl_zws, gdsyl_jqpj, gdsyl_zws) VALUES ("%s", %s)',
        'INSERT INTO hsls (dt, hyid, name, total, jtsyl_jqpj, jtsyl_zws, gdsyl_jqpj, gdsyl_zws) VALUES ("%s", %s)']

tbs = ['szsc', 'szzb', 'zxb', 'cyb', 'hsls']

sqlite_file = 'sqlite/cnindex.com.cn.db'
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
        for tr in soup.select('table')[1].select('tr'):
            entries.append(map(lambda x: x.text.strip(), tr.select('td')[0:7]))
    return entries

def main():
    for m in range(len(urls)):
        if search_sqlite(m):
            insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
