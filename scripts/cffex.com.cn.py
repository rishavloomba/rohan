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

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        conn.execute(sql % values)
    conn.commit()
    conn.close()

def parse_web():
    n = 0
    results = []
    print time.ctime() + ' -- ' + url
    while n < 10:
        n += 1
        try:
            entries = []
            http = httplib2.Http()
            response, content = http.request(url)
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
                results = entries
                n = 10
            elif response['content-type'] == 'text/html':
                n = 10
        except:
            print n
            time.sleep(60)
    return results

def main():
    insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
