import json
import httplib2
import sqlite3
import time

dt = time.strftime("%Y%m%d")
sqlite_file = 'sqlite/sse.com.cn.db'
url = 'http://yunhq.sse.com.cn:32041/v1/sh1/dayk/000001?select=date%2Copen%2Chigh%2Clow%2Cclose%2Cvolume%2Camount&begin=-2&end=-1'
sql = 'INSERT INTO szzs (dt,open,high,low,close,volume,amount) VALUES ("%s","%s","%s","%s","%s","%s","%s")'

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
    n = 0
    results = []
    print time.ctime() + ' -- ' + url
    while n < 10:
        n += 1
        try:
            http = httplib2.Http()
            response, content = http.request(url)
            c = json.loads(content)
            if dt == str(c['kline'][-1][0]):
                results = c['kline']
            n = 10
        except:
            print n
            time.sleep(60)
    return results

def main():
    insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
