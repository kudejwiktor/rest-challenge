.PHONY: ssh fix-permissions composer-install run generate-key migrate seed docker-logs build

ssh:
	@docker-compose exec app sh

fix-permissions:
	@chmod -R 777 bootstrap/cache

migrate:
	@docker-compose exec app ./artisan migrate

seed:
	@docker-compose exec app ./artisan db:seed

generate-key:
	@docker-compose exec app ./artisan key:generate

composer-install:
	@sh composer install

run:
	@docker-compose up -d ;\

docker-logs:
	@docker-compose logs -f || exit 0 ;\

build: composer-install fix-permissions run migrate seed docker-logs
