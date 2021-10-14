# -*- coding: utf-8 -*-
from re import A
import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

import pandas.io.sql as pdsql

# sql文読み込み
import sql_sentence

# =================================================
# ====▼▼▼▼▼▼▼▼====       関数      ====▼▼▼▼▼▼▼▼====
# =================================================
from myfunction import tryBeautifulSoup
from myfunction import get_linkurl


# NonActiveCustomer Table内容の取得
dfCustomerData = pdsql.read_sql(sql_sentence.non_active_customer_select, db)
dfCustomerPageUrlAllData = pdsql.read_sql(sql_sentence.all_page_data_select, db)
all_urls = dfCustomerPageUrlAllData.page_url.values

arrUrlLink=[]
arrUpdatesActivateCustomer=[]

for index, row in dfCustomerData.iterrows():
    top_url = row.customer_toppage_url
    res, htmldata = tryBeautifulSoup(top_url)

    if htmldata:    
        get_links = get_linkurl(htmldata, top_url)
        for link in get_links:
            if not link in all_urls:
                top_page_flg = 0
                if link == top_url:
                    top_page_flg = 1
                arrUrlLink.append([row.customer_id, link, top_page_flg])
        
        if get_links:
            arrUpdatesActivateCustomer.append([1,row.customer_id])
            
    # 100の倍数でDBに入れる
    if (index + 1) % 100 == 0:
        if arrUrlLink:
            mycursor.executemany(sql_sentence.customer_page_insert, arrUrlLink)
        if arrUpdatesActivateCustomer:
            mycursor.executemany(sql_sentence.customer_update, arrUpdatesActivateCustomer)
        db.commit    
        # 入れた分は初期化
        arrUrlLink=[]
        arrUpdatesActivateCustomer=[]
        
if arrUrlLink:
    mycursor.executemany(sql_sentence.customer_page_insert, arrUrlLink)
if arrUpdatesActivateCustomer:
    mycursor.executemany(sql_sentence.customer_update, arrUpdatesActivateCustomer)

db.commit
db.close
