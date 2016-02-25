from csvfiles import *
import urllib2
import re
import time

search_engine_file = 'csv/search_engine.csv'
search_engines = readcsvfile(search_engine_file)

stock_lists_file = 'csv/stock_lists.csv'
stock_lists = readcsvfile(stock_lists_file)

daily_out_file = 'csv/daily_search_baidu.csv'

results = []
for search_engine in search_engines:
    for stock in sorted(stock_lists):
        url = search_engines[search_engine][0] % ('"' + stock_lists[stock][0] + '"')
        patten = search_engines[search_engine][1]
        print "%s,%s,%s" % (search_engine,stock,stock_lists[stock][0])
        response = urllib2.urlopen(url)
        time.sleep(1)
        page = response.read()
        hits = re.findall(patten, page)[0]
        print "Done:%s" % hits
        results.append([stock, hits.replace(',','')])
        time.sleep(1)

writecsvfile(daily_out_file, results)

