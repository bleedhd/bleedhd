#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../../Symfony"
CONSOLE="$SYMFONY/bin/console"
ROLE=${1:-ROLE_READER}

pushd "$SYMFONY"

while read name
do
	$CONSOLE fos:user:promote "$name" ROLE_READER
done < "${2:-/dev/stdin}"

popd
