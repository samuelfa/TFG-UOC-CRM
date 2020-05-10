PHP_SERVICE := php

.PHONY: coverage cs infection integration it test

it: cs test

dcup:
	@docker-compose up -d

database:
	@docker-compose exec -T $(PHP_SERVICE) bin/console doctrine:schema:update

check: vendor
	@docker-compose exec -T $(PHP_SERVICE) bin/console security:check

test-unit:
	@docker-compose exec -T $(PHP_SERVICE) bin/phpunit tests/Application

test-functional:
	@docker-compose exec -T $(PHP_SERVICE) bin/phpunit tests/Functional

test-all:
	@docker-compose exec -T $(PHP_SERVICE) bin/phpunit tests

down:
	@docker-compose down --volumes
	@make -s clean

clean:
	@docker system prune --volumes --force

coverage: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/phpunit --coverage-text

cs: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	@docker-compose exec -T $(PHP_SERVICE) vendor/bin/infection --min-covered-msi=80 --min-msi=80

vendor: composer.json composer.lock
	@docker-compose exec -T $(PHP_SERVICE) composer self-update
	@docker-compose exec -T $(PHP_SERVICE) composer validate
	@docker-compose exec -T $(PHP_SERVICE) composer install

composer-update:
	@docker-compose exec -T $(PHP_SERVICE) composer update

composer-require:
	@docker-compose exec -T $(PHP_SERVICE) composer require $(filter-out $@,$(MAKECMDGOALS));

cache-clear:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console cache:clear

console:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console $(filter-out $@,$(MAKECMDGOALS));

create-fixture:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console make:fixture

load-fixtures:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console doctrine:fixtures:load

db-diff:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console make:migration --em=default
	@docker-compose exec -T $(PHP_SERVICE) php bin/console make:migration --em=crm

db-migrate:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console doctrine:migrations:migrate

debug-router:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console debug:router

debug-events:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console debug:event-dispatcher

install-assets:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console assets:install --symlink --relative

translation:
	@docker-compose exec -T $(PHP_SERVICE) php bin/console translation:update --force $(filter-out $@,$(MAKECMDGOALS));

deploy:
	@ansible-playbook -i ./etc/ansible/inventory/dev ./etc/ansible/deploy.yml --vault-password-file .vault_pass

provision:
	@ansible-playbook -i ./etc/ansible/inventory/dev ./etc/ansible/provision.yml

all:
	@make -s dcup
	@make -s check
	@make -s test-all