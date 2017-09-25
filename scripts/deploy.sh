#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
CONSOLE="$SCRIPT_DIR/../Symfony/bin/console"
FINALIZE="bin/deploy-finalize.sh"

TARGET=${1:-dev}

case "$TARGET" in
	prod)
		ENV="prod"
		HOST="www-data@medbleedhdlp01"
		SYMFONY="~/test"
		;;
	demo)
		ENV="prod"
		HOST="bleedhd@moria"
		SYMFONY="/data/bleedhd/www/demo.bleedhd.com/"
		;;
	*)
		ENV="dev"
		HOST="bleedhd@moria"
		SYMFONY="/data/bleedhd/www/dev.bleedhd.com/"
		;;
esac

####################
# Deployment Process

$CONSOLE assetic:dump --env=$ENV

rsync --exclude-from="$SCRIPT_DIR/deploy.exclude.txt" --recursive --archive --compress --progress --delete --safe-links "$SCRIPT_DIR/../Symfony/" "$HOST:$SYMFONY"

SUBDIR="src/Getunik/BleedHd/AssessmentDataBundle/Resources/export"
rsync --recursive --archive --compress --progress --safe-links "$SCRIPT_DIR/../Symfony/$SUBDIR/" "$HOST:$SYMFONY$SUBDIR"

SUBDIR="src/Getunik/BleedHd/AssessmentDataBundle/Resources/questionnaire"
rsync --recursive --archive --compress --progress --safe-links "$SCRIPT_DIR/../Symfony/$SUBDIR/" "$HOST:$SYMFONY$SUBDIR"

ssh -tt $HOST -- "$SYMFONY$FINALIZE" "$ENV"
