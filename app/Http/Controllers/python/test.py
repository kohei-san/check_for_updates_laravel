from myfunction import openfile_to_diff
from myfunction import checkDif_createDifFile
from myfunction import mail_print

import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()
import pandas.io.sql as pdsql

# =================================================
# ====▼▼▼▼▼▼▼▼====       関数      ====▼▼▼▼▼▼▼▼====
# =================================================
from myfunction import create_htmlfile
from myfunction import removeFile
from myfunction import checkExistInBetTabel
from myfunction import changePathRelateiveToDirect
from myfunction import checkDif_createDifFile
from myfunction import tryBeautifulSoup
from myfunction import get_linkurl
from myfunction import mail_print


from os import path
import datetime
import sql_sentence
import DBconfig
import time

# sql文読み込み
import sql_sentence

page_url = 'http://www.seibu-co.com/'
page_id = 99999999
new_dir_path_recursive = "C:/Users/kurita/Desktop"
res, htmldata = tryBeautifulSoup(page_url)

if res.status_code < 400 and htmldata:
    encode_thishtml = htmldata.original_encoding
    htmldata = changePathRelateiveToDirect(htmldata, page_url)
    print(encode_thishtml)
    create_htmlfile(new_dir_path_recursive, str(page_id), htmldata, 'utf-8')
    time_get_file = datetime.datetime.now()

    beforefilename = new_dir_path_recursive + "/99999999aaa.html"
    favoritefilename = new_dir_path_recursive + "/" + str(page_id) + ".html"
    afterfilename = new_dir_path_recursive + "/" + str(page_id) + ".html"

    # 直近比較
    diffShortHTML, difShortCheck_flg = checkDif_createDifFile(beforefilename, afterfilename, 'utf-8')

    if( difShortCheck_flg == 1 ):
        create_htmlfile(new_dir_path_recursive, str(page_id)+'bbbb', diffShortHTML, 'utf-8')




