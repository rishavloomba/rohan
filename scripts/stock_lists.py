import httplib2
import sqlite3
import json

url = 'http://www.szse.cn/szseWeb/ShowReport.szse?SHOWTYPE=json&CATALOGID=1110&ENCODE=1&TABKEY=tab1&tab1PAGESIZE=3000'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

sqlite_file = 'sqlite/stock_lists.db'

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    if entries:
        conn.execute('delete from stock')
    for entry in entries:
        conn.execute('insert into stock values ("%s","%s")' % (entry[0], entry[1]))
    conn.commit()
    conn.close()

def parse_web():
    http = httplib2.Http(timeout=60)
    response, content = http.request(url, 'GET', headers=headers)
    results = []
    c = json.loads(content.decode('gbk').encode('utf-8'))
    for i in c[0]['data']:
        results.append([i['zqdm'],i['gsjc']])
    return results

def main():
    insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
