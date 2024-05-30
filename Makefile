# include .env

SHELL := bash

# Setup —————————————————————————————————————————————————————————————————————————
EXEC_DOCKER_FPM = docker compose exec fpm
SYMFONY = $(EXEC_DOCKER_FPM) bin/console
COMPOSER = $(EXEC_DOCKER_FPM) composer
OS ?= $(shell uname -s)
OS_ARCH ?= $(shell uname -m)

.DEFAULT_GOAL := help

help: ## Outputs this help screen
	@grep -E '(^[0-9a-zA-Z_%-]+:.*?##.*$$)|(^##)' $(firstword $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install:
	make install-mkcert
	make mkcert-generate-certificates
	make install-docker-hostmanager
	make local-network
	touch docker/volumes/.bash_history
	make up
	make url

.PHONY: install-mkcert mkcert-generate-certificates
install-mkcert: # @see https://github.com/FiloSottile/mkcert
ifeq ($(shell which mkcert),)
ifeq ($(OS),Linux)
	@printf "\033[0;94mInstalling mkcert and the local CA...\033[0m\n"
	@printf "You may be asked for your password to install mkcert\n"
	@sudo apt install libnss3-tools
	@sudo chmod +x /usr/local/bin/mkcert
	@mkcert -install
	@printf "mkcert has been installed\n"
else
	@printf "mkcert installation is not supported. Please install it on your own.\n"
	@exit 1
endif
else
	@printf "mkcert is already installed\n"
endif

.PHONY: install-docker-hostmanager
install-docker-hostmanager: # @see https://hub.docker.com/r/iamluc/docker-hostmanager
	@if [[ ! $$(docker inspect -f '{{.State.Running}}' docker-hostmanager) == true ]]; then \
		printf "\033[0;94mStarting docker-hostmanager container...\033[0m\n"; \
		docker run -d --name docker-hostmanager --restart=always -v /var/run/docker.sock:/var/run/docker.sock -v /etc/hosts:/hosts iamluc/docker-hostmanager 2>/dev/null || docker start docker-hostmanager; \
	fi
	@printf "docker-hostmanager is up and running\n"

## —— Docker ——————————————————————————————————————————————————————————————————
fpm: ## Enter in container app
	$(EXEC_DOCKER_FPM) bash

local-network:
	docker network create app.local || true

up: ## Build containers
	docker compose up -d --build

down: ## Destroy containers
	docker compose down

clear:
	make down
	rm -rf bin composer.json composer.lock config LICENSE src symfony.lock tmp/ var vendor/ .env || true
	find ./public -type f ! -name '.gitkeep' -delete || true
	find ./public/* -type d -delete || true
	find ./docker/nginx/ssl/* -type f ! -name '.gitkeep' -delete || true

mkcert-generate-certificates: ## Generate local SSL certificate
ifeq ($(wildcard ./docker/nginx/ssl/wildcard.app.local.crt),)
	@mkcert -cert-file ./docker/nginx/ssl/wildcard.app.local.crt -install -key-file ./docker/nginx/ssl/wildcard.app.local.key "app.local" "*.app.local"
endif

## —— Project ——————————————————————————————————————————————————————————————————
p-update: ## Update project
	make cinstall
	make migrate



## —— Envs ——————————————————————————————————————————————————————————————————
url: ## Display the local url
	@echo
	@echo -e "$(BOLD_CYAN)🌐 API Accessible! 🌐"
	@echo -e "$(BOLD_GREEN)Your API is now accessible at:"
	@echo -e "$(BOLD_CYAN)https://project.app.local$(NO_COLOR)"
	@echo



no-docker:
	$(eval EXEC_DOCKER_FPM := )
