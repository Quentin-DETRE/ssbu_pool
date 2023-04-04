# Docker - php 8.1
## Description

This docker is pre-configured for symfony PHP project.

## Installation

- clone the repository
- remove .git
```shell
rm -Rf .git
```
- remove www.gitkeep
```shell
rm www/.gitkeep
```
- replace .docker/.env with project's information
```
NAMESPACE               used for container's name
SERVER_NAME             url to access project
# Mysql
MYSQL_DATABASE          mysql database name
MYSQL_USER              mysql user name
MYSQL_PASSWORD          mysql user password
MYSQL_ROOT_PASSWORD     mysql root password
```
- launch docker
```shell
make up
```
- Connect to php container
```shell
make ssh
```
- Enjoy =)

## Mise en place projet symfony
- Connect to php container
```shell
make ssh
```
- install Symfony with symfony-cli (don't forget --no-git)
```shell
symfony new . --demo --no-git
symfony new . --webapp --no-git
symfony new . --full --no-git
```

## containers

### container list
- traefik: Used for url mapping and https
- php: 8.1.3
- mariadb: 10.5.8
- adminer: you can change for phpMyAdmin

### container info
#### php

the server use symfony's local server
Application installed:
- composer
- symfony-cli

Enabled extensions :
- php8.1-mysql
- php8.1-pgsql
- php8.1-sqlite3
- php8.1-bcmath
- php8.1-bz2
- php8.1-dba
- php8.1-enchant
- php8.1-gd
- php8.1-gmp 
- php8.1-imap
- php8.1-intl
- php8.1-igbinary
- php8.1-imagick

## Makefile content

The Makefile contains commands to make it easy to control all the docker containers.  
All supported commands can be found by executing `make` without any argument.

```
⟩ make 

DOCKER MANAGEMENT
_________________

up:                  Builds all container and starts them
down:                Stops all containers
kill:                Kill all docker container
logs:                Displays the latest logs and auto refresh them
ssh:                 Logs-in the php container as the php user
ssh-sql:             Logs-in the mariadb container

```


# TODO
- [X] Faire la doc
- [x] Mettre adminer
- [ ] Mettre yarn
- [ ] Mettre phpmyadmin dans la doc
- [ ] Mettre mailhog
- [ ] mettre en place le https avec certificat de treafic
- [ ] Readme: Explications
- [ ] Readme: installation de base
- [ ] Installer et faire fonctionner le pas a pas php / x-debug (voir pour le rendre "activable" si ralentit trop l'application)
- [ ] partager le bash history avec le container php
- [ ] remettre en place une install facile et un guide blackfire

- [ ] trouver comment importer facilement une bdd existante (cd db_dump)


# help

