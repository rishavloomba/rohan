import httplib2
import sqlite3
import time
import sys

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

url = ['http://www.shibor.org/shibor/web/html/downLoad.html?nameNew=Historical_Shibor_Data_2016.txt&nameOld=Shibor%CA%FD%BE%DD2016.txt&shiborSrc=http%3A%2F%2Fwww.shibor.org%2Fshibor%2F&downLoadPath=data',
       'http://www.shibor.org/shibor/web/html/downLoad.html?nameNew=Historical_Shibor_Tendency_Data_2016.txt&nameOld=Shibor%BE%F9%D6%B5%CA%FD%BE%DD2016.txt&shiborSrc=http%3A%2F%2Fwww.shibor.org%2Fshibor%2F&downLoadPath=data']

sql = ['insert into shibor (dt,o_n,w1,w2,m1,m3,m6,m9,y1) values ("%s")',
       'insert into shibor_ma (dt,o_n_5,o_n_10,o_n_20,w1_5,w1_10,w1_20,w2_5,w2_10,w2_20,m1_5,m1_10,m1_20,m3_5,m3_10,m3_20,m6_5,m6_10,m6_20,m9_5,m9_10,m9_20,y1_5,y1_10,y1_20) values ("%s")']
sqlite_file = 'sqlite/shibor.org.db'

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '","'.join(entry)
        conn.execute(sql[num] % values)
    conn.commit()
    conn.close()

def parse_web(num):
    n = 0
    results = []
    print time.ctime() + ' -- ' + url[num]
    while n < 10:
        n += 1
        try:
            entries = []
            http = httplib2.Http()
            response, content = http.request(url[num])
            if response['status'] == '200':
                for line in content.split('\r\n'):
                    cells = line.split()
                    if(len(cells) > 1 and cells[0] == dt):
                        entries.append(cells)
                results = entries
                n = 10
        except:
            print n
            time.sleep(60)
    return results

def main():
    for m in range(len(url)):
        insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
