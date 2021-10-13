# -*- coding: utf-8 -*-

import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

from bs4 import BeautifulSoup
import requests
from urllib.parse import urljoin
from lxml.html.clean import Cleaner
from chardet import detect
import os
from os import path
import lxml.html
import difflib
import sql_sentence
import pandas.io.sql as pdsql
import datetime
import shutil


# ファイルを作成する
def create_htmlfile(foldername, filename, text ,encode_thishtml):
    with open(foldername + '/' + filename + '.html','wb') as f:
        f.write(text.encode(encode_thishtml))

# ファイルを削除する
def removeFile(fullpath):
    if (os.path.exists(fullpath)):
        os.remove(fullpath) 

df_ng_word_list = pdsql.read_sql(sql_sentence.url_ng_word_select, db)
# url内のNGワード
def ng_words(target_word):
    for row in df_ng_word_list.itertuples():
        if row.ng_word in target_word:
            return True

def tryBeautifulSoup(url):
    try:
        res = requests.get(url)
        htmldata = BeautifulSoup(res.content,'lxml')
    except:
        res = ""
        htmldata = ""
    
    return res, htmldata

url_deepth = 1
def get_linkurl(htmldata, toppage_url):
    arrHref = []
    if htmldata:
        links = htmldata.find_all('a')
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

# betdeffrence table 二つに登録されていない page_id を探し出す
# page_id登録用のlistを作成
def checkExistInBetTabel(dfPageDataData, dfDiffernceData):
    liDifferenceData = []
    diff_page_ids = dfDiffernceData.page_id.values
    for index, row in dfPageDataData.iterrows():
        if not row.page_id in diff_page_ids:
            liDifferenceData.append([row.page_id, row.customer_id])
    return liDifferenceData

# 文字化け削除
def unescape(s):
    s = s.replace("&lt;", "<")
    s = s.replace("&gt;", ">")
    # this has to be last:
    s = s.replace("&amp;", "&")
    return s


# 全ての相対パスを絶対パスに変更
def changePathRelateiveToDirect(htmldata, url):
    tags = htmldata.findAll()
    for tag in tags:
        if tag.get('src'):
            tag['src'] = urljoin(url, tag['src'])
        if tag.get('href'):
            tag['href'] = urljoin(url, tag['href'])
    return htmldata

# 差分を見るためのファイルを開く
def openfile_to_diff(filename):
    from lxml.html.clean import Cleaner
    from chardet import detect
    cleaner = Cleaner(page_structure=False, remove_tags=('ruby', 'rb', 'br'), kill_tags=('rt', 'rp'))
    with open(filename, mode='rb') as f1:
        enc = detect(f1.read())['encoding']
    with open(filename, mode='r',encoding=enc) as f2:
        if enc == 'utf-8':
            HTMLtext = f2.read().encode()
        else:
            try:
                HTMLtext = cleaner.clean_html(f2.read().encode(enc)).decode('utf-8')
            except:
                with open(filename, mode='r',encoding="utf-8_sig") as f3:
                    try:
                        HTMLtext = cleaner.clean_html(f3.read().encode("utf-8_sig")).decode('utf-8')
                    except:
                        HTMLtext = ""

    return HTMLtext
# 比較するために不要なタグや属性の整理
dfTagToExcludeData = pdsql.read_sql(sql_sentence.tag_to_exclude_select, db)
def adjustment_tag(htmlData):
    # タグ名の削除項目
    for tag_list in dfTagToExcludeData.itertuples():
        # 属性値があって、属性がないときはスルー
        if ( tag_list.attribute_value ):
            if not ( tag_list.attribute ):
                continue
        
        # xpath の作成
        tag_name = ""
        if ( tag_list.tag_name or (tag_list.attribute and tag_list.attribute_value) ):
            tag_name = ".//"
            if( tag_list.tag_name ):
                tag_name += tag_list.tag_name
            else:
                tag_name += "*"
            
            if( tag_list.attribute and tag_list.attribute_value ):
                tag_name += "[@"
                tag_name += tag_list.attribute
                tag_name += "='"
                tag_name += tag_list.attribute_value
                tag_name += "']"

        # タグを指定できる時は指定する
        # できないときはすべて取得
        if( tag_name ):
            tags = htmlData.findall(tag_name)
        else:
            tags = htmlData.iter()

        # タグ破壊
        if( tag_list.tag_or_attribute == 0 ):
            # タグを指定しているときのみ実行
            if ( tag_name ):
                for tag in tags:
                    tag.drop_tree()

        # 属性のみ破壊
        elif ( tag_list.tag_or_attribute == 1 ):
            for tag in tags:
                tag.attrib.pop(tag_list.attribute, None)

    return htmlData

# 差分テキスト作成
change_flg_word = ["-","+","-"]
li_ng_diffrence = ['<div>', '</div>', '<a>', '</a>', '<p>','</p>', '<span>' ,'</span>', '</div></div>', '</span>', '\xa0', '<p>', '</p>']
def create_diff(beforefilename, afterfilename):
    
    # 比較符号と、比較対象を入れるリスト作成
    liDifference = []
    diffrence_flg = 0 
    if path.exists(beforefilename):
        beforeHTMLtext = openfile_to_diff(beforefilename)
        if beforeHTMLtext:

            beforeHTML = lxml.html.fromstring(beforeHTMLtext)
            beforeHTML = adjustment_tag(beforeHTML)
            beforeFile = lxml.html.etree.tostring(beforeHTML, encoding='utf-8').decode()
            
            afterHTMLtext= openfile_to_diff(afterfilename)
            if afterHTMLtext:

                afterHTML = lxml.html.fromstring(afterHTMLtext)
                afterHTML = adjustment_tag(afterHTML)
                afterFile = lxml.html.etree.tostring(afterHTML, encoding='utf-8').decode()

                difdiffer = difflib.Differ()
                diff = difdiffer.compare(beforeFile.splitlines(), afterFile.splitlines())

                # 比較対象があるかどうかのチェックと比較分のみ抽出
                for diffrence in diff:
                    if diffrence[:1] in change_flg_word:
                        diffrence_flg = 1
                        # + のみ検出かつli_ng_diffrenceに入っていないものかつ <div・・・>でないもの
                        if diffrence[:1] == '+' and not(diffrence[2:].replace(' ', '') in li_ng_diffrence) and not(diffrence[2:].startswith("<div") and diffrence[2:].endswith(">") and diffrence[2:].count("<")==1):
                            liDifference.append([diffrence[:1], diffrence[2:]])
    else:
        if ("favorite" in beforefilename) and path.exists(afterfilename):
            shutil.copy2(afterfilename, beforefilename)
    
    return liDifference, diffrence_flg


# 差分がある箇所に赤枠を生成する
change_flg_word = ["-","+","-"]
li_ng_diffrence = ['<div>', '</div>', '<a>', '</a>', '<p>','</p>', '<span>' ,'</span>', '</div></div>', '</span>', '\xa0', '<p>', '</p>']
def create_comparison_reflection_file(li_differ_sentences, afterfilename, encode_thishtml):
    # インテンド位置
    div_stage = 0
    td_stage = 0
    # 直近の親要素のライン行
    last_divstage_status = {}
    last_tdstage_status = {}
    # 比較を反映するafterfile と同等ファイルを開いて、行ごとに分ける。（afterfileは整理されているため、新しいfileを開く）
    with open(afterfilename, mode='r',encoding=encode_thishtml) as f:

        li_comparison_reflection_file = f.read().splitlines()

        # div のみのインテンド位置を計算しておくことで、親divにstyleを付ける。
        # 最初のifがそのための準備計算
        # もし変更があれば、style を付ける。同じdivに付けない。またstyleがあればstyleの中に、なければ追加する。
        for index, line in enumerate(li_comparison_reflection_file):
            if "div" in line:
                if r'<div' in line:
                    for num in range(div_stage, div_stage + line.count(r'<div')):
                        last_divstage_status[num] = index
                    div_stage += line.count(r'<div')
                div_stage -= line.count(r'</div')

            if "td" in line:
                if r'<td' in line:
                    for num in range(td_stage, td_stage + line.count(r'<td')):
                        last_tdstage_status[num] = index
                    td_stage += line.count(r'<td')
                td_stage -= line.count(r'</td')
            
            for li_differ_sentence in li_differ_sentences:
                if line == li_differ_sentence:
                    if div_stage > 0:
                        if not('style="border:4px double red' in li_comparison_reflection_file[last_divstage_status[div_stage-1]]):
                            replace_sentence = li_comparison_reflection_file[last_divstage_status[div_stage-1]]
                            if 'style="' in replace_sentence:
                                li_comparison_reflection_file[last_divstage_status[div_stage-1]] = replace_sentence.replace('style="','style="border:4px double red !important; ')
                            else:
                                li_comparison_reflection_file[last_divstage_status[div_stage-1]] = replace_sentence.replace('<div','<div style="border:4px double red !important;"')

                    elif td_stage > 0:
                        if not('style="border:4px double red' in li_comparison_reflection_file[last_tdstage_status[td_stage-1]]):
                            replace_sentence = li_comparison_reflection_file[last_tdstage_status[td_stage-1]]
                            if 'style="' in replace_sentence:
                                li_comparison_reflection_file[last_tdstage_status[td_stage-1]] = replace_sentence.replace('style="','style="border:4px double red !important; ')
                            else:
                                li_comparison_reflection_file[last_tdstage_status[td_stage-1]] = replace_sentence.replace('<td','<td style="border:4px double red !important;"')              
                    else:
                        li_comparison_reflection_file[index] += '赤枠例外変更点です。栗田に報告お願いします。'
    return li_comparison_reflection_file

# 差分チェックメイン
# 差分比較ファイルが生成されたかが0,1で返る。
def checkDif_createDifFile(beforefilename, afterfilename, encode_thishtml):
    # 比較ファイル名から、比較リストの生成
    liDifference, diffrence_flg = create_diff(beforefilename, afterfilename)
    difcheck_flg = 0
    diffHTML = ""
    # 比較対象があるかどうかの分岐
    if diffrence_flg == 1:
        # 比較対象センテンスのみ取り出し
        li_differ_sentences = [sentence[1] for sentence in liDifference]
        # プラス要素があるかどうか分岐
        if(liDifference):
            difcheck_flg = 1

            # 今回取得ファイルの中で、差分がある箇所に赤枠を生成する
            li_comparison_reflection_file = create_comparison_reflection_file(li_differ_sentences, afterfilename, encode_thishtml)
            
            # 行分解をして一つにまとめる。
            diffHTML ='\n'.join(li_comparison_reflection_file)

    # 差分ファイルが作れたら1　なかったら0
    return diffHTML, difcheck_flg

def mail_print(arrPrintTime):
    print('【CorSin のクロールが完了しました】')
    print()
    print('=====処理全体=====')
    print('開始時刻 : ' + arrPrintTime['python-start'].strftime("%Y:%m:%d %H:%M:%S"))
    print('終了時刻 : ' + arrPrintTime['python-end'].strftime("%Y:%m:%d %H:%M:%S"))
    time = arrPrintTime['python-end']-arrPrintTime['python-start']
    print('経過時間 : ' + str(time))
    print()
    print()
    print('---customer_page 更新---')
    print('開始時刻 : ' + arrPrintTime['up_pagelist-start'].strftime("%Y:%m:%d %H:%M:%S"))
    print('終了時刻 : ' + arrPrintTime['up_pagelist-end'].strftime("%Y:%m:%d %H:%M:%S"))
    time = arrPrintTime['up_pagelist-end']-arrPrintTime['up_pagelist-start']
    print('経過時間 : ' + str(time))
    print()
    print('---前処理---')
    print('開始時刻 : ' + arrPrintTime['preprocessing-start'].strftime("%Y:%m:%d %H:%M:%S"))
    print('終了時刻 : ' + arrPrintTime['preprocessing-end'].strftime("%Y:%m:%d %H:%M:%S"))
    time = arrPrintTime['preprocessing-end']-arrPrintTime['preprocessing-start']
    print('経過時間 : ' + str(time))
    print()
    print('---データ読み込み処理---')
    print('開始時刻 : ' + arrPrintTime['crawl-start'].strftime("%Y:%m:%d %H:%M:%S"))
    print('終了時刻 : ' + arrPrintTime['crawl-end'].strftime("%Y:%m:%d %H:%M:%S"))
    time = arrPrintTime['crawl-end']-arrPrintTime['crawl-start']
    print('経過時間 : ' + str(time))
    print()

