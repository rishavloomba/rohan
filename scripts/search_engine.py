from csvfiles import *
import urllib2
import re
import sqlite3

search_engine_file = 'csv/search_engine.csv'
search_engines = readcsvfile(search_engine_file)

stock_lists_file = 'csv/stock_lists.csv'
stock_lists = readcsvfile(stock_lists_file)

sqlite_file = 'sqlite/search_engine.db'

conn = sqlite3.connect(sqlite_file)

for search_engine in search_engines:
    for stock in sorted(stock_lists):
        url = search_engines[search_engine][0] % ('"' + stock_lists[stock][0] + '"')
        patten = search_engines[search_engine][1]
        print "%s,%s" % (search_engine,stock)
        response = urllib2.urlopen(url)
        page = response.read()
        hit = re.findall(patten, page)[0]
        sql = "INSERT INTO %s (id, hit) VALUES ('%s', '%s')" % (search_engine, stock, hit)
        print sql
        conn.execute(sql)

conn.commit()
conn.close()

