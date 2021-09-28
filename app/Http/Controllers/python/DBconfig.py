# -*- coding: utf-8 -*-
import mysql.connector as mc
import platform

def functionDBconfig():
    pf = platform.system()
    # ローカル環境
    if pf == 'Windows' or pf == 'Darwin':
        db = mc.connect(
            user='root',
            passwd='',
            host='localhost',
            db='check_for_updates',
            charset='utf8')
    # 本番環境
    else:
        db = mc.connect(
            user='xs330114_root',
            passwd='Dn9ERS24k8KV.Qy',
            host='127.0.0.1',
            db='xs330114_rmcervn',
            charset='utf8')
    return db
