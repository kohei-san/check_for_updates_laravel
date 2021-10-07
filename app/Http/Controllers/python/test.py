from myfunction import openfile_to_diff
from myfunction import checkDif_createDifFile
from myfunction import mail_print

import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()
import pandas.io.sql as pdsql


from os import path
import datetime
import sql_sentence
import DBconfig
import time

# sql文読み込み
import sql_sentence

db = DBconfig.functionDBconfig()
mycursor = db.cursor()
dfPageData = pdsql.read_sql(sql_sentence.pagedata_select, db)
dfForPageData = pdsql.read_sql(sql_sentence.create_new_page_select_SQL(46000), db)

print(dfPageData.tail(1).page_id.values[0])

# for i in range(2):
#     if i == 1:
#         print('predeta_last_page_id')
#         print(predeta_last_page_id)

#     else:
#         for index, row in dfForPageData.iterrows():
#             pass

#         predeta_last_page_id = index
#         print('index')
#         print(index)

