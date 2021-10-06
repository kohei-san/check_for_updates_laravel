# -*- coding: utf-8 -*-
import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

from bs4 import BeautifulSoup
import requests
import pandas.io.sql as pdsql

# url の深さ
url_deepth = 1

# pageurlの保存
sql = "SELECT * FROM customer WHERE active_flg=0 AND del_flg=0"

# pageurlの保存
sql_customer_page_insert = """
INSERT INTO customer_page (customer_id, page_url, top_page_flg)
VALUES(%s, %s, %s)
"""

# 登録したURLのcustomer.active_flgを1にする
sql_update = '''
UPDATE customer SET active_flg=%s WHERE customer_id=%s
'''

# url内のNGワード
def ng_words(target_word):
    ng_word_list = ["blog", "contact", "archive", "?", "/sp/", "#", "calendar"]
    for ng_word in ng_word_list:
        if ng_word in target_word:
            return True

def get_linkurl(toppage_url):
    arrHref = []
    try:
        res = requests.get(toppage_url)
        soup = BeautifulSoup(res.text,'html.parser')
    except (requests.exceptions.RequestException) as e:
        return arrHref

    links = soup.find_all('a')
    slashCount = toppage_url.count('/') + url_deepth
    arrHref.append(toppage_url)

    for link in links:
        href = link.get('href')

        # plesk画面の場合 空にして渡す
        if 'plesk.com' in str(href):
            arrHref = []
            return arrHref

        # href なしはスルー
        if (not href) or href =='/' or ng_words(href):
            continue

        # /始まりは、一文字目の「/」移行をtopurlに結合
        if href[0] == '/':
            href = toppage_url + href[1:]
        
        if href[-1] != '/':
            href = href + '/'

        # hrefの「/」の数がオーバー、またはNGワードがあるかで場合分け
        if href.count('/') > slashCount:
            continue
        
        # topurl が入っているか（外部リンク除外）かつ 今までの arrHref に格納されてない場合
        if (toppage_url in href) and not(href in arrHref):
            arrHref.append(href)
    
    return arrHref

# Customer Table内容の取得
dfCustomerData = pdsql.read_sql(sql, db)

arrUrlLink=[]
arrUpdatesActivateCustomer=[]

for index, row in dfCustomerData.iterrows():
    get_links = get_linkurl(row.customer_toppage_url)
    for url in get_links:
        top_page_flg = 0
        if url == row.customer_toppage_url:
            top_page_flg = 1
        arrUrlLink.append([row.customer_id,url,top_page_flg])
    if get_links:
        arrUpdatesActivateCustomer.append([1,row.customer_id])

    # 100の倍数でDBに入れる
    if (index + 1) % 100 == 0:
        if arrUrlLink:
            mycursor.executemany(sql_customer_page_insert, arrUrlLink)
        if arrUpdatesActivateCustomer:
            mycursor.executemany(sql_update, arrUpdatesActivateCustomer)
        db.commit    
        # 入れた分は初期化
        arrUrlLink=[]
        arrUpdatesActivateCustomer=[]
        
if arrUrlLink:
    mycursor.executemany(sql_customer_page_insert, arrUrlLink)
if arrUpdatesActivateCustomer:
    mycursor.executemany(sql_update, arrUpdatesActivateCustomer)
db.commit    


db.close
