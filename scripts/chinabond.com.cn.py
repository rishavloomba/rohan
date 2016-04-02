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

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        conn.execute(sql % (dt, entry[0], entry[1]))
    conn.commit()
    conn.close()

def parse_web():
    n = 0
    results = []
    n_map = {'0.0y':'0d','0.08y':'1m','0.17y':'2m','0.25y':'3m','0.5y':'6m','0.75y':'9m','1.0y':'1y',
             '3.0y':'3y','5.0y':'5y','7.0y':'7y','10.0y':'10y','15.0y':'15y','20.0y':'20y','30.0y':'30y','40.0y':'40y','50.0y':'50y'}
    print time.ctime() + ' -- ' + url
    while n < 10:
        n += 1
        try:
            entries = []
            http = httplib2.Http()
            response, content = http.request(url)
            if response['status'] == '200':
                soup = BeautifulSoup(content, 'lxml')
                for tr in soup.select('tr'):
                    tds = tr.select('td')
                    name = tds[0].text.strip()
                    rate = tds[1].text.strip()
                    if name.endswith('y'):
                        entries.append([n_map[name], rate])
                results = entries
                n = 10
        except:
            print n
            time.sleep(60)
    return results

def main():
    insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
