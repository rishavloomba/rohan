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

sqlite_file = 'sqlite/cnindex.com.cn.db'

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        sql = sqls[num] % (dt, values)
        conn.execute(sql)
    conn.commit()
    conn.close()

def parse_web(num):
    n = 0
    results = []
    print urls[num]
    while n < 10:
        n += 1
        try:
            entries = []
            http = httplib2.Http()
            response, content = http.request(urls[num])
            if response['status'] == '200':
                soup = BeautifulSoup(content, 'lxml')
                for tr in soup.select('table')[1].select('tr'):
                    entries.append(map(lambda x: x.text.strip(), tr.select('td')[0:7]))
                results = entries
                n = 10
            elif response['status'] == '404':
                n = 10
        except:
            print n
            time.sleep(600)
    return results

def main():
    for m in range(len(urls)):
        insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
