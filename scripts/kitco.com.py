import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

url = 'http://www.kitco.com/gold.londonfix.html'
sql = 'INSERT INTO london_metal (dt, au_am, au_pm, ag_pm, pt_am, pt_pm, pd_am, pd_pm) VALUES (%s)'
sqlite_file = 'sqlite/kitco.com.db'

def insert_sqlite(entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        conn.execute(sql % values)
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
        for tr in tables[14].select('tr'):
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
