#!/bin/bash

# To run this on a remote machine, copy it there and run it with:
# $ scp ./scripts/permissions.sh user@host:/path/to/
# $ ssh -tt user@host sudo /path/to/permissions.sh /path/to/symfonyroot username

DIR=$1
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
USER=${2:-`whoami`}


# Configure var directory access
echo "Configuring 'var' directory ACL using setfacl"

setfacl -R -m u:"$HTTPDUSER":rwX -m u:"$USER":rwX "$DIR/var"
setfacl -dR -m u:"$HTTPDUSER":rwX -m u:"$USER":rwX "$DIR/var"
