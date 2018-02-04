import httplib2
import sqlite3
import time
import sys

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

url = ['http://www.shibor.org/shibor/web/html/downLoad.html?nameNew=Historical_Shibor_Data_2018.txt&nameOld=Shibor%CA%FD%BE%DD2018.txt&shiborSrc=http%3A%2F%2Fwww.shibor.org%2Fshibor%2F&downLoadPath=data',
       'http://www.shibor.org/shibor/web/html/downLoad.html?nameNew=Historical_Shibor_Tendency_Data_2018.txt&nameOld=Shibor%BE%F9%D6%B5%CA%FD%BE%DD2018.txt&shiborSrc=http%3A%2F%2Fwww.shibor.org%2Fshibor%2F&downLoadPath=data']

sql = ['insert into shibor (dt,o_n,w1,w2,m1,m3,m6,m9,y1) values ("%s")',
       'insert into shibor_ma (dt,o_n_5,o_n_10,o_n_20,w1_5,w1_10,w1_20,w2_5,w2_10,w2_20,m1_5,m1_10,m1_20,m3_5,m3_10,m3_20,m6_5,m6_10,m6_20,m9_5,m9_10,m9_20,y1_5,y1_10,y1_20) values ("%s")']

tbs = ['shibor', 'shibor_ma']

sqlite_file = 'sqlite/shibor.org.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite(num):
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM %s WHERE dt=?' % tbs[num], (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '","'.join(entry)
        conn.execute(sql[num] % values)
    conn.commit()
    conn.close()

def parse_web(num):
    print time.ctime() + ' -- ' + url[num]
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(url[num], headers=headers)
    if response['status'] == '200':
        for line in content.split('\r\n'):
            cells = line.split()
            if(len(cells) > 1 and cells[0] == dt):
                entries.append(cells)
    return entries

def main():
    for m in range(len(url)):
        if search_sqlite(m):
            insert_sqlite(m, parse_web(m))

if __name__ == '__main__':
    main()
