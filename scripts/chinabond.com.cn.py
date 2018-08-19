import httplib2
import sqlite3
import time
import json
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

urls = ['http://yield.chinabond.com.cn/cbweb-mn/yc/ycDetail?ycDefIds=2c9081e50a2f9606010a3068cae70001&zblx=txy&dxbj=0&qxlx=0&yqqxN=N&yqqxK=K&wrjxCBFlag=0&workTime=%s' % dt,
        'http://yield.chinabond.com.cn/cbweb-mn/yc/searchYc?xyzSelect=txy&dxbj=0&qxll=0,&yqqxN=N&yqqxK=K&ycDefIds=8a8b2ca037a7ca910137bfaa94fa5057&wrjxCBFlag=0&locale=zh_CN&workTimes=%s' % dt]
sqls = ['INSERT INTO bond (dt, name, rate) VALUES ("%s", "%s", "%s")',
        'INSERT INTO gkh (dt, name, rate) VALUES ("%s", "%s", "%s")']
tbs = ['bond','gkh']

sqlite_file = 'sqlite/chinabond.com.cn.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite(n):
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM %s WHERE dt=?' % tbs[n], (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(n, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        conn.execute(sqls[n] % (dt, entry[0], entry[1]))
    conn.commit()
    conn.close()

def parse_web():
    n_map = {'0.0y':'0d','0.08y':'1m','0.17y':'2m','0.25y':'3m','0.5y':'6m','0.75y':'9m','1.0y':'1y',
             '2.0y': '2y','3.0y':'3y','4.0y':'4y','5.0y':'5y','6.0y':'6y','7.0y':'7y','8.0y':'8y',
             '9.0y':'9y','10.0y':'10y','15.0y':'15y','20.0y':'20y','30.0y':'30y','40.0y':'40y','50.0y':'50y'}
    print time.ctime() + ' -- ' + urls[0]
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(urls[0], method='POST', headers=headers)
    if response['status'] == '200':
        soup = BeautifulSoup(content, 'lxml')
        for tr in soup.select('tr'):
            tds = tr.select('td')
            name = tds[0].text.strip()
            rate = tds[1].text.strip()
            if name.endswith('y'):
                entries.append([n_map[name], rate])
    return entries

def parse_web2():
    n_map = {0:'0d', 0.0833:'1m', 0.1667:'2m', 0.25:'3m', 0.5:'6m', 0.75:'9m', 1.0:'1y',
             3.0:'3y', 5.0:'5y', 7.0:'7y', 10.0:'10y', 15.0:'15y', 20.0:'20y', 30.0:'30y', 40.0:'40y', 50.0:'50y'}
    print time.ctime() + ' -- ' + urls[1]
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(urls[1], method='POST', headers=headers)
    try:
        j = json.loads(content)
        for ent in j[0]['seriesData']:
            if ent[0] in n_map.keys():
                entries.append([n_map[ent[0]], ent[1]])
    except:
        pass
    return entries

def main():
    if search_sqlite(0):
        insert_sqlite(0,parse_web())
    if search_sqlite(1):
        insert_sqlite(1,parse_web2())

if __name__ == '__main__':
    main()
