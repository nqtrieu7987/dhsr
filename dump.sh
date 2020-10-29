docker exec  databasemy mysqldump  --user=root --password=Dshr2020 --host=localhost --port=3306 --result-file="/dumps/mysqldump"$(date +%s)".sql" --databases "dshr"
