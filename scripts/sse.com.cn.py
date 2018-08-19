import json
import httplib2
import sqlite3
import time
import sys

dt = time.strftime("%Y%m%d")
sqlite_file = 'sqlite/sse.com.cn.db'
url = 'http://yunhq.sse.com.cn:32041/v1/sh1/dayk/000001?select=date%2Copen%2Chigh%2Clow%2Cclose%2Cvolume%2Camount&begin=-2&end=-1'
sql = 'INSERT INTO szzs (dt,open,high,low,close,volume,amount) VALUES ("%s","%s","%s","%s","%s","%s","%s")'
tbs = ['szzs', 'shsc']

if len(sys.argv) < 2:
    dt2 = time.strftime("%Y-%m-%d")
else:
    dt2 = sys.argv[1]
url2 = 'http://query.sse.com.cn/marketdata/tradedata/queryTradingByProdTypeData.do?searchDate=%s&prodType=gp' % dt2
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite(num):
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM %s WHERE dt=?' % tbs[num], (dt2,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        d = str(entry[0])
        dat = d[0:4]+'-'+d[4:6]+'-'+d[6:8]
        sqli = sql % (dat,entry[1],entry[2],entry[3],entry[4],entry[5],entry[6])
        conn.execute(sqli)
    conn.commit()
    conn.close()

def parse_web():
    results = []
    print time.ctime() + ' -- ' + url
    http = httplib2.Http(timeout=60)
    response, content = http.request(url, headers=headers)
    c = json.loads(content)
    if dt == str(c['kline'][-1][0]):
        results = c['kline']
    return results

def insert_sqlite2(entries):
    tbs = {'12': 'shsc', '1': 'shag', '2': 'shbg'}
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        if not tbs.has_key(entry['productType']): continue
        sql = 'INSERT INTO %s (dt,sjzz,ltsz,cjl,cjje,cjbs,pjsyl,hsl) VALUES ("%s","%s","%s","%s","%s","%s","%s","%s")' % (
               tbs[entry['productType']],dt2,entry['marketValue'],entry['negotiableValue'],entry['trdVol'],entry['trdAmt'],entry['trdTm'],entry['profitRate'],entry['exchangeRate'])
        conn.execute(sql)
    conn.commit()
    conn.close()

def parse_web2():
    results = []
    print time.ctime() + ' -- ' + url2
    http = httplib2.Http(timeout=60)
    headers.update({'Referer': 'http://www.sse.com.cn/market/stockdata/overview/day/'})
    response, content = http.request(url2, 'GET', headers=headers)
    c = json.loads(content)
    if c['result'][0]['profitRate']:
        results = c['result']
    return results

def main():
    if search_sqlite(0):
        insert_sqlite(parse_web())
    if search_sqlite(1):
        insert_sqlite2(parse_web2())

if __name__ == '__main__':
    main()
