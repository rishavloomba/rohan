from csvfiles import *
import sqlite3
import sys

csv_dir = 'csv/raw/'
sqlite_dir = 'sqlite/'
sqlite2csv = 'csv/sqlite2csv.csv'

def dump_data(csv_file, sqlite_file, table):
    conn = sqlite3.connect(sqlite_file)
    curs = conn.cursor()
    curs.execute('SELECT * FROM %s' % table)
    data = curs.fetchall()
    writecsvfile(csv_file, data)
    conn.close()

def dump_search(csv_file, sqlite_file, table):
    conn = sqlite3.connect(sqlite_file)
    curs = conn.cursor()
    curs.execute('SELECT * FROM %s' % table)
    data = curs.fetchall()
    ds = {}
    stocks = []
    for d in data:
        if d[3] in ds:
            ds[d[3]].update({d[1]: d[2]})
        else:
            ds.update({d[3]: {d[1]: d[2]}})
        if d[1] not in stocks:
            stocks.append(d[1])
    conn.close()
    stocks = sorted(stocks)
    all = [['Date']+stocks]
    for dt in sorted(ds.keys()):
        en = [dt]
        for st in stocks:
            if st not in ds[dt]:
                en.append(0)
            else:
                en.append(ds[dt][st])
        all.append(en)
    writecsvfile(csv_dir+table+'.csv', all)


def main():
    reload(sys)
    sys.setdefaultencoding('utf-8')
    entries = readcsvfile(sqlite2csv)
    for csv_file in entries:
        if entries[csv_file][0] == 'search_engine.db':
            dump_search(csv_dir + csv_file, sqlite_dir + entries[csv_file][0], entries[csv_file][1])
        dump_data(csv_dir + csv_file, sqlite_dir + entries[csv_file][0], entries[csv_file][1])

if __name__ == '__main__':
    main()
