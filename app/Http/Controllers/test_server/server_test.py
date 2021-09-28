# -*- coding: utf-8 -*-# -*- coding: utf-8 -*-
import mysql.connector as mc
from bs4 import BeautifulSoup
import lxml.html
from lxml.html.clean import Cleaner
import difflib
from chardet import detect
import requests
import pandas.io.sql as pdsql
import datetime
import os
from os import path
from urllib.parse import urljoin
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

def adjustment_tag(htmlData):
    # タグ名の削除項目
    remove_tag_names = ['style', 'script', 'noscript', 'meta']
    remove_tags = []
    for tag_name in remove_tag_names:
        remove_tags.append('.//' + tag_name)

    # タグ名の削除
    for remove_tag in remove_tags:
        for tag in htmlData.findall(remove_tag):
            tag.drop_tree()
    
    # 要素の削除項目
    remove_elements = ['style', 'width', 'height']

    # 要素の削除
    for tag in htmlData.iter():
        for element in remove_elements:
            tag.attrib.pop(element, None)

    # id の場合は while /タグを取得できないから
    try:
        while(True):
            htmlData.get_element_by_id("today").attrib.pop('id', None)
    except:
        pass


    try:
        while(True):
            tag = htmlData.get_element_by_id("inquiryform-1").getchildren()[0]
            tag.getparent().remove(tag)
    except:
        pass
    
    try:
        while(True):
            tag = htmlData.get_element_by_id("inquiryform-2").getchildren()[0]
            tag.getparent().remove(tag)
    except:
        pass
    
    try:
        while(True):
            tag = htmlData.get_element_by_id("map_canvas")
            tag.getparent().remove(tag)
    except:
        pass

    try:
        while(True):
            tag = htmlData.get_element_by_id("jquery_slider")
            tag.getparent().remove(tag)
    except:
        pass

    try:
        while(True):
            tag = htmlData.get_element_by_id("jquery_slider_pc").getchildren()[0]
            tag.getparent().remove(tag)
    except:
        pass
    
    try:
        while(True):
            tag = htmlData.get_element_by_id("calendar_wrap")
            tag.getparent().remove(tag)
    except:
        pass
    
    try:
        tags = htmlData.find_class("fixed_btn_in")
        for tag in tags:
            tag.getparent().remove(tag)   
    except:
        pass
    
    try:
        tags = htmlData.find_class("fixed_btn_fb")
        for tag in tags:
            tag.getparent().remove(tag)   
    except:
        pass

    try:
        tags = htmlData.find_class("fixed_btn_tw")
        for tag in tags:
            tag.getparent().remove(tag)   
    except:
        pass

    try:
        tags = htmlData.find_class("viewer")
        for tag in tags:
            tag.getparent().remove(tag)        
    except:
        pass


    return htmlData


db = functionDBconfig()
mycursor = db.cursor()


time_stamp = datetime.datetime.now()
file_nametime_stamp = datetime.datetime.now().strftime("%Y%m%d_%H%M")

print('---datetime---')
print(time_stamp)
print(file_nametime_stamp)
print('▲▲▲datetime▲▲▲')
print('')
print('')


print('---os---')
this_dir_path = path.dirname(__file__)
print('this_dir_path')
print(this_dir_path)
print('▲▲▲os▲▲▲')
print('')
print('')



print('---pandas・mysql.connector---')
sql_pagedata_select = '''
    SELECT * FROM customer_page 
    WHERE page_id < 10
'''
dfCustomerData = pdsql.read_sql(sql_pagedata_select, db)
print(dfCustomerData)
print('▲▲▲pandas・mysql.connector▲▲▲')
print('')
print('')



print('---requests・BeautifulSoup---')
dfCustomerData
page_url = dfCustomerData.query('page_id == 2').reset_index().loc[0,'page_url']
res = requests.get(page_url)
htmlData = BeautifulSoup(res.content,'lxml')
print(htmlData)
print('▲▲▲requests・BeautifulSoup▲▲▲')
print('')
print('')



print('---urllib.parse import urljoin---')
tags = htmlData.findAll()
for tag in tags:
    if tag.get('src'):
        tag['src'] = urljoin(page_url, tag['src'])
    if tag.get('href'):
        print('------------')
        print(tag['href'])
        print('↓変換')
        tag['href'] = urljoin(page_url, tag['href'])
        print(tag['href'])
print('▲▲▲urllib.parse import urljoin▲▲▲')
print('')
print('')


filename_now = this_dir_path + '/2.html'
filename_pre = this_dir_path + '/compare.html'

file = open(filename_now,'wb')
encode_thishtml = htmlData.original_encoding
file.write(htmlData.encode(encode_thishtml))
file.close()


cleaner = Cleaner(page_structure=False, remove_tags=('ruby', 'rb', 'br'), kill_tags=('rt', 'rp'))
if os.path.exists(filename_now):
    print('createfile_is_exists')
    error_flg = 0
    # 過去比較ファイルを開いて整理する

    with open(filename_pre, mode='rb') as f1:
        print('---chardet---')
        enc = detect(f1.read())['encoding']
        print(enc)
        print('▲▲▲chardet▲▲▲')
        print('')
        print('')
        with open(filename_pre, mode='r',encoding=enc) as f2:
            print('---from lxml.html.clean import Cleaner---')
            filetext_pretext = cleaner.clean_html(f2.read().encode(enc)).decode('utf-8')
            print('▲▲▲from lxml.html.clean import Cleaner▲▲▲')

    # 過去比較ファイルを開いて整理する      
    with open(filename_now, mode='rb') as f1:
        print('---chardet---')
        enc = detect(f1.read())['encoding']
        print(enc)
        print('▲▲▲chardet▲▲▲')
        print('')
        print('')
        with open(filename_now, mode='r',encoding=enc) as f2:
            print('---from lxml.html.clean import Cleaner---')
            filetext_nowtext = cleaner.clean_html(f2.read().encode(enc)).decode('utf-8')
            print('▲▲▲from lxml.html.clean import Cleaner▲▲▲')
    print('')
    print('')
    print('---lxml・difflib---')
    
    beforeHTML = lxml.html.fromstring(filetext_pretext)
    beforeHTML = adjustment_tag(beforeHTML)

    afterHTML = lxml.html.fromstring(filetext_nowtext)
    afterHTML = adjustment_tag(afterHTML)

    # lxml から 文字列に変換
    beforeFile = lxml.html.etree.tostring(beforeHTML, encoding='utf-8').decode()
    afterFile = lxml.html.etree.tostring(afterHTML, encoding='utf-8').decode()

    difdiffer = difflib.Differ()
    # ファイルを比較
    diff = difdiffer.compare(beforeFile.splitlines(), afterFile.splitlines())
    change_flg_word = ["-","+","-"]
    # 比較対象があるかどうかのチェックと比較分のみ抽出
    for diffrence in diff:
        if diffrence[:1] in change_flg_word:
            print(diffrence)
    print('▲▲▲from lxml.html.clean import Cleaner▲▲▲')
        
else:
    print('createfile_isnot')
    