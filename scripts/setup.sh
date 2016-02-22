#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../Symfony"
CONSOLE="$SYMFONY/bin/console"
ENV=${1:-prod}

pushd "$SYMFONY"

# Configure var directory access
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

$CONSOLE assets:install --symlink --env=$ENV
$CONSOLE assetic:dump --env=$ENV
$CONSOLE doctrine:migrations:migrate --env=$ENV
$CONSOLE getu:oauth-server:create-client BleedHD --grant-type="password" --grant-type="refresh_token"
$CONSOLE fos:user:create --super-admin
$CONSOLE cache:clear --env=$ENV

popd
