#!/usr/bin/env ash
set -e

if [ "$1" = "php-fpm" ] || [ "$1" = "php" ]; then
    if [ ! -f composer.json ]; then
      rm -Rf tmp/
      composer create-project "symfony/skeleton" ./tmp --stability="stable" --prefer-dist --no-progress --no-interaction --no-install

      cd tmp
      cp -Rp . ..
      cd -
#      rm -Rf tmp/
    fi
    if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
      composer install --prefer-dist --no-progress --no-interaction
    fi
    if [ -e "vendor/autoload.php" ] && [ "$( find ./migrations -iname "*.php" -print -quit )" ]; then
      XDEBUG_MODE=off bin/console doctrine:migrations:migrate --no-interaction
    fi

    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

exec docker-php-entrypoint "$@"
