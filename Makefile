.PHONY: docker-up

deps:
	yarn install
	composer install

build:
	yarn run build

docker-up:
	docker-compose up

php-bash:
	docker-compose exec php sh