#!/home/xs330114/anaconda3/bin/python3.8.exe
from bs4 import BeautifulSoup
import requests



url_deepth = 1

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
        if (not href) or href =='/':
            continue

        # /始まりは、一文字目の「/」移行をtopurlに結合
        if href[0] == '/':
            href = toppage_url + href[1:]
        
        if href[-1] != '/':
            href = href + '/'

        # hrefの「/」の数がオーバー、またはNGワードがあるかで場合分け
        if href.count('/') > slashCount or ng_words(href):
            continue
        
        # topurl が入っているか（外部リンク除外）かつ 今までの arrHref に格納されてない場合
        if (toppage_url in href) and not(href in arrHref):
            arrHref.append(href)
    
    return arrHref


print(get_linkurl("https://r-m.jp/"))

# print("hello")


        


