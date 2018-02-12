import pyodbc
import json

cn = ''
with open('../../src/config.json') as configFile:
    config = json.load(configFile)

    odbc = config[u'odbc']
    print str(odbc)
    settings = [key+'='+odbc[key] for key in odbc.keys()];
    cn = ';'.join(settings);

cnxn = pyodbc.connect(cn)
cursor = cnxn.cursor()
cursor.execute('select * from "OHCND01".po_header where po_no=203163;')
row = cursor.fetchone()
if row:
    print str(row)

