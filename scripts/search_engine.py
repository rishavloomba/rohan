from csvfiles import *
import urllib2
import re
import sqlite3
import time

search_engine_file = 'csv/search_engine.csv'
search_engines = readcsvfile(search_engine_file)

stock_lists_file = 'csv/stock_lists.csv'
stock_lists = readcsvfile(stock_lists_file)

sqlite_file = 'sqlite/search_engine.db'

def search_keyword(url, patten):
    n = 0
    hit = '0'
    while n < 10:
        n += 1
        try:
            response = urllib2.urlopen(url)
            page = response.read()
            hit = re.findall(patten, page)[0]
            n = 10
        except:
            time.sleep(5)
    return hit

def insert_sqlite(search_results):
    conn = sqlite3.connect(sqlite_file)
    for entry in search_results:
        sql = "INSERT INTO %s (id, hit) VALUES ('%s', '%s')" % (entry[0], entry[1], entry[2])
        conn.execute(sql)
    conn.commit()
    conn.close()

def main():
    search_results = []
    for search_engine in search_engines:
        for stock in sorted(stock_lists):
            url = search_engines[search_engine][0] % ('"' + stock_lists[stock][0] + '"')
            patten = search_engines[search_engine][1]
            print stock
            hit = search_keyword(url, patten)
            search_results.append([search_engine, stock, hit.replace(',','')])
    insert_sqlite(search_results)

if __name__ == '__main__':
    main()
