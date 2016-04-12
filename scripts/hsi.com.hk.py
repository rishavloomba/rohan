import httplib2
import sqlite3
import StringIO
import xlrd
import datetime

fnames = ['hsi_pe.xls','hscei_pe.xls', 'hsi_dy.xls','hscei_dy.xls']
sqlite_file = 'hsi.com.hk.db'

tables = ['hsi_pe','hscei_pe','hsi_dy','hscei_dy']
cols = [6,2,6,2]

def insert_sqlite(num, entries):
    conn = sqlite3.connect(sqlite_file)
    for entry in entries:
        values = '"' + '", "'.join(entry) + '"'
        conn.execute('insert into %s values (%s)' % (tables[num], values))
    conn.commit()
    conn.close()

def parse_web(num):
    f = open(fnames[num],'rb')
    filedata = f.read()
    f.close()
    return filedata

def parse_excel(num, filedata):
    workbook = xlrd.open_workbook(file_contents=filedata)
    table = workbook.sheet_by_index(0)
    rows = table.nrows
    results = []
    for row in range(3, rows):
        if table.cell(row,0).value:
            entry = []
            y,m,d,hh,mm,ss = xlrd.xldate_as_tuple(table.cell(row,0).value, 0)
            dt = datetime.datetime.strftime(datetime.datetime(y, m, 1, 0, 0), '%Y-%m-%d')
            entry.append(dt)
            for m in range(1, cols[num]):
                cell = '0' if table.cell(row,m).value == '--' else str(table.cell(row,m).value)
                entry.append(cell)
            results.append(entry)
    return results

def main():
    for x in range(len(tables)):
        insert_sqlite(x, parse_excel(x, parse_web(x)))

if __name__ == '__main__':
    main()
