Скопировать одну из конфигураций nginx:

Для проекта с SSL:
~~~
sudo cp .docker/nginx/with_ssl.conf .docker/nginx/main.conf
~~~
Или простой
~~~
sudo cp .docker/nginx/simple.conf .docker/nginx/main.conf
~~~

Запустить
~~~
make init
~~~


Добавить в cron задачу на перевыпуск SSL сертификата:
~~~
@monthly cd /var/www/calinv/ && docker compose up certbot --build && docker compose up web --build -d
~~~
