PHP_SERVICE := php

.PHONY: coverage cs infection integration it test

it: cs test

up:
	@docker-compose up -d

database:
	@docker-compose exec -T $(PHP_SERVICE) bin/console doctrine:schema:update

test: vendor
	@docker-compose exec -T $(PHP_SERVICE) bin/console security:check
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/phpunit --configuration=test/Unit/phpunit.xml
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/phpunit --configuration=test/Integration/phpunit.xml

down:
	@docker-compose down --volumes
	@make -s clean

clean:
	@docker system prune --volumes --force

coverage: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

cs: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/infection --min-covered-msi=80 --min-msi=80

vendor: composer.json composer.lock
	@docker-compose exec -T $(PHP_SERVICE) composer self-update
	@docker-compose exec -T $(PHP_SERVICE) composer validate
	@docker-compose exec -T $(PHP_SERVICE) composer install

deps_update:
	@docker-compose exec -T $(PHP_SERVICE) composer update

require:
	@docker-compose exec -T $(PHP_SERVICE) composer require $(filter-out $@,$(MAKECMDGOALS));

console:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console $(filter-out $@,$(MAKECMDGOALS));

all:
	@make -s up
	@make -s vendor
	@make -s deps_update
	@make -s database
	@make -s test