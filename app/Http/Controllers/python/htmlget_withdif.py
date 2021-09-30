# -*- coding: utf-8 -*-
import DBconfig
db = DBconfig.functionDBconfig()
mycursor = db.cursor()

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


sql_difference_shortterm_diftrue_update  = "UPDATE difference_bet_shortterm SET difference_flg=%s, time_stamp_dif_short=%s WHERE page_id=%s"

sql_difference_shortterm_diffalse_update  = "UPDATE difference_bet_shortterm SET difference_flg=%s WHERE page_id=%s"

sql_create_short_difference_insert = """
    INSERT INTO create_short_difference (create_html_id_from, create_html_id_to)
    VALUES(%s,%s)
"""

sql_tag_to_exclude_select = '''
    SELECT * FROM tag_to_exclude 
    WHERE del_flg=0 
'''

# タグ名の削除項目
dfTagToExcludeData = pdsql.read_sql(sql_tag_to_exclude_select, db)


def create_htmlfile(foldername, filename, text ,encode_thishtml):
    file = open(foldername + '/' + filename + '.html','wb')
    file.write(text.encode(encode_thishtml))
    file.close()

# 比較するために不要なタグや属性の整理
def adjustment_tag(htmlData):
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

def unescape(s):
    s = s.replace("&lt;", "<")
    s = s.replace("&gt;", ">")
    # this has to be last:
    s = s.replace("&amp;", "&")
    return s

time_stamp = datetime.datetime.now()
file_nametime_stamp = datetime.datetime.now().strftime("%Y%m%d_%H%M")

# Customer Table を取得
dfCustomerData = pdsql.read_sql(sql_customerdata_select, db)

# customer_page Table を取得
dfPageDataData = pdsql.read_sql(sql_pagedata_select, db)
# 比較一個前のcreate_html_idを取得
dfpreCreatePageid = pdsql.read_sql(sql_create_page_select, db)
# 比較のcreate_html_idに対応するフォルダパス
pre_dir_path_recursive = path.dirname(__file__) + "/acquired_data/" + dfpreCreatePageid.loc[0,'filename_timestamp'] + "/html"

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

# create_short_difference Table に新規追加
li_create_short_difference=[]
li_create_short_difference.append([dfpreCreatePageid.loc[0,'filename_timestamp'], dfCreatePageid.loc[0,'filename_timestamp']])
mycursor.executemany(sql_create_short_difference_insert, li_create_short_difference)
db.commit

create_html_id = dfCreatePageid.loc[0,'create_html_id']
arrHTMLData = []
arrOKorNgPageNo = []
arrCheckDifferenceTrueShort = []
arrCheckDifferenceFalseShort = []
change_flg_word = ["-","+","-"]
li_ng_diffrence = ['<div>', '</div>', '<a>', '</a>', '<p>','</p>', '<span>' ,'</span>', '</div></div>', '</span>', '\xa0', '<p>', '</p>']

for index, row in dfPageDataData.iterrows():
    try:
        res = requests.get(row.page_url)
        htmldata = BeautifulSoup(res.content,'lxml')
    except:
        htmldata = "error"
    
    short_term_path = path.dirname(__file__)+'/defferent/short_term/'+str(row.page_id)+'.html'
    if (os.path.exists(short_term_path)):
        os.remove(short_term_path) 
    print(row.page_id)
    # ページデータがあるかどうかの分岐
    if res.status_code < 400 and not htmldata == "error":
        print('page_true')
        # 相対パスを変更する
        tags = htmldata.findAll()
        for tag in tags:
            if tag.get('src'):
                tag['src'] = urljoin(row.page_url, tag['src'])
            if tag.get('href'):
                tag['href'] = urljoin(row.page_url, tag['href'])
        
        # エンコード取得
        encode_thishtml = htmldata.original_encoding
        create_htmlfile(new_dir_path_recursive, str(row.page_id), htmldata, encode_thishtml)
        time_get_file = datetime.datetime.now()
        arrHTMLData.append([row.page_id, int(row.customer_id), int(create_html_id), time_get_file])
        arrOKorNgPageNo.append([0, row.page_id])

        # 比較ファイルのアドレスを取得
        beforefilename = pre_dir_path_recursive + "/" + str(row.page_id) + ".html"
        afterfilename = new_dir_path_recursive + "/" + str(row.page_id) + ".html"
        
        cleaner = Cleaner(page_structure=False, remove_tags=('ruby', 'rb', 'br'), kill_tags=('rt', 'rp'))

        # 前回分があるかどうか
        if os.path.exists(beforefilename):
            error_flg = 0
            # 過去比較ファイルを開いて整理する
            with open(beforefilename, mode='rb') as f1:
                enc = detect(f1.read())['encoding']
                print(enc)
                with open(beforefilename, mode='r',encoding=enc) as f2:
                    if enc == 'utf-8':
                        beforeHTMLtext = f2.read().encode('utf-8')
                    else:
                        try:
                            beforeHTMLtext = cleaner.clean_html(f2.read().encode(enc)).decode('utf-8')
                        except:
                            print('error')
                            error_flg = 1

            # フォルダが開けたら
            if error_flg == 0:
                # 今回比較ファイルを開いて整理する
                with open(afterfilename, mode='rb') as f1:
                    enc = detect(f1.read())['encoding']
                    print(enc)
                    with open(afterfilename, mode='r',encoding=enc) as f2:
                        if enc == 'utf-8':
                            afterHTMLtext = f2.read().encode('utf-8')
                        else:
                            try:
                                afterHTMLtext = cleaner.clean_html(f2.read().encode(enc)).decode('utf-8')
                            except:
                                print('error')
                                error_flg = 1
            # フォルダが開けたら
            if error_flg == 0:
                beforeHTML = lxml.html.fromstring(beforeHTMLtext)
                beforeHTML = adjustment_tag(beforeHTML)

                afterHTML = lxml.html.fromstring(afterHTMLtext)
                afterHTML = adjustment_tag(afterHTML)

                # lxml から 文字列に変換
                beforeFile = lxml.html.etree.tostring(beforeHTML, encoding='utf-8').decode()
                afterFile = lxml.html.etree.tostring(afterHTML, encoding='utf-8').decode()

                difdiffer = difflib.Differ()
                # ファイルを比較
                diff = difdiffer.compare(beforeFile.splitlines(), afterFile.splitlines())
                
                # 比較符号と、比較対象を入れるリスト作成
                liDifference = []

                # 比較対象があるかどうかのチェックと比較分のみ抽出
                diffrence_flg = 0
                for diffrence in diff:
                    if diffrence[:1] in change_flg_word:
                        
                        diffrence_flg = 1
                        # + のみ検出かつli_ng_diffrenceに入っていないものかつ <div・・・>でないもの
                        if diffrence[:1] == '+' and not(diffrence[2:].replace(' ', '') in li_ng_diffrence) and not(diffrence[2:].startswith("<div") and diffrence[2:].endswith(">") and diffrence[2:].count("<")==1):
                            liDifference.append([diffrence[:1],''.join(diffrence[2:])])
            
            # フォルダが開けなかったら
            else:
                diffrence_flg = 0

        # 前回分があるかどうか(なし)
        else:
            diffrence_flg = 0

        # 比較対象があるかどうかの分岐
        if diffrence_flg == 1:
            # 比較対象センテンスのみ取り出し
            li_differ_sentences = [sentence[1] for sentence in liDifference]
            # プラス要素があるかどうか分岐
            if(liDifference):
                arrCheckDifferenceTrueShort.append([1, time_get_file, row.page_id])
                # インテンド位置
                div_stage = 0
                # 直近の親要素のライン行
                last_stage_status = {}
                
                # 比較を反映するafterfile と同等ファイルを開いて、行ごとに分ける。（afterfileは整理されているため、新しいfileを開く）
                li_comparison_reflection_file = open(afterfilename, mode='r',encoding=encode_thishtml).read().splitlines()

                # div のみのインテンド位置を計算しておくことで、親divにstyleを付ける。
                # 最初のifがそのための準備計算
                # もし変更があれば、style を付ける。同じdivに付けない。またstyleがあればstyleの中に、なければ追加する。
                for index, line in enumerate(li_comparison_reflection_file):
                    if "div" in line:
                        if r'<div' in line:
                            for num in range(div_stage, div_stage + line.count(r'<div')):
                                last_stage_status[num] = index
                            div_stage += line.count(r'<div')
                        div_stage -= line.count(r'</div')
                    
                    if line in li_differ_sentences:
                        if div_stage > 0 and not('style="border:4px double red' in li_comparison_reflection_file[last_stage_status[div_stage-1]]):
                            replace_sentence = li_comparison_reflection_file[last_stage_status[div_stage-1]]
                            if 'style="' in replace_sentence:
                                li_comparison_reflection_file[last_stage_status[div_stage-1]] = replace_sentence.replace('style="','style="border:4px double red; ')
                            else:
                                li_comparison_reflection_file[last_stage_status[div_stage-1]] = replace_sentence.replace('<div','<div style="border:4px double red;"')
                
                # 行分解しいるため、これを一つにまとめる。
                diffHTML ='\n'.join(li_comparison_reflection_file)
                create_htmlfile(path.dirname(__file__)+'/defferent/short_term', str(row.page_id), diffHTML, 'utf-8')
                print('diftrue')
            # プラス要素があるかどうか(なし)
            else:
                arrCheckDifferenceFalseShort.append([0, row.page_id])
        
        # 比較対象があるかどうかの分岐(なし)
        else:
            arrCheckDifferenceFalseShort.append([0, row.page_id])
                
    # ページデータがあるかどうかの分岐(なし)
    else:
        print('page_false')
        arrOKorNgPageNo.append([1, row.page_id])
        arrCheckDifferenceFalseShort.append([0, row.page_id])

    if (index + 1) % 100 == 0:
        try:
            # HTML作った履歴
            mycursor.executemany(sql_create_htmlsrc_insert, arrHTMLData)
            db.commit
            arrHTMLData = []

            # 差分あるか更新
            mycursor.executemany(sql_difference_shortterm_diftrue_update, arrCheckDifferenceTrueShort)
            db.commit
            arrCheckDifferenceTrueShort = []

            mycursor.executemany(sql_difference_shortterm_diffalse_update, arrCheckDifferenceFalseShort)
            db.commit
            arrCheckDifferenceFalseShort = []

            mycursor.executemany(sql_ngpage_update, arrOKorNgPageNo)
            db.commit
            arrOKorNgPageNo = []

            print('DBインサート')
            print(index + 1)
        except:
            print('DBエラー' + str(datetime.datetime.now()))


if arrHTMLData:
    mycursor.executemany(sql_create_htmlsrc_insert, arrHTMLData)   
    mycursor.executemany(sql_difference_shortterm_diftrue_update, arrCheckDifferenceTrueShort)
    mycursor.executemany(sql_difference_shortterm_diffalse_update, arrCheckDifferenceFalseShort)

if arrOKorNgPageNo: 
    mycursor.executemany(sql_ngpage_update, arrOKorNgPageNo)

db.commit 
db.close
