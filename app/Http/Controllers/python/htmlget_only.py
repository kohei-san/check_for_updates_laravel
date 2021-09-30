# -*- coding: utf-8 -*-
import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

from bs4 import BeautifulSoup
import requests
import pandas.io.sql as pdsql
import datetime
import os
from os import path
from urllib.parse import urljoin

time_stamp = datetime.datetime.now()
file_nametime_stamp = datetime.datetime.now().strftime("%Y%m%d_%H%M")

def get_page_html(pageUrl):
    try:
        res = requests.get(pageUrl)
        htmlData = BeautifulSoup(res.content,'lxml')
    except requests.exceptions.RequestException as e:
        htmlData = ""
    return htmlData

def create_htmlfile(foldername, filename, text):
    file = open(foldername + '/' + filename + '.html','wb')
    file.write(text.encode(text.original_encoding))
    file.close()


sql_pagedata_select = '''
    SELECT * FROM customer_page 
    WHERE customer_id IN ( SELECT customer_id FROM customer WHERE active_flg=1 AND del_flg=0 )
'''

sql_customerdata_select = '''
    SELECT customer_id, customer_toppage_url FROM customer WHERE active_flg=1 AND del_flg=0
'''

sql_create_page_insert = """
    INSERT INTO create_html (time_stamp, filename_timestamp)
    VALUES(%s,%s)
"""

sql_create_page_select = """
    SELECT * FROM create_html WHERE create_html_id=( SELECT MAX(create_html_id) FROM create_html )
"""

sql_create_htmlsrc_insert = """
    INSERT INTO page_html (page_id, customer_id, create_html_id, time_stamp_htmlsrc)
    VALUES(%s,%s,%s,%s)
"""


sql_ngpage_update = "UPDATE customer_page SET ng_flg=%s WHERE page_id=%s"



# Customer Table を取得
dfCustomerData = pdsql.read_sql(sql_customerdata_select, db)
# customer_page Table を取得
dfPageDataData = pdsql.read_sql(sql_pagedata_select, db)


# create_html Table に新規追加
arrtime_stamp=[]
arrtime_stamp.append([time_stamp,file_nametime_stamp])
mycursor.executemany(sql_create_page_insert, arrtime_stamp)
db.commit

# 上記で作成した新しいcreate_html_idを取得
dfCreatePageid = pdsql.read_sql(sql_create_page_select, db)

# 上記のcreate_html_idに対応するフォルダを作成
new_dir_path_recursive = path.dirname(__file__) + "/acquired_data/" + dfCreatePageid.loc[0,'filename_timestamp'] + "/html"
os.makedirs(new_dir_path_recursive, exist_ok=True)

create_html_id = dfCreatePageid.loc[0,'create_html_id']
arrHTMLData = []
arrNgPageNo = []

for index, row in dfPageDataData.iterrows():
    htmldata = get_page_html(row.page_url)
    
    # 画像の相対パスを変更する
    if htmldata:
        tags = htmldata.findAll()
        for tag in tags:
            if tag.get('src'):
                tag['src'] = urljoin(row.page_url, tag['src'])
            if tag.get('href'):
                tag['href'] = urljoin(row.page_url, tag['href'])

        create_htmlfile(new_dir_path_recursive, str(row.page_id), htmldata)
        arrHTMLData.append([row.page_id, int(row.customer_id), int(create_html_id), datetime.datetime.now()])
        
    else:
        arrNgPageNo.append([1, row.page_id])
    

    if (index + 1) % 100 == 0:

        try:
            mycursor.executemany(sql_create_htmlsrc_insert, arrHTMLData)
            db.commit
            arrHTMLData = []
            print(index+1)
        except:
            print(index+1)
            print('DBエラー' + str(datetime.datetime.now()))


if arrHTMLData:
    mycursor.executemany(sql_create_htmlsrc_insert, arrHTMLData)    

if arrNgPageNo: 
    mycursor.executemany(sql_ngpage_update, arrNgPageNo)

db.commit 
db.close