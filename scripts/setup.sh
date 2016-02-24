#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../Symfony"
CONSOLE="$SYMFONY/bin/console"
ENV=${1:-prod}

pushd "$SYMFONY"

composer install

# Configure var directory access
echo "Configuring cache and log access using setfacl"
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

echo "Initializing the database"
$CONSOLE doctrine:migrations:migrate --env=$ENV -n

echo "Setting up the application"
$CONSOLE getu:oauth-server:create-client BleedHD --grant-type="password" --grant-type="refresh_token"

echo "Please note/copy the two OAuth tokens above and then press RETURN to continue"
read

echo "Creating your login - chose any username/email/password combination"
$CONSOLE fos:user:create --super-admin

echo "Finalizing application"
$CONSOLE assets:install --symlink --env=$ENV
$CONSOLE assetic:dump --env=$ENV
$CONSOLE cache:clear --env=$ENV

echo ""
echo "The setup is complete. You can now add the OAuth to the parameters.yml file"

popd
