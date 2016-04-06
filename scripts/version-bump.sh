#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../Symfony"
GIT_DIR="$SCRIPT_DIR/../.git"
GIT="git --git-dir=$SCRIPT_DIR/../.git --work-tree=$SCRIPT_DIR/../"
CONSOLE="$SYMFONY/bin/console"
VERSION=$1

pushd "$SYMFONY"

if [ -z "$1" ]; then
	echo "You must supply the semantic version number of this release ([major].[minor].[build])"
else
	# in-place editing of config.yml
	sed -i "s/^\(\s*version:\).*/\1 \"v-$VERSION\"/" "$SYMFONY/app/config/config.yml"
	$GIT add "$SYMFONY/app/config/config.yml"
	$GIT commit -v -e -m "Version number bump to v-$1"
fi

popd
