#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="/data/bleedhd/www/dev.bleedhd.com/"
CONSOLE="$SYMFONY/bin/console"
ENV=${1:-prod}

rsync --exclude-from="$SCRIPT_DIR/deploy.exclude.txt" --recursive --archive --compress --progress "$SCRIPT_DIR/../Symfony/" "bleedhd@moria:$SYMFONY"

#pushd "$SYMFONY"

#$CONSOLE assets:install --symlink --env=$ENV
#$CONSOLE assetic:dump --env=$ENV
#$CONSOLE doctrine:migrations:migrate --env=$ENV
#$CONSOLE cache:clear --env=$ENV

#popd
