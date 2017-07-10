import httplib2
import sqlite3
import time
import sys
import csv
import StringIO

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]
    
dts = dt.split('-')
url = 'http://www.cffex.com.cn/fzjy/mrhq/{0}{1}/{2}/{0}{1}{2}_1.csv'.format(dts[0],dts[1],dts[2])
sql = 'INSERT INTO future (dt,name,open,high,low,cloes,balance,volume,amount,position) VALUES (%s)'
sqlite_file = 'sqlite/cffex.com.cn.db'
headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0'}

def search_sqlite():
    conn = sqlite3.connect(sqlite_file)
    cursor = conn.execute('SELECT * FROM future WHERE dt=?', (dt,))
    ret = cursor.fetchone()
    conn.close()
    return False if ret else True

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        conn.execute(sql % values)
    conn.commit()
    conn.close()

def parse_web():
    print time.ctime() + ' -- ' + url
    entries = []
    http = httplib2.Http(timeout=60)
    response, content = http.request(url, headers=headers)
    if response['content-type'] == 'text/csv':
        f = StringIO.StringIO(content)
        t = ''
        m = 1
        for row in csv.reader(f):
            if row[0].startswith('IF'):
                if t != 'IF': m = 1
                t = 'IF'
            elif row[0].startswith('IC'):
                if t != 'IC': m = 1
                t = 'IC'
            elif row[0].startswith('IH'):
                if t != 'IH': m = 1
                t = 'IH'
            elif row[0].startswith('TF'):
                if t != 'TF': m = 1
                t = 'TF'
            elif row[0].startswith('T'):
                if t != 'T': m = 1
                t = 'T'
            else:
                continue
            entries.append([dt,"%s%s" % (t,m),row[1],row[2],row[3],row[7],row[8],row[4],row[5],row[6]])
            m += 1
    return entries

def main():
    if search_sqlite():
        insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
