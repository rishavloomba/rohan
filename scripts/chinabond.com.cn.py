import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

url = 'http://yield.chinabond.com.cn/cbweb-mn/yc/ycDetail?ycDefIds=2c9081e50a2f9606010a3068cae70001&zblx=txy&dxbj=0&qxlx=0&yqqxN=N&yqqxK=K&wrjxCBFlag=0&workTime=%s' % dt
sql = 'INSERT INTO bond (dt, name, rate) VALUES ("%s", "%s", "%s")'

sqlite_file = 'sqlite/chinabond.com.cn.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite():
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM bond WHERE dt=?', (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        conn.execute(sql % (dt, entry[0], entry[1]))
    conn.commit()
    conn.close()

def parse_web():
    n_map = {'0.0y':'0d','0.08y':'1m','0.17y':'2m','0.25y':'3m','0.5y':'6m','0.75y':'9m','1.0y':'1y',
             '2.0y': '2y','3.0y':'3y','4.0y':'4y','5.0y':'5y','6.0y':'6y','7.0y':'7y','8.0y':'8y',
             '9.0y':'9y','10.0y':'10y','15.0y':'15y','20.0y':'20y','30.0y':'30y','40.0y':'40y','50.0y':'50y'}
    print time.ctime() + ' -- ' + url
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(url, headers=headers)
    if response['status'] == '200':
        soup = BeautifulSoup(content, 'lxml')
        for tr in soup.select('tr'):
            tds = tr.select('td')
            name = tds[0].text.strip()
            rate = tds[1].text.strip()
            if name.endswith('y'):
                entries.append([n_map[name], rate])
    return entries

def main():
    if search_sqlite():
        insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
