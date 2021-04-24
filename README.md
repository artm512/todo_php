# php_todo

## local
http://localhost:8562/
## docker 起動
$ docker-compose up -d
## docker 停止
$ docker-compose down

## 起動しているコンテナの名前を知る
$ docker-compose ps --service

## dbコンテナにログインしてデータベースの設定
$ docker-compose exec db bash
### mysqlにログイン
$ mysql -u myappuser -p myapp
