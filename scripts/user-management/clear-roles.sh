#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../../Symfony"
CONSOLE="$SYMFONY/bin/console"

# mysql -u [user] -p -sNe 'SELECT username_canonical FROM fos_user;' [database]

pushd "$SYMFONY"

while read name
do
	$CONSOLE fos:user:demote "$name" ROLE_READER
	$CONSOLE fos:user:demote "$name" ROLE_ASSESSOR
	$CONSOLE fos:user:demote "$name" ROLE_SUPERVISOR
	$CONSOLE fos:user:demote "$name" ROLE_ADMIN
	# old role - doesn't exist anymore
	$CONSOLE fos:user:demote "$name" ROLE_EDITOR
done < "${1:-/dev/stdin}"

popd
