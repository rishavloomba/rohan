import httplib2
import sqlite3
import time
import sys
import re
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d", time.gmtime(time.time()-86400))
else:
    dt = sys.argv[1]

url = 'http://www.kitco.com/gold.londonfix.html'
sqlite_file = 'sqlite/kitco.com.db'

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        dt = entry[0]
        au_am = entry[1]
        au_pm = entry[2]
        ag_pm = entry[3]
        pt_am = entry[4]
        pt_pm = entry[5]
        pd_am = entry[6]
        pd_pm = entry[7]
        if re.match(r"[\d\.]+", au_am) and re.match(r"[\d\.]+", au_pm):
            pass
        elif re.match(r"[\d\.]+", au_am):
            au_pm = au_am
        elif re.match(r"[\d\.]+", au_pm):
            au_am = au_pm
        else:
            au_am = '0'
            au_pm = '0'
        if not re.match(r"[\d\.]+", ag_pm):
            ag_pm = '0'
        if re.match(r"[\d\.]+", pt_am) and re.match(r"[\d\.]+", pt_pm):
            pass
        elif re.match(r"[\d\.]+", pt_am):
            pt_pm = pt_am
        elif re.match(r"[\d\.]+", pt_pm):
            pt_am = pt_pm
        else:
            pt_am = '0'
            pt_pm = '0'
        if re.match(r"[\d\.]+", pd_am) and re.match(r"[\d\.]+", pd_pm):
            pass
        elif re.match(r"[\d\.]+", pd_am):
            pd_pm = pd_am
        elif re.match(r"[\d\.]+", pd_pm):
            pd_am = pd_pm
        else:
            pd_am = '0'
            pd_pm = '0'
        sql = "insert into london_metal (dt,au_am,au_pm,ag_pm,pt_am,pt_pm,pd_am,pd_pm) values ('%s','%s','%s','%s','%s','%s','%s','%s')" % (dt,au_am,au_pm,ag_pm,pt_am,pt_pm,pd_am,pd_pm)
        conn.execute(sql)
    conn.commit()
    conn.close()

def parse_web():
    n = 0
    print time.ctime() + ' -- ' + url
    entries = []
    http = httplib2.Http()
    response, content = http.request(url)
    if response['status'] == '200':
        soup = BeautifulSoup(content, 'lxml')
        tables = soup.select('table')
        for tr in tables[15].select('tr'):
            tds = tr.select('td')
            rows = map(lambda x: x.text.strip(), tds)
            if len(rows) == 8 and dt == rows[0]:
                print rows
                entries.append(rows)
    return entries

def main():
    insert_sqlite(parse_web())

if __name__ == '__main__':
    main()
