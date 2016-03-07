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

def main():
    reload(sys)
    sys.setdefaultencoding('utf-8')
    entries = readcsvfile(sqlite2csv)
    for csv_file in entries:
        dump_data(csv_dir + csv_file, sqlite_dir + entries[csv_file][0], entries[csv_file][1])

if __name__ == '__main__':
    main()
