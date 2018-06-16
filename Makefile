.PHONY: docker-up docker-down

docker-up:
	docker-sync start > ./var/log/docker/docker-sync.log 2>&1 &
	docker-compose up -d

docker-down:
	docker-sync stop
	docker-compose down