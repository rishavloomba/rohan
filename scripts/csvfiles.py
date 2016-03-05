import csv

def readcsvfile(csvfile):
    results = {}
    f = file(csvfile, 'rb')
    r = csv.reader(f)
    for row in r:
        if len(row) >= 2 and row[0].find('#') != 0:
            results[row[0]] = row[1:]
    f.close()
    return results

def writecsvfile(csvfile, data):
    f = file(csvfile, 'wb')
    w = csv.writer(f)
    w.writerows(data)
    f.close()

