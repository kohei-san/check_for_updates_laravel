import bs4
import os

# file_name="C:/Users/kurita/Desktop/1.html"
# file_name1="C:/Users/kurita/Desktop/1435.html"

# try:
#     soup = bs4.BeautifulSoup(open(file_name), 'lxml')
# except:
#     soup = bs4.BeautifulSoup(open(file_name,'r',encoding="utf-8_sig"), 'lxml')

# with open(file_name, 'wb') as f:
#     f.write(soup.encode("utf-8"))

directry_folder="C:/xampp-7422/htdocs/check_for_updates_laravel/app/Http/Controllers/python/acquired_data/favorite/html"
files = os.listdir(directry_folder)
count = 1
for file in files:
    print(count)
    # if(file == "46744.html"):
    print(file)
    file_name = directry_folder+ "/" + file
    try:
        with open(file_name) as f:
            soup = bs4.BeautifulSoup(f, 'lxml')
    except:
        try:
            with open(file_name,'r',encoding="utf_8_sig") as f:
                soup = bs4.BeautifulSoup(f, 'lxml')
        except:
            try:
                with open(file_name,'r',encoding="Windows-1254") as f:
                    soup = bs4.BeautifulSoup(f, 'lxml')
            except:
                with open(file_name,'r',encoding="euc_jp") as f:
                    soup = bs4.BeautifulSoup(f, 'lxml')
    
    with open(file_name, 'wb') as f:
        f.write(soup.encode("utf-8"))
    
    count = count + 1

