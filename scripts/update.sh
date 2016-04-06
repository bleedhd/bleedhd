#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../Symfony"
CONSOLE="$SYMFONY/bin/console"
ENV=${1:-prod}

pushd "$SYMFONY"

composer install
$CONSOLE assets:install --symlink --env=$ENV
$CONSOLE assetic:dump --env=$ENV
$CONSOLE doctrine:migrations:migrate --env=$ENV
$CONSOLE cache:clear --env=$ENV

popd
