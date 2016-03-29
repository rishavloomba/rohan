import httplib2
import time
import datetime
import csv
from bs4 import BeautifulSoup

sdate = '2016-01-01'
edate = '2016-03-30'
stock = '600436'

csvfile = '%s.csv' % stock

delta = datetime.timedelta(days=1)
url = 'http://www.csindex.com.cn/sseportal/csiportal/syl/indexsyl.do?type=2&indexCode=%s&date=%s&classType=1'

def parse_web(d):
    n = 0
    results = []
    urls = url % (stock, d)
    print time.ctime() + ' -- ' + urls
    while n < 10:
        n += 1
        try:
            http = httplib2.Http()
            response, content = http.request(urls)
            if response['status'] == '200':
                soup = BeautifulSoup(content, 'lxml', from_encoding='gbk')
                trs = soup.select('tr')
                if len(trs) == 2:
                    tds = map(lambda x: x.text.strip(), trs[1].select('td'))
                    results = [tds[7],tds[8],tds[9],tds[10]]
                n = 10
        except:
            print n
            time.sleep(60)
    return results

def main():
    s = datetime.datetime.strptime(sdate, '%Y-%m-%d')
    e = datetime.datetime.strptime(edate, '%Y-%m-%d')
    f = file(csvfile, 'wb')
    w = csv.writer(f)
    while s < e:
        if s.weekday() not in [5,6]:
            d = s.strftime('%Y-%m-%d')
            data = parse_web(d)
            if data:
                w.writerow([d] + data)
        s = s + delta
    f.close()

if __name__ == '__main__':
    main()
