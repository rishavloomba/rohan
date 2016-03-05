import httplib2
import sqlite3
import time
from bs4 import BeautifulSoup

urls = ['http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=1',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=2',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=3',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=1&zb_flg=4',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=1',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=2',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=3',
        'http://www.csindex.com.cn/sseportal/csiportal/syl/hytype.do?code=3&zb_flg=4']

sqls = ['INSERT INTO hy_jtsyl (hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO hy_gdsyl (hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO hy_sjl (hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO hy_gxl (hyid, name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO bk_jtsyl (name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO bk_gdsyl (name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO bk_sjl (name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)',
        'INSERT INTO bk_gxl (name, pe, total, loss, pe1, pe3, pe6, pe12) VALUES (%s)']

sqlite_file = 'sqlite/csindex.com.cn.db'

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        sql = sqls[num] % values
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
            soup = BeautifulSoup(content, 'lxml', from_encoding='gbk')
            for tr in soup.select('tr .list-div-table-header'):
                entries.append(map(lambda x: x.text.strip(), tr.select('td')))
            results = entries
        except:
            print n
            time.sleep(600)
    return results

def main():
    for m in range(len(urls)):
        insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()