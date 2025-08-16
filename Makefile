bash:
	docker exec -it $(shell basename $(CURDIR))-php-1 bash

build:
	docker compose build
	
db-rights:
	if [ -d database/ ]; \
	then \
		sudo chmod 777 -R database/; \
	fi;	

vendor-rights:
	if [ -d vendor/ ]; \
	then \
		sudo chmod 777 -R vendor/; \
	fi;

rights: db-rights vendor-rights
	sudo chmod 777 -R web/
	sudo chmod 777 -R runtime/
	sudo chmod 777 -R migrations/

	if [ -d message/ ]; \
	then \
		sudo chmod 777 -R message/; \
	fi;
	sudo chmod 777 -R tests/

down:
	docker compose down web
	docker compose down php
	docker compose down db
up:
	docker compose up -d db
	docker compose up -d php
	docker compose up -d web

composer-install:
	docker compose run --rm php composer install

composer-update:
	docker compose run --rm php composer update

update:
	git pull
	docker compose run --rm php composer install
	docker compose run --rm php php yii migrate --interactive=0

migrate:
	docker compose run --rm php php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
	docker compose run --rm php php yii migrate --interactive=0

certbot:
	docker compose up -d certbot

init: down rights build up composer-install migrate

migrate-prev:
	docker compose run --rm php php yii migrate/down --interactive=0

db-fresh:
	docker compose run --rm php php yii migrate/fresh --interactive=0