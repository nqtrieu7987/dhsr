# dhsr
Chien best of F*ck

1 thư mục code nodejs -srcnode

2 thư mục php code – src

3 thư mục ssl – key ssl , cer

4thư mục nginx config  nginx

5 thư mục database ( dữ liệu  ).

6 thư mục fpm config php-fpm.

.env cifonfig database cluster (hien tai chi can chay master).
 
  

 

build nodejs
cd dockernodejs
mkdir uploads
docker build -t nodejspm2 .

 
cd src/dshr/storage
mkdir framework 
mkdir framework/sessions
mkdir framework/views
mkdir framework/cache 
mkdir debugbar
mkdir users 
chmod -R 777 storage
sua file nginx host ve local neu khong dung host

bung file vendor.zip 
Chạy ứng dụng kiểm tra
 
1.       docker-compose up -d
  

 
truy cap host/admin.php tao va import db.

