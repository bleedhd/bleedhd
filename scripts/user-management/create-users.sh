#!/bin/bash

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
SYMFONY="$SCRIPT_DIR/../../Symfony"
CONSOLE="$SYMFONY/bin/console"
SALT="3.1415926"

pushd "$SYMFONY"

# The expected input file format is: one line per user with the following structure
#
# username,email,role
#
# The password is generated as the md5 hash of the $SALT concatenated with the user
# name (password should be reset by user anyway)
while IFS=, read name email role
do
	pass=$(echo "$SALT$name" | md5sum | cut -d " " -f1)
	echo "Creating user '$name:$pass'"
	$CONSOLE fos:user:create "$name" "$email" "$pass"
	$CONSOLE fos:user:promote "$name" "$role"
done < "${1:-/dev/stdin}"

popd
