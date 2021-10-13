# -*- coding: utf-8 -*-
# =================================================
# ====▼▼▼▼▼▼▼▼====　　　sql 文　　　====▼▼▼▼▼▼▼▼====
# =================================================

pagedata_select = '''
    SELECT * FROM customer_page 
    WHERE customer_id IN ( SELECT customer_id FROM customer WHERE active_flg=1 AND del_flg=0 )
'''

all_page_data_select = '''
    SELECT * FROM customer_page
'''

customerdata_select = '''
    SELECT customer_id, customer_toppage_url FROM customer WHERE active_flg=1 AND del_flg=0
'''

create_page_insert = """
    INSERT INTO create_html (time_stamp, filename_timestamp)
    VALUES(%s,%s)
"""

create_page_select_max = """
    SELECT * FROM create_html WHERE create_html_id=( SELECT MAX(create_html_id) FROM create_html )
"""

del_create_page_select = """
    SELECT * FROM create_html
    WHERE favorite=0 AND save_flg=0 AND del_flg=0 AND create_html_id not in(
        SELECT * FROM (
            SELECT create_html_id 
            FROM create_html 
            order by create_html_id desc 
            LIMIT 5
        )v
    )
"""

create_page_del_update = "UPDATE create_html SET del_flg=%s WHERE create_html_id=%s"

create_htmlsrc_insert = """
    INSERT INTO page_html (page_id, customer_id, create_html_id, time_stamp_htmlsrc)
    VALUES(%s,%s,%s,%s)
"""

ngpage_update = "UPDATE customer_page SET ng_flg=%s WHERE page_id=%s"


# short 処理
difference_shortterm_diftrue_update  = "UPDATE difference_bet_shortterm SET difference_flg=%s, time_stamp_dif_short=%s WHERE page_id=%s"

difference_shortterm_diffalse_update  = "UPDATE difference_bet_shortterm SET difference_flg=%s WHERE page_id=%s"

difference_shortterm_select = """
    SELECT * FROM difference_bet_shortterm
"""

difference_shortterm_insert = """
    INSERT INTO difference_bet_shortterm (page_id, customer_id)
    VALUES(%s, %s)
"""

create_short_difference_insert = """
    INSERT INTO create_short_difference (filename_timestamp_from, filename_timestamp_to)
    VALUES(%s,%s)
"""

# long 処理
difference_longterm_diftrueorfalse_update  = "UPDATE difference_bet_longterm SET difference_flg=%s, time_stamp_dif_long=%s WHERE page_id=%s"

difference_longterm_diffalse_update  = "UPDATE difference_bet_longterm SET difference_flg=%s WHERE page_id=%s"

difference_longterm_select = """
    SELECT * FROM difference_bet_longterm
"""

favo_create_page_select = """
    SELECT * FROM create_html WHERE create_html_id =(SELECT MAX(create_html_id) FROM create_html WHERE favorite=1) 
"""

difference_longterm_insert = """
    INSERT INTO difference_bet_longterm (page_id, customer_id)
    VALUES(%s, %s)
"""

create_long_difference_insert = """
    INSERT INTO create_long_difference (filename_timestamp_from, filename_timestamp_to)
    VALUES(%s,%s)
"""


create_difference_history_insert = """
    INSERT INTO difference_history (page_id, customer_id, time_stamp_dif)
    VALUES(%s,%s,%s)
"""


tag_to_exclude_select = '''
    SELECT * FROM tag_to_exclude 
    WHERE del_flg=0 
'''

# pageurlの保存
non_active_customer_select = "SELECT * FROM customer WHERE active_flg=0 AND del_flg=0"

# pageurlの保存
customer_page_insert = """
INSERT INTO customer_page (customer_id, page_url, top_page_flg)
VALUES(%s, %s, %s)
"""

# 登録したURLのcustomer.active_flgを1にする
customer_update = '''
UPDATE customer SET active_flg=%s WHERE customer_id=%s
'''

# url_ng_word 取得
url_ng_word_select = "SELECT ng_word FROM url_ng_word"


def create_new_page_select_SQL(predeta_last_page_id):
    customer_page_NEW_select = pagedata_select
    customer_page_NEW_select += """AND page_id > """
    customer_page_NEW_select += str(predeta_last_page_id)
    
    return customer_page_NEW_select
# =================================================
# ====▲▲▲▲▲▲▲▲====　　　sql 文　　　====▲▲▲▲▲▲▲▲====
# =================================================




