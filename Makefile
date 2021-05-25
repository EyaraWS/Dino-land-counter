# VARIABLES
DC=docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml
CONTAINER=php
EXEC=$(DC) exec --user=application $(CONTAINER)
AWK := $(shell command -v awk 2> /dev/null)
UID := ${shell id -u}
EXEC_ROOT=$(DC) exec $(CONTAINER)

.DEFAULT_GOAL := help
.PHONY: help

help:
ifndef AWK
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
else
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
endif

##
## Setup
##---------------------------------------------------------------------------

.PHONY: install vendor
.PRECIOUS: .env docker-compose.yaml

install: ## Process all step in order to setup the project
install: up vendor

vendor:
	$(EXEC) composer install --no-suggest --no-progress

update-vendor:
	$(EXEC) composer update

##
## Application
##---------------------------------------------------------------------------

.PHONY: execute
.PRECIOUS: execute

execute: ## Executes the application
	$(EXEC) php public/index.php

##
## Docker
##---------------------------------------------------------------------------

.PHONY: docker-files up down
.PRECIOUS: env-setup .php.env .env docker-compose.yaml

docker-files: env-setup docker/docker-compose.yml

env-setup: .env .php.env

.env: .env.dist
	@if [ -f .env ]; \
    	then\
    		echo '\033[1;41m/!\ The .env.dist file has changed. Please check your .env file (this message will not be displayed again).\033[0m';\
    		touch .env;\
    		exit 1;\
    	else\
    		echo cp .env.dist .env;\
    		cp .env.dist .env;\
    	fi

.php.env: .php.env.dist
	@if [ -f .php.env ]; \
    	then\
    		echo '\033[1;41m/!\ The .php.env.dist file has changed. Please check your .php.env file (this message will not be displayed again).\033[0m';\
    		touch .php.env;\
    		exit 1;\
    	else\
    		echo cp .php.env.dist .php.env;\
    		cp .php.env.dist .php.env;\
    	fi

up: ## Mount the containers
up: docker-files
	@$(DC) up --remove-orphans -d

down: ## Stops, remove the containers and their volumes
down: docker-files
	@$(DC) down -v --remove-orphans

bash: ## Access the bash of the php container
bash: docker-files
	@$(EXEC) bash

perm: docker-files
	-$(EXEC) chmod -R u+rwX,go+rX,go-w var
