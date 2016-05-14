import json
import re
import httplib2
import sqlite3
import time
import sys
from bs4 import BeautifulSoup

if len(sys.argv) < 2:
    dt = time.strftime("%Y-%m-%d")
else:
    dt = sys.argv[1]

url = 'http://www.chinacp.com.cn/NewChinaCp/market/getDayReport.action?bSortable_0=false&bSortable_1=false&bSortable_2=false&iColumns=3&iDisplayLength=10&iDisplayStart=0&iSortCol_0=0&iSortingCols=1&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&sColumns=&sEcho=1&sSortDir_0=asc'
url2 = 'http://www.chinacp.com.cn/NewChinaCp/market/DayReport.action?statQtItemID=%s'
sql = 'INSERT INTO piaoju (dt,rate1,rate2,rate3,rate4,amount1,amount2,amount3,amount4) VALUES (%s)'

sqlite_file = 'sqlite/chinacp.com.cn.db'

def insert_sqlite(entry):
    conn = sqlite3.connect(sqlite_file)
    values = '"' + dt + '","' + '","'.join(entry) + '"'
    conn.execute(sql % values)
    conn.commit()
    conn.close()

def parse_item():
    http = httplib2.Http()
    response, content = http.request(url)
    j = json.loads(content)
    entry = filter(lambda x: x[2] == dt, j['aaData'])
    if len(entry) == 0:
        exit(0)
    item = re.findall(r'statQtItemID=(\d+)', entry[0][1])[0]
    return item

def parse_web(item):
    print time.ctime() + ' -- ' + url2 % item
    http = httplib2.Http()
    response, content = http.request(url2 % item)
    soup = BeautifulSoup(content, 'lxml')
    data = []
    for tr in soup.select('tr')[1:]:
        tds = tr.select('td')
        data.append([tds[0].text.strip(), tds[4].text.strip()])
    a1 = 0 if data[2][0] == '--' else float(data[2][0])
    a2 = 0 if data[3][0] == '--' else float(data[3][0])
    a3 = 0 if data[0][0] == '--' else float(data[0][0])
    a4 = 0 if data[1][0] == '--' else float(data[1][0])
    a5 = 0 if data[6][0] == '--' else float(data[6][0])
    a6 = 0 if data[7][0] == '--' else float(data[7][0])
    a7 = 0 if data[4][0] == '--' else float(data[4][0])
    a8 = 0 if data[5][0] == '--' else float(data[5][0])
    r1 = 0 if data[2][1] == '--' else float(data[2][1])
    r2 = 0 if data[3][1] == '--' else float(data[3][1])
    r3 = 0 if data[0][1] == '--' else float(data[0][1])
    r4 = 0 if data[1][1] == '--' else float(data[1][1])
    r5 = 0 if data[6][1] == '--' else float(data[6][1])
    r6 = 0 if data[7][1] == '--' else float(data[7][1])
    r7 = 0 if data[4][1] == '--' else float(data[4][1])
    r8 = 0 if data[5][1] == '--' else float(data[5][1])
    amount1 = '' if (a1+a5) == 0 else "%.2f" % (a1+a5)
    amount2 = '' if (a2+a6) == 0 else "%.2f" % (a2+a6)
    amount3 = '' if (a3+a7) == 0 else "%.2f" % (a3+a7)
    amount4 = '' if (a4+a8) == 0 else "%.2f" % (a4+a8)
    rate1 = '' if (a1+a5) == 0 else "%.4f" % ((a1*r1+a5*r5)/(a1+a5))
    rate2 = '' if (a2+a6) == 0 else "%.4f" % ((a2*r2+a6*r6)/(a2+a6))
    rate3 = '' if (a3+a7) == 0 else "%.4f" % ((a3*r3+a7*r7)/(a3+a7))
    rate4 = '' if (a4+a8) == 0 else "%.4f" % ((a4*r4+a8*r8)/(a4+a8))
    return [rate1,rate2,rate3,rate4,amount1,amount2,amount3,amount4]

def main():
    insert_sqlite(parse_web(parse_item()))

if __name__ == '__main__':
    main()
