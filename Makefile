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

	if [ -d message/ ]; \
	then \
		sudo chmod 777 -R message/; \
	fi;
	sudo chmod 777 -R tests/

down:
	docker compose down
up:
	docker compose up -d

composer-install:
	docker compose run --rm php composer install

composer-update:
	docker compose run --rm php composer update

update:
	git pull
	docker compose run --rm php composer install
	docker exec -it $(shell basename $(CURDIR))-php-1 php yii update-issuers

init: down rights build up composer-install
