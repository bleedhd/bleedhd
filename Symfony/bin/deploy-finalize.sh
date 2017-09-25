#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
CONSOLE="$SCRIPT_DIR/console"
ENV=${1:-prod}

pushd "$SCRIPT_DIR/.." > /dev/null

$CONSOLE assets:install --symlink --env=$ENV
$CONSOLE doctrine:migrations:migrate --env=$ENV
$CONSOLE cache:clear --env=$ENV

popd > /dev/null
