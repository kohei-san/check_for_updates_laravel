# -*- coding: utf-8 -*-
from genericpath import exists
import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

# sql文読み込み
import sql_sentence

import os
from os import path
import pandas.io.sql as pdsql
import datetime
import shutil

arrPrintTime={}
arrPrintTime['python-start'] = datetime.datetime.now()

# customer_page 更新
arrPrintTime['up_pagelist-start'] = datetime.datetime.now()
import linkurlget
arrPrintTime['up_pagelist-end'] = datetime.datetime.now()


# =================================================
# ====▼▼▼▼▼▼▼▼====       関数      ====▼▼▼▼▼▼▼▼====
# =================================================
from myfunction import regist_difference_bet_xterm
from myfunction import create_htmlfile
from myfunction import removeFile
from myfunction import checkExistInBetTabel
from myfunction import changePathRelateiveToDirect
from myfunction import checkDif_createDifFile
from myfunction import tryBeautifulSoup
from myfunction import get_linkurl
from myfunction import mail_print



# ======================================================================
# ====▼▼▼▼▼▼▼▼====(1) 前処理　DB 取得・登録　ファイルの削除====▼▼▼▼▼▼▼▼====
# ======================================================================

arrPrintTime['preprocessing-start'] = datetime.datetime.now()

dfPageData = pdsql.read_sql(sql_sentence.pagedata_select, db)

# urlチェック用
dfCustomerPageUrlAllData = pdsql.read_sql(sql_sentence.all_page_data_select, db)
all_urls = dfCustomerPageUrlAllData.page_url.values

# 比較一個前のcreate_html_idを取得
dfpreCreatePageid = pdsql.read_sql(sql_sentence.create_page_select_max, db)
pre_dir_path_recursive = path.dirname(__file__) + "/acquired_data/" + dfpreCreatePageid.loc[0,'filename_timestamp'] + "/html/"

dfFavoriteCreatePageid = pdsql.read_sql(sql_sentence.favo_create_page_select, db)
favorite_dir_path_recursive = path.dirname(__file__) + "/acquired_data/favorite/html/"

# 新しいcreate_html_idを作成
time_stamp = datetime.datetime.now()
file_nametime_stamp = time_stamp.strftime("%Y%m%d_%H%M")
arrtime_stamp=[]
arrtime_stamp.append([time_stamp,file_nametime_stamp])
mycursor.executemany(sql_sentence.create_page_insert, arrtime_stamp)
db.commit

# 上記で作成した新しいcreate_html_idを取得
dfCreatePageid = pdsql.read_sql(sql_sentence.create_page_select_max, db)
new_dir_path_recursive = path.dirname(__file__) + "/acquired_data/" + dfCreatePageid.loc[0,'filename_timestamp'] + "/html/"
os.makedirs(new_dir_path_recursive, exist_ok=True)

li_create_short_difference=[]
li_create_short_difference.append([dfpreCreatePageid.loc[0,'filename_timestamp'], dfCreatePageid.loc[0,'filename_timestamp']])
mycursor.executemany(sql_sentence.create_short_difference_insert, li_create_short_difference)
db.commit

li_create_long_difference=[]
li_create_long_difference.append([dfFavoriteCreatePageid.loc[0,'filename_timestamp'], dfCreatePageid.loc[0,'filename_timestamp']])
mycursor.executemany(sql_sentence.create_long_difference_insert, li_create_long_difference)
db.commit

# 古いデータの削除 過去５回分以外は削除する（favoriteを除く)
li_delete_html_folder=[]
dfpreCreatePageid = pdsql.read_sql(sql_sentence.del_create_page_select, db)
for index,row in dfpreCreatePageid.iterrows():
    filepath = path.dirname(__file__) + "/acquired_data/" + row.filename_timestamp
    if os.path.exists(filepath):
        shutil.rmtree(filepath)
        li_delete_html_folder.append([1,row.create_html_id])
if li_delete_html_folder:
    mycursor.executemany(sql_sentence.create_page_del_update, li_delete_html_folder)

# difference_bet_short/long term Table に未登録分新規追加
regist_difference_bet_xterm(dfPageData)

arrPrintTime['preprocessing-end'] = datetime.datetime.now()

# ======================================================================
# ====▲▲▲▲▲▲▲▲====(1) 前処理　DB 取得・登録　ファイルの削除====▲▲▲▲▲▲▲▲====
# ======================================================================

# ==============================================================
# ====▼▼▼▼▼▼▼▼====(2) page ごとにHTMLを取得・保存====▼▼▼▼▼▼▼▼====
# ==============================================================

create_html_id = dfCreatePageid.loc[0,'create_html_id']
arrHTMLData = []
arrOKorNgPageNo = []
arrCheckDifferenceTrueShort = []
arrCheckDifferenceFalseShort = []
arrCheckDifferenceTrueOrFalseLong = []
short_term_path = path.dirname(__file__)+'/different/short_term'
long_term_path = path.dirname(__file__)+'/different/long_term'


arrPrintTime['crawl-start'] = datetime.datetime.now()

for i in range(2):
    if i == 0:
        dfForPageData = dfPageData
    elif i == 1:
        predeta_last_page_id = dfPageData.tail(1).page_id.values[0]
        dfForPageData = pdsql.read_sql(sql_sentence.create_new_page_select_SQL(predeta_last_page_id), db)
        regist_difference_bet_xterm(dfForPageData)

    
    for index, row in dfForPageData.iterrows():
        page_url = row.page_url
        page_id = row.page_id
        removeFile(short_term_path + "/" + str(page_id)+'.html')
        removeFile(long_term_path + "/" + str(page_id)+'.html')
        page_url = row.page_url
        res, htmldata = tryBeautifulSoup(page_url)

        if res:
            if res.status_code < 400 and htmldata:
                # toppageだった場合ページ更新
                if row.top_page_flg == 1:
                    arrUrlLink = []
                    get_links = get_linkurl(htmldata, page_url)
                    for link in get_links:
                        if not link in all_urls:
                            arrUrlLink.append([row.customer_id, link, 0])
                    if arrUrlLink:
                        try:
                            mycursor.executemany(sql_sentence.customer_page_insert, arrUrlLink)
                            db.commit
                            arrUrlLink = []
                        except:
                            db.close
                            db = DBconfig.functionDBconfig()
                            mycursor = db.cursor()
                            pass
        
                htmldata = changePathRelateiveToDirect(htmldata, page_url)
                encode_thishtml = 'utf-8'
                create_htmlfile(new_dir_path_recursive, str(page_id), htmldata, encode_thishtml)
                time_get_file = datetime.datetime.now()
                arrHTMLData.append([page_id, int(row.customer_id), int(create_html_id), time_get_file])
                arrOKorNgPageNo.append([0, page_id])

                # ==============================================================
                # ====▲▲▲▲▲▲▲▲====(2) page ごとにHTMLを取得・保存====▲▲▲▲▲▲▲▲====
                # ==============================================================

                # ========================================================
                # ====▼▼▼▼▼▼▼▼====(3) 取得したファイルの比較====▼▼▼▼▼▼▼▼====
                # ========================================================

                beforefilename = pre_dir_path_recursive + str(page_id) + ".html"
                favoritefilename = favorite_dir_path_recursive + str(page_id) + ".html"
                afterfilename = new_dir_path_recursive + str(page_id) + ".html"

                # 直近比較
                diffShortHTML, difShortCheck_flg = checkDif_createDifFile(beforefilename, afterfilename, encode_thishtml)
                if( difShortCheck_flg == 1 ):
                    create_htmlfile(short_term_path, str(page_id), diffShortHTML, 'utf-8')
                    arrCheckDifferenceTrueShort.append([1, time_get_file, page_id])
                else:
                    arrCheckDifferenceFalseShort.append([0, page_id])

                # favorite比較
                diffLongHTML, difLongCheck_flg = checkDif_createDifFile(favoritefilename, afterfilename, encode_thishtml)
                if( difLongCheck_flg == 1 ):
                    create_htmlfile(long_term_path, str(page_id), diffLongHTML, 'utf-8')
                    arrCheckDifferenceTrueOrFalseLong.append([1, time_get_file, page_id])
                else:
                    arrCheckDifferenceTrueOrFalseLong.append([0, '0000-00-00 00:00:00', page_id])

                # ========================================================
                # ====▲▲▲▲▲▲▲▲====(3) 取得したファイルの比較====▲▲▲▲▲▲▲▲====
                # ========================================================
            else:
                arrOKorNgPageNo.append([1, page_id])
                arrCheckDifferenceFalseShort.append([0, page_id])
                arrCheckDifferenceTrueOrFalseLong.append([0, '0000-00-00 00:00:00', page_id])
        else:
            arrOKorNgPageNo.append([1, page_id])
            arrCheckDifferenceFalseShort.append([0, page_id])
            arrCheckDifferenceTrueOrFalseLong.append([0, '0000-00-00 00:00:00', page_id])

        # 100回ごとに登録していく
        if (index + 1) % 100 == 0:
            try:
                if arrUrlLink:
                    mycursor.executemany(sql_sentence.customer_page_insert, arrUrlLink)
                    db.commit
                    arrUrlLink = []

                if arrHTMLData:
                    mycursor.executemany(sql_sentence.create_htmlsrc_insert, arrHTMLData)
                    db.commit
                    arrHTMLData = []

                if arrCheckDifferenceTrueShort:
                    mycursor.executemany(sql_sentence.difference_shortterm_diftrue_update, arrCheckDifferenceTrueShort)
                    db.commit
                    arrCheckDifferenceTrueShort = []

                if arrCheckDifferenceTrueOrFalseLong:
                    mycursor.executemany(sql_sentence.difference_longterm_diftrueorfalse_update, arrCheckDifferenceTrueOrFalseLong)
                    db.commit
                    arrCheckDifferenceTrueOrFalseLong = []

                if arrCheckDifferenceFalseShort:
                    mycursor.executemany(sql_sentence.difference_shortterm_diffalse_update, arrCheckDifferenceFalseShort)
                    db.commit
                    arrCheckDifferenceFalseShort = []
                
                if arrOKorNgPageNo:
                    mycursor.executemany(sql_sentence.ngpage_update, arrOKorNgPageNo)
                    db.commit
                    arrOKorNgPageNo = []
            except:
                db.close
                db = DBconfig.functionDBconfig()
                mycursor = db.cursor()
                pass

    if arrHTMLData:
        mycursor.executemany(sql_sentence.create_htmlsrc_insert, arrHTMLData)

    if arrCheckDifferenceTrueShort:
        mycursor.executemany(sql_sentence.difference_shortterm_diftrue_update, arrCheckDifferenceTrueShort)

    if arrCheckDifferenceTrueOrFalseLong:
        mycursor.executemany(sql_sentence.difference_longterm_diftrueorfalse_update, arrCheckDifferenceTrueOrFalseLong)

    if arrCheckDifferenceFalseShort:
        mycursor.executemany(sql_sentence.difference_shortterm_diffalse_update, arrCheckDifferenceFalseShort)

    if arrOKorNgPageNo: 
        mycursor.executemany(sql_sentence.ngpage_update, arrOKorNgPageNo)
    db.commit 
    
db.close
arrPrintTime['crawl-end'] = datetime.datetime.now()
arrPrintTime['python-end'] = datetime.datetime.now()

mail_print(arrPrintTime)
