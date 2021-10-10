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
import os


if "favorite" in path.dirname(__file__) + "/acquired_data/20202/html/":
    print("true")

