#!/bin/bash


echo "Comprobando PHPStan - PHP Static Analysis Tool"
echo "https://github.com/phpstan/phpstan"

# Core
#./vendor/bin/phpstan analyse ./app -c ./dev-tools/phpstan.neon --level=4 --no-progress -vvv
php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./app -c ./dev-tools/phpstan.neon --level=5 --memory-limit=2G -vvv
# php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./packages/clavel/basic -c ./dev-tools/phpstan.neon --level=1 --memory-limit=4000M -vvv
# php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./packages/clavel/crud-generator -c ./dev-tools/phpstan.neon --level=1 --memory-limit=4000M -vvv
# php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./packages/clavel/elearning -c ./dev-tools/phpstan.neon --level=1 --memory-limit=4000M -vvv
# php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./packages/clavel/gamification -c ./dev-tools/phpstan.neon --level=1 --memory-limit=4000M -vvv
# php -d memory_limit=-1 ./vendor/bin/phpstan analyse ./packages/clavel/translator-manager -c ./dev-tools/phpstan.neon --level=1 --memory-limit=4000M -vvv
