include ./.docker/.env

.DEFAULT_GOAL := default
default:
	@echo ""
	@echo "DOCKER MANAGEMENT"
	@echo "_________________"
	@echo ""
	@echo "up:                  Builds all container and starts them"
	@echo "down:                Stops all containers"
	@echo "kill:                Kill all docker container"
	@echo "logs:                Displays the latest logs and auto refresh them"
	@echo "ssh:                 Logs-in the php container as the php user"
	@echo "ssh-sql:             Logs-in the mariadb container"


link:
	@echo "\x1b[33m Vous pouvez maintenant accéder au site sur https://${SERVER_NAME}  \x1b[0m"
	@echo "\x1b[33m Et à la base de données sur https://adminer.localhost  \x1b[0m"

up:
	cd .docker && docker-compose up --build -d
	make link

build:
	cd .docker && docker-compose build --no-cache

down:
	cd .docker && docker-compose down

kill:
	cd .docker && docker container rm $$(docker ps -aq) -f

logs:
	cd .docker && docker-compose logs -f

ssh:
	cd .docker && docker exec --workdir /var/www --user php -it $(NAMESPACE)_php /bin/bash

ssh-sql:
	docker exec -it $(NAMESPACE)_mariadb /bin/bash
