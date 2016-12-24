import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

urls = ['http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=1&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=2&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=3&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=4&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=1&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=2&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=3&db_type=0&date=%s' % dt,
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=4&db_type=0&date=%s' % dt]

sqls = ['INSERT INTO hy_jtsyl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_gdsyl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_sjl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO hy_gxl (dt, hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_jtsyl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_gdsyl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_sjl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)',
        'INSERT INTO bk_gxl (dt, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES ("%s", %s)']

sqlite_file = 'sqlite/csindex.com.cn.db'

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
    print time.ctime() + ' -- ' + urls[num]
    while n < 10:
        n += 1
        try:
            entries = []
            http = httplib2.Http()
            response, content = http.request(urls[num])
            if response['status'] == '200':
                soup = BeautifulSoup(content, 'lxml', from_encoding='gbk')
                for tr in soup.select('tr .list-div-table-header'):
                    entries.append(map(lambda x: x.text.strip(), tr.select('td')))
                results = entries
                if entries:
                    n = 10
                else:
                    time.sleep(10)
        except:
            print n
            time.sleep(600)
    return results

def main():
    for m in range(len(urls)):
        insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
