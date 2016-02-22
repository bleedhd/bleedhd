#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../Symfony"
CONSOLE="$SYMFONY/app/console"
ENV=${1:-prod}

pushd "$SYMFONY"
php "$CONSOLE" assets:install --symlink --env=$ENV
php "$CONSOLE" assetic:dump --env=$ENV
php "$CONSOLE" doctrine:migrations:migrate --env=$ENV
php "$CONSOLE" cache:clear --env=$ENV
popd
